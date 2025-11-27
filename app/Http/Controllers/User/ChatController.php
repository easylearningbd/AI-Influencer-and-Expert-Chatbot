<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Influencer;
use Illuminate\Support\Str;
use App\Services\ChatService;

class ChatController extends Controller
{

    protected $chatService;

    public function __construct(ChatService $chatService, ){

        $this->chatService = $chatService;

    }



    public function UserChatIndex(){

        $influencers = Influencer::where('is_active',true)
                ->withCount('chats')
                ->orderBy('chat_count','desc')
                ->get();
        return view('client.backend.chat.chat_index',compact('influencers'));
    }
    // End Method 

    public function NewSession(Influencer $influencer){

        $sessionId = uniqid('session_', true);

        return redirect()->route('user.chat.show',[
            'influencer' => $influencer->slug,
            'session_id' => $sessionId
        ]);

    }
    // End Method 

    public function ChatShow(Influencer $influencer){

        if (!$influencer->is_active) {
            abort(404,'This influencer is not available for chat.');
        }

        $user = Auth::user();

        // Get chat sessions 
        $sessions = $this->chatService->getChatSessions($user,$influencer);

        $sessionId = request('session_id',null);

        // Get chat history for this session 
        $chatHistory = [];
        if ($sessionId) {
           $chatHistory = $this->chatService->getChatHistory($user,$influencer,$sessionId);
        }

        return view('client.backend.chat.chat_show',compact('influencer','sessions','chatHistory','sessionId'));
    }
    // End Method 



} 
