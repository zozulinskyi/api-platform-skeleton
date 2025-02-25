<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Email\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Authentication\Email\Input\ValidateCodeInput;
use App\ApiResource\Authentication\Email\Output\ValidateCodeOutput;
use App\Repository\Authentication\CodeRepository;
use App\Repository\Authentication\UserRepository;
use App\Services\Authentication\CodeGeneratorService;
use Lexik\Bundle\JWTAuthenticationBundle\Services\JWTTokenManagerInterface;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @implements ProcessorInterface<ValidateCodeInput, ValidateCodeOutput>
 */
final readonly class ValidateCodeProcessor implements ProcessorInterface
{
    public function __construct(
        private UserRepository $userRepository,
        private CodeRepository $codeRepository,
        private CodeGeneratorService $codeGeneratorService,
        private JWTTokenManagerInterface $JWTTokenManager,
    )
    {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ValidateCodeOutput
    {
        $codeEntity = $this->codeRepository->findOneBy(['login' => $data->email]);

        if (is_null($codeEntity) || !$this->codeGeneratorService->isValid(code: $data->code, hash: $codeEntity->getSecretCode())) {
            throw new AccessDeniedHttpException(message: 'Incorrect code');
        }
        if ($codeEntity->getExpiredAt()->lessThan(date: 'now')) {
            throw new AccessDeniedHttpException(message: 'Your code was expired');
        }

        $user = $this->userRepository->findOrCreate(email: $data->email);
        $token = $this->JWTTokenManager->create(user: $user);

        $this->codeRepository->clearByLogin(login: $data->email);

        return new ValidateCodeOutput(token: $token);
    }
}
