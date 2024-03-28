<?php

namespace App\Http\Controllers\Api\Outlets;

use App\Http\Controllers\Api\Controller as ApiController;
use Dentro\Yalr\Attributes;
use Winata\Core\Response\Http\Response;

#[Attributes\Name('outlet', false, true)]
#[Attributes\Prefix('outlet')]
class OutletController extends ApiController
{
    #[Attributes\Get('', name: 'index')]
    public function index(): Response
    {
        return $this->response();
    }
}
