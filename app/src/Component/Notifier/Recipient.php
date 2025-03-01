<?php
declare(strict_types=1);

namespace App\Component\Notifier;

use App\Component\Notifier\Database\DatabaseRecipientInterface;
use Symfony\Component\Notifier\Recipient\EmailRecipientTrait;
use Symfony\Component\Notifier\Recipient\Recipient as BaseRecipient;
use Symfony\Component\Notifier\Recipient\SmsRecipientTrait;
use Symfony\Component\Security\Core\User\UserInterface;

final class Recipient extends BaseRecipient implements DatabaseRecipientInterface
{
    use EmailRecipientTrait, SmsRecipientTrait;

    private null|UserInterface $user;

    public function __construct(?UserInterface $user = null, string $email = '', string $phone = '')
    {
        $this->user = $user;
        $this->email = $email;
        $this->phone = $phone;

        if ($email !== '' || $phone !== '') {
            parent::__construct($email, $phone);
        }
    }

    public function getUserId(): string
    {
        return $this->user->getUserIdentifier();
    }
}
