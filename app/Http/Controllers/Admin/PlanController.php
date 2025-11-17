<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Plan;

class PlanController extends Controller
{
    public function PlanIndex(){
        $plans = Plan::orderBy('sort_order')->orderBy('price')->get();
        return view('admin.backend.plans.plans_index',compact('plans'));

    }
    // End Method 

    public function PlanCreate(){
        return view('admin.backend.plans.plans_create');
    }
     // End Method 

    public function PlanStore(Request $request){

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tokens' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:one-time,monthly,yearly',
            'sort_order' => 'nullable|integer',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',

        ]);

        $data = $request->all();

        // Filter out empty features
        if (isset($data['features'])) {
           $data['features'] = array_filter($data['features'], function($feature) {
            return !empty(trim($feature));
           });
        }

        Plan::create($data);

         $notification = array(
            'message' => 'Plan Created Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->route('admin.plans.index')->with($notification); 

    }
    // End Method 


    public function PlanEdit(Plan $plan){
        return view('admin.backend.plans.plans_edit',compact('plan'));
    }
     // End Method 

     public function PlanUpdate(Request $request,Plan $plan){

        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'tokens' => 'required|integer|min:1',
            'price' => 'required|numeric|min:0',
            'type' => 'required|in:one-time,monthly,yearly',
            'sort_order' => 'nullable|integer',
            'features' => 'nullable|array',
            'features.*' => 'string',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',

        ]);

        $data = $request->all();

        // Filter out empty features
        if (isset($data['features'])) {
           $data['features'] = array_filter($data['features'], function($feature) {
            return !empty(trim($feature));
           });
        }

        /// Handle checkboxes
        $data['is_active'] = $request->has('is_active');
        $data['is_featured'] = $request->has('is_featured');

        $plan->update($data);

         $notification = array(
            'message' => 'Plan Updated Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->route('admin.plans.index')->with($notification); 

    }
    // End Method 

    public function PlanStatusUpdate(Plan $plan){

         $plan->update([
            'is_active' => !$plan->is_active
         ]);

         $notification = array(
            'message' => 'Plan Status Updated Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->back()->with($notification); 

    }
    // End Method 

    public function PlansDelete(Plan $plan){

        $plan->delete();

        $notification = array(
            'message' => 'Plan Deleted Successfully',
            'alert-type' => 'success'
        );  
        return redirect()->back()->with($notification); 
    }
     // End Method 





}
