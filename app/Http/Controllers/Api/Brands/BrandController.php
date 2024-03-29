<?php

namespace App\Http\Controllers\Api\Brands;

use App\Actions\Brands\DeleteBrand;
use App\Http\Controllers\Api\Controller as ApiController;
use App\Http\Resources\Brands\BrandCollection;
use App\Http\Resources\Brands\BrandResource;
use App\Models\Brands\Brand;
use App\Queries\Brand\BrandQuery;
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
    /**
     * @throws ValidationException
     */
    #[Attributes\Get('', name: 'index')]
    public function index(): Response
    {
        $query = (new BrandQuery())->build();

        return $this->response(new BrandCollection($query->paginate()));
    }

    /**
     * @param Request $request
     * @return Response
     * @throws Throwable
     * @throws ValidationException
     */
    #[Attributes\Post('', name: 'create')]
    public function create(Request $request): Response
    {
        (new BrandService())->create(request: $request);
        return $this->response();
    }

    #[Attributes\Get('{brand}', name: 'show')]
    public function show(Brand $brand): Response
    {
        return $this->response(new BrandResource($brand));
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
    #[Attributes\Delete('{brand}', name: 'destroy')]
    public function destroy(Brand $brand): Response
    {
        (new DeleteBrand(brand: $brand));
        return $this->response();
    }
}
