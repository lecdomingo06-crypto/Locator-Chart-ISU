<?php

namespace App\Http\Controllers;

use App\Services\CampusChatbotService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ChatbotController extends Controller
{
    public function message(Request $request, CampusChatbotService $chatbot): JsonResponse
    {
        $validated = $request->validate([
            'message' => ['required', 'string', 'max:500'],
        ]);

        return response()->json(
            $chatbot->answer($validated['message'], $request->user())
        );
    }
}
