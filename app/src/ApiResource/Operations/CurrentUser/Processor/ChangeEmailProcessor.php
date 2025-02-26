<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\CurrentUser\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Operations\CurrentUser\Input\ChangeEmailInput;
use App\ApiResource\Operations\CurrentUser\Output\ChangeEmailOutput;
use App\Entity\Authentication\User;
use App\Repository\Authentication\EmailChangeRequestRepository;
use App\Repository\Authentication\UserRepository;
use App\Services\Authentication\CodeGeneratorService;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;

/**
 * @implements ProcessorInterface<ChangeEmailInput, ChangeEmailOutput>
 */
final readonly class ChangeEmailProcessor implements ProcessorInterface
{
    public function __construct(
        private Security $security,
        private UserRepository $userRepository,
        private MailerInterface $mailer,
        private CodeGeneratorService $codeGeneratorService,
        private EmailChangeRequestRepository $emailChangeRequestRepository,
    )
    {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): object
    {
        /** @var User $user **/
        $user = $this->security->getUser();

        // pre-validate current request. maybe we can move this logic into another place
        if ($user->getEmail() === $data->newEmail) {
            throw new BadRequestHttpException(message: 'It\'s your current email');
        }
        if ($this->userRepository->count(['email' => $data->newEmail])) {
            throw new BadRequestHttpException(message: 'This email already taken');
        }

        // generate security codes
        [$oldEmailCode, $oldEmailHash] = $this->codeGeneratorService->generateRandomCodeWithHash();
        [$newEmailCode, $newEmailHash] = $this->codeGeneratorService->generateRandomCodeWithHash();

        // store current request into our db
        $changeEmailRequest = $this->emailChangeRequestRepository->generate(
            user: $user,
            newEmail: $data->newEmail,
            oldEmailSecretCode: $oldEmailHash,
            newEmailSecretCode: $newEmailHash,
        );

        // generate and send emails
        $firstEmail = (new Email())
            ->to($user->getEmail())
            ->text(sprintf('You create a request to change your email address to %s. To confirm this action, enter the next code: %s', $data->newEmail, $oldEmailCode))
            ->subject('Change Email Address');

        $secondEmail = (new Email())
            ->to($data->newEmail)
            ->text(sprintf('To use this email, enter the next code: %s', $newEmailCode))
            ->subject('Confirm your Email');

        $this->mailer->send(message: $firstEmail);
        $this->mailer->send(message: $secondEmail);

        // generate and send response
        return new ChangeEmailOutput(id: $changeEmailRequest->getId()->toString());
    }
}
