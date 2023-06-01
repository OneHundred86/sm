<?php
use Oh86\Sm\Sm3;
use Oh86\Sm\Sm4;

if (!function_exists("sm3")) {
    function sm3(string $str, bool $binary = false) :string
    {
        $hex = (new Sm3())->encrypt($str);
        if ($binary){
            return hex2bin($hex);
        }else{
            return $hex;
        }
    }
}

if (!function_exists("hmac_sm3")) {
    function hmac_sm3(string $data, string $key) :string
    {
        $block_size = 64; // 块大小固定为64字节
        $opad = str_repeat(chr(0x5c), $block_size); // 内部填充值opad为0x5c
        $ipad = str_repeat(chr(0x36), $block_size); // 内部填充值ipad为0x36

        if (strlen($key) > $block_size) {
            $key = sm3($key, true); // 如果密钥长度超过块大小，则先对密钥进行SM3哈希处理
        }
        $key = str_pad($key, $block_size, chr(0)); // 如果密钥长度小于块大小，则在密钥末尾补0，直到与块大小相等

        $o_key_pad = $key ^ $opad; // 生成外部密钥o_key_pad
        $i_key_pad = $key ^ $ipad; // 生成内部密钥i_key_pad

        $inner_hash = sm3($i_key_pad . $data, true); // 先计算内部哈希值
        return sm3($o_key_pad . $inner_hash, false); // 再计算最终的HMAC-SM3值
    }
}

if (!function_exists("sm4_encrypt")) {
    function sm4_encrypt(string $str):string
    {
        return app(Sm4::class)->encrypt($str);
    }
}

if (!function_exists("sm4_decrypt")) {
    function sm4_decrypt(string $str) :string
    {
        return app(Sm4::class)->decrypt($str);
    }
}