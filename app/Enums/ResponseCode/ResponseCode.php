<?php

namespace App\Enums\ResponseCode;

use ArchTech\Enums\From;
use Symfony\Component\HttpFoundation\Response;
use Winata\Core\Response\Concerns\HasOnResponse;
use Winata\Core\Response\Contracts\OnResponse;

enum ResponseCode implements OnResponse
{
    use HasOnResponse;
    use From;

    case SUCCESS;
    case ERR_ENTITY_NOT_FOUND;
    case ERR_ENTITY_ALREADY_EXISTS;

    /**
     * Determine httpCode from response code.
     *
     * @return int
     */
    public function httpCode(): int
    {
        return match ($this) {
            default => Response::HTTP_BAD_REQUEST
        };
    }

}
