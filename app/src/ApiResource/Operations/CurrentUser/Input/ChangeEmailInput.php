<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\CurrentUser\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class ChangeEmailInput
{
    #[Assert\NotNull, Assert\NotBlank, Assert\Email]
    public string $newEmail;
}
