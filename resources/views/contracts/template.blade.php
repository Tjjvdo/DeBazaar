<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Business Contract</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { width: 80%; margin: 0 auto; padding: 20px; border: 1px solid #000; }
        .title { font-size: 24px; font-weight: bold; text-align: center; }
        .content { margin-top: 20px; }
        .signature { margin-top: 40px; text-align: right; }
    </style>
</head>
<body>
    <div class="container">
        <div class="title">Business Contract</div>
        <div class="content">
            <p><strong>User ID:</strong> {{ $user_id }}</p>
            <p><strong>Date:</strong> {{ $date }}</p>
            <p><strong>Company:</strong> {{ $company }}</p>
        </div>
        <div class="signature">
            <p>Signature: ___________________</p>
        </div>
    </div>
</body>
</html>