<?php
declare(strict_types=1);

namespace App\ApiResource\Operations\Notification;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model;
use App\ApiResource\Operations\Notification\Output\NotificationOutput;
use App\ApiResource\Operations\Notification\Provider\NotificationCountProvider;
use App\ApiResource\Operations\Notification\Provider\NotificationListProvider;
use App\ApiResource\Output\CountOutput;

#[ApiResource(
    shortName: 'Notification',
    operations: [
        new Get(
            uriTemplate: '/unread/count',
            openapi: new Model\Operation(
                summary: 'Get count for unread notifications',
                description: 'Method return a count of unread notification for current user',
            ),
            output: CountOutput::class,
            provider: NotificationCountProvider::class,
        ),
        new Post(
            uriTemplate: '/read-all',
            openapi: new Model\Operation(
                summary: 'Mark all notifications as read',
                description: 'Method allow to mark all user notification as read',
            ),
        ),
        new Patch(
            uriTemplate: '/{id}/read',
            openapi: new Model\Operation(
                summary: 'Mark notification as read',
                description: 'Method allow to mark selected notification as read',
            ),
        ),
        new GetCollection(
            uriTemplate: '/all',
            openapi: new Model\Operation(
                summary: 'Get all user notifications',
                description: 'Method allow to receive all notifications for current user',
            ),
            output: NotificationOutput::class,
            provider: NotificationListProvider::class,
        ),
        new GetCollection(
            uriTemplate: '/unread',
            openapi: new Model\Operation(
                summary: 'Get unread user notifications',
                description: 'Method allow to receive unread notifications for current user',
            ),
            output: NotificationOutput::class,
            provider: NotificationListProvider::class,
        ),
    ],
    routePrefix: '/v1/notifications',
)]
final readonly class NotificationResource
{}
