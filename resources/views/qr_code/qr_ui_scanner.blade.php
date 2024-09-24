@extends('layouts.scanning_page')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-xl-10 col-lg-10 col-md-11">
            <!-- Flip Card Container -->
            <div class="flip-card-container">
                <div class="flip-card">
                    <div class="flip-card-inner">
                        <!-- Front Card (Initial View) -->
                        <div class="flip-card-front card o-hidden border-0 shadow-lg my-5">
                            <div class="card-body p-5">
                                <div class="row qr-welcome">
                                    <div class="col-lg-6 d-none d-lg-flex justify-content-center align-items-center">
                                        <img src="{{ asset('sb-admin-2/img/undraw_modern_design_re_dlp8.svg') }}" alt="QR Scanner Image" class="img-fluid p-5" style="max-width: 26rem">
                                    </div>
                                    <div class="col-lg-6">
                                        <div class="text-center text-xl-start text-xxl-center mt-4 ">
                                            <h3 class="text-primary">Welcome to App Name!</h3>
                                            <p class="text-gray-700 mb-0">Get ready to scan your QR code. Once scanned, we'll confirm your details.</p>
                                        </div>
                                        <button id="scan-btn" class="btn btn-primary btn-block mt-4">Start Scanning</button>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Back Card (QR Code Scanner View) -->
                        <div class="flip-card-back card o-hidden border-0 shadow-lg my-5">
                            <div class="card-body p-5">
                                <div class="text-center mb-4">
                                    <h3 class="text-primary">Scan Here</h3>
                                    <p class="text-gray-700">Position the QR code within the frame.</p>
                                </div>
                                
                                <!-- QR Scanner Section -->
                                <div id="reader" style="width: 100%; max-width: 350px; margin: 0 auto;"></div>
                                
                                <!-- Input for QR Code -->
                                <div id="manual-input" style="display: none;" class="mt-4 text-center">
                                    <input type="text" id="qr-input" class="form-control" placeholder="Enter QR Code" autofocus>
                                    <button id="submit-qr" class="btn btn-primary mt-2">Submit</button>
                                </div>
                                
                                <!-- Result Section -->
                                <div id="result" class="mt-4 text-center"></div>
                            

                                <div class="form-group d-flex justify-content-between align-items-center">
                                    <button id="reset-btn" class="btn btn-secondary mt-2">Reset</button>
                                    <div class="form-check form-switch" title="switch this if you are using a scanner device">
                                        <input class="form-check-input" type="checkbox" role="switch" id="flexSwitchCheckChecked">
                                        <label class="form-check-label" for="flexSwitchCheckChecked">Scanning device</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://cdnjs.cloudflare.com/ajax/libs/html5-qrcode/2.3.8/html5-qrcode.min.js"></script>

<script>
    const html5QrCode = new Html5Qrcode("reader");

    document.getElementById('scan-btn').addEventListener('click', function() {
        const flipCardInner = document.querySelector('.flip-card-inner');
        flipCardInner.classList.add('flipped');

        // Start the QR code scanner after a brief delay
        setTimeout(() => {
            html5QrCode.start(
                { facingMode: "environment" },
                {
                    fps: 10,
                    qrbox: { width: 250, height: 250 }
                },
                (decodedText, decodedResult) => {
                    handleDecodedText(decodedText);
                },
                errorMessage => {
                    console.warn(`QR code scan error: ${errorMessage}`);
                }
            ).catch(err => {
                console.error(`Unable to start scanning: ${err}`);
            });
        }, 600);
    });

    document.getElementById('reset-btn').addEventListener('click', function() {
        // Clear result message
        document.getElementById('result').innerHTML = '';

        // Check if switch is off
        if (!document.getElementById('flexSwitchCheckChecked').checked) {
            // Restart the QR scanner
            startQrScanner();
                // Update the message
                messageElement.textContent = "Position the QR code within the frame.";
        } else {
            // If switch is checked, simply clear the input field
            document.getElementById('qr-input').value = ''; // Clear manual input
            messageElement.textContent = "Enter QR Code:";
        }
    });



    document.getElementById('flexSwitchCheckChecked').addEventListener('change', function() {
        const messageElement = document.querySelector('.flip-card-back .text-gray-700');
        if (this.checked) {
            // Stop QR scanner
            html5QrCode.stop().then(() => {
                console.log("QR Code scanning stopped.");
            }).catch(err => console.error("Error stopping scanner", err));
            
            // Show manual input
            document.getElementById('reader').style.display = 'none'; // Hide QR scanner
            document.getElementById('manual-input').style.display = 'block'; // Show manual input
            document.getElementById('qr-input').focus(); // Focus on the input

                   
            // Clear result message
            document.getElementById('result').innerHTML = ''; 
            messageElement.textContent = "Enter QR Code:";
        } else {
            // Show QR scanner
            document.getElementById('reader').style.display = 'block'; // Show QR scanner
            document.getElementById('manual-input').style.display = 'none'; // Hide manual input
            startQrScanner(); // Start scanning again
                       
            // Clear result message
            document.getElementById('result').innerHTML = ''; 
            messageElement.textContent = "Position the QR code within the frame.";
        }
    });

    document.getElementById('submit-qr').addEventListener('click', function() {
        const qrInput = document.getElementById('qr-input').value;
        handleDecodedText(qrInput); // Process the input value as if it was scanned
    });

    function handleDecodedText(decodedText) {
        fetch('/get-account-info', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
            },
            body: JSON.stringify({ code: decodedText })
        })
        .then(response => {
            if (!response.ok) {
                return response.text().then(text => { throw new Error(text) });
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                document.getElementById('result').innerHTML = `<div class="alert alert-danger">${data.error}</div>`;
            } else {

                console.log(data);
                const resultDiv = document.getElementById('result');
                // Determine if the scanned QR code is for a member or a guest
                const typeLabel = data.type === 'member' ? 'Member' : 'Guest';

                // Create the HTML content based on the type
                resultDiv.innerHTML = `
                    <div class="alert alert-success text-left">
                        <h5 class="alert-heading">${typeLabel} Details</h5>
                        ${data.type === 'member' ? `
                            <p><strong>Member Name:</strong> ${data.name || 'N/A'}</p>
                            <p><strong>Member Email:</strong> ${data.email || 'N/A'}</p>
                            <p><strong>Membership #:</strong> ${data.id || 'N/A'}</p>
                        ` : `
                            <p><strong>Guest Name:</strong> ${data.name || 'N/A'}</p>
                            <p><strong>Guest Email:</strong> ${data.email || 'N/A'}</p>
                            ${data.member_id ? `<p><strong>Associated Member:</strong> ${data.member_name || 'N/A'}</p>` : ''}
                        `}
                    </div>
                `;


            }
        })
        .catch(err => {
            console.error('Error fetching account info', err);
            document.getElementById('result').innerHTML = `<div class="alert alert-danger">This QR is not found in our system</div>`;
        });
    }

    function startQrScanner() {
        html5QrCode.start(
            { facingMode: "environment" },
            {
                fps: 10,
                qrbox: { width: 250, height: 250 }
            },
            (decodedText, decodedResult) => {
                handleDecodedText(decodedText);
            },
            errorMessage => {
                console.warn(`QR code scan error: ${errorMessage}`);
            }
        ).catch(err => {
            console.error(`Unable to start scanning: ${err}`);
        });
    }
</script>
@endsection
