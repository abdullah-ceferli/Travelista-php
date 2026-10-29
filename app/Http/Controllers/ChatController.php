<?php

namespace App\Http\Controllers;

use App\Models\ChatThread;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ChatController extends Controller
{
    public function index(Request $request): View
    {
        return view('connect', ['all_users' => User::orderBy('name')->get(), 'user' => $request->user()]);
    }

    public function thread(Request $request, User $user): JsonResponse
    {
        $ids = [$request->user()->id, $user->id];
        sort($ids);
        $thread = ChatThread::firstOrCreate(['first_user_id' => $ids[0], 'second_user_id' => $ids[1]]);

        return response()->json(['thread_id' => $thread->id]);
    }

    public function messages(Request $request, ChatThread $thread): JsonResponse
    {
        abort_unless($thread->includes($request->user()), 403);
        $messages = $thread->messages()->with('user')->orderByDesc('id')->limit(200)->get()->reverse()->values();

        return response()->json($messages->map(fn ($message) => [
            'id' => $message->id, 'user_id' => $message->user_id, 'username' => $message->user->name,
            'message' => $message->message, 'created_at' => $message->created_at->toIso8601String(),
        ]));
    }

    public function store(Request $request): JsonResponse
    {
        $data = $request->validate(['thread_id' => ['required', 'integer', 'exists:chat_threads,id'], 'message' => ['required', 'string', 'max:5000']]);
        $thread = ChatThread::findOrFail($data['thread_id']);
        abort_unless($thread->includes($request->user()), 403);
        $message = $thread->messages()->create(['user_id' => $request->user()->id, 'message' => $data['message']]);

        return response()->json(['id' => $message->id], 201);
    }
}
