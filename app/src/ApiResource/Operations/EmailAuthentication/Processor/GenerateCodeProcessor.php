<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\EmailAuthentication\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Operations\EmailAuthentication\Input\GenerateCodeInput;
use App\Repository\Authentication\CodeRepository;
use App\Services\Authentication\CodeGeneratorService;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\RateLimiter\RateLimiterFactory;

/**
 * @implements ProcessorInterface<GenerateCodeInput, void>
 */
final readonly class GenerateCodeProcessor implements ProcessorInterface
{
    public function __construct(
        private CodeRepository $repository,
        private MailerInterface $mailer,
        private RateLimiterFactory $authenticationLimiter,
        private CodeGeneratorService $codeGeneratorService,
    )
    {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        // rate limiter validation
        $limiter = $this->authenticationLimiter->create(key: $data->email);
        $limit = $limiter->consume();

        if ($limit->isAccepted() === false) {
            throw new TooManyRequestsHttpException(retryAfter: $limit->getRetryAfter()->getTimestamp() - time());
        }

        // prepare data and send email
        [$code, $hash] = $this->codeGeneratorService->generateRandomCodeWithHash();
        $codeEntity = $this->repository->generate(login: $data->email, secretCode: $hash);
        $email = (new Email())
            ->to($codeEntity->getLogin())
            ->text(body: sprintf('Your authorization code is: %s', $code))
            ->subject(subject: 'Your authorization code.');

        $this->mailer->send(message: $email);
    }
}
