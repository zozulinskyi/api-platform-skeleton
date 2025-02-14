<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Email\Handler;

use App\ApiResource\Authentication\Email\Input\GenerateCodeInput;
use App\Repository\Authentication\CodeRepository;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final readonly class GenerateCodeHandler
{
    public function __construct(
        private CodeRepository $repository,
        private MailerInterface $mailer,
    )
    {}

    public function __invoke(GenerateCodeInput $input): void
    {
        $codeEntity = $this->repository->generate(login: $input->email);
        $email = (new Email())
            ->to($input->email)
            ->text(body: sprintf('Your authorization code is: %s', $codeEntity->getCode()))
            ->subject(subject: 'Your authorization code.');

        $this->mailer->send(message: $email);
    }
}
