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
            <h1 style="font-size: 18px; font-weight: bold; text-align: center; padding-bottom: 5px;">
                Weekly Report
            </h1>
            <h2 style="font-size: 14px; font-weight: bold; text-align: center; padding-bottom: 20px;">
                {{ $week }}
            </h2>
        </div>
        <div cellspacing="0" cellpadding="0" border="0" align="center" width="100%" style="width: 100%;">
            <table style="width: 100%;">
                <tbody>
                    <tr style="color: #374151; border-bottom: 1px solid #E5E7EB; font-size: 12px; text-align: left; font-weight: bold;">
                        <th style="padding: 15px 10px; text-align: left;">
                            Member
                        </th>
                        @foreach ($dates as $date)
                            <th style="padding: 15px 10px; text-align: left;">
                                {{ $date->format('M d, Y') }}
                            </th>
                        @endforeach
                        <th style="padding: 15px 10px; text-align: left;">
                            Weekly Total
                        </th>
                    </tr>
                    @foreach ($users as $userName => $activity)
                        <tr style="color: #374151; font-size: 12px; text-align: left; border-bottom: 1px solid #E5E7EB;">
                            <td style="padding: 15px 10px; text-align: left;">
                                {{ $userName }}
                            </td>
                            @foreach ($activity['days'] as $day)
                                <td style="padding: 15px 10px; text-align: left;">
                                    {{ $day['seconds'] }}
                                </td>
                            @endforeach
                            <td style="padding: 15px 10px; text-align: left;">
                                {{ $activity['total'] }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </body>
</html>