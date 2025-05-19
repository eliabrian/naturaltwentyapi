<?php

namespace App\JsonApi\V1;

use App\JsonApi\V1\Events\EventSchema;
use App\JsonApi\V1\Games\GameSchema;
use App\JsonApi\V1\Rooms\RoomSchema;
use App\JsonApi\V1\Tags\TagSchema;
use LaravelJsonApi\Core\Server\Server as BaseServer;

class Server extends BaseServer
{

    /**
     * The base URI namespace for this server.
     *
     * @var string
     */
    protected string $baseUri = '/api/v1';

    /**
     * Bootstrap the server when it is handling an HTTP request.
     *
     * @return void
     */
    public function serving(): void
    {
        // no-op
    }

    /**
     * Get the server's list of schemas.
     *
     * @return array
     */
    protected function allSchemas(): array
    {
        return [
            TagSchema::class,
            GameSchema::class,
            RoomSchema::class,
            EventSchema::class,
        ];
    }
}
