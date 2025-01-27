<?php declare(strict_types=1);

namespace zhaeng\Serializer;

use InvalidArgumentException;
use Mdanter\Ecc\Crypto\Signature\Signature;
use Mdanter\Ecc\Crypto\Signature\SignatureInterface;

class HexSignatureSerializer
{
    public function serialize(SignatureInterface $signature): string {
        $r = $signature->getR();
        $s = $signature->getS();
        $rr = str_pad(gmp_strval($r,16), 64, '0', STR_PAD_LEFT);
        if ((hexdec(substr($rr,0,2)) & 0x80) != 0) {
            $rr = '00' . $rr;
        }
        $ss = str_pad(gmp_strval($s,16), 64, '0', STR_PAD_LEFT);
        if ((hexdec(substr($ss,0,2)) & 0x80) != 0) {
            $ss = '00' . $ss;
        }

        $signature = '02' . dechex(strlen(hex2bin($rr))) . $rr . '02' . dechex(strlen(hex2bin($ss))) . $ss;

        return '30' . dechex(strlen(hex2bin($signature))) . $signature;
        // change by zhaeng 2021-11-10
        //$r = $signature->getR();
        //$s = $signature->getS();

        //return str_pad(gmp_strval($r, 16), 64, '0', STR_PAD_LEFT) . str_pad(gmp_strval($s, 16), 64, '0', STR_PAD_LEFT);
    }

    public function parse(string $binary): SignatureInterface {
        $binary_lower = mb_strtolower($binary);

        if (strpos($binary_lower, '0x') >= 0) {
            $count = 1;
            $binary_lower = str_replace('0x', '', $binary_lower, $count);
        }
        if (mb_strlen($binary_lower) !== 128) {
            throw new InvalidArgumentException('Binary string was not correct.');
        }
        $r = mb_substr($binary_lower, 0, 64);
        $s = mb_substr($binary_lower, 64, 64);

        return new Signature(
            gmp_init($r, 16),
            gmp_init($s, 16)
        );
    }
}
