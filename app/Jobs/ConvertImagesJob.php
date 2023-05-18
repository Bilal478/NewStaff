<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Intervention\Image\Facades\Image;
use Illuminate\Support\Facades\Log;
use App\Models\Screenshot;

class ConvertImagesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        set_time_limit(0);
        $this->rename();
        // $this->chnageFormate();
  
    }

    public function rename(){
        // $screenshots = Screenshot::where('id',69220)->first();
        
        // $screenshot_name_array = explode('.',$screenshots->path);
        // $screenshots->path = $screenshot_name_array[0].'.webp';
        // $screenshots->save();
        // dd($screenshots);
        $i = 0;
        
        $screenshots = Screenshot::all();
        foreach($screenshots as $screenshot){
            
            $screenshot_name_array = explode('.',$screenshot->path);
            $screenshot->path = $screenshot_name_array[0].'.webp';
            $screenshot->save();
            Log::info([$i++,$screenshot->id,$screenshot->path]);
        }
    }

    public function chnageFormate(){
        // $file = storage_path('app/screenshots/2/1674228048851.png');
       
        // $oldenameArray =  explode(".",'1674228048851.png');
        // $image = Image::make(storage_path('app/screenshots/2/1674228048851.png'));
        // $name = $oldenameArray[0].'.webp';
        // $path = storage_path('app/screenshots/22/'.$name);
        // $image->encode('webp')->save($path);

        $i = 0;
        $files = scandir(storage_path('app/screenshots/2/'));
        foreach($files as $file) {
            if($file == "." || $file ==".."){
                
            }
            else{
            
                
                // if($i <= 10){
                    Log::info([$i++,$file]);
                    $oldenameArray =  explode(".",$file);
                    $image = Image::make(storage_path('app/screenshots/2/'.$file));
                    $name = $oldenameArray[0].'.webp';
                    $path = storage_path('app/screenshots/22/'.$name);
                    $image->encode('webp')->save($path);
                // }
                
            }
            
            
        }
           
    }
}
