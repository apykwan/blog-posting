<?php

namespace App\Livewire;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Auth;
use Livewire\Component;
use Livewire\Attributes\On;

class Chat extends Component
{
    public $textvalue = '';
    public $chatLog;

    public function mount()
    {
        $this->chatLog = collect(); // Ensure it's fresh per session
    }

    #[On('newMessageFromSocket')]
    public function newMessageFromSocket($data)
    {
        // Check if $data is null or not an array
        if (is_null($data) || !is_array($data)) {
            \Log::error('Invalid socket data: data is null or not an array', ['data' => $data]);
            return;
        }

        // Check if required keys exist
        if (!isset($data['username'], $data['textvalue'], $data['avatar'])) {
            \Log::error('Invalid socket data: missing required keys', ['data' => $data]);
            return;
        }

        // Don't push if it's our own message
        if (Auth::check() && $data['username'] === Auth::user()->username) {
            \Log::info('Skipping own message:', ['username' => $data['username']]);
            return;
        }

        // Add the message to the chat log
        $data['selfmessage'] = false;

        dd($data);
        $this->chatLog->push($data);
    }

    public function send() 
    {
        if (!Auth::check()) {
            abort(403, 'Unauthorized');
        }

        if (trim(strip_tags($this->textvalue)) == '') return;

        $this->chatLog->push([
            'selfmessage' => true,
            'username' => Auth::user()->username,
            'textvalue' => strip_tags($this->textvalue),
            'avatar' => Auth::user()->avatar
        ]);

        $message = [
            'selfmessage' => true,
            'username' => Auth::user()->username,
            'textvalue' => strip_tags($this->textvalue),
            'avatar' => Auth::user()->avatar,
        ];

        try {
            $response = Http::post('http://localhost:' . env('NODE_SERVER_PORT', 5001) . '/send-chat-message', $message);
            if ($response->failed()) {
                \Log::error('Failed to send message to socket server:', ['error' => $response->json()]);
            }
        } catch (\Exception $e) {
            \Log::error('Exception while sending message to socket server:', ['error' => $e->getMessage()]);
        }

        $this->textvalue = '';
    }

    public function render()
    {
        return view('livewire.chat');
    }
}
