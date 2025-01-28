<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
</head>
<body>
    <h1>inventory Reservation From {{ $user->email }}</h1>
    <p>There is new inventory reservation request from {{ $user->first_name }} {{ $user->last_name }}.</p>
    <p>
        <strong>inventory:</strong> {{ $inventoryReserf->inventory->item_name }}<br>
        <strong>Start Time:</strong> {{ $inventoryReserf->start_time }}<br>
        <strong>End Time:</strong> {{ $inventoryReserf->end_time }}<br>
        <strong>Needs:</strong> {{ $inventoryReserf->needs }}
    </p>
    <p>Thank you for using our reservation system.</p>
</body>
</html>