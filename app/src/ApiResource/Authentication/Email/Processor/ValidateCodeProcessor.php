<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Email\Processor;

use ApiPlatform\Metadata\Operation;
use ApiPlatform\State\ProcessorInterface;
use App\ApiResource\Authentication\Email\Input\ValidateCodeInput;
use App\ApiResource\Authentication\Email\Output\ValidateCodeOutput;
use App\Repository\Authentication\CodeRepository;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;

/**
 * @implements ProcessorInterface<ValidateCodeInput, ValidateCodeOutput>
 */
final readonly class ValidateCodeProcessor implements ProcessorInterface
{
    public function __construct(
        private CodeRepository $codeRepository,
    )
    {}

    public function process(mixed $data, Operation $operation, array $uriVariables = [], array $context = []): ValidateCodeOutput
    {
        $codeEntity = $this->codeRepository->findOneBy(['login' => $data->email]);

        if (is_null($codeEntity) || !password_verify(password: $data->code, hash: $codeEntity->getHash())) {
            throw new AccessDeniedHttpException(message: 'Incorrect code');
        }
        if ($codeEntity->getExpiredAt()->lessThan(date: 'now')) {
            throw new AccessDeniedHttpException(message: 'Your code was expired');
        }

        // todo: implement logic for generate access token here...

        return new ValidateCodeOutput(token: '123');
    }
}
