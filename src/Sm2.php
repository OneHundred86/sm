<?php

namespace Oh86\Sm;

use Mdanter\Ecc\Crypto\Key\PrivateKey;
use Mdanter\Ecc\Crypto\Signature\Signature;
use Rtgm\ecc\Sm2Signer;
use Rtgm\sm\RtSm2;

class Sm2 extends RtSm2
{
    public function doSignWithoutAsn1($document, $privateKey, $userId = null): string
    {
        if (empty($userId)) {
            $userId = $this->userId;
        }
        $adapter = $this->adapter;
        $generator = $this->generator;
        $algorithm = 'sha256';
        $secret = gmp_init($privateKey, 16);
        $key = new PrivateKey($adapter, $generator, $secret);
        return $this->_dosignWithoutAsn1($document, $key, $adapter, $generator, $userId, $algorithm);
    }

    protected function _dosignWithoutAsn1($document, $key, $adapter, $generator, $userId, $algorithm = 'sha256'): string
    {
        $obPoint = $key->getPublicKey()->getPoint();

        $pubKeyX = $adapter->decHex($obPoint->getX());
        $pubKeyY = $adapter->decHex($obPoint->getY());

        $hash = $this->_doS3Hash($document, $pubKeyX, $pubKeyY, $generator, $userId);

        # Derandomized signatures are not necessary, but is avoids
        # the risk of a low entropy RNG, causing accidental reuse
        # of a k value for a different message, which leaks the
        # private key.
        if ($this->useDerandomizedSignatures) {
            $random = \Mdanter\Ecc\Random\RandomGeneratorFactory::getHmacRandomGenerator($key, $hash, $algorithm);
        } else {
            $random = \Mdanter\Ecc\Random\RandomGeneratorFactory::getRandomGenerator();
        }

        $randomK = $random->generate($generator->getOrder());

        $signer = new Sm2Signer($adapter);
        $signature = $signer->sign($key, $hash, $randomK);

        return $this->intToHex($signature->getR()).$this->intToHex($signature->getS());
    }

    private function intToHex($number, $byteLength = 32): string
    {
        // 将整数转换为大端字节序的字节串
        $hex = str_pad(gmp_strval($number, 16), $byteLength * 2, '0', STR_PAD_LEFT);
        return $hex;
    }

    public function verifySignWithoutAsn1($document, $sign, $publicKey, $userId = null)
    {
        $adapter = $this->adapter;
        $generator = $this->generator;
        if (empty($userId)) {
            $userId = $this->userId;
        }

        if ($this->formatSign == 'base64') {
            $sign = bin2hex(base64_decode($sign));
        }

        if (strlen($sign) != 128) {
            return false;
        }

        $rHex = substr($sign, 0, 64);
        $sHex = substr($sign, 64);

        $sig = new Signature(gmp_init($rHex, 16), gmp_init($sHex, 16));

        // get hash
        list($pubKeyX, $pubKeyY) = $this->_getKeyXY($publicKey);

        $hash = $this->_doS3Hash($document, $pubKeyX, $pubKeyY, $generator, $userId);

        // get pubkey parse
        $key = $this->_getPubKeyObject($pubKeyX, $pubKeyY);

        $signer = new Sm2Signer($adapter);
        return  $signer->verify($key, $sig, $hash);
    }
}
