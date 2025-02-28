<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\EmailAuthentication\Input;

use Symfony\Component\Validator\Constraints as Assert;

class GenerateCodeInput
{
    #[Assert\NotNull, Assert\NotBlank, Assert\Email]
    public string $email;
}
