<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Influencer;
use Illuminate\Support\Str;
use App\Services\ChatService;
use Barryvdh\DomPDF\Facade\Pdf;

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

    public function ChatSend(Request $request, Influencer $influencer){

        $request->validate([
            'message' => 'required|string',
            'session_id' => 'nullable|string',
            'language' => 'nullable|string',
        ]);

        if (!$influencer->is_active) {
            return response()->json([
                'success' => false,
                'error' => 'This influencer is not avaiable for chat'
            ],404);
        }

        try {
            $user = Auth::user();

            $result = $this->chatService->chat(
                $user,
                $influencer,
                $request->message,
                $request->session_id,
                $request->input('language','en') 
            );

            return response()->json($result);


        } catch (\Exception $e) {
           return response()->json([
            'success' =>  false,
            'error' => $e->getMessage()
           ],400);
        }

    }
     // End Method 

     public function ChatExport(Request $request,Influencer $influencer, string $sessionId ){


     }
      // End Method 



} 
