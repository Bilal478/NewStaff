<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use App\Mail\DailyWorkSummaryEmail;
use App\Mail\ManagerDailyWorkSummaryEmail;
use App\Mail\MemberDailyWorkSummaryEmail;
use App\Models\Account;
use App\Models\Activity;
use App\Models\User;
use Carbon\Carbon;
use Carbon\CarbonInterval;
use Illuminate\Support\Facades\DB;

class DailyWorkSummaryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $date;
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
        $start_time = date('Y-m-d H:i:s');
        $total_emails_sent=0;
        $accountData= [];
        // $this->date = Carbon::now()->subDay()->format('Y-m-d');
        $this->date = '2023-07-31';
        $ownersRecords=$this->getUsersReport();
        $membersRecords = $this->getMemberUsersReport();
        $managersRecords = $this->getManagerUsersReport();

        foreach ($managersRecords as $accountId => $managerData) {
            if ($managerData) {
                foreach ($managerData as $managerId => $records) {
                    if($records){
                    $mergedActivities = [];
                    $managerUsers=[];
                    foreach ($records as $memberid=>$record) {
                        if($record){
                        $totalDuration = 0; // Initialize total duration for the user
                        $total_productivity=0;
                        $count=0;       
                        $mergedActivities = array_merge($mergedActivities, $record);
                        foreach ($record as $activity) {
                            $durationParts = explode(':', $activity['duration']);
                            $seconds = ($durationParts[0] * 3600) + ($durationParts[1] * 60) + $durationParts[2];
                            $totalDuration += $seconds;
                            $total_productivity += $activity['productivity'];
                            $count++;
                            $userProjects = collect($record)
                            ->groupBy('project_id')
                            ->map(function ($groupedRecords) {
                            $mergedRecord = null;
                            foreach ($groupedRecords as $rec) {
                                if (!$mergedRecord) {
                                    $mergedRecord = $rec;
                                } else {
                                    $mergedRecord['minutes'] += $rec['minutes'];
                                }
                            }
                            $mergedRecord['formatted_minutes'] = gmdate('H:i:s', $mergedRecord['minutes'] * 60);
                            return $mergedRecord;
                        })
                        ->values()
                        ->sortByDesc('minutes');
                        }
                        $totalDurationFormatted = gmdate('H:i:s', $totalDuration);
                        $totalProductivityFormatted = intval(round(($total_productivity/$count),0));
                        $member=User::where('id',$memberid)->first();
                        $memberName=$member->firstname.' '.$member->lastname;
                        $managerUsers[$memberid]=[
                            'user_projects' => $userProjects,
                            'member_name' => $memberName,
                            'total_duration' => $totalDurationFormatted,
                            'total_productivity' => $totalProductivityFormatted,
                        ];

                    }
                    }
                    $totalTimeSeconds = 0;
                    $totalActivity = 0;
                    $totalUsers = count($managerUsers);

                    foreach ($managerUsers as $userData) {
                    //    $totalTime += strtotime($userData['total_duration']) - strtotime('00:00:00');
                       $time = $userData['total_duration'];
                       $timeParts = explode(':', $time);
                       $hours = (int) $timeParts[0];
                       $minutes = (int) $timeParts[1];
                       $seconds = (int) $timeParts[2];
                       $totalTimeSeconds += $hours * 3600 + $minutes * 60 + $seconds;
                       $totalActivity += $userData['total_productivity'];
                    }
                    $hours = floor($totalTimeSeconds / 3600);
                    $minutes = floor(($totalTimeSeconds % 3600) / 60);
                    $seconds = $totalTimeSeconds % 60;

                    $totalTime = sprintf('%02d:%02d:%02d', $hours, $minutes, $seconds);
                    if ($totalUsers > 0) {
                        $averageActivity = ($totalActivity / $totalUsers) - floor($totalActivity / $totalUsers) >= 0.5 ? ceil($totalActivity / $totalUsers) : floor($totalActivity / $totalUsers);
                    } else {
                        $averageActivity = 0;
                    }
                    // $totalTime=gmdate('H:i:s', $totalTime);
                    
                    $top5Members = [];
                    usort($managerUsers, function ($a, $b) {
                        $durationA = strtotime($a['total_duration']);
                        $durationB = strtotime($b['total_duration']);
                        return $durationB - $durationA;
                    }); 
                    // usort($managerUsers, function ($a, $b) {
                    //     return $b['total_productivity'] - $a['total_productivity'];
                    // });  
                    $top5Members = array_slice($managerUsers, 0, 5);
                    $bottom5Members = [];
                    // usort($managerUsers, function ($a, $b) {
                    //     $durationA = strtotime($a['total_duration']);
                    //     $durationB = strtotime($b['total_duration']);
                    //     return $durationA - $durationB; // Compare in ascending order
                    // });
                    usort($managerUsers, function ($a, $b) {
                        return $a['total_productivity'] - $b['total_productivity']; // Compare in ascending order
                    });                  
                    $bottom5Members = array_slice($managerUsers, 0, 5);
                    $account=Account::where('id',$accountId)->first();
                    $accountName= $account->name;
                    $user=User::where('id',$managerId)->first();
                    $userName=$user->firstname.' '.$user->lastname;
                    $accountUserManager=DB::table('account_user')->where('account_id',$accountId)->where('user_id',$user->id)->first();
                    if($accountUserManager->email_status==1){
                        Mail::to('mianwaqas870@gmail.com')->send(new ManagerDailyWorkSummaryEmail($top5Members,$accountName,$userName,$totalTime,$totalUsers,$averageActivity,$bottom5Members));
                        $total_emails_sent++;
                    }
                    $customEmails = DB::table('manage_custom_emails')
                    ->where('account_id', $accountId)
                    ->where('email_status', 1)
                    ->get();

                    foreach ($customEmails as $customEmail) {
                        Mail::to($customEmail->email)->send(new ManagerDailyWorkSummaryEmail($top5Members, $accountName, $userName, $totalTime, $totalUsers, $averageActivity, $bottom5Members));
                        $total_emails_sent++;
                    }
                }
              }
            }
        }
        foreach ($membersRecords as $accountId => $userData) {
            $total_productivity=0;
            $count=0;
            if ($userData) {
                $membersAccountData = [];
                foreach ($userData as $userId => $records) {
                    if($records){
                    $totalDuration = 0; 
                    foreach ($records as $record) {
                        $durationParts = explode(':', $record['duration']);
                        $seconds = ($durationParts[0] * 3600) + ($durationParts[1] * 60) + $durationParts[2];
                        $totalDuration += $seconds;
                        $total_productivity+= $record['productivity'];
                        $count+=1;
                        $userProjects = collect($records)
                        ->groupBy('project_id')
                        ->map(function ($groupedRecords) {
                            $mergedRecord = null;
                            foreach ($groupedRecords as $record) {
                                if (!$mergedRecord) {
                                    $mergedRecord = $record;
                                } else {
                                    $mergedRecord['minutes'] += $record['minutes'];
                                }
                            }
                            $mergedRecord['formatted_minutes'] = gmdate('H:i:s', $mergedRecord['minutes'] * 60);
                            return $mergedRecord;
                        })
                        ->values()
                        ->sortByDesc('minutes');
                    }
                    $totalDurationFormatted = gmdate('H:i:s', $totalDuration);
                    $membersAccountData[$userId] = [
                        'total_duration' => $totalDurationFormatted,
                        'total_productivity' => intval(round(($total_productivity/$count),0)),
                        'user_projects' =>   $userProjects,
                    ];
                    $account=Account::where('id',$accountId)->first();
                    $accountName= $account->name;
                    $user=User::where('id',$userId)->first();
                    $userName=$user->firstname.' '.$user->lastname;
                    Mail::to($user->email)->send(new MemberDailyWorkSummaryEmail($membersAccountData,$accountName,$userName));
                    $total_emails_sent++;
                }
              }
            }
        }
        $totalTime = CarbonInterval::create(0, 0, 0);
        $userId=[];
        $total_productivity=0;
        $count=0;
        foreach ($ownersRecords as $accountId => $data) {
          if($data){
            foreach ($data as $record) {
            $topProjects = collect($data)
            ->groupBy('project_id')
            ->map(function ($groupedRecords) {
                $mergedRecord = null;
                foreach ($groupedRecords as $record) {
                    if (!$mergedRecord) {
                        $mergedRecord = $record;
                    } else {
                        $mergedRecord['minutes'] += $record['minutes'];
                    }
                }
                return $mergedRecord;
            })
            ->values()
            ->sortByDesc('minutes');
            
            $topMembers = collect($data)
            ->groupBy('user_id')
            ->map(function ($groupedRecords) {
                $mergedData = [];
                foreach ($groupedRecords as $record) {
                    if (isset($mergedData[$record['user_id']])) {
                        $mergedData[$record['user_id']]['minutes'] += $record['minutes'];
                        $mergedData[$record['user_id']]['productivity'] += $record['productivity'];
                    } else {
                        $mergedData[$record['user_id']] = $record;
                    }
                }
                return $mergedData;
            })
            ->flatten(1)
            ->sortByDesc('minutes')
            ->take(5);
            $lowMembers = collect($data)
            ->groupBy('user_id')
            ->map(function ($groupedRecords) {
                $mergedData = [];
                foreach ($groupedRecords as $record) {
                    if (isset($mergedData[$record['user_id']])) {
                        $mergedData[$record['user_id']]['minutes'] += $record['minutes'];
                        $mergedData[$record['user_id']]['productivity'] += $record['productivity'];
                    } else {
                        $mergedData[$record['user_id']] = $record;
                    }
                }
                return $mergedData;
            })
            ->flatten(1)
            ->sortBy('productivity')
            ->take(2);
            $topMembersRecord=[];
            foreach ($topMembers as $value) {
                $totalMinutes=$value['minutes'];
                $user_id=$value['user_id'];
                $productivity=$value['productivity'];
                $user=User::where('id',$user_id)->first();
                $user_name=$user->firstname.' '.$user->lastname;
                $hours = floor($totalMinutes / 60);
                $remainingMinutes = $totalMinutes % 60;
                $seconds = 0;
                $membersDuration= sprintf('%02d:%02d:%02d', $hours, $remainingMinutes, $seconds);
                $topMembersRecord[]=[
                    'duration' => $membersDuration,
                    'user_name' => $user_name,
                    'productivity' => $productivity,
                ];
            }
            $lowMembersRecord=[];
            foreach ($lowMembers as $value) {
                $totalMinutes=$value['minutes'];
                $user_id=$value['user_id'];
                $productivity=$value['productivity'];
                $user=User::where('id',$user_id)->first();
                $user_name=$user->firstname.' '.$user->lastname;
                $hours = floor($totalMinutes / 60);
                $remainingMinutes = $totalMinutes % 60;
                $seconds = 0;
                $membersDuration= sprintf('%02d:%02d:%02d', $hours, $remainingMinutes, $seconds);
                $lowMembersRecord[]=[
                    'duration' => $membersDuration,
                    'user_name' => $user_name,
                    'productivity' => $productivity,
                ];
            }
            $topProjectRecord=[];
            foreach ($topProjects as $value) {
                $totalMinutes=$value['minutes'];
                $project_title=$value['project_title'];
                $productivity=$value['productivity'];
                $hours = floor($totalMinutes / 60);
                $remainingMinutes = $totalMinutes % 60;
                $seconds = 0;
                $membersDuration= sprintf('%02d:%02d:%02d', $hours, $remainingMinutes, $seconds);
                $topProjectRecord[]=[
                    'duration' => $membersDuration,
                    'project_title' => $project_title,
                    'productivity' => $productivity,
                ];
            }
            $duration = $record['duration']; // Parse the duration string
            $userId[]= $record['user_id'];
            $total_productivity+= $record['productivity'];
            $count+=1;
            $timeParts = explode(':', $duration);
            $hours = (int) $timeParts[0];
            $minutes = (int) $timeParts[1];
            $seconds = (int) $timeParts[2];
            $totalTime = $totalTime->addHours($hours)
                                   ->addMinutes($minutes)
                                   ->addSeconds($seconds);
                                   
        }
        // Calculate total duration in seconds
        $totalDurationInSeconds = ($totalTime->hours * 3600) + ($totalTime->minutes * 60) + $totalTime->seconds;
        $totalHours = floor($totalDurationInSeconds / 3600);
        $totalMinutes = floor(($totalDurationInSeconds % 3600) / 60);
        $totalSeconds = $totalDurationInSeconds % 60;
        $totalDurationFormatted = sprintf('%02d:%02d:%02d', $totalHours, $totalMinutes, $totalSeconds);
        $uniqueUserIds = array_unique($userId);
        $totalMembers=count($uniqueUserIds);
        $accountData[$accountId] = [
            'total_duration' => $totalDurationFormatted,
            'total_members' =>  $totalMembers,
            'total_productivity' => intval(round(($total_productivity/$count),0)),
            'top_members' =>   $topMembersRecord,
            'low_members' =>   $lowMembersRecord,
            'top_project' =>   $topProjectRecord,
        ];
        $owner=DB::table('account_user')->where(['account_id'=> $accountId,'role'=>'owner'])->first();
        $account=Account::where('id',$accountId)->first();
        $accountName= $account->name;
        $userMail=User::where('id',$owner->user_id)->first();
        Mail::to($userMail->email)->send(new DailyWorkSummaryEmail($accountData,$accountName));
        $total_emails_sent++;
        $totalTime = CarbonInterval::create(0, 0, 0);
        $userId=[]; 
        $productivity=0;
    }
    }
    $end_time = date('Y-m-d H:i:s');
    DB::table('summary_logs')->insert([
        'start_datetime' => $start_time,
        'end_datetime' =>  $end_time,
        'total_emails_sent' => $total_emails_sent,
    ]);
    

 }
 public function getUsersReport()
 {    
     $allArrayData = [];
     $accounts=Account::all();
     $accountIds = $accounts->pluck('id');
     foreach($accountIds as $account_id)
     {
     $results = Activity::where('activities.date', $this->date)
     ->where('activities.account_id',$account_id)
     ->leftJoin('tasks', 'activities.task_id', '=', 'tasks.id')
     ->leftJoin('projects', 'activities.project_id', '=', 'projects.id')
     ->select('activities.*', 
              DB::raw('COALESCE(tasks.title, "No-todo") as task_title'),
              DB::raw('COALESCE(projects.title, "No-todo") as project_title'))
     ->orderBy('activities.user_id')
     ->get();
     $ss = [];
     $arrayData = [];
     $seconds_sum_of_day = 0;
     $seconds_sum = 0;
     $productivity=0;
     $count=0;
     $start_time_index=0;
     $i=0;
     $j=0;
     foreach($results as $index=>$result){
         $startDateTime = Carbon::parse($result->end_datetime);
         $seconds_sum_of_day += $result->seconds;
         if(isset($results[$index+1])){
             if($seconds_sum == 0){
                 $start_time_index = $index;
             }
             $endDateTime = Carbon::parse($results[$index+1]->start_datetime);
             
             $seconds_sum += $result->seconds;
             $productivity+= $result->total_activity_percentage;
             $count+=1;
             $diffInSeconds = $startDateTime->diffInSeconds($endDateTime);
             $ss[]=$diffInSeconds;
             if($result->user_id != $results[$index+1]->user_id){
                $j++;
                 $arrayData[] = [
                     'user_id' => $result->user_id,
                     'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
                     'end_time' => $result->end_datetime->format('h:i A'),
                     'date' => $result->date->format('Y-m-d'),
                     'duration'=> CarbonInterval::seconds($seconds_sum)->cascade()->format('%H:%I:%S'),
                     'minutes'=> $seconds_sum/60,
                     'productivity' => intval(round(($productivity/$count),0)),
                     'project_id' => $result->project_id,
                     'project_title' => $result->project_title,
                     'task_id' => $result->task_id,
                     'account_id' => $result->account_id,
                     'task_title' =>  isset($result->task_id)  ? $result->task_title : 'No to-do',  
                 ];
                 $seconds_sum = 0;
                 $productivity = 0;
                 $count =0; 
             }
         }   
     }
     //  Code to handle the last index
     $lastIndex = count($results) - 1;
     if ($seconds_sum > 0 && isset($results[$lastIndex])) {
         $lastResult = $results[$lastIndex];
         $arrayData[] = [
             'user_id' => $lastResult->user_id,
             'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
             'end_time' => $lastResult->end_datetime->format('h:i A'),
             'date' => $lastResult->date->format('Y-m-d'),
             'duration' => CarbonInterval::seconds($seconds_sum+600)->cascade()->format('%H:%I:%S'),
             'minutes' => ($seconds_sum / 60)+10,
             'productivity' => intval(round(($productivity/$count),0)),
             'project_id' => $lastResult->project_id,
             'project_title' => $lastResult->project_title,
             'task_id' => $lastResult->task_id,
             'account_id' => $lastResult->account_id,
             'task_title' => isset($lastResult->task_id) ? $lastResult->task_title : 'No to-do',
         ];
 } 
 $allArrayData[$account_id] = $arrayData;
 }
 return $allArrayData;  
}
public function getMemberUsersReport()
{   
    $allAccountData = [];
    $accounts=Account::all();
    $accountIds = $accounts->pluck('id');
    foreach($accountIds as $account_id)
    {
        $users=DB::table('account_user')->where(['account_id'=> $account_id,'role'=>'member'])->get();
        $allArrayData = [];
        foreach($users as $user){
        $results = Activity::where('activities.date', $this->date)
        ->where('activities.user_id',$user->user_id)
        ->where('activities.account_id',$account_id)
        ->leftJoin('tasks', 'activities.task_id', '=', 'tasks.id')
        ->leftJoin('projects', 'activities.project_id', '=', 'projects.id')
        ->select('activities.*', 
                 DB::raw('COALESCE(tasks.title, "No-todo") as task_title'),
                 DB::raw('COALESCE(projects.title, "No-todo") as project_title'))
        ->orderBy('activities.start_datetime')
        ->get();
        $ss = [];
        $arrayData = [];
        $seconds_sum_of_day = 0;
        $seconds_sum = 0;
        $productivity=0;
        $count=0;
        $start_time_index=0;
        $i=0;
        $j=0;
        foreach($results as $index=>$result){
        $startDateTime = Carbon::parse($result->end_datetime);
        $seconds_sum_of_day += $result->seconds;
        if(isset($results[$index+1])){
            if($seconds_sum == 0){
                $start_time_index = $index;
            }
            if($results[$index]->start_datetime==$results[$index+1]->start_datetime){
            // $seconds_sum += $result->seconds;
                continue;
            }
            $endDateTime = Carbon::parse($results[$index+1]->start_datetime);
            
            $seconds_sum += $result->seconds;
            $productivity+= $result->total_activity_percentage;
            $count+=1;
            $diffInSeconds = $startDateTime->diffInSeconds($endDateTime);
            $ss[]=$diffInSeconds;
            if($diffInSeconds > 0 || ($result->task_id != $results[$index+1]->task_id) ){
               $j++;
                $arrayData[] = [
                    'user_id' => $result->user_id,
                    'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
                    'end_time' => $result->end_datetime->format('h:i A'),
                    'date' => $result->date->format('Y-m-d'),
                    'duration'=> CarbonInterval::seconds($seconds_sum)->cascade()->format('%H:%I:%S'),
                    'minutes'=> $seconds_sum/60,
                    'productivity' => intval(round(($productivity/$count),0)),
                    'project_id' => $result->project_id,
                    'project_title' => $result->project_title,
                    'task_id' => $result->task_id,
                    'account_id' => $result->account_id,
                    'task_title' =>  isset($result->task_id)  ? $result->task_title : 'No to-do',
                    
                ];
                
                
                $seconds_sum = 0;
                $productivity = 0;
                $count =0; 
            }
        } 
    }

    // / Code to handle the last index
    $lastIndex = count($results) - 1;
    if ($seconds_sum > 0 && isset($results[$lastIndex])) {
        $lastResult = $results[$lastIndex];
        $arrayData[] = [
            'user_id' => $lastResult->user_id,
            'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
            'end_time' => $lastResult->end_datetime->format('h:i A'),
            'date' => $lastResult->date->format('Y-m-d'),
            'duration' => CarbonInterval::seconds($seconds_sum+600)->cascade()->format('%H:%I:%S'),
            'minutes' => ($seconds_sum / 60)+10,
            'productivity' => intval(round(($productivity/$count),0)),
            'project_id' => $lastResult->project_id,
            'project_title' => $lastResult->project_title,
            'task_id' => $lastResult->task_id,
            'account_id' => $lastResult->account_id,
            'task_title' => isset($lastResult->task_id) ? $lastResult->task_title : 'No to-do',
        ];
}
$allArrayData[$user->user_id] = $arrayData;
}
$allAccountData[$account_id]=$allArrayData;
}
return $allAccountData; 
}
public function getManagerUsersReport()
{  
    $allAccountDataFull = [];
    $accounts=Account::all();
    $accountIds = $accounts->pluck('id');
    foreach($accountIds as $account_id)
    {
        $mamagers=DB::table('account_user')->where(['account_id'=> $account_id,'role'=>'manager'])->get();
        $allAccountData = [];
        foreach($mamagers as $mamager){
            $userDepartment=DB::table('department_admin')->where('user_id',$mamager->user_id)->get();
            $departmentIds=[];
            foreach($userDepartment as $department){
                $departmentIds[]=$department->department_id;
            }
            $uniqueDepartmentIds = array_unique($departmentIds);
            $userIds=[];
            foreach($uniqueDepartmentIds as $id){
            $usersOfDepartment=DB::table('department_user')->where('department_id',$id)->get();
            foreach($usersOfDepartment as $user){
                $userIds[]=$user->user_id;
            }
        }
        $uniqueUserIds = array_unique($userIds);
        $allArrayData = [];
        foreach($uniqueUserIds as $userid){
        $results = Activity::where('activities.date',$this->date)
        ->where('activities.user_id',$userid)
        ->where('activities.account_id',$account_id)
        ->leftJoin('tasks', 'activities.task_id', '=', 'tasks.id')
        ->leftJoin('projects', 'activities.project_id', '=', 'projects.id')
        ->select('activities.*', 
                 DB::raw('COALESCE(tasks.title, "No-todo") as task_title'),
                 DB::raw('COALESCE(projects.title, "No-todo") as project_title'))
        ->orderBy('activities.start_datetime')
        ->get();
        $ss = [];
        $arrayData = [];
        $seconds_sum_of_day = 0;
        $seconds_sum = 0;
        $productivity=0;
        $count=0;
        $start_time_index=0;
        $i=0;
        $j=0;
        foreach($results as $index=>$result){
        $startDateTime = Carbon::parse($result->end_datetime);
        $seconds_sum_of_day += $result->seconds;
        if(isset($results[$index+1])){
            if($seconds_sum == 0){
                $start_time_index = $index;
            }
            if($results[$index]->start_datetime==$results[$index+1]->start_datetime){
            // $seconds_sum += $result->seconds;
                continue;
            }
            $endDateTime = Carbon::parse($results[$index+1]->start_datetime);
            
            $seconds_sum += $result->seconds;
            $productivity+= $result->total_activity_percentage;
            $count+=1;
            $diffInSeconds = $startDateTime->diffInSeconds($endDateTime);
            $ss[]=$diffInSeconds;
            if($diffInSeconds > 0 || ($result->task_id != $results[$index+1]->task_id) ){
               $j++;
                $arrayData[] = [
                    'user_id' => $result->user_id,
                    'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
                    'end_time' => $result->end_datetime->format('h:i A'),
                    'date' => $result->date->format('Y-m-d'),
                    'duration'=> CarbonInterval::seconds($seconds_sum)->cascade()->format('%H:%I:%S'),
                    'minutes'=> $seconds_sum/60,
                    'productivity' => intval(round(($productivity/$count),0)),
                    'project_id' => $result->project_id,
                    'project_title' => $result->project_title,
                    'task_id' => $result->task_id,
                    'account_id' => $result->account_id,
                    'task_title' =>  isset($result->task_id)  ? $result->task_title : 'No to-do',
                    
                ];
                $seconds_sum = 0;
                $productivity = 0;
                $count =1; 
            }
        }
    }

    // / Code to handle the last index
    $lastIndex = count($results) - 1;
    if ($seconds_sum > 0 && isset($results[$lastIndex])) {
        $lastResult = $results[$lastIndex];
        $arrayData[] = [
            'user_id' => $lastResult->user_id,
            'start_time' => $results[$start_time_index]->start_datetime->format('h:i A'),
            'end_time' => $lastResult->end_datetime->format('h:i A'),
            'date' => $lastResult->date->format('Y-m-d'),
            'duration' => CarbonInterval::seconds($seconds_sum+600)->cascade()->format('%H:%I:%S'),
            'minutes' => ($seconds_sum / 60)+10,
            'productivity' => intval(round(($productivity/$count),0)),
            'project_id' => $lastResult->project_id,
            'project_title' => $lastResult->project_title,
            'task_id' => $lastResult->task_id,
            'account_id' => $lastResult->account_id,
            'task_title' => isset($lastResult->task_id) ? $lastResult->task_title : 'No to-do',
        ];
}
$allArrayData[$userid] = $arrayData;
}
$allAccountData[$mamager->user_id]=$allArrayData;
}
$allAccountDataFull[$account_id]=$allAccountData;
}
return $allAccountDataFull; 
}

}
