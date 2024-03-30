<?php

namespace App\Http\Controllers\Api\Products;

use App\Actions\Outlets\DeleteOutlet;
use App\Actions\Products\DeleteProduct;
use App\Http\Controllers\Api\Controller as ApiController;
use App\Http\Resources\Outlets\OutletResource;
use App\Http\Resources\Products\ProductCollection;
use App\Http\Resources\Products\ProductResource;
use App\Models\Outlets\Outlet;
use App\Models\Products\Product;
use App\Queries\Product\ProductQuery;
use App\Services\Outlet\OutletService;
use App\Services\Product\ProductService;
use Dentro\Yalr\Attributes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
use Winata\Core\Response\Http\Response;

#[Attributes\Name('product', false, true)]
#[Attributes\Prefix('product')]
class ProductController extends ApiController
{
    #[Attributes\Get('', name: 'index')]
    public function index(Request $request): Response
    {
        $query = (new ProductQuery())->build();
        if ($request->has('pagination') && 'true' === $request->input('pagination')) {
            $outlets = $query->paginate(
                perPage: $request->input('limit'),
                page: $request->input('page')
            );
        } else {
            $outlets = $query->get();
        }
        return $this->response(new ProductCollection($outlets));
    }

    /**
     * @param Request $request
     * @param ProductService $service
     * @return Response
     * @throws ValidationException
     */
    #[Attributes\Post('', name: 'create')]
    public function create(Request $request, ProductService $service): Response
    {
        $service->create(request: $request);
        return $this->response();
    }

    #[Attributes\Get('{product}', name: 'show')]
    public function show(OutletService $brand): Response
    {
        return $this->response(new ProductResource($brand));
    }

    /**
     * @param Request $request
     * @param Product $product
     * @param ProductService $service
     * @return Response
     * @throws ValidationException
     * @throws Throwable
     */
    #[Attributes\Put('{product}', name: 'update')]
    public function update(Request $request, Product $product, ProductService $service): Response
    {
        $service->update(product: $product,request: $request);
        return $this->response();
    }

    /**
     * @param Product $product
     * @return Response
     */
    #[Attributes\Delete('{product}', name: 'destroy')]
    public function destroy(Product $product): Response
    {
        (new DeleteProduct(product: $product ));
        return $this->response();
    }
}
