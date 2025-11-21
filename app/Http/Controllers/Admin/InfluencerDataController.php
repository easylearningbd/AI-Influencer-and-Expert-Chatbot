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




}
