<?php

namespace App\Services;


class HelperService
{
    private $secret_key = 'DJfcQsgiUZccxaXn';
    private $secret_iv = 'MEBoFBjctSdPILVn';
    private $encrypt_method = "AES-256-CBC";
    /**
     * Return the directory path of a given value ($id = 123 will return 000/000/123/) .
     * @param int $id
     * @return string
     */
    public function path(int $id): string
    {
        $id = 1000000000 + $id;
        $path = substr((string)$id, 1);
        $path = str_split($path, 3);
        $path = implode('/', $path) . '/';
        return $path;
    }

    /**
     * Returns a random string upper or lower case of specified length
     * @param int $length
     * @param bool $upper
     * @return string
     */
    public function randomKey(int $length, bool $upper = false): string
    {
        $string = SHA1(microtime()) . SHA1(microtime()) . SHA1(microtime());
        $string = substr($string, rand(0, (64 - $length)), $length);
        return ($upper === TRUE) ? strtoupper($string) : $string;
    }

    /**
     * @param int $min
     * @param int $max
     * @param int $quantity
     * @return array
     */
    public function uniqueRandomNumbers(int $min, int $max, int $quantity): array
    {
        $numbers = range($min, $max);
        shuffle($numbers);
        return array_slice($numbers, 0, $quantity);
    }

    public function encode($string)
    {
        $key = hash('sha256', $this->secret_key);
        $iv = substr(hash('sha256', $this->secret_iv), 0, 16);
        return $this->safe_b64encode(openssl_encrypt($string, $this->encrypt_method, $key, 0, $iv));
    }

    public function decode($string)
    {
        $key = hash('sha256', $this->secret_key);
        $iv = substr(hash('sha256', $this->secret_iv), 0, 16);
        return openssl_decrypt($this->safe_b64decode($string), $this->encrypt_method, $key, 0, $iv);
    }

    private function safe_b64encode($string)
    {
        $data = base64_encode($string);
        $data = str_replace(array('+', '/', '='), array('-', '_', ''), $data);
        return $data;
    }

    private function safe_b64decode($string)
    {
        $data = str_replace(array('-', '_'), array('+', '/'), $string);
        $mod4 = strlen($data) % 4;
        if ($mod4) {
            $data .= substr('====', $mod4);
        }
        return base64_decode($data);
    }
}