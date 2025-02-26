<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\EmailAuthentication\Handler;

use App\ApiResource\Operations\EmailAuthentication\Input\GenerateCodeInput;
use App\Repository\Authentication\CodeRepository;
use App\Services\Authentication\CodeGeneratorService;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Messenger\Attribute\AsMessageHandler;
use Symfony\Component\Mime\Email;

#[AsMessageHandler]
final readonly class GenerateCodeHandler
{
    public function __construct(
        private CodeRepository $repository,
        private MailerInterface $mailer,
        private CodeGeneratorService $codeGeneratorService,
    )
    {}

    public function __invoke(GenerateCodeInput $input): void
    {
        [$code, $hash] = $this->codeGeneratorService->generateRandomCodeWithHash();
        $codeEntity = $this->repository->generate(login: $input->email, secretCode: $hash);

        $email = (new Email())
            ->to($codeEntity->getLogin())
            ->text(body: sprintf('Your authorization code is: %s', $code))
            ->subject(subject: 'Your authorization code.');

        $this->mailer->send(message: $email);
    }
}
