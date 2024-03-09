<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title> {{ $holidayPlan['title'] }} </title>
</head>
<body>
    <h1> {{ $holidayPlan['title'] }} </h1>
    <h2> Date: {{ $holidayPlan['date'] }}</h2>

    <p>
        Description: {{ $holidayPlan['description'] }}
    </p>

    <p>
        Location: {{ $holidayPlan['location'] }}
    </p>

    <p>
        Participants: {{ $holidayPlan['participants'] }}
    </p>

</body>
</html>
