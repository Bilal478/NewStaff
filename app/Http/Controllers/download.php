<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\DB;

class download extends Controller
{
    public function index()
    { 
      $setup = DB::table('setup_version')->where('id',1)->first();
      $filePath = public_path($setup->version_name.'.exe');
    	$headers = ['Content-Type: application/zip'];
    	$fileName = $setup->version_name.'.exe';
		
    	return response()->download($filePath, $fileName, $headers);
    }
    public function macFile()
    {   
      $setup = DB::table('setup_version')->where('id',2)->first();
      $filePath = public_path($setup->version_name.'.dmg');
    	$headers = ['Content-Type: application/zip'];
    	$fileName = $setup->version_name.'.dmg';
		
    	return response()->download($filePath, $fileName, $headers);
    }

    public function ubuntuFile()
    {   
      $setup = DB::table('setup_version')->where('id',3)->first();
      $filePath = public_path($setup->version_name.'.deb');
    	$headers = ['Content-Type: application/zip'];
    	$fileName = $setup->version_name.'.deb';
		
    	return response()->download($filePath, $fileName, $headers);
    }

    public function TermsAndConditions(){
      return view('livewire.accounts.terms.terms-and-conditions');
    }
    public function hipaa(){
      return view('livewire.accounts.hipaa.hipaa-compliance');
    }
}