<?php

namespace App\Http\Controllers\Api\Brands;

use App\Http\Controllers\Api\Controller as ApiController;
use Dentro\Yalr\Attributes;
use Winata\Core\Response\Http\Response;

#[Attributes\Name('brand', true, true)]
#[Attributes\Prefix('brand')]
class BrandController extends ApiController
{
    #[Attributes\Get('', name: 'index')]
    public function index(): Response
    {
        return $this->response();
    }
}
