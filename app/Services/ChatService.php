<?php

namespace App\Services;
use App\Models\Influencer;
use App\Models\User;
use App\Models\Chat;
use OpenAI;

class ChatService
{
    protected $client;
    protected $model;
     
    public function __construct()
    {
        $apiKey = config('services.openai.api_key', env('OPENAI_API_KEY'));

        if (!$apiKey) {
           throw new \Exception('OpenAI API key is not configured. plz add your OPENAI_API_KEY to your .env file.');
        }

        $this->client = OpenAI::client($apiKey);
        $this->model = config('services.openai.model', env('OPENAI_MODEL','gpt-4-turbo-preview')); 

    }

    // Send a message to influencer AI and Get a response 

    public function chat(User $user, Influencer $influencer, string $message, ?string $sessionId = null, string $language = 'en'): array {

    }
    /// End chat Method 

    




}
