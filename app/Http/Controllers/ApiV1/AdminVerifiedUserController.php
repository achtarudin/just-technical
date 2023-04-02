<?php

namespace App\Http\Controllers\ApiV1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\AdminVerifiedUserService;
use App\Http\Requests\ApiV1\AdminVerifiedUserRequest;

class AdminVerifiedUserController extends Controller
{
    protected $service;

    public function __construct(AdminVerifiedUserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $result =  $this->service->search([])->paginate(10);
        return response()->json($result);
    }

    public function store(Request $request)
    {
        return response()->json([], 404);
    }

    public function show($id)
    {
        $registration =  $this->service
            ->search(['id' => $id])
            ->first();

        if ($registration == null) {
            return response()->json($registration ?? null, 404);
        }

        return response()->json($registration ?? null);
    }

    public function update(AdminVerifiedUserRequest $request, $id)
    {
        $registration =  $this->service
            ->search(['id' => $id])
            ->first();

        if ($registration == null) {
            return response()->json($registration ?? null, 404);
        }

        $updateRegistration =  $this->service->update(model: $registration, attributes: $request->all())->fresh();

        return response()->json([
            'message'   => "Registration {$registration->status} to {$updateRegistration->status} ",
            'data'      => $updateRegistration
        ]);
    }

    public function destroy($id)
    {
        return response()->json([], 404);

    }
}
