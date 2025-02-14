<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Email\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use ApiPlatform\Symfony\Messenger\Processor;
use App\ApiResource\Authentication\Email\Input\GenerateCodeInput;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\HttpKernel\Exception\TooManyRequestsHttpException;
use Symfony\Component\RateLimiter\RateLimiterFactory;

/**
 * @implements ProcessorInterface<GenerateCodeInput, mixed>
 */
final readonly class GenerateCodeProcessor implements ProcessorInterface
{
    public function __construct(
        #[Autowire(service: Processor::class)]
        private ProcessorInterface $messengerProcessor,
        private RateLimiterFactory $authenticationLimiter,
    )
    {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): mixed
    {
        $limiter = $this->authenticationLimiter->create(key: $data->email);
        $limit = $limiter->consume();

        if ($limit->isAccepted() === false) {
            throw new TooManyRequestsHttpException(retryAfter: $limit->getRetryAfter()->getTimestamp() - time());
        }

        return $this->messengerProcessor->process($data, $operation, $uriVariables, $context);
    }
}
