<?php

namespace App\Services;
use App\Models\Influencer;
use App\Models\User;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ImageGenerationService
{
     
    protected $apiKey;
    protected $model = 'dall-e-3'; 

    public function __construct()
    {
       $this->apiKey = confit('services.openai.api_key');
    }

    /// Generate an image using dall-e 3 based on influencer and user request
    public function generateImage(Influencer $influencer, $userRequest): array {

        $prompt = $this->buildImagePrompt($influencer, $userRequest);

        try {
            // Call dall-e 3 API
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->apiKey,
                'Content-Type' => 'application/json',
            ])->timeout(120)->post('https://api.openai.com/v1/images/generations',[
                'model' => $this->model,
                'prompt' => $prompt,
                'n' => 1,
                'size' => '1024x1024',
                'quality' => 'standard',
                'response_format' => 'url'
            ]);

        if (!$response->successful()) {
           throw new \Exception('Failed to generate image: ' . $response->body());
        }

        $data = $response->json();
        $imageUrl = $data['data'][0]['url'] ?? null;

        if (!$imageUrl) {
           throw new \Exception('No image URL returned form API');
        }

        // DOWNLOAD AND STORE THE IMAGE
        $storedPath = $this->downloadAndStoreImage($imageUrl,$influencer);

        return [
            'success' => true,
            'image_url' => $imageUrl,
            'stored_path' => $storedPath,
            'prompt_used' => $prompt
        ];         
            

        } catch (\Exception $e) {
           return [
            'success' => false,
            'error' => $e->getMessage(),
           ];
        }
    }
    /// End generateImage Method 
    











}
