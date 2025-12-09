<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Email Verification - Watoto Church</title>
</head>
<body style="margin: 0; padding: 0; font-family: Arial, Helvetica, sans-serif; line-height: 1.6; color: #333333; background-color: #f8f9fa;">
    
    <!-- Email Container -->
    <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 0; padding: 20px 0;">
        <tr>
            <td align="center">
                <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="600" style="max-width: 600px; margin: 0 auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Header -->
                    <tr>
                        <td style="background: #64017f; color: white; padding: 40px 30px; text-align: center;">
                            <h1 style="margin: 0 0 10px 0; font-size: 28px; font-weight: bold; font-family: Arial, Helvetica, sans-serif;">
                                ⛪ Watoto Church
                            </h1>
                            <h2 style="margin: 0 0 8px 0; font-size: 24px; font-weight: 600; font-family: Arial, Helvetica, sans-serif;">
                                Email Verification
                            </h2>
                            <p style="margin: 0; font-size: 16px; opacity: 0.9; font-family: Arial, Helvetica, sans-serif;">
                                Complete your registration with us
                            </p>
                        </td>
                    </tr>
                    
                    <!-- Body -->
                    <tr>
                        <td style="padding: 40px 30px;">
                            
                            <!-- Greeting -->
                            <p style="margin: 0 0 20px 0; font-size: 18px; color: #2c3e50; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">
                                Hello <?= esc($user_name) ?>,
                            </p>
                            
                            <!-- Message -->
                            <p style="margin: 0 0 30px 0; font-size: 16px; line-height: 1.8; color: #555555; font-family: Arial, Helvetica, sans-serif;">
                                Thank you for registering with Watoto Church Wedding Booking System! We're excited to help you plan your special day.
                                <br><br>
                                To complete your registration and verify your email address, please use the One-Time Password (OTP) below:
                            </p>
                            
                            <!-- OTP Container -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" style="background: #f8f9fa; border: 2px solid #64017f; border-radius: 8px; padding: 30px; text-align: center;">
                                            <tr>
                                                <td>
                                                    <p style="margin: 0 0 10px 0; font-size: 14px; color: #666666; text-transform: uppercase; letter-spacing: 1px; font-weight: 600; font-family: Arial, Helvetica, sans-serif;">
                                                        Your Verification Code
                                                    </p>
                                                    <p style="margin: 10px 0; font-size: 36px; font-weight: bold; color: #64017f; letter-spacing: 8px; font-family: 'Courier New', Courier, monospace;">
                                                        <?= esc($otp_code) ?>
                                                    </p>
                                                    <p style="margin: 15px 0 0 0; font-size: 14px; color: #e74c3c; font-weight: 500; font-family: Arial, Helvetica, sans-serif;">
                                                        ⏰ This code expires in <?= esc($expiry_minutes) ?> minutes
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Instructions -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0;">
                                <tr>
                                    <td style="background: #e8f5e8; border-left: 4px solid #27ae60; padding: 20px; border-radius: 0 8px 8px 0;">
                                        <h3 style="margin: 0 0 10px 0; color: #27ae60; font-size: 16px; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">
                                            📋 How to verify your email:
                                        </h3>
                                        <ol style="margin: 0; padding-left: 20px; color: #2c3e50; font-family: Arial, Helvetica, sans-serif;">
                                            <li style="margin-bottom: 8px; font-size: 14px;">Go back to the registration page in your browser</li>
                                            <li style="margin-bottom: 8px; font-size: 14px;">Enter the 6-digit code shown above</li>
                                            <li style="margin-bottom: 8px; font-size: 14px;">Click "Verify Email" to complete your registration</li>
                                            <li style="margin-bottom: 8px; font-size: 14px;">You'll then be able to access your dashboard</li>
                                        </ol>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Warning -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0;">
                                <tr>
                                    <td style="background: #fff3cd; border-left: 4px solid #ffc107; padding: 20px; border-radius: 0 8px 8px 0;">
                                        <h3 style="margin: 0 0 10px 0; color: #856404; font-size: 16px; font-family: Arial, Helvetica, sans-serif; font-weight: bold;">
                                            🔒 Security Notice
                                        </h3>
                                        <p style="margin: 0; color: #856404; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">
                                            This code is valid for one-time use only. If you didn't request this verification, please ignore this email or contact our support team if you have concerns about your account security.
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                            <!-- Support Contact -->
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="margin: 30px 0;">
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0; color: #666666; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">
                                            Need help? Contact our support team at 
                                            <a href="mailto:support@watotochurch.org" style="color: #64017f; text-decoration: none; font-weight: bold;">support@watotochurch.org</a>
                                        </p>
                                    </td>
                                </tr>
                            </table>
                            
                        </td>
                    </tr>
                    
                    <!-- Footer -->
                    <tr>
                        <td style="background: #2c3e50; color: white; padding: 30px; text-align: center;">
                            <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%">
                                <tr>
                                    <td align="center">
                                        <p style="margin: 0 0 15px 0; font-weight: bold; font-size: 16px; font-family: Arial, Helvetica, sans-serif;">
                                            Watoto Church
                                        </p>
                                        <p style="margin: 0 0 15px 0; font-size: 14px; font-family: Arial, Helvetica, sans-serif;">
                                            Wedding Booking Department<br>
                                            📧 weddings@watotochurch.org<br>
                                            📞 +256 (0) 414 123 456
                                        </p>
                                        
                                        <!-- Social Links -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" align="center" style="margin: 20px 0;">
                                            <tr>
                                                <td>
                                                    <a href="#" style="color: white; text-decoration: none; margin: 0 10px; font-size: 18px;">📘</a>
                                                    <a href="#" style="color: white; text-decoration: none; margin: 0 10px; font-size: 18px;">🐦</a>
                                                    <a href="#" style="color: white; text-decoration: none; margin: 0 10px; font-size: 18px;">📷</a>
                                                    <a href="#" style="color: white; text-decoration: none; margin: 0 10px; font-size: 18px;">📺</a>
                                                </td>
                                            </tr>
                                        </table>
                                        
                                        <!-- Copyright -->
                                        <table role="presentation" cellspacing="0" cellpadding="0" border="0" width="100%" style="border-top: 1px solid #34495e; margin-top: 20px; padding-top: 20px;">
                                            <tr>
                                                <td align="center">
                                                    <p style="margin: 0; font-size: 12px; opacity: 0.8; font-family: Arial, Helvetica, sans-serif;">
                                                        © <?= date('Y') ?> Watoto Church. All rights reserved.<br>
                                                        This email was sent to <?= esc($user_email) ?> as part of your registration process.
                                                    </p>
                                                </td>
                                            </tr>
                                        </table>
                                    </td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    
                </table>
            </td>
        </tr>
    </table>
    
</body>
</html>
