<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        // TODO auth: resolve current user_id
        // $userId = $request->user()->id;
        $userId = 1;

        $data = $request->validate([
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $contacts = Contact::query()
            ->where('user_id', $userId)
            ->with(['tags' => function ($query) use ($userId) {
                $query->where('tags.user_id', $userId);
            }])
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate($data['per_page'] ?? 20);

        $contacts->getCollection()->transform(function ($contact) {
            $contact->tags = $contact->tags->map(function ($tag) {
                return [
                    'tags_id' => $tag->id,
                    'name' => $tag->name,
                ];
            });

            return $contact;
        });

        return response()->json($contacts);
    }

    public function store(Request $request)
    {
        // TODO auth: resolve current user_id
        // $userId = $request->user()->id;
        $userId = 1;

        $data = $request->validate([
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('contacts', 'email')->where('user_id', $userId),
            ],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $contact = Contact::create([
            'user_id' => $userId,
            'email' => $data['email'],
            'name' => $data['name'],
            'description' => $data['description'] ?? null,
        ]);

        return response()->json(['id' => $contact->id], 201);
    }

    public function storeTags(Request $request)
    {
        // TODO auth: resolve current user_id
        // $userId = $request->user()->id;
        $userId = 1;

        $data = $request->validate([
            'contact_id' => [
                'required',
                'integer',
                Rule::exists('contacts', 'id')->where('user_id', $userId),
            ],
            'tags_id' => ['required', 'array', 'min:1'],
            'tags_id.*' => [
                'integer',
                Rule::exists('tags', 'id')->where('user_id', $userId),
            ],
        ]);

        $rows = collect($data['tags_id'])
            ->unique()
            ->map(function ($tagId) use ($data, $userId) {
                return [
                    'user_id' => $userId,
                    'contact_id' => $data['contact_id'],
                    'tag_id' => $tagId,
                    'created_at' => now(),
                ];
            })
            ->values()
            ->all();

        if (!empty($rows)) {
            DB::table('contact_tags')->insertOrIgnore($rows);
        }

        return response()->json(['count' => count($rows)], 201);
    }
}
