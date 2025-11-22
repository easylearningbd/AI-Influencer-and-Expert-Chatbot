<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Influencer;
use App\Models\InfluencerData;
use Illuminate\Support\Str;
use Smalot\PdfParser\Parser as PdfParser;

class InfluencerDataController extends Controller
{
    public function AdminInfluencersDataUpload(Request $request, Influencer $influencer){

        $request->validate([
            'file' => 'required|file|mimes:pdf,txt|max:10240', // 10MB Max
        ]);

        $file = $request->file('file');
        $fileName = time() . '_' . Str::slug($file->getClientOriginalName());
        $filePath = 'upload/training_data';

        $file->move(public_path($filePath),$fileName);
        $fullPath = $filePath . '/' .$fileName;

        /// Extract text content based on file type 
        $extension = $file->getClientOriginalExtension();
        $content = '';

        if ($extension === 'txt') {
            $content = file_get_contents(public_path($fullPath));
        } elseif($extension === 'pdf'){
            $content = $this->extractPdfText(public_path($fullPath));
        }

        // store data in database table InfluencerData
        InfluencerData::create([
            'influencer_id' => $influencer->id,
            'type' => $extension,
            'file_name' => $file->getClientOriginalName(),
            'file_path' => $fullPath,
            'content' => $content,
            'chunk_size' => strlen($content),
        ]);

        $notification = array(
            'message' => 'Training data uploaded Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->back()->with($notification); 

    }
    // End Method 

    // Extract the text from pdf using pdfparser pacakge
    private function extractPdfText($filePath){
        try {

            $parser = new PdfParser();
            $pdf = $parser->parseFile($filePath);
            $text = $pdf->getText();

            /// clearn up the text 
            $text = preg_replace('/\s+/',' ',$text);
            $text = trim($text);

            return $text;
          
        } catch (\Exception $e) {
            return "Error extracing PDF Text: " . $e->getMessage();
        }
    }
      // End private Method 


    public function AdminInfluencersDataAddtext(Request $request, Influencer $influencer){

         $request->validate([
            'content' => 'required|string|min:10',  
        ]);

       // store data in database table InfluencerData
        InfluencerData::create([
            'influencer_id' => $influencer->id,
            'type' => 'txt', 
            'content' => $request->content,
            'chunk_size' => strlen($request->content),
        ]);

        $notification = array(
            'message' => 'Text Content data Added Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->back()->with($notification);  

    }
      // End Method 

    public function AdminInfluencersDataDelete(InfluencerData $influencer){

        // Delete file if exists
        if ($influencer->file_path && file_exists(public_path($influencer->file_path))) {
            unlink(public_path($influencer->file_path));
        }

        $influencer->delete();

         $notification = array(
            'message' => 'Training data deleted Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->back()->with($notification);  

    }
    // End Method 

    public function AdminInfluencersDataYoutube(Request $request, Influencer $influencer){

        $request->validate([
            'youtube_url' => 'required|url',  
        ]);

        // Extract video ID from Url 
        $videoId = $this->extractYoutubeVideoId($request->youtube_url);

        if (!$videoId) {
           return redirect()->back()->withErrors(['youtube_url' => 'Invalid Youtube URL']);
        };

        // Fetch transcript data 
        $transcript = $this->fetchYoutubeTranscript($videoId);

        if (!$transcript) {
           return redirect()->back()->withErrors(['youtube_url' => 'Could not fetch transcript. Make sure the video has captions.']);
        }

         InfluencerData::create([
            'influencer_id' => $influencer->id,
            'type' => 'youtube', 
            'youtube_url' => $request->youtube_url, 
            'content' => $transcript,
            'chunk_size' => strlen($transcript),
        ]);

        $notification = array(
            'message' => 'Youtube Transcript Added Successfully',
            'alert-type' => 'success'
        ); 

        return redirect()->back()->with($notification);  
    }
    // End Method 


    private function extractYoutubeVideoId($url){

        $pattern = '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/';

        if (preg_match($pattern,$url,$matches)) {
            return $matches[1];
        }
        return null;
    }
      // End Method 

      private function fetchYoutubeTranscript($videoId){

        try {
           
            $apiKey = config('services.youtube.api_key');

            if (!$apiKey || $apiKey === 'your-env-api-key') {
               return "Please add YOUTUBE_API_KEY to your .env file to fetch youtube data. Video ID: {$videoId} ";
            }

       // Fetch video details (title, description, channel info, statistics)
        $videoUrl = "https://www.googleapis.com/youtube/v3/videos?part=snippet,contentDetails,statistics&id={$videoId}&key={$apiKey}";

        $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $videoUrl);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            $videoResponse = curl_exec($ch);
            curl_close($ch); 

        $videoData = json_decode($videoResponse, true);

        if (!isset($videoData['items'][0])) {
            return "Could not fetch yout video data.";
        }

        $video = $videoData['items'][0];
        $snippet = $video['snippet'];
        $statistics = $video['statistics'] ?? [];

        /// Build Comprehensive transcript 
        $transcript = "=== VIDEO INFORMATION ===\n\n";
        $transcript .= "Title: " . $snippet['title'] . "\n\n";
        $transcript .= "Channel: " . $snippet['channelTitle'] . "\n\n";
        $transcript .= "Published: " . date('F d, Y', strtotime($snippet['publishedAt'])) . "\n\n";

        if (isset($statistics['viewCount'])) {
            $transcript .= "Views: " . number_format($statistics['viewCount']) . "\n\n"; 
        }

         if (isset($statistics['likeCount'])) {
            $transcript .= "Likes: " . number_format($statistics['likeCount']) . "\n\n"; 
        }

        $transcript .= "=== VIDEO DESCRIPTION ===\n\n";
        $transcript .= $snippet['description'] . "\n\n"; 

        $captionText = $this->fetchYoutubeCaptions($videoId,$apiKey);

        if ($captionText) {
           $transcript .= "=== VIDEO TRANSCRIPT (Captions) ===\n\n";
           $transcript .= $captionText . "\n\n"; 
           $transcript .= "NOTE: This is the actual spoken content from the video. Use this for training the AI to speak like the influencer.\n\n";

        } else {
             $transcript .= "=== NOTE ===\n";
             $transcript .= "Video captions are not publicly available. Training data includes title and description only.\n";
             $transcript .= "Recommendation: For better AI training, manually add transcripts or use videos with captions enabled.\n\n";
        }

        $transcript .= "Video URL: https://www.youtube.com/watch?v={$videoId}\n";
        $transcript .= "Video ID: {$videoId}";        

        return $transcript;           

        } catch (\Exception $e) {
            return "Error fetching youtube data: " . $e->getMessage() . "Video ID: {$videoId}";
        }

      }
       // End Method 



}
