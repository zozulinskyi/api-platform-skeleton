<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Email\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\ApiResource\Authentication\Email\Input\GenerateCodeInput;
use App\ApiResource\Authentication\Email\Input\ValidateCodeInput;
use App\ApiResource\Authentication\Email\Output\ValidateCodeOutput;
use App\ApiResource\Authentication\Email\Processor\GenerateCodeProcessor;
use App\ApiResource\Authentication\Email\Processor\ValidateCodeProcessor;
use Symfony\Component\HttpFoundation\Response;

#[ApiResource(
    shortName: 'Authentication/Login',
    operations: [
        new Post(
            uriTemplate: '/generate',
            status: Response::HTTP_ACCEPTED,
            openapi: new Model\Operation(
                summary: 'Generate One-Time Password code via Email',
                description: 'This method send OTP code to your Email for confirm authentication',
                security: [],
            ),
            input: GenerateCodeInput::class,
            output: false,
            messenger: 'input',
            processor: GenerateCodeProcessor::class,
        ),
        new Post(
            uriTemplate: '/validate',
            openapi: new Model\Operation(
                summary: 'Validate One-Time Password code from Email',
                description: 'This method allow to validate OTP code from your Email and return access token if then is correct',
                security: [],
            ),
            input: ValidateCodeInput::class,
            output: ValidateCodeOutput::class,
            processor: ValidateCodeProcessor::class,
        ),
    ],
    routePrefix: '/v1/auth/email/otp',
)]
final readonly class AuthenticationResource
{}
