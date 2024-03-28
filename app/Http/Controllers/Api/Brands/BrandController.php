<?php

namespace App\Http\Controllers\Api\Brands;

use App\Actions\Brands\DeleteBrand;
use App\Http\Controllers\Api\Controller as ApiController;
use App\Models\Brands\Brand;
use App\Services\Brand\BrandService;
use Dentro\Yalr\Attributes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
use Winata\Core\Response\Http\Response;

#[Attributes\Name('brand', false, true)]
#[Attributes\Prefix('brand')]
class BrandController extends ApiController
{
    #[Attributes\Get('', name: 'index')]
    public function index(): Response
    {
        return $this->response();
    }

    /**
     * @param Request $request
     * @param BrandService $service
     * @return Response
     * @throws ValidationException
     * @throws Throwable
     */
    #[Attributes\Post('', name: 'create')]
    public function create(Request $request, BrandService $service): Response
    {
        $service->create(request: $request);
        return $this->response();
    }

    #[Attributes\Get('{brand}', name: 'show')]
    public function show(): Response
    {
        return $this->response();
    }

    /**
     * @param Request $request
     * @param Brand $brand
     * @param BrandService $service
     * @return Response
     * @throws ValidationException
     * @throws Throwable
     */
    #[Attributes\Put('{brand}', name: 'update')]
    public function update(Request $request, Brand $brand, BrandService $service): Response
    {
        $service->update(brand: $brand,request: $request);
        return $this->response();
    }

    /**
     * @param Brand $brand
     * @return Response
     */
    #[Attributes\Delete('{brand}', name: 'update')]
    public function destroy(Brand $brand): Response
    {
        (new DeleteBrand(brand: $brand));
        return $this->response();
    }
}
