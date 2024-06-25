<?php
namespace App\Http\Controllers\Api\v1\Report_error;

use Illuminate\Http\Request;
use DB;
use App\Http\Requests;
use App\Http\Controllers\Controller;

class ReportAnError extends Controller {
	
	public function index(Request $request){
		
		
		$UserID = $request->UserID?$request->UserID:0;
		$Description = $request->Description;
		$Ip = $request->Ip;
		
	
		$id = DB::table('report_an_error')->insertGetId(
         ['Ticket_number' => "0000000",
		 'UserID' => $UserID,
		 'Description' => $Description,
		 'Ip' => $Ip		 
		 ]);
		

		$Id_length= strlen(strval($id));
		$ticket_number="00000000";
		$ticket = substr($ticket_number, 0, -$Id_length);
		$result = strval($ticket.$id);
		
		DB::update('update report_an_error set Ticket_number = "'.$result.'" where ID = ?', [$id]);
		
		return api_response("Report success", 200);
		
	}

}