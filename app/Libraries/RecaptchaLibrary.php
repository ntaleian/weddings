<?php

namespace App\Libraries;

class RecaptchaLibrary
{
    private $config;
    
    public function __construct()
    {
        $this->config = config('Recaptcha');
    }

    /**
     * Verify reCAPTCHA response
     *
     * @param string $response The reCAPTCHA response token
     * @param string $remoteIp The user's IP address (optional)
     * @return bool
     */
    public function verify($response, $remoteIp = null)
    {
        if (!$this->config->enabled) {
            return true; // Skip verification if disabled
        }

        if (empty($response)) {
            return false;
        }

        $data = [
            'secret' => $this->config->secretKey,
            'response' => $response
        ];

        if ($remoteIp) {
            $data['remoteip'] = $remoteIp;
        }

        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];

        $context = stream_context_create($options);
        $result = file_get_contents($this->config->verifyUrl, false, $context);

        if ($result === false) {
            log_message('error', 'reCAPTCHA verification request failed');
            return false;
        }

        $resultJson = json_decode($result, true);

        if (!isset($resultJson['success'])) {
            log_message('error', 'Invalid reCAPTCHA response format');
            return false;
        }

        if (!$resultJson['success']) {
            if (isset($resultJson['error-codes'])) {
                log_message('error', 'reCAPTCHA verification failed: ' . implode(', ', $resultJson['error-codes']));
            }
            return false;
        }

        return true;
    }

    /**
     * Get reCAPTCHA site key
     *
     * @return string
     */
    public function getSiteKey()
    {
        return $this->config->siteKey;
    }

    /**
     * Check if reCAPTCHA is enabled
     *
     * @return bool
     */
    public function isEnabled()
    {
        return $this->config->enabled;
    }

    /**
     * Get reCAPTCHA theme
     *
     * @return string
     */
    public function getTheme()
    {
        return $this->config->theme;
    }

    /**
     * Get reCAPTCHA size
     *
     * @return string
     */
    public function getSize()
    {
        return $this->config->size;
    }
}