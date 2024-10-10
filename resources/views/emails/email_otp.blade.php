<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }
        h1 {
            color: #007bff;
        }
        .qr-container {
            text-align: center;
            margin: 20px 0;
        }
        .qr-container img {
            width: 150px;
            height: 150px;
        }
        p {
            font-size: 16px;
        }
        a {
            background-color: #007bff;
            color: white;
            padding: 10px 15px;
            text-decoration: none;
            border-radius: 5px;
            display: inline-block;
            margin-top: 10px;
        }
        a:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Your OTP Code</div>
        <p>Hi there!</p>
        <p>Your one-time password (OTP) is:</p>
        <div class="otp">{{ $otp_code }}</div>
        <p>This OTP is valid for {{ $otp_expiry_minutes }} minutes. Please do not share it with anyone.</p>
        <div class="footer">
            <p>If you did not request this OTP, please ignore this email.</p>
            <p>Thank you,<br>Your Company Team</p>
        </div>
    </div>
</body>
</html>

