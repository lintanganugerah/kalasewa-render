<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
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

<body>
    <div class="email-container">
        <div class="email-header">
            <h2>Kalasewa</h2>
        </div>
        <div class="email-body">
            <p>Anda Mengajukan Penarikan Saldo Penghasilan toko pada {{ date('d/m/Y') }} jam {{ date('h:m:s') }}.
                Lanjutkan penarikan dengan
                memasukkan kode OTP berikut:
            </p>
            <div class="otp-code">{{ $otp }}</div>
            <p>Jika Anda tidak meminta kode ini, Anda bisa mengabaikan email ini.</p>
            <p>Salam,</p>
            <p>Tim Kalasewa</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Kalasewa. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
