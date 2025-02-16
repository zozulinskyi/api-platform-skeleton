<?php
declare(strict_types=1);

namespace App\ApiResource\Authentication\Session\Resource;

use ApiPlatform\Doctrine\Orm\State\Options;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model;
use App\ApiResource\Authentication\Session\Output\SessionOutput;
use App\Entity\Authentication\Session;

#[ApiResource(
    shortName: 'Authentication/Session',
    operations: [
        new GetCollection(
            uriTemplate: '/sessions',
            openapi: new Model\Operation(
                summary: 'Get session list for current user',
                description: 'Method return all session for current user for monitoring or revoke a session',
            ),
            output: SessionOutput::class,
            stateOptions: new Options(entityClass: Session::class),
        ),
    ],
    routePrefix: '/v1/auth',
)]
final readonly class SessionResource
{}
