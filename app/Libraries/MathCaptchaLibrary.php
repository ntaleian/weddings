<?php

namespace App\Libraries;

use Config\Services;

class MathCaptchaLibrary
{
    protected $session;
    
    public function __construct()
    {
        $this->session = Services::session();
        helper('session');
    }
    
    /**
     * Generate a simple math question
     *
     * @return array
     */
    public function generateQuestion()
    {
        $operations = ['+', '-', '*'];
        $operation = $operations[array_rand($operations)];
        
        $num1 = 0;
        $num2 = 0;
        $answer = 0;
        $question = '';
        
        switch ($operation) {
            case '+':
                $num1 = rand(1, 20);
                $num2 = rand(1, 20);
                $answer = $num1 + $num2;
                $question = "{$num1} + {$num2}";
                break;
            
            case '-':
                $num1 = rand(10, 30);
                $num2 = rand(1, $num1 - 1);
                $answer = $num1 - $num2;
                $question = "{$num1} - {$num2}";
                break;
            
            case '*':
                $num1 = rand(2, 9);
                $num2 = rand(2, 9);
                $answer = $num1 * $num2;
                $question = "{$num1} × {$num2}";
                break;
            
            default:
                // Fallback to addition if something goes wrong
                $num1 = rand(1, 20);
                $num2 = rand(1, 20);
                $answer = $num1 + $num2;
                $question = "{$num1} + {$num2}";
                break;
        }
        
        // Store the answer in session for verification
        if ($this->session) {
            $this->session->set('captcha_answer', $answer);
            $this->session->set('captcha_question', $question);
            $this->session->set('captcha_timestamp', time());
        } else {
            session()->set('captcha_answer', $answer);
            session()->set('captcha_question', $question);
            session()->set('captcha_timestamp', time());
        }
        
        return [
            'question' => $question,
            'answer' => $answer
        ];
    }

    /**
     * Verify the captcha answer
     *
     * @param int $userAnswer
     * @return bool
     */
    public function verify($userAnswer)
    {
        $sessionAnswer = $this->session ? $this->session->get('captcha_answer') : session()->get('captcha_answer');
        $timestamp = $this->session ? $this->session->get('captcha_timestamp') : session()->get('captcha_timestamp');
        
        // Check if captcha exists and is not too old (5 minutes)
        if (!$sessionAnswer || !$timestamp || (time() - $timestamp) > 300) {
            return false;
        }
        
        // Verify the answer
        if ((int)$userAnswer === (int)$sessionAnswer) {
            // Clear the captcha from session after successful verification
            if ($this->session) {
                $this->session->remove('captcha_answer');
                $this->session->remove('captcha_question');
                $this->session->remove('captcha_timestamp');
            } else {
                session()->remove('captcha_answer');
                session()->remove('captcha_question');
                session()->remove('captcha_timestamp');
            }
            return true;
        }
        
        return false;
    }

    /**
     * Get the current question
     *
     * @return string|null
     */
    public function getCurrentQuestion()
    {
        return $this->session ? $this->session->get('captcha_question') : session()->get('captcha_question');
    }

    /**
     * Clear captcha from session
     */
    public function clearCaptcha()
    {
        if ($this->session) {
            $this->session->remove('captcha_answer');
            $this->session->remove('captcha_question');
            $this->session->remove('captcha_timestamp');
        } else {
            session()->remove('captcha_answer');
            session()->remove('captcha_question');
            session()->remove('captcha_timestamp');
        }
    }

    /**
     * Check if captcha is expired
     *
     * @return bool
     */
    public function isExpired()
    {
        $timestamp = $this->session ? $this->session->get('captcha_timestamp') : session()->get('captcha_timestamp');
        return !$timestamp || (time() - $timestamp) > 300;
    }
}