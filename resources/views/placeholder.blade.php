<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Placeholder' }}</title>
    <style>
        body { font-family: Arial, sans-serif; padding: 40px; }
        .box { max-width: 720px; padding: 24px; border: 1px solid #ddd; border-radius: 10px; }
        h1 { margin-top: 0; }
    </style>
</head>
<body>
    <div class="box">
        <h1>{{ $title ?? 'Placeholder' }}</h1>
        <p>{{ $message ?? 'This page will be implemented later.' }}</p>
        <p><b>Status:</b> Scaffold working ✅</p>
    </div>
</body>
</html>
