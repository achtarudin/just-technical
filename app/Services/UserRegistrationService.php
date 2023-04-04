<?php

namespace App\Services;

use Exception;
use Nette\Utils\Random;
use App\Models\UserModel;
use App\Models\Type\TypeModel;
use App\Exceptions\ApiV1Exception;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class UserRegistrationService implements ServiceInterface
{
    /**
     * Return null or Model
     * Param array of $attributes
     */
    public function findById(string $id): ?Model
    {
        return null;
    }

    /**
     * Return null or Builder
     * Param array of $attributes
     */
    public function search(array $attributes = []): ?Builder
    {
        try {
            return UserModel::query()
                ->when(count($attributes), function ($qw) use ($attributes) {
                    $qw->where($attributes);
                });
        } catch (Exception $th) {
            throw_if(true, new ApiV1Exception('Error When search user', 500));
        }
    }

    /**
     * Registration User
     * Return null or Model
     * Param array of $attributes
     */
    public function save(array $attributes): ?Model
    {
        DB::beginTransaction();
        try {
            // Create a new user
            $user = UserModel::create([
                'name'          => $attributes['name'],
                'email'         => $attributes['email'],
                'password'      => Hash::make($attributes['password']),
                'created_at'    => now()
            ]);

            // Find type user
            $typeUser = TypeModel::typeUser()->first();

            throw_if($typeUser == null, new ApiV1Exception('Type of User not found', 500));

            // Create type of user
            $user->user_type()->create([
                'types_id'          => $typeUser->id,
                'description'       => 'Some Description',
                'created_at'        => now()
            ]);

            // Create otp_registration for user
            $otp = $user->otp_registration()->create([
                'otp' => Random::generate(6, '0-9A-Z'),
                'created_at'    => now()
            ]);

            // Send otp_registration to user
            $resultOtp = $otp->sendOtpToUser();

            // Callback on otp error
            $resultOtp->onError(function () {
                throw_if(true, new ApiV1Exception('Failed Registration User For OTP', 500));
            });

            DB::commit();
            return $user;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    /**
     * Return null or Model
     * Param array of $attributes
     */
    public function update(Model $model, array $attributes): ?Model
    {
        DB::beginTransaction();
        try {
            $model->email_verified_at = now();
            $model->save();
            DB::commit();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
