<?php

namespace App\Http\Integrations\Laradevs\Requests;

use Saloon\Enums\Method;
use Saloon\Http\Request;

class DestroyDeveloperRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::DELETE;

    public function __construct(
        public readonly int $developerId
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/developers/' . $this->developerId;
    }
}
