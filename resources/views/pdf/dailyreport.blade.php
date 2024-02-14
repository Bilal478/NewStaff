<?php
use Carbon\Carbon;
$preWeekTotalDurationFormatted=$previousWeekUsers[0];
$preWeekaverageProductivityFormatted=$previousWeekUsers[1];
$totalDuration = collect($users)->sum(function ($item) {
// Convert duration to seconds and sum them up
return strtotime($item['duration']) - strtotime('00:00:00');
});

// Convert total seconds back to HH:MM:SS format
$totalDurationFormatted = gmdate('H:i:s', $totalDuration);
$totalProductivity = collect($users)->sum('productivity');

// Calculate average productivity
$averageProductivity = count($users) > 0 ? $totalProductivity / count($users) : 0;

// Format average productivity as needed
$averageProductivityFormatted = number_format($averageProductivity); 
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>{{$userName}}_timesheet_report_{{ $week }}</title>
        <style type="text/css">
            * { 
                -webkit-text-size-adjust: none; }
            body {
                margin: 0 !important; 
                padding: 0 !important; 
            }
            body,table,td,p,a {
                -ms-text-size-adjust: 100% !important;
                -webkit-text-size-adjust: 100% !important; 
            }
            table, tr, td {
                border-spacing: 0 !important;
                mso-table-lspace: 0px !important;
                mso-table-rspace: 0pt !important;
                border-collapse: collapse !important;
                mso-line-height-rule:exactly !important;
            }
            th {
                border-bottom: 2px solid #E5E7EB !important; 
            }
            td {
                border-bottom: 1px solid #E5E7EB !important; 
            }
            .wrapper {
                display: flex;
                flex-direction: column;
                min-height: 100vh;
            }
            .content {
                flex: 1;
                padding-bottom: 60px; /* Adjust padding to accommodate the footer height */
            }
            .footer {
                padding: 10px;
                position: fixed;
                left: 0;
                bottom: 0;
                width: 100%;
            }
            #neo_link{
                padding-left: 200px
            }

            /* Media query to adjust the footer position on smaller screens */
            @media (max-height: 600px) {
                .footer {
                    position: static;
                }
            }
        </style>
    </head>
    <body>
        <div class="wrapper">
            <div class="content">
                <div>
                    <span style="float: right">{{ $week }}</span><br>
                    <hr style="color: gray; margin-top:0px;"><br>
                    <h1 style="font-size: 18px; font-weight: bold; text-align: center; padding-bottom: 2px;">
                        Timesheet Report - {{$userName}}
                    </h1><br><br>
                </div>

                <div style="display: flex; justify-content: space-between;">
                    <div style="flex: 1;">
                        <span>Total Hours = {{$totalDurationFormatted}}</span>
                        <span style="margin-left: 15px">Total Activity = {{$averageProductivityFormatted}}%</span>
                    </div>
                    <div style="flex: 1; text-align: right;">
                        <span>Previous Week Total Hours = {{$preWeekTotalDurationFormatted}}</span>
                        <span style="margin-left: 15px">Previous Week Total Activity = {{$preWeekaverageProductivityFormatted}}%</span>
                    </div>
                </div>

        <div cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 100%;">
            <table style="width: 100%;">
            <tbody>
        
            @foreach ($dates as $date)
                <?php
                $totalDuration = collect($users)
                ->where('date', $date->format('Y-m-d'))
                ->sum(function ($item) {
                // Convert duration to seconds and sum them up
                return strtotime($item['duration']) - strtotime('00:00:00');
                });

                // Convert total seconds back to HH:MM:SS format
                $totalDurationFormatted = gmdate('H:i:s', $totalDuration);
                ?>
                <?php $inner_count = 0; ?>
            <tr style="color: #374151; font-size: 12px; text-align: left; border-bottom: 1px solid #E5E7EB;">
            <th style="padding: 15px 10px; text-align: left;">
                {{ $date->format('D,M d, Y') }}      
            </th>
            <th style="padding: 15px 10px; text-align: left;">
                 @if ($totalDurationFormatted != '00:00:00')
                     <span>{{ $totalDurationFormatted }} hrs</span>
                @endif
            </th>
            <th style="padding: 15px 10px; text-align: left;">
                
            </th>
            <th style="padding: 15px 10px; text-align: left;">
               
            </th>
            <th style="padding: 15px 10px; text-align: left;">
               
            </th>
            <th style="padding: 15px 10px; text-align: left;">
               
            </th>
            <th style="padding: 15px 10px; text-align: left;">
               
            </th>
            <th style="padding: 15px 10px; text-align: left;">
               
            </th>
            <th style="padding: 15px 10px; text-align: left;">
               
            </th>
            <th style="padding: 15px 10px; text-align: left;">
               
            </th>
            <th style="padding: 15px 10px; text-align: left;">
               
            </th>
            </tr>
            
            @foreach ($users as $day)
               
                @if($date->format('Y-m-d') == $day['date'])
                    @if($inner_count==0)
                   
                    <tr  style="color: #374151; font-size: 12px; text-align: left; border-bottom: 1px solid #E5E7EB;">
                    
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Organization
                    </td>
                   
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Project
                    </td>
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Task
                    </td>
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Activity
                    </td>
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Idle
                    </td> 
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Manual
                    </td> 
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Duration
                    </td> 
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Start
                    </td> 
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Stop
                    </td> 
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Type
                    </td> 
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Payment Type
                    </td>
                </tr>
                @endif
                <?php $inner_count = 1; ?>
                <tr style="color: #374151; font-size: 12px; text-align: left; border-bottom: 1px solid #E5E7EB; @if($loop->odd) background-color: #f3f4f6; @endif">
                   
                    <td style="padding: 15px 10px; text-align: left;">
                        <p><span class="taskTitle"> {{ $day['account_name'] }}</span></p>
                        
                    </td>
                    <td style="padding: 15px 10px; text-align: left;">
                   
                   <p><span class="taskTitle"> {{ $day['project_title'] }}</span></p>
                   
               </td>
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle"> {{ $day['task_title'] }}</span></p>
                   
               </td>
               
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle"> {{ $day['productivity'] }}%</span></p>
                   
               </td>
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle"> {{ $day['idle_percentage'] }}%</span></p>
                   
               </td>
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle"> {{ $day['manual_percentage'] }}%</span></p>
                   
               </td>
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle"> {{ $day['duration'] }}</span></p>
                   
               </td>
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle"> {{ $date->format('D, M d, Y') }} {{ Carbon::createFromFormat('h:i A', $day['start_time'])->format('g:i a') }}</span></p>
                   
               </td>
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle">{{ $date->format('D, M d, Y') }} {{ Carbon::createFromFormat('h:i A', $day['end_time'])->format('g:i a') }}</span></p>
                   
               </td>
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle"> Time Entry</span></p>
                   
               </td>
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle"> Billable</span></p>
                   
               </td>
                   
                </tr>
                @endif
                @endforeach
                @endforeach 
            </tbody>
         </table>
        </div>
               
       </div>
         <div class="footer">
            <span style="color: gray">Generated With </span><b style="color: blueviolet">NeoStaff</b><a href="#" style="margin-left: 767px">neostaff.app</a>   
         </div>
      </div>
    </body>
</html>