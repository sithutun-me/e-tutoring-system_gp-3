<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Welcome Page</title>
    @vite(['resources/js/app.js'])
</head>
<body>
    <div class="container d-flex justify-content-center align-items-center vh-100">
        <h1>welcome</h1>
        <h6><a href="{{ route('login') }}">login</a></h6>

    </div>
</body>
</html>
