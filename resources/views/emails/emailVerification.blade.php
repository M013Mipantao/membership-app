<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Guest QR Code Confirmation</title>
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
        <h1>Guest QR Code Confirmation</h1>
        <p>Balesin Member, <b>{{ $member_name }}</b>, has sent you a QR Code. This QR Code allows the guests <b>{{ $guest_name }}</b>, to use the credit in the Member's wallet.</p><br/>
        <p>This permission is valid for <b>{{ $visit_duration }}</b>.</p><br />
      
        <div class="qr-container">
            <img  src="{{$qrCodeUrl}}" alt="QR Code" style="width: 150px; height: 150px;" />
        </div>
        <p>Kindly present this QR code when making payments in Balesin.</p>

    </div>
</body>
</html>
