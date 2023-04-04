<?php

namespace App\Http\Controllers\ApiV1;

use Illuminate\Http\Request;
use App\Exceptions\ApiV1Exception;
use App\Http\Controllers\Controller;
use App\Services\AdminVerifiedUserService;
use App\Http\Requests\ApiV1\AdminVerifiedUserRequest;
use App\Http\Resources\VerificationColection;
use App\Http\Resources\VerificationResource;

class AdminVerifiedUserController extends Controller
{
    protected $service;

    public function __construct(AdminVerifiedUserService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        $results =  $this->service
            ->search()
            ->with(['user', 'admin'])
            ->whereHas('user', function ($qw) {
                $qw->whereNotNull(['email_verified_at']);
            })
            ->paginate(10);

        return new VerificationColection($results);
    }

    public function store(Request $request)
    {
        return response()->json([], 404);
    }

    public function show($id)
    {
        $result =  $this->service
            ->search(['id' => $id])
            ->whereHas('user', function ($qw) {
                $qw->whereNotNull(['email_verified_at']);
            })
            ->first();

        throw_if($result == null, new ApiV1Exception("Registration Otp Not Found", 404));

        return response()->json([
            'message'   => "Show User with otp code: {$result->otp}",
            'data'      => new VerificationResource($result),
        ]);
    }

    public function update(AdminVerifiedUserRequest $request, $id)
    {
        $valid =  $request->validated();

        $registrationOtp =  $this->service
            ->search(['id' => $id])
            ->whereHas('user', function ($qw) {
                $qw->whereNotNull(['email_verified_at']);
            })
            ->first();

        $oldStatus  = $registrationOtp->status;

        throw_if($registrationOtp == null, new ApiV1Exception("Registration Otp Not Found", 404));

        $result =  $this->service->update(model: $registrationOtp, attributes: $valid);

        $newStatus  = $result->status;

        return response()->json([
            'message'   => "Registration {$oldStatus} to {$newStatus} ",
            'data'      => new VerificationResource($result),
        ]);
    }

    public function destroy($id)
    {
        return response()->json([], 404);
    }
}
