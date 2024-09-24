<!DOCTYPE html>
<html>
<head>
    <title>QR Code Scanner</title>
</head>
<body>
    <h1>QR Code Scanner</h1>

    <!-- QR Code Scanner -->
    <div id="reader" style="width:500px"></div>
    <p>Scanned result: <span id="scanned-result"></span></p>

    <script src="https://unpkg.com/html5-qrcode@2.3.8/html5-qrcode.min.js"></script>
    <script>
        function onScanSuccess(decodedText, decodedResult) {
            // Handle the scanned result here
            document.getElementById('scanned-result').innerText = decodedText;
            console.log(`Code scanned = ${decodedText}`, decodedResult);
        }

        function onScanError(errorMessage) {
            // Handle scan error if any
            console.error(`Scan error = ${errorMessage}`);
        }

        let html5QrCode = new Html5Qrcode("reader");
        html5QrCode.start(
            { facingMode: "environment" }, // Use rear camera
            {
                fps: 10,    // Scans per second
                qrbox: { width: 250, height: 250 }  // QR scanning area
            },
            onScanSuccess,
            onScanError
        ).catch(err => {
            console.error(`Unable to start scanning, error: ${err}`);
        });
    </script>
</body>
</html>
