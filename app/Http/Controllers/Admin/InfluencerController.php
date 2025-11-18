<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Influencer;

class InfluencerController extends Controller
{
    public function AdminInfluencersIndex(){

        $influencers = Influencer::withCount('chats')->latest()->paginate(10);
        return view('admin.backend.influencers.influencer_index',compact('influencers'));

    }
    // End Method 

    public function AdminInfluencersCreate(){

        return view('admin.backend.influencers.influencer_create');
    }
    // End Method 




}
