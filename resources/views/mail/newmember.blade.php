<!doctype html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{$title}}</title>
</head>
<body>
<div>
    <p>Hello Peter, A new member joined the network.</p>
    <br/>

    <strong>Member info:</strong>

    <table cellpadding="10px">

       <tr><td><b>Name:</b></td><td>{{$fname}} {{$lname}}</td></tr>
        <tr><td><b>Career field:</b></td><td>{{$career}}</td></tr>
        <tr><td><b>Role:</b></td><td>{{ $role == 1 ? 'Mentor' : 'Mentee' }}</td></tr>

    </table>

    <p>Please login to control panel to review full member info.</p>

    <p>Thank You.</p>

    <p style="font-style:italic; font-weight:bold; color:#81007F">Pursuit of Purpose Network Team.</p>

</div>
</body>
</html>
