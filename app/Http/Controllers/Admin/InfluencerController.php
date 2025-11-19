<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Influencer;
use Illuminate\Support\Str;

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

    public function AdminInfluencersStore(Request $request){

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'niche' => 'nullable|string|max:255',
            'bio' => 'nullable|string',
            'style' => 'nullable|string',
            'system_prompt' => 'nullable|string',
            'image_prompt' => 'nullable|string',

            'can_generate_image' => 'nullable|boolean',
            'youtube_link' => 'nullable|url',
            'avatar' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'is_active' => 'nullable|boolean', 
        ]);

        /// Handle the avater image
        if ($request->hasFile('avatar')) {
            $avatar = $request->file('avatar');
            $avatarName = time(). '_' . Str::slug($request->name). '.' . $avatar->getClientOriginalExtension();
            $avatar->move(public_path('upload/avatars'),$avatarName);
            $validated['avatar'] = 'upload/avatars/' . $avatarName;
        }

        $validated['is_active'] = $request->has('is_active') ? true : false;
        $validated['can_generate_image'] = $request->has('can_generate_image') ? true : false;


        $influencer = Influencer::create($validated);

        $notification = array(
            'message' => 'Influencer Created Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->route('admin.influencers.index')->with($notification);  
    }
     // End Method 




}
