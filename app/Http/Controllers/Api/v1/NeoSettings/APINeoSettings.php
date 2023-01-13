<?php
namespace App\Http\Controllers\Api\v1\NeoSettings;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class APINeoSettings extends Controller {
	
	public function index(){
		//$users = DB::select('select * from student_details');
		//return view('settings');
		
		$neo_settings = DB::select('select * from neostaff_settings');
		//echo json_encode($neo_settings);
		
		return api_response(json_encode($neo_settings), 200);
	}

}