<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Password Reset</title>
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
        <h1>Guest QR Code Confirmation</h1>
        <p>I, <b>{{ $member_name }}</b>, hereby give permission for the guest, <b>{{ $guest_name }}</b> to use my account balance and consumable services</p><br/>
        <p>This consent is for a <b>{{ $visit }}</b> visit on <b>{{ $visit_duration }}</b>.</p><br />
        <p>By clicking the link, the member agreed to allow the guest to use his/her account balance and consumable services. <br />
            <a href="{{ url('/download-qr-code/'.$qr_id) }}">Download QR Code</a>
        </p>
    </div>
</body>
</html>
