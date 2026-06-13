<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kesalahan Pembayaran</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div style="text-align:center;margin-top:50px;">
        <h1 style="color:red;">❌ Terjadi Kesalahan</h1>
        <p>Mohon maaf, terjadi kesalahan.</p>
        <a href="{{ route('home') }}" style="padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;">Kembali ke Home</a>
    </div>
</body>
</html>