<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Company;
use Illuminate\Http\Request;
use App\Providers\RouteServiceProvider;

class download extends Controller
{
    public function index()
    {   
       $filePath = public_path("neostaff-Setup-1.2.3.exe");
    	$headers = ['Content-Type: application/zip'];
    	$fileName = 'neostaff-Setup-1.2.3.exe';
		
    	return response()->download($filePath, $fileName, $headers);
    }
}