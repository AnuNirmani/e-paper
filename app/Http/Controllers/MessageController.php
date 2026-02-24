<?php
// filepath: c:\laragon\www\e-paper\app\Http\Controllers\MessageController.php

namespace App\Http\Controllers;

use App\Models\Message;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MessageController extends Controller
{
    public function index()
    {
        $messages = Message::latest()->paginate(15);
        return view('messages.index', compact('messages'));
    }

    public function create()
    {
        $message = new Message(['is_active' => true]);
        return view('messages.create', compact('message'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'key' => ['required', 'string', 'max:100', 'alpha_dash', 'unique:messages,key'],
            'body' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        Message::create($data);

        return redirect()->route('messages.index')->with('success', 'Message created.');
    }

    public function edit(Message $message)
    {
        return view('messages.edit', compact('message'));
    }

    public function update(Request $request, Message $message)
    {
        $data = $request->validate([
            'name' => ['required', 'string', 'max:150'],
            'key' => ['required', 'string', 'max:100', 'alpha_dash', Rule::unique('messages', 'key')->ignore($message->id)],
            'body' => ['required', 'string'],
            'is_active' => ['nullable', 'boolean'],
        ]);

        $data['is_active'] = $request->boolean('is_active');
        $message->update($data);

        return redirect()->route('messages.index')->with('success', 'Message updated.');
    }

    public function destroy(Message $message)
    {
        $message->delete();
        return redirect()->route('messages.index')->with('success', 'Message deleted.');
    }
}
