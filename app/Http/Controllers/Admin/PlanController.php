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








}
