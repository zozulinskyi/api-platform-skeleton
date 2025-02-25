<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\User\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Authentication\User\Input\ConfirmEmailInput;
use App\Entity\Enum\EmailChangeRequestStatus;
use App\Repository\Authentication\EmailChangeRequestRepository;
use App\Services\Authentication\CodeGeneratorService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

/**
 * @implements ProcessorInterface<ConfirmEmailInput, null>
 */
final readonly class ConfirmEmailProcessor implements ProcessorInterface
{
    public function __construct(
        private CodeGeneratorService $codeGeneratorService,
        private EntityManagerInterface $entityManager,
        private EmailChangeRequestRepository $emailChangeRequestRepository,
    )
    {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): void
    {
        $emailRequest = $this->emailChangeRequestRepository->findOneBy([
            'status' => EmailChangeRequestStatus::PENDING,
            'id' => $data->requestId,
       ]);

        if (is_null($emailRequest)) {
            throw new BadRequestHttpException(message: 'We couldn\'t find your request for change email');
        }
        if ($emailRequest->getExpiredAt()->lessThan(date: 'now')) {
            throw new BadRequestHttpException(message: 'Your request for change email was expired');
        }
        if (!$this->codeGeneratorService->isValid(code: $data->codeFromOldEmail, hash: $emailRequest->getOldEmailSecretCode())) {
            throw new BadRequestHttpException(message: 'Incorrect code from old email');
        }
        if (!$this->codeGeneratorService->isValid(code: $data->codeFromNewEmail, hash: $emailRequest->getNewEmailSecretCode())) {
            throw new BadRequestHttpException(message: 'Incorrect code from new email');
        }

        $emailRequest->getUser()->setEmail(email: $emailRequest->getNewEmail());
        $emailRequest->setStatus(status: EmailChangeRequestStatus::CONFIRMED);

        $this->entityManager->flush();
    }
}
