<?php

namespace App\ServiceApiV2;

use App\Exceptions\ApiV2Exception;
use App\Models\UserModel;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Database\Eloquent\Model;

class ApiV2AuthService implements ApiV2AuthInterface
{
    public function findAuthor(array $attributes): ?Model
    {
        try {
            return UserModel::where($attributes)->first();
        } catch (Exception $e) {
            throw new ApiV2Exception('Login for Author Failed', 500);
        }
    }

    public function createAuthor(array $attributes): ?Model
    {
        DB::beginTransaction();
        try {
            $data = array_merge($attributes, [
                'password' => Hash::make($attributes['password'])
            ]);
            $author =  UserModel::create($data);
            $author->created_at = now();
            $author->email_verified_at = now();
            $author->save();
            DB::commit();
            return $author;
        } catch (Exception $e) {
            DB::rollBack();
            throw new ApiV2Exception('Registration for Author Failed', 500);
        }
    }

    public function updateAccountAuthor(array $attributes): ?Model
    {
        DB::beginTransaction();
        try {
            $newPassword = $attributes['password'] ?? null;
            $author =  auth()->user();

            $data = array_merge($attributes, [
                'password' => $newPassword ? Hash::make($newPassword) : $author->password
            ]);

            $author->update($data);
            $author->updated_at = now();
            $author->save();
            DB::commit();
            return $author;
        } catch (Exception $e) {
            DB::rollBack();
            throw new ApiV2Exception('Update Account for Author Failed', 500);
        }
    }
}
