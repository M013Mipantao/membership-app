<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your OTP Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f2f2f2;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #ffffff;
            padding: 20px;
            border-radius: 5px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 10px;
        }
        .otp {
            font-size: 36px;
            font-weight: bold;
            color: #007bff;
            margin: 20px 0;
            letter-spacing: 2px;
        }
        .footer {
            margin-top: 20px;
            font-size: 14px;
            color: #777;
        }
        a {
            color: #007bff;
            text-decoration: none;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">Your OTP Code</div>
        <p>Hi there!</p>
        <p>Your one-time password (OTP) is:</p>
        <div class="otp">{{ $otp_code }}</div>
        <p>This OTP is valid for 10 minutes. Please do not share it with anyone.</p>
        <div class="footer">
            <p>If you did not request this OTP, please ignore this email.</p>
            <p>Thank you,<br>Your Company Team</p>
        </div>
    </div>
</body>
</html>
