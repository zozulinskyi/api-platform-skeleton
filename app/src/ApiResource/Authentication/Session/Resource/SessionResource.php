<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Session\Resource;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model;
use App\ApiResource\Authentication\Session\Output\SessionOutput;
use App\ApiResource\Authentication\Session\Provider\SessionListProvider;

#[ApiResource(
    shortName: 'Authentication/Session',
    operations: [
        new GetCollection(
            uriTemplate: '',
            openapi: new Model\Operation(
                summary: 'Get session list for current user',
                description: 'Method return all session for current user for monitoring or revoke a session',
            ),
            output: SessionOutput::class,
            provider: SessionListProvider::class,
        ),
    ],
    routePrefix: '/v1/auth/sessions'
)]
final readonly class SessionResource
{}
