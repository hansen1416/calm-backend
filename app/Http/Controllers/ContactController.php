<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

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
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate($data['per_page'] ?? 20);

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
}
