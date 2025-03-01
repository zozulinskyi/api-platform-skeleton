<?php
declare(strict_types=1);

namespace App\Component\Notifier\Database;

use Symfony\Component\Notifier\Recipient\RecipientInterface;

interface DatabaseRecipientInterface extends RecipientInterface
{
    public function getUserId(): string;
}
