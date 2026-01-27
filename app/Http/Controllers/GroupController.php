<?php

namespace App\Http\Controllers;

use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class GroupController extends Controller
{
    public function index(Request $request)
    {
        // TODO auth: resolve current user_id
        // $userId = $request->user()->id;
        $userId = 1;

        $data = $request->validate([
            'per_page' => ['nullable', 'integer', 'min:1', 'max:100'],
        ]);

        $groups = Group::query()
            ->where('user_id', $userId)
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->paginate($data['per_page'] ?? 20);

        return response()->json($groups);
    }

    public function store(Request $request)
    {
        // TODO auth: resolve current user_id
        // $userId = $request->user()->id;
        $userId = 1;

        $data = $request->validate([
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('tags', 'name')->where('user_id', $userId),
            ],
            'description' => ['nullable', 'string'],
        ]);

        $group = Group::create([
            'user_id' => $userId,
            'name' => $data['name'],
        ]);

        return response()->json(['id' => $group->id], 201);
    }
}
