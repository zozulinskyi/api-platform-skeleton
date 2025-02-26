<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\CurrentUser\Input;

use Symfony\Component\Validator\Constraints as Assert;

final class ConfirmEmailInput
{
    #[Assert\NotNull, Assert\NotBlank, Assert\Uuid]
    public string $requestId;

    #[Assert\NotNull, Assert\NotBlank, Assert\Length(exactly: 6)]
    public string $codeFromOldEmail;

    #[Assert\NotNull, Assert\NotBlank, Assert\Length(exactly: 6)]
    public string $codeFromNewEmail;
}
