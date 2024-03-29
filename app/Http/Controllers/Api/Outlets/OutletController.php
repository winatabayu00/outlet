<?php

namespace App\Http\Controllers\Api\Outlets;

use App\Actions\Outlets\DeleteOutlet;
use App\Http\Controllers\Api\Controller as ApiController;
use App\Http\Resources\Outlets\OutletResource;
use App\Models\Outlets\Outlet;
use App\Services\Outlet\OutletService;
use Dentro\Yalr\Attributes;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;
use Throwable;
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

    /**
     * @param Request $request
     * @return Response
     * @throws Throwable
     * @throws ValidationException
     */
    #[Attributes\Post('', name: 'create')]
    public function create(Request $request): Response
    {
        (new OutletService())->create(request: $request);
        return $this->response();
    }

    #[Attributes\Get('{outlet}', name: 'show')]
    public function show(OutletService $brand): Response
    {
        return $this->response(new OutletResource($brand));
    }

    /**
     * @param Request $request
     * @param Outlet $outlet
     * @param OutletService $service
     * @return Response
     * @throws ValidationException
     * @throws Throwable
     */
    #[Attributes\Put('{outlet}', name: 'update')]
    public function update(Request $request, Outlet $outlet, OutletService $service): Response
    {
        $service->update(outlet: $outlet,request: $request);
        return $this->response();
    }

    /**
     * @param Outlet $outlet
     * @return Response
     */
    #[Attributes\Delete('{outlet}', name: 'destroy')]
    public function destroy(Outlet $outlet): Response
    {
        (new DeleteOutlet(outlet: $outlet));
        return $this->response();
    }
}
