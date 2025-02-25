<?php
declare(strict_types=1);

namespace App\Services\Authentication;

use Symfony\Component\String\ByteString;

final readonly class CodeGeneratorService
{
    public function isValid(string $code, string $hash): bool
    {
        return password_verify(password: $code, hash: $hash);
    }

    public function generateHash(string $code): string
    {
        return password_hash(password: $code, algo: PASSWORD_BCRYPT);
    }

    public function generateRandomCode(): string
    {
        return ByteString::fromRandom(length: 6, alphabet: '1234567890')->toString();
    }

    public function generateRandomCodeWithHash(): array
    {
        $code = $this->generateRandomCode();
        $hash = $this->generateHash(code: $code);

        return [$code, $hash];
    }
}
