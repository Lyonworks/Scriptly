<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>404 | Page Not Found</title>
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Poppins', sans-serif;
            background: #f4f6f9;
            margin: 0;
            padding: 0;
            display: flex;
            height: 100vh;
            justify-content: center;
            align-items: center;
        }
        .container {
            text-align: center;
        }
        h1 {
            font-size: 6rem;
            color: #ff4757;
            margin: 0;
        }
        h2 {
            font-size: 2rem;
            margin: 10px 0;
            color: #2f3542;
        }
        p {
            color: #57606f;
            margin-bottom: 20px;
        }
        a {
            display: inline-block;
            padding: 12px 24px;
            background: #2ed573;
            color: white;
            font-weight: bold;
            border-radius: 8px;
            text-decoration: none;
            transition: 0.3s;
        }
        a:hover {
            background: #1eae60;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>404</h1>
        <h2>Oops! Page not found</h2>
        <p>The page you are looking for doesn’t exist or has been moved.</p>
        <a href="{{ url('/') }}">Go Back Home</a>
    </div>
</body>
</html>
