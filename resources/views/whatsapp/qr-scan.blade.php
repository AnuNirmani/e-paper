<x-app-layout>
    <style>
        .whatsapp-container {
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: calc(100vh - 200px);
        }
        .whatsapp-box {
            background: white;
            padding: 40px;
            border-radius: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
        }
        .qr-container {
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 8px;
        }
        #qr-image {
            max-width: 300px;
            height: auto;
        }
        .status {
            padding: 10px 20px;
            border-radius: 5px;
            margin: 20px 0;
            font-weight: bold;
        }
        .status.connected {
            background: #d4edda;
            color: #155724;
        }
        .status.disconnected {
            background: #f8d7da;
            color: #721c24;
        }
        .error-box {
            background: #f8d7da;
            color: #721c24;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #dc3545;
            margin: 20px 0;
            text-align: left;
            line-height: 1.6;
        }
        .error-box p {
            margin: 10px 0;
        }
        .error-box a {
            color: #0066cc;
            text-decoration: none;
            word-break: break-all;
            font-weight: 500;
        }
        .error-box a:hover {
            text-decoration: underline;
            color: #004499;
        }
        .status.loading {
            background: #fff3cd;
            color: #856404;
        }
        .instructions {
            text-align: left;
            margin: 20px 0;
            padding: 15px;
            background: #e7f3ff;
            border-radius: 5px;
        }
        .instructions ol {
            margin: 10px 0;
            padding-left: 20px;
        }
        .spinner {
            border: 4px solid #f3f3f3;
            border-top: 4px solid #25D366;
            border-radius: 50%;
            width: 40px;
            height: 40px;
            animation: spin 1s linear infinite;
            margin: 20px auto;
        }
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        .btn-logout {
            margin-top: 20px;
            padding: 12px 24px;
            background: #dc3545;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
            transition: background 0.3s;
        }
        .btn-logout:hover {
            background: #c82333;
        }
        .btn-logout:disabled {
            background: #6c757d;
            cursor: not-allowed;
        }
    </style>

    <div class="whatsapp-container">
        <div class="whatsapp-box">
            <h1 style="font-size: 28px; margin-bottom: 20px;">🔗 Connect WhatsApp</h1>
        
        <div id="status" class="status loading">
            Checking connection status...
        </div>

        <div id="qr-section" style="display: none;">
            <div class="instructions">
                <h3>How to Connect:</h3>
                <ol>
                    <li>Open WhatsApp on your phone</li>
                    <li>Tap <strong>Menu</strong> or <strong>Settings</strong></li>
                    <li>Select <strong>Linked Devices</strong></li>
                    <li>Tap <strong>Link a Device</strong></li>
                    <li>Point your phone to this screen to scan the QR code</li>
                </ol>
            </div>

            <div class="qr-container">
                <div id="loading-spinner" class="spinner"></div>
                <img id="qr-image" src="" alt="QR Code" style="display: none;">
            </div>

            <p><small>QR code is valid for 2 minutes and will refresh automatically</small></p>
        </div>

        <div id="connected-section" style="display: none;">
            <h2 style="font-size: 24px; color: #155724;">✅ WhatsApp Connected!</h2>
            <p>Your WhatsApp instance is now connected and ready to use.</p>
            
            <button id="logout-btn" class="btn-logout" onclick="logoutWhatsApp()">
                🔓 Logout WhatsApp
            </button>
            <p style="margin-top: 10px; font-size: 12px; color: #666;">
                Logging out will disconnect this WhatsApp instance
            </p>
        </div>
        </div>
    </div>

    <script>
        let qrRefreshInterval;
        let statusCheckInterval;
        let isShowingQR = false;
        let isConnected = false;

        // Check connection status
        async function checkStatus() {
            try {
                const response = await fetch('{{ route("whatsapp.status") }}');
                const data = await response.json();
                
                console.log('Status:', data);

                // Check the actual API response structure
                const accountStatus = data?.status?.accountStatus?.status;
                
                if (accountStatus === 'authenticated') {
                    if (!isConnected) {
                        showConnected();
                    }
                } else {
                    if (!isShowingQR) {
                        showQRSection();
                    }
                }
            } catch (error) {
                console.error('Status check error:', error);
                document.getElementById('status').textContent = 'Error checking status';
            }
        }

        // Load QR code
        async function loadQRCode() {
            try {
                document.getElementById('loading-spinner').style.display = 'block';
                document.getElementById('qr-image').style.display = 'none';

                const response = await fetch('{{ route("whatsapp.qr") }}');
                const data = await response.json();
                
                console.log('QR Response:', data);

                // Handle different possible response formats from UltraMsg API
                if (data.already_connected) {
                    // Instance is already connected
                    showConnected();
                } else if (data.qrCode) {
                    // If qrCode field exists
                    document.getElementById('qr-image').src = data.qrCode;
                    document.getElementById('qr-image').style.display = 'block';
                    document.getElementById('loading-spinner').style.display = 'none';
                } else if (data.qr) {
                    // Alternative field name
                    document.getElementById('qr-image').src = data.qr;
                    document.getElementById('qr-image').style.display = 'block';
                    document.getElementById('loading-spinner').style.display = 'none';
                } else if (data.base64) {
                    // If it's base64 encoded
                    document.getElementById('qr-image').src = 'data:image/png;base64,' + data.base64;
                    document.getElementById('qr-image').style.display = 'block';
                    document.getElementById('loading-spinner').style.display = 'none';
                } else if (data.message && !data.error) {
                    // Success message
                    showConnected();
                } else if (data.error) {
                    // Parse error message and make URLs clickable
                    let errorHtml = data.error;
                    
                    // Check if it contains the specific UltraMsg URL pattern
                    const urlRegex = /(https?:\/\/[^\s"]+)/g;
                    errorHtml = errorHtml.replace(urlRegex, '<a href="$1" target="_blank">$1</a>');
                    
                    // Split by newline and format
                    const lines = errorHtml.split('\n');
                    let formattedError = '<div class="error-box">';
                    lines.forEach(line => {
                        if (line.trim()) {
                            formattedError += '<p>' + line.trim() + '</p>';
                        }
                    });
                    formattedError += '</div>';
                    
                    document.getElementById('status').className = '';
                    document.getElementById('status').innerHTML = formattedError;
                    document.getElementById('loading-spinner').style.display = 'none';
                    
                    // Hide the QR section and instructions when error occurs
                    document.getElementById('qr-section').style.display = 'none';
                } else {
                    // Show raw response for debugging
                    document.getElementById('status').className = 'status disconnected';
                    document.getElementById('status').textContent = 'Unexpected response format. Check console for details.';
                    document.getElementById('loading-spinner').style.display = 'none';
                    console.error('Unexpected response:', data);
                }
            } catch (error) {
                console.error('QR load error:', error);
                document.getElementById('status').className = 'status disconnected';
                document.getElementById('status').textContent = 'Error loading QR code: ' + error.message;
                document.getElementById('loading-spinner').style.display = 'none';
            }
        }

        function showQRSection() {
            isShowingQR = true;
            isConnected = false;
            
            document.getElementById('status').className = 'status disconnected';
            document.getElementById('status').textContent = '❌ Not Connected - Scan QR Code';
            document.getElementById('qr-section').style.display = 'block';
            document.getElementById('connected-section').style.display = 'none';

            // Load QR code immediately only once
            loadQRCode();

            // Refresh QR code every 110 seconds (valid for 2 minutes)
            if (qrRefreshInterval) clearInterval(qrRefreshInterval);
            qrRefreshInterval = setInterval(loadQRCode, 110000);
        }

        function showConnected() {
            isShowingQR = false;
            isConnected = true;
            
            document.getElementById('status').className = 'status connected';
            document.getElementById('status').textContent = '✅ Connected';
            document.getElementById('qr-section').style.display = 'none';
            document.getElementById('connected-section').style.display = 'block';

            // Stop QR refresh
            if (qrRefreshInterval) clearInterval(qrRefreshInterval);
        }

        // Logout WhatsApp
        async function logoutWhatsApp() {
            if (!confirm('Are you sure you want to logout from WhatsApp? You will need to scan the QR code again to reconnect.')) {
                return;
            }

            const logoutBtn = document.getElementById('logout-btn');
            logoutBtn.disabled = true;
            logoutBtn.textContent = 'Logging out...';

            try {
                const response = await fetch('{{ route("whatsapp.logout") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
                    }
                });

                const data = await response.json();

                if (data.success) {
                    alert('Successfully logged out from WhatsApp');
                    isShowingQR = false;
                    isConnected = false;
                    checkStatus(); // Refresh status
                } else {
                    alert(data.message || 'Failed to logout');
                    logoutBtn.disabled = false;
                    logoutBtn.textContent = '🔓 Logout WhatsApp';
                }
            } catch (error) {
                console.error('Logout error:', error);
                alert('Error logging out. Please try again.');
                logoutBtn.disabled = false;
                logoutBtn.textContent = '🔓 Logout WhatsApp';
            }
        }

        // Initial check
        checkStatus();

        // Check status every 5 seconds
        statusCheckInterval = setInterval(checkStatus, 5000);

        // Cleanup on page unload
        window.addEventListener('beforeunload', () => {
            if (qrRefreshInterval) clearInterval(qrRefreshInterval);
            if (statusCheckInterval) clearInterval(statusCheckInterval);
        });
    </script>
</x-app-layout>