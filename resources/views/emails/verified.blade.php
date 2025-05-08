<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Yeay! Akun Kamu Sudah Terverifikasi! 🎉</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            border: 1px solid #e0e0e0;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .email-header {
            text-align: center;
            margin-bottom: 20px;
        }

        .email-body {
            font-size: 16px;
            line-height: 1.6;
        }

        .otp-code {
            font-size: 24px;
            font-weight: bold;
            color: #EE1B2F;
            margin: 20px 0;
            text-align: center;
        }

        .email-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888888;
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f0f0f0; color: #333; padding: 20px;">
    <div class="email-container"
        style="max-width: 600px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <div class="email-header" style="text-align: center; margin-bottom: 20px;">
            <h2>Kalasewa</h2>
        </div>
        <div class="email-body">
            <p>Hey, <strong>{{ $user->nama }}</strong>!</p>
            <p>Selamat ya! Akun kamu udah berhasil diverifikasi. Sekarang kamu bisa akses semua fitur keren di platform
                kami.</p>
            <p>Kami seneng banget kamu udah join bersama kami. Kalau ada pertanyaan atau butuh bantuan, jangan ragu
                untuk hubungi tim support kami ya.</p>
            <p>Thanks udah bergabung!</p>
            <p style="font-size: 0.8em;">Cheers,<br>Tim Kalasewa</p>
        </div>
        <div class="email-footer" style="text-align: center; margin-top: 20px;">
            <p>&copy; {{ date('Y') }} Kalasewa. All rights reserved.</p>
        </div>
    </div>
</body>

</html>