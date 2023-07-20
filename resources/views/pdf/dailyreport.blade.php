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



        <div cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 100%;">
            <table style="width: 100%;">
            <tbody>
        
            @foreach ($dates as $date)
                <?php $inner_count = 0; ?>
            <tr style="color: #374151; font-size: 12px; text-align: left; border-bottom: 1px solid #E5E7EB;">
            <th style="padding: 15px 10px; text-align: left;">
                {{ $date->format('M d, Y') }} 
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
                            Project
                    </td>
                   
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Duration
                    </td>
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Activity
                    </td>
                    <td style="padding: 15px 10px; text-align: left; font-weight:800;">
                            Time
                    </td>
                </tr>
                @endif
                <?php $inner_count = 1; ?>
                <tr style="color: #374151; font-size: 12px; text-align: left; border-bottom: 1px solid #E5E7EB;">
                   
                    <td style="padding: 15px 10px; text-align: left;">
                    {{ $day['project_title'] }}
                        <p><span class="taskTitle"> {{ $day['task_title'] }}</span></p>
                        
                    </td>
                    <td style="padding: 15px 10px; text-align: left;">
                   
                   <p><span class="taskTitle"> {{ $day['duration'] }}</span></p>
                   
               </td>
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle"> {{ $day['productivity'] }}</span></p>
                   
               </td>
               
               <td style="padding: 15px 10px; text-align: left;">
              
                   <p><span class="taskTitle"> {{ $day['start_time'] }} - {{ $day['end_time'] }}</span></p>
                   
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