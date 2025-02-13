<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>Lab Reservation From {{ $user->email }}</h1>
    <p>There is new lab reservation request from {{ $user->first_name }} {{ $user->last_name }}.</p>
    <p>
        <strong>Lab:</strong> {{ $labReserve->room->name }}<br>
        <strong>Start Time:</strong> {{ $labReserve->start_time }}<br>
        <strong>End Time:</strong> {{ $labReserve->end_time }}<br>
        <strong>Needs:</strong> {{ $labReserve->needs }}
    </p>
    <p>Thank you for using our reservation system.</p>
</body>
</html>