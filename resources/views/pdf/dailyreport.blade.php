<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
        <title>Report - {{ $week }}</title>
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
        </style>
    </head>
    <body>
        <div>
            <h1 style="font-size: 18px; font-weight: bold; text-align: center; padding-bottom: 2px;">
                Daily Report
            </h1>
            <h4 style="font-size: 18px; font-weight: bold; text-align: center; padding-bottom: 2px;">
                {{$userName}}
            </h4>
            <h2 style="font-size: 14px; font-weight: bold; text-align: center; padding-bottom: 20px;">
                {{ $week }}
            </h2>
        </div>
        <div cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 100%;">
            <table style="width: 100%;">
                <tbody>
                <div class="w-full overflow-x-auto rounded-md border">
        <table class="w-full bg-white">
            <tbody>
        
            @foreach ($dates as $date)
                <?php $inner_count = 0; ?>
            <tr class="text-left uppercase text-xs text-gray-700 font-medium border-b-2">
            <th class="px-4 py-4">
                {{ $date->format('M d, Y') }} 
            </th>
            </tr>
            
            @foreach ($users as $day)
               
                @if($date->format('Y-m-d') == $day['date'])
                    @if($inner_count==0)
                   
                    <tr class="text-left font-extrabold uppercase text-xs text-gray-700 font-medium border-b-2">
                    
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Project
                    </td>
                   
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Duration
                    </td>
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Activity
                    </td>
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                            Time
                    </td>
                </tr>
                @endif
                <?php $inner_count = 1; ?>
                <tr class="text-left uppercase text-xs text-gray-700 font-medium border-b-2">
                   
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                    {{ $day['project_title'] }}
                        <p><span class="taskTitle"> {{ $day['task_title'] }}</span></p>
                        
                    </td>
                    <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
                   
                   <p><span class="taskTitle"> {{ $day['duration'] }}</span></p>
                   
               </td>
               <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
              
                   <p><span class="taskTitle"> {{ $day['productivity'] }}</span></p>
                   
               </td>
               
               <td class="min-w-52 sticky left-4 top-auto bg-white z-10 px-9 py-5">
              
                   <p><span class="taskTitle"> {{ $day['start_time'] }} - {{ $day['end_time'] }}</span></p>
                   
               </td>
                   
                </tr>
                @endif
                @endforeach
                @endforeach 
            </tbody>
        </table>
    </div>
                </tbody>
            </table>
        </div>
    </body>
</html>