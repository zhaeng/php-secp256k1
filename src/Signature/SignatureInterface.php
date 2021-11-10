<?php declare(strict_types=1);

namespace zhaeng\Signature;

use Mdanter\Ecc\Crypto\Signature\SignatureInterface as EccSignatureInterface;

interface SignatureInterface extends EccSignatureInterface {
    public function getRecoveryParam(): int;
}
