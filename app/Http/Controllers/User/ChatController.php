<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Influencer;
use Illuminate\Support\Str;
use App\Services\ChatService;
use App\Services\ImageGenerationService;
use Barryvdh\DomPDF\Facade\Pdf;

class ChatController extends Controller
{

    protected $chatService;
    protected $imageService;

    public function __construct(ChatService $chatService,ImageGenerationService $imageService ){

        $this->chatService = $chatService;
        $this->imageService = $imageService;

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

        $request->validate([
            'format' => 'required|in:pdf,txt',
        ]);

        $user = Auth::user();
        $tokensRequired = 5;

        // Check if user has enough tokens or not 
        if (!$user->hasEnoughTokens($tokensRequired)) {
           return back()->with([
            'message' => 'Insufficient tokens. You need 5 tokens to exprot a chat. Plz purchase more tokens',
            'alert-type' => 'error'
           ]);
        }

        // Get chat history 
        $chatHistory = $this->chatService->getChatHistory($user,$influencer,$sessionId);

        if (empty($chatHistory)) {
           return back()->with([
            'message' => 'No chat history found for this session',
            'alert-type' => 'error'
           ]);
        }

        $user->deductTokens($tokensRequired);
        $format = $request->format;
        $fileName = 'chat_' . $influencer->slug . '_' . date('Y-m-d_His');

        if ($format === 'pdf') {
            // Generate PDF 
            $pdf = Pdf::loadView('client.backend.chat.export-pdf', [
                'influencer' => $influencer,
                'chatHistory' => $chatHistory,
                'sessionId' => $sessionId,
                'exportDate' => now()->format('M d, Y H:i')
            ]);
            return $pdf->download($fileName . '.pdf');
        } else {
            // Generate text file
            $content = "Chat with {$influencer->name}\n";
            $content .= "Session Id {$sessionId}\n";
            $content .= "Exported: " . now()->format('M d, Y H:i') . "\n";
            $content .= str_repeat('=',50) . "\n\n";

            foreach($chatHistory as $chat){
                $content .= " You: {$chat->message}\n";
                $content .= "{$influencer->name}: {$chat->response}\n";
                $content .= "Time : " . $chat->created_at->format('M d, Y H:i') . "\n";
                $content .= str_repeat('-',50) . "\n\n";
            }

            return response($content, 200)
                ->header('Content-Type', 'text/plain')
                ->header('Content-Disposition', 'attachment; filename="' . $fileName . '.txt"');
        }

     }
      // End Method 


    // Per generate image it should be cut as 10 tokens 

    public function ChatGenerateImage(Request $request,Influencer $influencer ){

       $request->validate([
            'image_request' => 'required|string',
            'session_id' => 'required|string',
        ]);

        $user = Auth::user();
        $tokensRequired = 10;

        // Check if influencer can generate images
        if (!$influencer->can_generate_image) {
           return response()->json([
            'success' => false,
            'error' => 'This influencer does not support image generation', 
           ],400);
        }

        // Check if user has enough tokens or not 
        if (!$user->hasEnoughTokens($tokensRequired)) {
           return response()->json([
            'success' => false,
            'error' => 'Insufficient tokens. You need 10 tokens to exprot a chat. Plz purchase more tokens', 
           ],400);
        }

        try {
            // Generate the image
            $result =  $this->imageService->generateImage($influencer,$request->image_request);

            if (!$result['success']) {
                return response()->json([
                    'success' => false,
                    'error' => $result['error'] ?? 'Failed to generate image'
                ],500);
            }

    // Deduct tokens after successfully generate 
    $user->deductTokens($tokensRequired);

    // Get Image URL 
    $imageUrl = $this->imageService->getImageUrl($result['stored_path']);

    // Store as a chat message 




           
        } catch (\Throwable $th) {
            //throw $th;
        }



    }
    // End Method 



} 
