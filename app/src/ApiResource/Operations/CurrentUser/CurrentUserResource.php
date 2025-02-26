<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\CurrentUser;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\ApiResource\Operations\CurrentUser\Input\ChangeEmailInput;
use App\ApiResource\Operations\CurrentUser\Input\ConfirmEmailInput;
use App\ApiResource\Operations\CurrentUser\Output\ChangeEmailOutput;
use App\ApiResource\Operations\CurrentUser\Output\CurrentUserOutput;
use App\ApiResource\Operations\CurrentUser\Processor\ChangeEmailProcessor;
use App\ApiResource\Operations\CurrentUser\Processor\ConfirmEmailProcessor;
use App\ApiResource\Operations\CurrentUser\Provider\GetCurrentUserProvider;
use Symfony\Component\HttpFoundation\Response;

#[ApiResource(
    shortName: 'CurrentUser',
    operations: [
        new Get(
            uriTemplate: '/me',
            openapi: new Model\Operation(
                summary: 'Receive information about current user',
                description: 'This method return current user object, also you can verify JWT token',
            ),
            output: CurrentUserOutput::class,
            provider: GetCurrentUserProvider::class,
        ),
        new Post(
            uriTemplate: '/email/change',
            openapi: new Model\Operation(
                summary: 'Request to change email for current user',
                description: 'Method allow to send request for change email for current user',
            ),
            input: ChangeEmailInput::class,
            output: ChangeEmailOutput::class,
            processor: ChangeEmailProcessor::class,
        ),
        new Post(
            uriTemplate: '/email/confirm',
            status: Response::HTTP_OK,
            openapi: new Model\Operation(
                responses: [
                    Response::HTTP_OK => new Model\Response(
                        description: 'OK',
                    ),
                ],
                summary: 'Confirm change a current user email',
                description: 'Method confirm change user email operation',
            ),
            input: ConfirmEmailInput::class,
            output: false,
            processor: ConfirmEmailProcessor::class,
        ),
    ],
    routePrefix: '/v1/user',
)]
final readonly class CurrentUserResource
{}
