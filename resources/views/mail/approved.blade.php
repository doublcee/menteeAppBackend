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
    <p>Dear <b>{{ $fname }} {{ $lname }}</b> Your Account has successfully been Approved.</p>

    <p>
        Your login credentials are <br/><br/>
        <b>Email: {{ $email }}</b><br/>
        <b>Password: {{ $pass }}</b><br/>
    </p>
    <p>Thank You</p>

    <br/>
    <p style="font-style:italic; font-weight:bold; color:#81007F">Pursuit of Purpose Network Team.</p>
</div>
</body>
</html>
