<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <style>
        .sidebar, .content{
            float: left;
        }
        footer {
            clear: both;
        }
    </style>
</head>
<body>
    <header>
        <h1>Header</h1>
    </header>
    <main>
        <div class="sidebar">
            <h1>Sidebar</h1>
        </div>
        <div class="content">
            @yield('content')
        </div>
    </main>
    <footer>
        <h1>Footer</h1>
    </footer>
</body>
</html>