<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesan Belum Dibaca</title>
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
            <p>Anda memiliki <strong> {{ $totalChat }} </strong> pesan belum dibaca dari <strong> {{ $namaPengirim }}
                </strong></p>
            <p>Silahkan login ke kalasewa untuk membalas pesan tersebut.</p>
            <p>Salam,</p>
            <p>Tim Kalasewa</p>
        </div>
        <div class="email-footer">
            <p>&copy; {{ date('Y') }} Kalasewa. All rights reserved.</p>
        </div>
    </div>
</body>

</html>
