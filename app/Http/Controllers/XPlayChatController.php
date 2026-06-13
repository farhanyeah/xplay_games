<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use OpenAI\Laravel\Facades\OpenAI;

class XPlayChatController extends Controller
{
    public function chat(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
        ]);

        $message = $request->input('message');

        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => [
                    [
                        'role' => 'system', 
                        'content' => 'Anda adalah asisten virtual untuk platform XPLAY. Berikan jawaban yang helpful, friendly, dan akurat tentang layanan XPLAY.'
                    ],
                    [
                        'role' => 'user', 
                        'content' => $message
                    ],
                ],
                'max_tokens' => 500,
            ]);

            $response = $result->choices[0]->message->content;

            return response()->json([
                'success' => true,
                'response' => $response,
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }

    // Method untuk menyimpan percakapan (chat dengan history)
    public function chatWithHistory(Request $request)
    {
        $request->validate([
            'message' => 'required|string',
            'history' => 'nullable|array',
        ]);

        $newMessage = $request->input('message');
        $history = $request->input('history', []);

        // Build messages array
        $messages = [
            [
                'role' => 'system',
                'content' => 'Anda adalah asisten virtual untuk platform XPLAY. Berikan jawaban yang helpful, friendly, dan akurat.'
            ],
        ];

        // Tambahkan history percakapan
        foreach ($history as $chat) {
            $messages[] = [
                'role' => $chat['role'], // 'user' atau 'assistant'
                'content' => $chat['content'],
            ];
        }

        // Tambahkan pesan baru dari user
        $messages[] = ['role' => 'user', 'content' => $newMessage];

        try {
            $result = OpenAI::chat()->create([
                'model' => 'gpt-4o-mini',
                'messages' => $messages,
                'max_tokens' => 500,
            ]);

            $response = $result->choices[0]->message->content;

            return response()->json([
                'success' => true,
                'response' => $response,
                'usage' => [
                    'prompt_tokens' => $result->usage->promptTokens,
                    'completion_tokens' => $result->usage->completionTokens,
                    'total_tokens' => $result->usage->totalTokens,
                ],
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan: ' . $e->getMessage(),
            ], 500);
        }
    }
}