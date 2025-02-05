<?php declare(strict_types=1);

namespace zhaeng\Serializer;

use Mdanter\Ecc\Crypto\Key\PrivateKeyInterface;
use Mdanter\Ecc\Primitives\GeneratorPoint;
use Mdanter\Ecc\Serializer\PrivateKey\PrivateKeySerializerInterface;

class HexPrivateKeySerializer implements PrivateKeySerializerInterface
{
    protected $generator;

    public function __construct(GeneratorPoint $generator) {
        $this->generator = $generator;
    }

    public function serialize(PrivateKeyInterface $key): string {
        return gmp_strval($key->getSecret(), 16);
    }

    public function parse(string $formattedKey): PrivateKeyInterface {
        $key = gmp_init($formattedKey, 16);

        return $this->generator->getPrivateKeyFrom($key);
    }
}
