<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class Recaptcha extends BaseConfig
{
    /**
     * Google reCAPTCHA Site Key
     * Get this from: https://www.google.com/recaptcha/admin
     * Replace with your actual site key from Google reCAPTCHA admin
     */
    public $siteKey = '6LfXLNArAAAAAHVebjrPb-zOZH2NXy7KFtp1yde7'; // Replace with your real site key or use: getenv('RECAPTCHA_SITE_KEY')

    /**
     * Google reCAPTCHA Secret Key
     * Get this from: https://www.google.com/recaptcha/admin
     * Replace with your actual secret key from Google reCAPTCHA admin
     */
    public $secretKey = '6LfXLNArAAAAAHd4-V3FcpcKj7jTa7v-R7qKllIc'; // Replace with your real secret key or use: getenv('RECAPTCHA_SECRET_KEY')

    /**
     * reCAPTCHA API URL for verification
     */
    public $verifyUrl = 'https://www.google.com/recaptcha/api/siteverify';

    /**
     * Enable/disable reCAPTCHA
     * Set to false to disable reCAPTCHA during development
     */
    public $enabled = true; // Set to true when you have real keys

    /**
     * reCAPTCHA theme (light or dark)
     */
    public $theme = 'light';

    /**
     * reCAPTCHA size (normal, compact)
     */
    public $size = 'normal';
}