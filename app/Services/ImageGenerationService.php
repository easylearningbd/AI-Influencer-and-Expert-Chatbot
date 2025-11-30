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
       $this->apiKey = config('services.openai.api_key');
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

    protected function buildImagePrompt(Influencer $influencer, string $userRequest): string {
     
        // User influcer custom image prompt tempate 
        $basePrompt = $influencer->image_prompt ?? $this->getDefaultImagePrompt($influencer);

        // Combine base prompt with user request 
        $fullPrompt = str_replace('{scenario}', $userRequest,$basePrompt);

        $fullPrompt = $this->enhancePrompt($fullPrompt);

        return $fullPrompt;
    }

    /// get default image prompt for influencer 
    protected function getDefaultImagePrompt(Influencer $influencer): string {
        
        return "A high-quality, professional photograph of a {gender} influencer named {name}. {scenario}. The photo should be realistic, well-lit, and visually appealing, suitable for social media.";
    }

    // Enhance prompt with best parctices for DALL-E-3

    protected function enhancePrompt(string $prompt): string {

        if (!str_contains(strtolower($prompt), 'high-quality') && !str_contains(strtolower($prompt), 'professional')) {
           $prompt = 'High-quality, professional photo. ' .$prompt;
        }

        // ensure its descriptive 
        if (strlen($prompt) < 50) {
            $prompt .= ' The image should be detailed, realistic, and visually stunning with good composition and lighting. ';
        }

        return $prompt;
    }

    // Download image from url and store locally 
    protected function downloadAndStoreImage(string $imageUrl, Influencer $influencer): string {

        try {

            // Download the image 
            $imageContent = file_get_contents($imageUrl);

            if ($imageContent === false) {
                throw new \Exception('Failed to download image');
            }

            /// Generate unique file name 
            $filename = 'influencer_' . $influencer->id . '_' . Str::random(10) . '_' . time() . '.png'; 
            $path = 'influencer_image/' . $influencer->slug . '/' . $filename;

            // Store in public disk 
            Storage::disk('public')->put($path,$imageContent);

            return $path;
            
            
        } catch (\Exception $e) {
           return $imageUrl;
        }

    }

    ///// End downloadAndStoreImage Method 

    // Get full URL For stored image 

    public function getImageUrl(string $path): string {
       
        if (str_starts_with($path, 'http')) {
            return $path;
        }

        return asset('storage/' . $path);
    }

    // Delete generated image 

    public function deleteImage(string $path): bool {

        if (str_starts_with($path, 'http')) {
            return false;
        }
        return Storage::disk('public')->delete($path);

    }


    











}
