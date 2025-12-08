<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Coach;

class CoachesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Coach::create([
            'name' => 'Dr. Sarah Johnson',
            'slug' => 'career-coach',
            'title' => 'Career Coach',
            'speciality' => 'career',
            'description' => 'Assists in achieving career goals with guidance and planning',
            'credentials' => 'MBA, Certified Career Coach',
            'years_experience' => 15,
            'expertise' => ['Leadership Development', 'Career Transitions', 'Salary Negotiation', 'Interview Preparation', 'Personal Branding'],
            'onboarding_questions' => [
                'What is your current role?',
                'What role do you aspire to?',
                'How many years of experience do you have?',
                'What skills do you want to develop?',
                'What are your career aspirations?'
            ],
            'session_start_tokens' => 50,
            'subsequent_chats_free' => true,
            'is_active' => true,
        ]);

        Coach::create([
            'name' => 'Mike Thompson',
            'slug' => 'fitness-coach',
            'title' => 'Fitness Coach',
            'speciality' => 'fitness',
            'description' => 'Guides workouts and nutrition to improve strength, stamina, and health',
            'credentials' => 'CPT, Certified Personal Trainer',
            'years_experience' => 10,
            'expertise' => ['Weight Loss', 'Muscle Building', 'HIIT Training', 'Nutrition Planning', 'Injury Prevention'],
            'onboarding_questions' => [
                'What is your current fitness level?',
                'What are your fitness goals?',
                'What is your height and weight?',
                'What is your age and gender?',
                'Do you have any health conditions?'
            ],
            'session_start_tokens' => 50,
            'subsequent_chats_free' => true,
            'is_active' => true,
        ]);

        Coach::create([
            'name' => 'David Chen',
            'slug' => 'finance-advisor',
            'title' => 'Finance Advisor',
            'speciality' => 'finance',
            'description' => 'Helps manage money, investments, and financial planning effectively',
            'credentials' => 'CFP, Certified Financial Planner',
            'years_experience' => 12,
            'expertise' => ['Investment Planning', 'Retirement Planning', 'Debt Management', 'Budgeting', 'Tax Optimization'],
            'onboarding_questions' => [
                'What is your approximate monthly income?',
                'What are your monthly expenses?',
                'Do you have any savings or debt?',
                'What is your risk tolerance?',
                'What are your financial goals?'
            ],
            'session_start_tokens' => 50,
            'subsequent_chats_free' => true,
            'is_active' => true,
        ]);

        Coach::create([
            'name' => 'Chef Maria Rodriguez',
            'slug' => 'personal-chef',
            'title' => 'Personal Chef',
            'speciality' => 'nutrition',
            'description' => 'Prepares healthy, delicious meals tailored to your dietary needs',
            'credentials' => 'Culinary Institute Graduate, 20 years experience',
            'years_experience' => 20,
            'expertise' => ['Meal Planning', 'Special Diets', 'Nutrition', 'Quick & Easy Recipes', 'International Cuisine'],
            'onboarding_questions' => [
                'Do you have any dietary restrictions?',
                'Do you have any food allergies?',
                'What types of food do you enjoy?',
                'What is your daily calorie goal?',
                'How much time do you have for meal prep?'
            ],
            'session_start_tokens' => 50,
            'subsequent_chats_free' => true,
            'is_active' => true,
        ]);
    }
}
