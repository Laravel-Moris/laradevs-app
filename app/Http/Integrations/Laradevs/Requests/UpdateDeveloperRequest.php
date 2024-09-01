<?php

namespace App\Http\Integrations\Laradevs\Requests;

use Saloon\Contracts\Body\HasBody;
use Saloon\Enums\Method;
use Saloon\Http\Request;
use Saloon\Traits\Body\HasJsonBody;

class UpdateDeveloperRequest extends Request implements HasBody
{
    use HasJsonBody;

    /**
     * The HTTP method of the request
     */
    protected Method $method = Method::PUT;

    public function __construct(
        public readonly int $developerId,
        public readonly array $updateFormData
    ) {}

    /**
     * The endpoint for the request
     */
    public function resolveEndpoint(): string
    {
        return '/developers/' . $this->developerId;
    }

    public function defaultBody(): array
    {
        return $this->updateFormData;
    }
}
