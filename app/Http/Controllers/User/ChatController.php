<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Influencer;
use Illuminate\Support\Str;

class ChatController extends Controller
{
    public function UserChatIndex(){

        $influencers = Influencer::where('is_active',true)
                ->withCount('chats')
                ->orderBy('chat_count','desc')
                ->get();
        return view('client.backend.chat.chat_index',compact('influencers'));
    }
    // End Method 
} 
