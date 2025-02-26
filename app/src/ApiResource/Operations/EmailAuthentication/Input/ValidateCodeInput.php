<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\EmailAuthentication\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class ValidateCodeInput extends GenerateCodeInput
{
    #[Assert\NotNull, Assert\NotBlank, Assert\Length(exactly: 6)]
    public string $code;
}
