<?php

namespace App\Http\Controllers\Api\Products;

use App\Http\Controllers\Api\Controller as ApiController;
use Dentro\Yalr\Attributes;
use Winata\Core\Response\Http\Response;

#[Attributes\Name('product', true, true)]
#[Attributes\Prefix('product')]
class ProductController extends ApiController
{
    #[Attributes\Get('', name: 'index')]
    public function index(): Response
    {
        return $this->response();
    }
}
