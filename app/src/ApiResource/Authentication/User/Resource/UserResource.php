<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\User\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\OpenApi\Model;
use App\ApiResource\Authentication\User\Output\UserOutput;
use App\ApiResource\Authentication\User\Provider\GetCurrentUserProvider;

#[ApiResource(
    shortName: 'Authentication',
    operations: [
        new Get(
            uriTemplate: '/user',
            openapi: new Model\Operation(
                summary: 'Receive information about current user',
                description: 'This method return current user object, also you can verify JWT token',
            ),
            output: UserOutput::class,
            provider: GetCurrentUserProvider::class,
        ),
    ],
    routePrefix: '/v1/auth',
)]
final readonly class UserResource
{}
