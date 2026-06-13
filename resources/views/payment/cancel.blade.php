<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran Dibatalkan</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>
<body>
    <div style="text-align:center;margin-top:50px;">
        <h1 style="color:orange;">⚠️ Pembayaran Dibatalkan</h1>
        <p>Anda membatalkan proses pembayaran.</p>
        <a href="{{ route('sewa.index') }}" style="padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;">Coba Lagi</a>
    </div>
</body>
</html>