@extends('client.client_dashboard')
@section('client') 
<div class="container-xxl">

    <div class="py-3 d-flex align-items-sm-center flex-sm-row flex-column">
        <div class="flex-grow-1">
            <h4 class="fs-18 fw-semibold m-0">Onboarding - {{ $coach->name }}</h4>
        </div>
    </div> 
    <div class="row justify-content-center">
        <div class="col-xl-10">
            <div class="card">
                <div class="card-body">

                    <!-- Coach Header -->
<div class="text-center mb-4">
    <div class="avatar-lg mx-auto mb-3">
        <span class="avatar-title rounded-circle bg-soft-primary text-primary font-24 fw-bold">
           {{ strtoupper(substr($coach->name, 0,1)) }}
        </span>
    </div>
    <h3>{{ $coach->name }}</h3>
    <p class="text-muted">{{ $coach->title }}</p>
    <p class="text-muted">{{ $coach->description }}</p>
</div>

<div class="alert alert-info">
    <i class="mdi mdi-information-outline me-2"></i>
    <strong>Why we need this:</strong> Your responses help personalize coaching for your specific needs.
</div>

<!-- Onboarding Form -->
<form action="{{ route('coaches.onboarding.submit',$coach->slug) }}" method="POST">
    @csrf

    @if ($coach->speciality == 'career') 
    <!-- Career Questions -->
    <div class="mb-3">
        <label class="form-label">Current Role</label>
        <input type="text" class="form-control" name="current_role" value="{{ old('current_role', $profile->current_role ?? '') }}">
        
    </div>
    <div class="mb-3">
        <label class="form-label">Target Role</label>
        <input type="text" class="form-control " name="target_role" value="{{ old('target_role', $profile->target_role ?? '') }}">
        
    </div>
    <div class="mb-3">
        <label class="form-label">Years of Experience</label>
        <input type="number" class="form-control" name="years_experience" value="{{ old('years_experience', $profile->years_experience ?? '') }}">
        
    </div>
    <div class="mb-3"> 
        <label class="form-label">Skills (one per line)</label>
        <textarea class="form-control" name="skills_text" rows="3">{{ old('skills_text',$profile && $profile->skills ? implode("\n",$profile->skills) : '') }}</textarea>
        
    </div>
    <div class="mb-3">
        <label class="form-label">Career Aspirations</label>
        <textarea class="form-control" name="career_aspirations" rows="3">{{ old('career_aspirations', $profile->career_aspirations ?? '') }}</textarea>
            
    </div>

    @elseif ($coach->speciality == 'fitness') 
    <!-- Fitness Questions -->
    <div class="mb-3">
        <label class="form-label">Fitness Level</label>
        <select class="form-select" name="fitness_level">
            <option value="">Select level</option>
            <option value="beginner" {{ old('fitness_level', $profile->fitness_level ?? '') == 'beginner' ? 'selected' : '' }} >Beginner</option>
            <option value="intermediate" {{ old('fitness_level', $profile->fitness_level ?? '') == 'intermediate' ? 'selected' : '' }} >Intermediate</option>
            <option value="advanced"{{ old('fitness_level', $profile->fitness_level ?? '') == 'advanced' ? 'selected' : '' }} >Advanced</option>
        </select> 
    </div>
    <div class="row">
        <div class="col-md-4 mb-3">
            <label class="form-label">Height (cm)</label>
            <input type="number" step="0.01" class="form-control" name="height" value="{{ old('height', $profile->height ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Weight (kg)</label>
            <input type="number" step="0.01" class="form-control" name="weight" value="{{ old('weight', $profile->weight ?? '') }}">
        </div>
        <div class="col-md-4 mb-3">
            <label class="form-label">Age</label>
            <input type="number" class="form-control" name="age" value="{{ old('age', $profile->age ?? '') }}">
        </div>
    </div>
    <div class="mb-3">
        <label class="form-label">Gender</label>
        <select class="form-select" name="gender">
            <option value="">Select</option>
            <option value="male" {{ old('gender', $profile->gender ?? '') == 'male' ? 'selected' : '' }} >Male</option>
            <option value="female" {{ old('gender', $profile->gender ?? '') == 'female' ? 'selected' : '' }} >Female</option>
            <option value="other" {{ old('gender', $profile->gender ?? '') == 'other' ? 'selected' : '' }} >Other</option>
        </select>
    </div>
    <div class="mb-3">
        <label class="form-label">Fitness Goals</label>
        <textarea class="form-control" name="fitness_goals" rows="3">{{ old('fitness_goals', $profile->fitness_goals ?? '') }}</textarea>
    </div>

    @elseif ($coach->speciality == 'finance') 
    <!-- Finance Questions -->
    <div class="mb-3">
        <label class="form-label">Monthly Income ($)</label>
        <input type="number" step="0.01" class="form-control" name="monthly_income" value="{{ old('monthly_income', $profile->monthly_income ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Monthly Expenses ($)</label>
        <input type="number" step="0.01" class="form-control" name="monthly_expenses" value="{{ old('monthly_expenses', $profile->monthly_expenses ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Current Savings ($)</label>
        <input type="number" step="0.01" class="form-control" name="savings" value="{{ old('savings', $profile->savings ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Current Debt ($)</label>
        <input type="number" step="0.01" class="form-control" name="debt" value="{{ old('debt', $profile->debt ?? '') }}">
    </div>
    <div class="mb-3">
        <label class="form-label">Risk Tolerance</label>
        <select class="form-select" name="risk_tolerance">
            <option value="">Select</option>
            <option value="conservative"  {{ old('risk_tolerance', $profile->risk_tolerance ?? '') == 'conservative' ? 'selected' : '' }} >Conservative</option>
            <option value="moderate" {{ old('risk_tolerance', $profile->risk_tolerance ?? '') == 'moderate' ? 'selected' : '' }} >Moderate</option>
            <option value="aggressive" {{ old('risk_tolerance', $profile->risk_tolerance ?? '') == 'aggressive' ? 'selected' : '' }} >Aggressive</option>
        </select>
    </div>

     @elseif ($coach->speciality == 'nutrition') 
    <!-- Nutrition Questions -->
    <div class="mb-3">
        <label class="form-label">Dietary Restrictions (one per line)</label>
        <textarea class="form-control" name="dietary_restrictions_text" rows="3">{{ old('dietary_restrictions_text',$profile && $profile->dietary_restrictions ? implode("\n",$profile->dietary_restrictions) : '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Food Allergies (one per line)</label>
        <textarea class="form-control" name="food_allergies_text" rows="3">{{ old('food_allergies_text',$profile && $profile->food_allergies ? implode("\n",$profile->food_allergies) : '') }}</textarea>
    </div>
    <div class="mb-3">
        <label class="form-label">Daily Calorie Goal</label>
        <input type="number" class="form-control" name="daily_calorie_goal" value="{{ old('daily_calorie_goal', $profile->daily_calorie_goal ?? '') }}" placeholder="2000">
    </div>
    
   @endif

    <div class="text-center mt-4">
        <a href=" " class="btn btn-light me-2">Cancel</a>
        <button type="submit" class="btn btn-primary">Complete Onboarding</button>
    </div>
</form>

                </div>
            </div>
        </div>
    </div>

</div>

 
@endsection
