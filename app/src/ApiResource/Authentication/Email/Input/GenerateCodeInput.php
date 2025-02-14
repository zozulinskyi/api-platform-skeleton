<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Email\Input;

use Symfony\Component\Messenger\Attribute\AsMessage;
use Symfony\Component\Validator\Constraints as Assert;

#[AsMessage(transport: 'async')]
class GenerateCodeInput
{
    #[Assert\NotNull, Assert\NotBlank, Assert\Email]
    public string $email;
}
