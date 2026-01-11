<?php

namespace App\Controllers;

use App\Models\UserModel;
use App\Libraries\OtpLibrary;
use App\Libraries\MathCaptchaLibrary;
use CodeIgniter\Controller;

class Auth extends Controller
{
    protected $userModel;
    protected $otpLibrary;
    protected $mathCaptchaLibrary;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->otpLibrary = new OtpLibrary();
        $this->mathCaptchaLibrary = new MathCaptchaLibrary();
        helper(['form', 'url']);
        $this->session = \Config\Services::session();
    }

    public function login()
    {
        if (session()->get('user_id')) {
            return redirect()->to(site_url('dashboard'));
        }

        $data = [
            'title' => 'Login - Watoto Church Wedding Booking'
        ];

        return view('auth/login', $data);
    }

    public function authenticate()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->getUserByEmail($email);

        if ($user && $this->userModel->verifyPassword($password, $user['password'])) {
            if (!$user['is_active']) {
                return redirect()->back()->with('error', 'Your account is deactivated. Please contact support.');
            }

            $sessionData = [
                'user_id' => $user['id'],
                'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);

            if ($user['role'] === 'admin') {
                return redirect()->to(site_url('admin/dashboard'));
            } else {
                return redirect()->to(site_url('dashboard'));
            }
        }

        return redirect()->back()->with('error', 'Invalid email or password.');
    }

    public function register()
    {
        if (session()->get('user_id')) {
            return redirect()->to(site_url('dashboard'));
        }

        // Generate math captcha question
        $this->mathCaptchaLibrary->generateQuestion();

        $data = [
            'title' => 'Register - Watoto Church Wedding Booking',
            'captcha_question' => $this->mathCaptchaLibrary->getCurrentQuestion()
        ];

        return view('auth/register', $data);
    }

    public function store()
    {
        // Verify math captcha first
        $captchaAnswer = $this->request->getPost('captcha_answer');
        if (!$this->mathCaptchaLibrary->verify($captchaAnswer)) {
            // Regenerate captcha for retry
            $this->mathCaptchaLibrary->generateQuestion();
            return redirect()->back()->withInput()->with('error', 'Incorrect security question answer. Please try again.');
        }

        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'first_name' => 'required|min_length[2]|max_length[100]',
            'last_name' => 'required|min_length[2]|max_length[100]',
            'email' => 'required|valid_email|is_unique[users.email]',
            // 'phone' => 'required|min_length[10]|max_length[20]',
            'password' => 'required|min_length[8]',
            'confirm_password' => 'required|matches[password]',
            'captcha_answer' => 'required|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            // Regenerate captcha for retry
            $this->mathCaptchaLibrary->generateQuestion();
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        // Sanitize input data
        $userData = [
            'first_name' => esc($this->request->getPost('first_name', FILTER_SANITIZE_STRING)),
            'last_name' => esc($this->request->getPost('last_name', FILTER_SANITIZE_STRING)),
            'email' => filter_var($this->request->getPost('email'), FILTER_SANITIZE_EMAIL),
            'password' => $this->request->getPost('password'), // Don't sanitize password, it will be hashed
            'role' => 'user',
            'is_active' => true,
            'is_email_verified' => false
        ];

        if ($this->userModel->insert($userData)) {
            // Generate and send OTP
            $otp = $this->otpLibrary->generateOtp();
            $email = $userData['email'];
            $userName = $userData['first_name'] . ' ' . $userData['last_name'];
            
            // Store OTP in database
            if ($this->otpLibrary->storeOtp($email, $otp, 15)) {
                // Send OTP email
                if ($this->sendOtpEmail($email, $userName, $otp, 15)) {
                    // Store user data in session for verification
                    session()->set([
                        'pending_verification_email' => $email,
                        'pending_verification_name' => $userName
                    ]);
                    
                    return redirect()->to('/verify-email')->with('success', 'Registration successful! Please check your email for the verification code.');
                } else {
                    return redirect()->back()->with('error', 'Registration successful but failed to send verification email. Please try again.');
                }
            } else {
                return redirect()->back()->with('error', 'Registration failed. Please try again.');
            }
        }

        return redirect()->back()->with('error', 'Registration failed. Please try again.');
    }

    public function refreshCaptcha()
    {
        // Only allow POST requests
        if ($this->request->getMethod() !== 'post') {
            return $this->response->setStatusCode(405)->setJSON([
                'success' => false,
                'message' => 'Method not allowed'
            ]);
        }
        
        try {
            $this->mathCaptchaLibrary->generateQuestion();
            $question = $this->mathCaptchaLibrary->getCurrentQuestion();
            
            if (!$question) {
                throw new \Exception('Failed to generate captcha question');
            }
            
            return $this->response->setJSON([
                'success' => true,
                'question' => $question,
                'csrf_token' => csrf_hash()
            ]);
        } catch (\Exception $e) {
            log_message('error', 'Captcha refresh error: ' . $e->getMessage());
            return $this->response->setStatusCode(500)->setJSON([
                'success' => false,
                'message' => 'Failed to generate captcha. Please try again.'
            ]);
        }
    }

    public function verifyEmail()
    {
        $email = session()->get('pending_verification_email');
        if (!$email) {
            return redirect()->to('/register')->with('error', 'No pending email verification found.');
        }

        $data = [
            'title' => 'Verify Email - Watoto Church Wedding Booking',
            'email' => $email
        ];

        return view('auth/verify_email', $data);
    }

    public function processEmailVerification()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'otp_code' => 'required|exact_length[6]|numeric'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = session()->get('pending_verification_email');
        $otpCode = $this->request->getPost('otp_code');

        if (!$email) {
            return redirect()->to('/register')->with('error', 'No pending email verification found.');
        }

        if ($this->otpLibrary->verifyOtp($email, $otpCode)) {
            // Mark user as verified
            $this->userModel->where('email', $email)->set([
                'is_email_verified' => true,
                'email_verified_at' => date('Y-m-d H:i:s')
            ])->update();

            // Get user and create session
            $user = $this->userModel->getUserByEmail($email);
            
            $sessionData = [
                'user_id' => $user['id'],
                'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);
            
            // Clear verification session data
            session()->remove(['pending_verification_email', 'pending_verification_name']);

            return redirect()->to(site_url('dashboard'))->with('success', 'Email verified successfully! Welcome to Watoto Church Wedding Booking.');
        }

        return redirect()->back()->with('error', 'Invalid or expired verification code. Please try again.');
    }

    public function resendOtp()
    {
        $email = session()->get('pending_verification_email');
        $userName = session()->get('pending_verification_name');

        if (!$email) {
            return redirect()->to('/register')->with('error', 'No pending email verification found.');
        }

        // Generate new OTP
        $otp = $this->otpLibrary->generateOtp();
        
        // Store new OTP
        if ($this->otpLibrary->storeOtp($email, $otp, 15)) {
            // Send new OTP email
            if ($this->sendOtpEmail($email, $userName, $otp, 15)) {
                return redirect()->back()->with('success', 'New verification code sent to your email.');
            } else {
                return redirect()->back()->with('error', 'Failed to send verification email. Please try again.');
            }
        }

        return redirect()->back()->with('error', 'Failed to generate new verification code. Please try again.');
    }

    public function resendVerification()
    {
        if (!session()->get('isLoggedIn')) {
            return $this->response->setJSON(['success' => false, 'message' => 'Not authenticated']);
        }

        $userId = session()->get('user_id');
        $user = $this->userModel->find($userId);

        if (!$user) {
            return $this->response->setJSON(['success' => false, 'message' => 'User not found']);
        }

        if ($user['is_email_verified']) {
            return $this->response->setJSON(['success' => false, 'message' => 'Email is already verified']);
        }

        // Generate new OTP
        $otp = $this->otpLibrary->generateOtp();
        
        // Store new OTP
        if ($this->otpLibrary->storeOtp($user['email'], $otp, 15)) {
            // Send new OTP email
            if ($this->sendOtpEmail($user['email'], $user['first_name'] . ' ' . $user['last_name'], $otp, 15)) {
                return $this->response->setJSON(['success' => true, 'message' => 'Verification email sent successfully']);
            } else {
                return $this->response->setJSON(['success' => false, 'message' => 'Failed to send verification email']);
            }
        }

        return $this->response->setJSON(['success' => false, 'message' => 'Failed to generate verification code']);
    }

    private function sendOtpEmail($email, $userName, $otp, $expiryMinutes)
    {
        $emailService = \Config\Services::email();
        
        // Load email configuration
        $emailConfig = new \Config\Email();
        
        $emailService->setFrom($emailConfig->fromEmail ?: 'noreply@watotochurch.com', $emailConfig->fromName ?: 'Watoto Church');
        $emailService->setTo($email);
        $emailService->setSubject('Email Verification - Watoto Church Wedding Booking');
        
        // Prepare email data
        $emailData = [
            'user_name' => $userName,
            'user_email' => $email,
            'otp_code' => $otp,
            'expiry_minutes' => $expiryMinutes
        ];
        
        // Load HTML template
        $htmlMessage = view('emails/otp_verification', $emailData);
        $textMessage = view('emails/otp_verification_text', $emailData);
        
        $emailService->setMessage($htmlMessage);
        $emailService->setAltMessage($textMessage);
        
        return $emailService->send();
    }

    public function logout()
    {
        session()->destroy();
        return redirect()->to('/')->with('success', 'You have been logged out successfully.');
    }

    public function adminLogin()
    {
        // If already logged in and is admin, redirect to admin dashboard
        if ($this->session->get('user_id') && $this->session->get('role') === 'admin') {
            return redirect()->to(site_url('admin/dashboard'));
        }

        return view('auth/admin_login', ['title' => 'Admin Login - Watoto Church']);
    }

    public function adminAuthenticate()
    {
        $validation = \Config\Services::validation();
        
        $validation->setRules([
            'email' => 'required|valid_email',
            'password' => 'required|min_length[6]'
        ]);

        if (!$validation->withRequest($this->request)->run()) {
            return redirect()->back()->withInput()->with('errors', $validation->getErrors());
        }

        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        $user = $this->userModel->getUserByEmail($email);

        if ($user && $user['role'] === 'admin' && $this->userModel->verifyPassword($password, $user['password'])) {
            if (!$user['is_active']) {
                return redirect()->back()->with('error', 'Your account is deactivated. Please contact support.');
            }

            $sessionData = [
                'user_id' => $user['id'],
                'user_name' => $user['first_name'] . ' ' . $user['last_name'],
                'user_email' => $user['email'],
                'user_role' => $user['role'],
                'isLoggedIn' => true
            ];

            session()->set($sessionData);

            return redirect()->to(site_url('admin/dashboard'));
        }

        return redirect()->back()->with('error', 'Invalid admin credentials.');
    }

    function view_email_template()
    {
        // Dummy data for testing the email template
        $emailData = [
            'user_name' => 'John Doe',
            'user_email' => 'john.doe@example.com',
            'otp_code' => '123456',
            'expiry_minutes' => 15
        ];
        
        return view('emails/otp_verification', $emailData);
    }

    public function test_email()
    {
        // Enable debugging
        $output = [];
        $output[] = "<h2>Email Configuration Test</h2>";
        
        try {
            // Get email configuration
            $emailConfig = new \Config\Email();
            
            $output[] = "<h3>📧 Email Configuration:</h3>";
            $output[] = "<ul>";
            $output[] = "<li><strong>Protocol:</strong> " . $emailConfig->protocol . "</li>";
            $output[] = "<li><strong>SMTP Host:</strong> " . ($emailConfig->SMTPHost ?: 'Not set') . "</li>";
            $output[] = "<li><strong>SMTP Port:</strong> " . $emailConfig->SMTPPort . "</li>";
            $output[] = "<li><strong>SMTP User:</strong> " . ($emailConfig->SMTPUser ?: 'Not set') . "</li>";
            $output[] = "<li><strong>SMTP Password:</strong> " . (empty($emailConfig->SMTPPass) ? 'Not set' : 'Set (hidden)') . "</li>";
            $output[] = "<li><strong>SMTP Crypto:</strong> " . $emailConfig->SMTPCrypto . "</li>";
            $output[] = "<li><strong>From Email:</strong> " . ($emailConfig->fromEmail ?: 'Not set') . "</li>";
            $output[] = "<li><strong>From Name:</strong> " . ($emailConfig->fromName ?: 'Not set') . "</li>";
            $output[] = "<li><strong>Mail Type:</strong> " . $emailConfig->mailType . "</li>";
            $output[] = "</ul>";

            // Check if required settings are configured
            $output[] = "<h3>⚙️ Configuration Status:</h3>";
            $configIssues = [];
            
            if (empty($emailConfig->SMTPHost)) {
                $configIssues[] = "SMTP Host is not configured";
            }
            if (empty($emailConfig->SMTPUser)) {
                $configIssues[] = "SMTP User is not configured";
            }
            if (empty($emailConfig->SMTPPass)) {
                $configIssues[] = "SMTP Password is not configured";
            }
            if (empty($emailConfig->fromEmail)) {
                $configIssues[] = "From Email is not configured";
            }

            if (empty($configIssues)) {
                $output[] = "<p style='color: green;'>✅ All required email settings are configured!</p>";
                
                // Test email sending
                $output[] = "<h3>📤 Testing Email Send:</h3>";
                
                // Get test email from request or use default
                $testEmail = 'ntaleian@gmail.com';
                $output[] = "<p><strong>Test Email:</strong> " . $testEmail . "</p>";
                
                $emailService = \Config\Services::email();
                
                // Enable debug mode for verbose output
                $emailService->initialize([
                    'protocol' => $emailConfig->protocol,
                    'SMTPHost' => $emailConfig->SMTPHost,
                    'SMTPUser' => $emailConfig->SMTPUser,
                    'SMTPPass' => $emailConfig->SMTPPass,
                    'SMTPPort' => $emailConfig->SMTPPort,
                    'SMTPCrypto' => $emailConfig->SMTPCrypto,
                    'mailType' => $emailConfig->mailType,
                    'charset' => 'utf-8',
                    'wordWrap' => true,
                    'validate' => true
                ]);
                
                $emailService->setFrom($emailConfig->fromEmail ?: 'noreply@watotochurch.org', $emailConfig->fromName ?: 'Watoto Church');
                $emailService->setTo($testEmail);
                $emailService->setSubject('Email Configuration Test - ' . date('Y-m-d H:i:s'));
                
                // Prepare test email content
                $testData = [
                    'user_name' => 'Test User',
                    'user_email' => $testEmail,
                    'otp_code' => '123456',
                    'expiry_minutes' => 15
                ];
                
                $htmlMessage = view('emails/otp_verification', $testData);
                $textMessage = "Test Email from Watoto Church Wedding Booking System\n\n";
                $textMessage .= "This is a test email to verify your email configuration.\n";
                $textMessage .= "Test OTP: 123456\n";
                $textMessage .= "Time: " . date('Y-m-d H:i:s') . "\n\n";
                $textMessage .= "If you received this email, your configuration is working correctly!";
                
                $emailService->setMessage($htmlMessage);
                $emailService->setAltMessage($textMessage);
                
                // Attempt to send email
                $output[] = "<p>🚀 Attempting to send test email...</p>";
                
                if ($emailService->send()) {
                    $output[] = "<p style='color: green; font-weight: bold;'>✅ SUCCESS: Test email sent successfully!</p>";
                    $output[] = "<div style='background: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
                    $output[] = "<h4>📬 Email Details:</h4>";
                    $output[] = "<ul>";
                    $output[] = "<li><strong>To:</strong> " . $testEmail . "</li>";
                    $output[] = "<li><strong>From:</strong> " . ($emailConfig->fromEmail ?: 'noreply@watotochurch.org') . "</li>";
                    $output[] = "<li><strong>Subject:</strong> Email Configuration Test</li>";
                    $output[] = "<li><strong>Type:</strong> HTML with text alternative</li>";
                    $output[] = "<li><strong>Time:</strong> " . date('Y-m-d H:i:s') . "</li>";
                    $output[] = "</ul>";
                    $output[] = "</div>";
                } else {
                    $output[] = "<p style='color: red; font-weight: bold;'>❌ FAILED: Could not send test email</p>";
                    $output[] = "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin: 10px 0;'>";
                    $output[] = "<h4>🐛 Debug Information:</h4>";
                    $output[] = "<pre style='background: #fff; padding: 10px; border-radius: 3px; overflow-x: auto;'>";
                    $output[] = htmlspecialchars($emailService->printDebugger(['headers', 'subject', 'body']));
                    $output[] = "</pre>";
                    $output[] = "</div>";
                }
                
            } else {
                $output[] = "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px;'>";
                $output[] = "<p style='color: red;'><strong>❌ Configuration Issues Found:</strong></p>";
                $output[] = "<ul>";
                foreach ($configIssues as $issue) {
                    $output[] = "<li style='color: red;'>" . $issue . "</li>";
                }
                $output[] = "</ul>";
                $output[] = "<p><strong>Please update your .env file with the missing configuration.</strong></p>";
                $output[] = "</div>";
            }

            // Environment variables check
            $output[] = "<h3>🔧 Environment Variables:</h3>";
            $envVars = [
                'email.fromEmail',
                'email.fromName', 
                'email.protocol',
                'email.SMTPHost',
                'email.SMTPUser',
                'email.SMTPPass',
                'email.SMTPPort',
                'email.SMTPCrypto',
                'email.mailType'
            ];
            
            $output[] = "<ul>";
            foreach ($envVars as $var) {
                $value = $_ENV[$var] ?? 'Not set';
                if ($var === 'email.SMTPPass' && $value !== 'Not set') {
                    $value = 'Set (hidden)';
                }
                $output[] = "<li><strong>" . $var . ":</strong> " . $value . "</li>";
            }
            $output[] = "</ul>";

            // Instructions
            $output[] = "<h3>📋 Testing Instructions:</h3>";
            $output[] = "<div style='background: #e2f3ff; border: 1px solid #bee5eb; padding: 15px; border-radius: 5px;'>";
            $output[] = "<ol>";
            $output[] = "<li>To test with your own email: <code>/test-email?email=your-email@example.com</code></li>";
            $output[] = "<li>Check your email inbox (and spam folder) for the test email</li>";
            $output[] = "<li>If you see configuration issues above, update your <code>.env</code> file</li>";
            $output[] = "<li>For Gmail: Use App Password instead of regular password</li>";
            $output[] = "<li>Check firewall settings if connection fails</li>";
            $output[] = "</ol>";
            $output[] = "</div>";

        } catch (\Exception $e) {
            $output[] = "<div style='background: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px;'>";
            $output[] = "<h3 style='color: red;'>💥 Error Occurred:</h3>";
            $output[] = "<p style='color: red;'><strong>Error:</strong> " . $e->getMessage() . "</p>";
            $output[] = "<p><strong>File:</strong> " . $e->getFile() . "</p>";
            $output[] = "<p><strong>Line:</strong> " . $e->getLine() . "</p>";
            $output[] = "<pre style='background: #fff; padding: 10px; border-radius: 3px; overflow-x: auto;'>";
            $output[] = htmlspecialchars($e->getTraceAsString());
            $output[] = "</pre>";
            $output[] = "</div>";
        }

        $htmlOutput = implode("\n", $output);
        
        return "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>Email Test - Watoto Church</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; line-height: 1.6; }
        h2, h3 { color: #333; }
        pre { background: #f4f4f4; padding: 10px; border-radius: 5px; overflow-x: auto; }
        ul { margin: 10px 0; }
        li { margin: 5px 0; }
        code { background: #f4f4f4; padding: 2px 5px; border-radius: 3px; }
    </style>
</head>
<body>
    {$htmlOutput}
    <hr style='margin: 30px 0;'>
    <p><a href='/view-email-template'>📧 View Email Template</a> | <a href='/'>🏠 Home</a></p>
</body>
</html>";
    }
}
