<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ding Dong!🔔 Yuk Reset Kata Sandi!</title>
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

        .button {
            display: inline-block;
            padding: 10px 20px;
            font-size: 16px;
            color: #fff;
            background-color: #007bff;
            text-decoration: none;
            border-radius: 5px;
        }

        .button:hover {
            background-color: #0056b3;
        }

        .email-footer {
            text-align: center;
            margin-top: 20px;
            font-size: 14px;
            color: #888888;
        }

        .small-text {
            font-size: 12px;
            color: #888888;
        }
    </style>
</head>

<body style="font-family: Arial, sans-serif; background-color: #f0f0f0; color: #333; padding: 20px;">
    <div class="email-container"
        style="max-width: 1200px; margin: 0 auto; background-color: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1);">
        <div class="email-header" style="text-align: center; margin-bottom: 20px;">
            <h2>{{ config('app.name') }}</h2>
        </div>
        <div class="email-body">
            <p>Hey, sobat!</p>
            <p>Wah, kami dapet info kalau kamu pengen reset kata sandi akunmu. Yuk, langsung aja klik tombol di bawah
                ini buat reset kata sandimu:</p>
            <div style="text-align: center; margin: 20px 0;">
                <a href="{{ $actionUrl }}" class="button" target="_blank">Reset Kata Sandi</a>
            </div>
            <p>Link buat reset kata sandi ini bakal kadaluarsa dalam waktu
                {{ config('auth.passwords.' . config('auth.defaults.passwords') . '.expire') }} menit. Jadi, buruan ya!
            </p>
            <p>Kalo kamu nggak ngerasa minta reset kata sandi, abaikan aja email ini. Nggak usah khawatir!</p>
            <p>Cheers, tim {{ config('app.name') }}</p>
        </div>
        <div class="email-footer" style="text-align: center; margin-top: 20px;">
            <p class="small-text">Kalo kamu kesulitan buat klik tombol "Reset Kata Sandi", copy dan paste URL di bawah
                ini ke browser kamu: <a href="{{ $actionUrl }}">{{ $actionUrl }}</a></p>
            <p class="text-center">&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>

        <div class="regards text-center w-100">

        </div>
    </div>
</body>

</html>