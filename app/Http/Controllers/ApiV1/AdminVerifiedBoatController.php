<?php

namespace App\Http\Controllers\ApiV1;

use App\Exceptions\ApiV1Exception;
use App\Http\Controllers\Controller;
use App\Http\Resources\BoatResource;
use App\Http\Resources\BoatCollection;
use App\Services\AdminVerifiedBoatService;
use App\Http\Requests\ApiV1\AdminVerifiedBoatRequest;

class AdminVerifiedBoatController extends Controller
{
    protected $service;

    public function __construct(AdminVerifiedBoatService $service)
    {
        $this->service = $service;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $results =  $this->service
            ->search()
            ->with(['author', 'admin'])
            ->paginate(10);

        return new BoatCollection($results);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $result =  $this->service->findById($id);

        throw_if($result == null, new ApiV1Exception('Boat Not Found', 404));

        return response()->json([
            'message'   => "Show A Boat",
            'data'      => new BoatResource($result)
        ], 200);
    }


    public function update(AdminVerifiedBoatRequest $request, $id)
    {
        $valid = $request->validated();

        $result =  $this->service->findById($id);

        throw_if($result == null, new ApiV1Exception('Boat Not Found', 404));

        $resultUpdate =  $this->service->update($result, $valid);

        return response()->json([
            'message'   => "Upadate A Boat",
            'data'      => new BoatResource($resultUpdate->fresh())
        ], 201);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $result =  $this->service->findById($id);

        throw_if($result == null, new ApiV1Exception('Boat Not Found', 404));

        $this->service->delete($result);

        return response()->json([
            'message'   => "Deleted A Boat",
            'data'      => new BoatResource($result)
        ], 201);

    }
}
