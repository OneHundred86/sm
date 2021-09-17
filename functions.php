<?php
use Southcn\Sm\Sm3;
use Southcn\Sm\Sm4;

if (!function_exists("sm3")) {
    function sm3(string $str)
    {
        return app(Sm3::class)->encrypt($str);
    }
}

if (!function_exists("sm4_encrypt")) {
    function sm4_encrypt(string $str)
    {
        return app(Sm4::class)->encrypt($str);
    }
}

if (!function_exists("sm4_decrypt")) {
    function sm4_decrypt(string $str)
    {
        return app(Sm4::class)->decrypt($str);
    }
}