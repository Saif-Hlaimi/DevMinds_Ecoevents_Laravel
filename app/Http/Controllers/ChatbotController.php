<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class ChatbotController extends Controller
{
    public function index()
    {
        return view('chatbot'); // Crée la vue resources/views/chatbot.blade.php
    }

    public function ask(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        try {
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . config('services.openai.api_key'),
                'Content-Type'  => 'application/json',
            ])->post('https://openrouter.ai/api/v1/chat/completions', [
                'model' => 'gpt-3.5-turbo',
                'messages' => [
                    ['role' => 'user', 'content' => $request->message]
                ],
                'temperature' => 0.7,
            ]);

            if ($response->failed()) {
                return response()->json(['error' => $response->body()]);
            }

            $data = $response->json();
            $reply = $data['choices'][0]['message']['content'] ?? "Désolé, je n'ai pas compris.";

            return response()->json(['reply' => $reply]);

        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()]);
        }
    }
}
