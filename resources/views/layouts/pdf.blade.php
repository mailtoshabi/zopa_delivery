<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>@yield('title', config('app.name'))</title>
    <style>
        body {
            font-family: DejaVu Sans, sans-serif;
            color: #333;
            line-height: 1.5;
            margin: 30px;
        }
        h1, h2, h3, h4, h5 {
            color: #444;
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .header img {
            max-height: 80px;
            margin-bottom: 10px;
        }
        .header h2 {
            margin: 5px 0;
            font-size: 20px;
        }
        .header .slogan {
            font-size: 14px;
            color: #888;
        }
        hr {
            border: none;
            border-top: 1px solid #ccc;
            margin: 20px 0;
        }
        ul {
            padding-left: 20px;
        }
        .footer {
            text-align: center;
            font-size: 12px;
            color: #aaa;
            margin-top: 40px;
        }
        .section-title {
            background: #f5f5f5;
            padding: 5px 10px;
            border-left: 4px solid #e74c3c;
            margin-top: 20px;
            margin-bottom: 10px;
            font-weight: bold;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }
        table th, table td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
    </style>
    @stack('styles')
</head>
<body class="locale-{{ app()->getLocale() }}">

<div class="header">
    <img src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('front/images/logo_red.png'))) }}" alt="Zopa Logo">
    <p style="font-size: 16px; margin-top: 0;">Homely Meals. Every Day. Your Way.</p>
</div>

@yield('content')

<div class="footer">
    © {{ date('Y') }} config('app.name') — www.zopa.in
</div>

</body>
</html>
