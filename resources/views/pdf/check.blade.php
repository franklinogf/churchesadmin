<!DOCTYPE html>
<html lang="en">

<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Check Test</title>
</head>

<body>

    <div style="width: 7in; height: 3in; position: relative;">
        <img src={{ public_path("assets/check.png") }} style="width: 100%; height: 100%; object-fit: cover; position: absolute; top: 0; left: 0;" />
        <div style="position: absolute; top: 0.5in; left: 1in;">
            <strong>Pay to the order of:</strong> {{ $name }}
        </div>
        <div style="position: absolute; top: 100px; left: 5.5in;">
            <strong>${{ number_format($amount, 2) }}</strong>
        </div>
        <!-- Add other fields similarly -->
    </div>

</body>

</html>
