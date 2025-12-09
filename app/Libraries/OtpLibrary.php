<?php

namespace App\Libraries;

class OtpLibrary
{
    protected $db;
    
    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }
    
    /**
     * Generate a 6-digit OTP
     * @return string
     */
    public function generateOtp()
    {
        return str_pad(random_int(100000, 999999), 6, '0', STR_PAD_LEFT);
    }
    
    /**
     * Store OTP in database
     * @param string $email
     * @param string $otp
     * @param int $expiryMinutes
     * @return bool
     */
    public function storeOtp($email, $otp, $expiryMinutes = 15)
    {
        // Delete any existing OTPs for this email
        $this->db->table('email_verifications')->where('email', $email)->delete();
        
        $data = [
            'email' => $email,
            'otp_code' => $otp,
            'expires_at' => date('Y-m-d H:i:s', strtotime("+{$expiryMinutes} minutes")),
            'created_at' => date('Y-m-d H:i:s'),
            'is_used' => false
        ];
        
        return $this->db->table('email_verifications')->insert($data);
    }
    
    /**
     * Verify OTP
     * @param string $email
     * @param string $otp
     * @return bool
     */
    public function verifyOtp($email, $otp)
    {
        $builder = $this->db->table('email_verifications');
        $verification = $builder->where([
            'email' => $email,
            'otp_code' => $otp,
            'is_used' => false,
            'expires_at >' => date('Y-m-d H:i:s')
        ])->get()->getRowArray();
        
        if ($verification) {
            // Mark OTP as used
            $builder->where('id', $verification['id'])->update(['is_used' => true]);
            return true;
        }
        
        return false;
    }
    
    /**
     * Check if OTP exists and is valid (not expired)
     * @param string $email
     * @param string $otp
     * @return bool
     */
    public function isValidOtp($email, $otp)
    {
        $builder = $this->db->table('email_verifications');
        $verification = $builder->where([
            'email' => $email,
            'otp_code' => $otp,
            'is_used' => false,
            'expires_at >' => date('Y-m-d H:i:s')
        ])->get()->getRowArray();
        
        return !empty($verification);
    }
    
    /**
     * Clean up expired OTPs
     * @return bool
     */
    public function cleanupExpiredOtps()
    {
        return $this->db->table('email_verifications')
                       ->where('expires_at <', date('Y-m-d H:i:s'))
                       ->delete();
    }
    
    /**
     * Get remaining time for OTP in minutes
     * @param string $email
     * @param string $otp
     * @return int|null
     */
    public function getRemainingTime($email, $otp)
    {
        $builder = $this->db->table('email_verifications');
        $verification = $builder->where([
            'email' => $email,
            'otp_code' => $otp,
            'is_used' => false
        ])->get()->getRowArray();
        
        if ($verification) {
            $expiryTime = strtotime($verification['expires_at']);
            $currentTime = time();
            $remainingSeconds = $expiryTime - $currentTime;
            
            return $remainingSeconds > 0 ? ceil($remainingSeconds / 60) : 0;
        }
        
        return null;
    }
}
