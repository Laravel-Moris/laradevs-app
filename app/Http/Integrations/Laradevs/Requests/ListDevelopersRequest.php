<?php

namespace App\Http\Integrations\Laradevs\Requests;

use App\Http\Integrations\Laradevs\Responses\DevelopersListResponse;
use Saloon\Enums\Method;
use Saloon\Http\Request;

class ListDevelopersRequest extends Request
{
    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::GET;

    protected ?string $response = DevelopersListResponse::class;

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/developers';
    }
}
