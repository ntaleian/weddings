<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Watoto Church</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Outfit:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Outfit', 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f8f9fa;
        }
        
        .email-container {
            max-width: 600px;
            margin: 0 auto;
            background: #ffffff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
        }
        
        .email-header {
            background: linear-gradient(135deg, #64017f, #8e44ad);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .logo {
            font-size: 28px;
            font-weight: 700;
            margin-bottom: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
        }
        
        .logo i {
            font-size: 32px;
        }
        
        .email-header h1 {
            font-size: 24px;
            margin-bottom: 8px;
            font-weight: 600;
        }
        
        .email-header p {
            font-size: 16px;
            opacity: 0.9;
        }
        
        .email-body {
            padding: 40px 30px;
        }
        
        .greeting {
            font-size: 18px;
            margin-bottom: 20px;
            color: #2c3e50;
        }
        
        .message {
            font-size: 16px;
            margin-bottom: 30px;
            line-height: 1.8;
            color: #555;
        }
        
        .otp-container {
            background: #f8f9fa;
            border: 2px solid #64017f;
            border-radius: 12px;
            padding: 30px;
            text-align: center;
            margin: 30px 0;
        }
        
        .otp-label {
            font-size: 14px;
            color: #666;
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 1px;
            font-weight: 600;
        }
        
        .otp-code {
            font-size: 36px;
            font-weight: 700;
            color: #64017f;
            letter-spacing: 8px;
            font-family: 'Outfit', 'Courier New', monospace;
            margin: 10px 0;
        }
        
        .otp-validity {
            font-size: 14px;
            color: #e74c3c;
            margin-top: 15px;
            font-weight: 500;
        }
        
        .instructions {
            background: #e8f5e8;
            border-left: 4px solid #27ae60;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .instructions h3 {
            color: #27ae60;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .instructions ol {
            margin-left: 20px;
            color: #2c3e50;
        }
        
        .instructions li {
            margin-bottom: 8px;
            font-size: 14px;
        }
        
        .warning {
            background: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin: 30px 0;
            border-radius: 0 8px 8px 0;
        }
        
        .warning h3 {
            color: #856404;
            margin-bottom: 10px;
            font-size: 16px;
        }
        
        .warning p {
            color: #856404;
            font-size: 14px;
            margin: 0;
        }
        
        .email-footer {
            background: #2c3e50;
            color: white;
            padding: 30px;
            text-align: center;
        }
        
        .footer-content {
            margin-bottom: 20px;
        }
        
        .contact-info {
            font-size: 14px;
            margin-bottom: 15px;
        }
        
        .social-links {
            margin: 20px 0;
        }
        
        .social-links a {
            color: white;
            text-decoration: none;
            margin: 0 10px;
            font-size: 18px;
        }
        
        .copyright {
            font-size: 12px;
            opacity: 0.8;
            border-top: 1px solid #34495e;
            padding-top: 20px;
            margin-top: 20px;
        }
        
        .button {
            display: inline-block;
            padding: 12px 30px;
            background: #64017f;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: 600;
            margin: 20px 0;
            transition: background 0.3s ease;
        }
        
        .button:hover {
            background: #8e44ad;
        }
        
        @media (max-width: 600px) {
            .email-container {
                margin: 0;
                border-radius: 0;
            }
            
            .email-header,
            .email-body,
            .email-footer {
                padding: 25px 20px;
            }
            
            .otp-code {
                font-size: 28px;
                letter-spacing: 4px;
            }
            
            .logo {
                font-size: 24px;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="email-header">
            <div class="logo">
                <i class="fas fa-church"></i>
                Watoto Church
            </div>
            <h1>Email Verification</h1>
            <p>Complete your registration with us</p>
        </div>
        
        <!-- Body -->
        <div class="email-body">
            <div class="greeting">
                Hello <?= esc($user_name) ?>,
            </div>
            
            <div class="message">
                Thank you for registering with Watoto Church Wedding Booking System! We're excited to help you plan your special day.
                <br><br>
                To complete your registration and verify your email address, please use the One-Time Password (OTP) below:
            </div>
            
            <div class="otp-container">
                <div class="otp-label">Your Verification Code</div>
                <div class="otp-code"><?= esc($otp_code) ?></div>
                <div class="otp-validity">⏰ This code expires in <?= esc($expiry_minutes) ?> minutes</div>
            </div>
            
            <div class="instructions">
                <h3>📋 How to verify your email:</h3>
                <ol>
                    <li>Go back to the registration page in your browser</li>
                    <li>Enter the 6-digit code shown above</li>
                    <li>Click "Verify Email" to complete your registration</li>
                    <li>You'll then be able to access your dashboard</li>
                </ol>
            </div>
            
            <div class="warning">
                <h3>🔒 Security Notice</h3>
                <p>This code is valid for one-time use only. If you didn't request this verification, please ignore this email or contact our support team if you have concerns about your account security.</p>
            </div>
            
            <div style="text-align: center; margin-top: 30px;">
                <p style="color: #666; font-size: 14px;">
                    Need help? Contact our support team at 
                    <a href="mailto:family@watotochurch.com" style="color: #64017f;">family@watotochurch.com</a>
                </p>
            </div>
        </div>
        
        <!-- Footer -->
        <div class="email-footer">
            <div class="footer-content">
                <div class="contact-info">
                    <strong>Watoto Church</strong><br>
                    Watoto Family Department<br>
                    📧 family@watotochurch.com<br>
                    📞 +256 (0) 778 208 159
                </div>
                
                <div class="social-links">
                    <a href="#" title="Facebook"><i class="fab fa-facebook"></i></a>
                    <a href="#" title="Twitter"><i class="fab fa-twitter"></i></a>
                    <a href="#" title="Instagram"><i class="fab fa-instagram"></i></a>
                    <a href="#" title="YouTube"><i class="fab fa-youtube"></i></a>
                </div>
            </div>
            
            <div class="copyright">
                © <?= date('Y') ?> Watoto Church. All rights reserved.<br>
                This email was sent to <?= esc($user_email) ?> as part of your registration process.
            </div>
        </div>
    </div>
</body>
</html>
