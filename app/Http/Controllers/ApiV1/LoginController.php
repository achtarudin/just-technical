<?php

namespace App\Http\Controllers\ApiV1;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\RegistrationService;
use App\Http\Requests\ApiV1\RegistrationRequest;

class LoginController extends Controller
{
    protected $service;

    public function __construct(RegistrationService $service)
    {
        $this->service = $service;
    }

    public function index()
    {
        return response()->json([], 404);
    }

    public function store(RegistrationRequest $request)
    {
        $valid = $request->validated();
        $user = $this->service->save($valid);
        return response()->json(['message' => "Registration Success for email : {$user->email}"], 200);
    }

    public function show($id)
    {
        return response()->json([], 404);
    }

    public function update(Request $request, $id)
    {
        return response()->json([], 404);
    }

    public function destroy($id)
    {
        return response()->json([], 404);
    }
}
