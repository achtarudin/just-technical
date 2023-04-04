<?php

namespace App\Http\Controllers\ApiV1;

use Illuminate\Http\Request;
use App\Services\UserBoatService;
use App\Exceptions\ApiV1Exception;
use App\Http\Controllers\Controller;
use App\Http\Resources\BoatResource;
use App\Http\Resources\BoatCollection;
use App\Http\Requests\ApiV1\BoatRequest;

class UserBoatController extends Controller
{

    protected $service;

    public function __construct(UserBoatService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $results =  $this->service->search()->paginate(10);

        return new BoatCollection($results);

        // return response()->json([
        //     'message'   => "All Boats",
        //     'data'      => new BoatCollection($results)
        // ]);
    }


    public function store(BoatRequest $request)
    {
        $valid = $request->validated();

        $result =  $this->service->save($valid);

        return response()->json([
            'message'   => "Create a new boats success",
            'data'      => new BoatResource($result->fresh())
        ]);
    }

    public function show($id)
    {
        $result =  $this->service->findById($id);

        throw_if($result == null, new ApiV1Exception('Boat Not Found', 404));

        return response()->json([
            'message'   => "All Boats",
            'data'      => new BoatResource($result)
        ]);

    }

    public function update(BoatRequest $request, $id)
    {
        $valid = $request->validated();

        $result =  $this->service->findById($id);

        throw_if($result == null, new ApiV1Exception('Boat Not Found', 404));

        $resultUpdate =  $this->service->update($result, $valid);

        return response()->json([
            'message'   => "Upadate Boats",
            'data'      => new BoatResource($resultUpdate->fresh())
        ]);
    }

    public function destroy($id)
    {
        //
    }
}
