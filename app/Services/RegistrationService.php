<?php

namespace App\Services;

use Exception;
use Nette\Utils\Random;
use App\Models\UserModel;
use App\Models\Type\TypeModel;
use App\Services\ServiceInterface;
use Illuminate\Support\Facades\DB;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class RegistrationService implements ServiceInterface
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
    public function search(array $attributes): ?Builder
    {
        return null;
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

            throw_if($typeUser == null, new Exception('Type of User not found'));

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
                throw_if(true, new Exception('Failed Registration User For OTP'));
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
            DB::commit();
            return $model;
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
