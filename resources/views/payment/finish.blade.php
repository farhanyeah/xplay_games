<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran Berhasil - XPlay Games</title>
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
</head>

<body>
    <div style="text-align:center;margin-top:50px;">
        <h1 style="color:green;">✅ Pembayaran Berhasil!</h1>
        <p>Terima kasih! Pembayaran Anda telah kami terima.</p>
        <a href="{{ route('sewa.index') }}"
            style="padding:10px 20px;background:#007bff;color:white;text-decoration:none;border-radius:5px;">
            Kembali ke Halaman Sewa
        </a>
    </div>
</body>

</html>