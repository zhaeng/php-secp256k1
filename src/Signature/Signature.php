<?php declare(strict_types=1);

namespace zhaeng\Signature;

use GMP;
use zhaeng\Serializer\HexSignatureSerializer;
use zhaeng\Signature\SignatureInterface;
use Mdanter\Ecc\Crypto\Signature\Signature as EccSignature;

class Signature extends EccSignature implements SignatureInterface
{
    protected $serializer;

    protected $recoveryParam;

    public function __construct(GMP $r, GMP $s, int $recoveryParam) {
        parent::__construct($r, $s);

        $this->serializer = new HexSignatureSerializer;
        $this->recoveryParam = $recoveryParam;
    }

    public function toHex(): string {
        return $this->serializer->serialize($this);
    }

    public function getRecoveryParam(): int {
        return $this->recoveryParam;
    }
}