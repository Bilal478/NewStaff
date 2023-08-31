@php
// dd($data,$accountName,$userName);
    foreach($data as $key=>$value){
        $totalHours=$value['total_duration'];
        $totalProductivity=$value['total_productivity'];
        $userProjects=$value['user_projects'];
    }
@endphp
<!DOCTYPE html>
<html>
<head>
</head>
<body style="padding: 0; margin: 0; font-family: Arial, sans-serif; background-color: #f7f7f7;">
    <div style="padding: 30px 30px 0px 30px; text-align: center; font-size: 24px; font-weight: bold;">
        <a href="{{ route('home') }}" class="inline-block">
            <x-logo/>
        </a>
    </div>
    <div style="max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 30px; border-radius: 5px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);">
        <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="font-family: 'Helvetica Neue', sans-serif; color: #333333; margin: 0;">Daily Work Summary for {{$accountName}}</h1>
            <p style="color: #777777; margin: 5px 0 0;">{{ now()->subDay()->format('D, M j, Y') }}</p>
        </div>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th style="background-color: #f3f3f3; padding: 10px; text-align: left;">Member</th>
                <th style="background-color: #f3f3f3; padding: 10px; text-align: left;">Hours Worked</th>
                <th style="background-color: #f3f3f3; padding: 10px; text-align: left;">Activity</th>
            </tr>
            <tr>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{$userName}}</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{$totalHours}}</td>
                <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{$totalProductivity}}%</td>
            </tr>
        </table>
        <h2 style="margin-top: 20px;">Projects Worked</h2>
        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <th style="background-color: #f3f3f3; padding: 10px; text-align: left;">Project</th>
                <th style="background-color: #f3f3f3; padding: 10px; text-align: left;">Hours Worked</th>
                <th style="background-color: #f3f3f3; padding: 10px; text-align: left;">Activity</th>
            </tr>
            @foreach ($userProjects as $project)
                <tr>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{$project['project_title']}}</td>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{$project['formatted_minutes']}} total</td>
                    <td style="padding: 10px; border-bottom: 1px solid #ddd;">{{$project['productivity']}}% active</td>
                </tr>
            @endforeach
        </table>
    </div>
</body>
</html>
         

