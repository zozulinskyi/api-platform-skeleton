<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\Session;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model;
use App\ApiResource\Operations\Session\Output\SessionOutput;
use App\ApiResource\Operations\Session\Provider\SessionListProvider;
use App\Entity\Authentication\Session;

#[ApiResource(
    shortName: 'Session',
    operations: [
        new Delete(
            uriTemplate: '/sessions/{id}',
            openapi: new Model\Operation(
                summary: 'Remove user session by ID',
                description: 'Method allow to remove user session by ID',
            ),
            security: "is_granted('SESSION_DELETE', object)",
            stateOptions: new Options(entityClass: Session::class),
        ),
        new GetCollection(
            uriTemplate: '/sessions',
            openapi: new Model\Operation(
                summary: 'Get session list for current user',
                description: 'Method return all session for current user for monitoring or revoke a session',
            ),
            output: SessionOutput::class,
            provider: SessionListProvider::class,
            stateOptions: new Options(entityClass: Session::class),
        ),
    ],
    routePrefix: '/v1/auth',
)]
final readonly class SessionResource
{}
