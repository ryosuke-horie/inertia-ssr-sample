<?php

namespace App\Http\Controllers\ChatGPT;

use App\Http\Controllers\Controller;
use App\Services\ChatGPTService;

class ChatGPTController extends Controller
{
    private $chatGPTService;

    public function __construct(ChatGPTService $chatGPTService)
    {
        $this->chatGPTService = $chatGPTService;
    }

    public function userChatGPT(int $staffId, int $promptId)
    {
        $args = $this->chatGPTService->userMessage($staffId, $promptId);

        return response()->json($args);
    }

    public function tipChatGPT(int $tipId, int $promptId)
    {
        $args = $this->chatGPTService->staffReply($tipId, $promptId);

        return response()->json($args);
    }
}
