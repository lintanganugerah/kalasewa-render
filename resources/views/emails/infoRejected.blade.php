<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>OTP Verification</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css" rel="stylesheet">
    <style>
    body,
    html {
        height: 100%;
        margin: 0;
        display: flex;
        justify-content: center;
        align-items: center;
    }

    .email-container {
        max-width: 600px;
        padding: 20px;
        border: 1px solid #e0e0e0;
        border-radius: 10px;
        background-color: #f9f9f9;
    }

    .email-image {
        text-align: center;
        margin-bottom: 20px;
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

    .logo-kalasewa {
        width: 25%;
    }
    </style>
</head>

<body>
    <div class="email-container">
        <div class="email-image">
            <h1>Kalasewa</h1>
        </div>
        <div class="email-header">
            <h3>Dear, Lintang</h3>
        </div>
        <div class="email-body">
            <p>Terima kasih telah mendaftar di Kalasewa. Namun, kami belum dapat memverifikasi akun anda. Beberapa
                alasan yang menyebabkan akun anda tidak dapat diverifikasi adalah:</p>
            <div class="otp-code"></div>
            <p>{!! nl2br(e('- Kamu belum login Microsoft Flight Simulator selama 2 bulan terakhir')) !!}</p>
            <p></p>
            <p>Salam,</p>
            <p>Tim Kalasewa</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Kalasewa. All rights reserved.</p>
        </div>
    </div>
</body>

</html>