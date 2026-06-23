<?php

namespace Config;

use CodeIgniter\Config\BaseConfig;

class WeddingAuth extends BaseConfig
{
    /**
     * Set auth.requireEmailVerification=true when email delivery is configured.
     */
    public bool $requireEmailVerification = false;

    /**
     * Used only when email verification is not required.
     */
    public bool $autoLoginAfterRegistration = true;

    public function __construct()
    {
        parent::__construct();

        $this->requireEmailVerification = $this->envBool(
            'auth.requireEmailVerification',
            $this->requireEmailVerification
        );
        $this->autoLoginAfterRegistration = $this->envBool(
            'auth.autoLoginAfterRegistration',
            $this->autoLoginAfterRegistration
        );
    }

    private function envBool(string $key, bool $default): bool
    {
        $value = env($key, $default);

        return filter_var($value, FILTER_VALIDATE_BOOL, FILTER_NULL_ON_FAILURE) ?? $default;
    }
}
