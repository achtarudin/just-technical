<?php

namespace App\Services;

use Exception;
use App\Models\UserModel;
use App\Models\Type\TypeModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\Translation\Exception\NotFoundResourceException;

/**
 * This class only use in migration file to insert some value
 */
class InitMigrationServices
{

    static public function createTypes(): void
    {
        DB::beginTransaction();
        try {
            TypeModel::insert([
                ['name' => TypeModel::ADMIN, 'created_at' => now(),  'description' => 'Insert Using Migration'],
                ['name' => TypeModel::USER,  'created_at' => now(),  'description' => 'Insert Using Migration']
            ]);
            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }

    static public function createNewAdmin()
    {
        DB::beginTransaction();
        try {
            $typeAdminId = TypeModel::whereName(TypeModel::ADMIN)->first();

            throw_if($typeAdminId == null, new NotFoundResourceException('Type with value ' . TypeModel::ADMIN . ' not found'));

            $user = UserModel::create([
                'name'          => 'New Admin',
                'email'         => 'new@admin.com',
                'password'      => Hash::make('secret214'),
            ]);

            $user->user_type()->create([
                'types_id'      => $typeAdminId->id,
                'created_at'    => now(),
                'description'   => 'Insert Using Migration'
            ]);

            $user->email_verified_at = now();
            $user->save();

            DB::commit();
        } catch (Exception $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
