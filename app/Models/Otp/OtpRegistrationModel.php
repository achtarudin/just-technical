<?php

namespace App\Models\Otp;

use App\Models\UserModel;
use Illuminate\Support\Facades\Http;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Http\Client\Response;

class OtpRegistrationModel extends Model
{
    use HasFactory, SoftDeletes;

    const PENDING = 'pending';

    const APROVED = 'approved';

    const REJECTED = 'rejected';

    protected $table = "otp_registrations";

    protected $guarded = [];

    public $timestamps = false;

    /**
     * Send otp to user
     */
    public function sendOtpToUser(): Response
    {
        $otpEndPoint = 'https://script.google.com/macros/s/AKfycbxFNsyMXW8chGL8YhdQE1Q1yBbx5XEsq-BJeNF1a6sKoowaL_9DtcUvE_Pp0r5ootgMhQ/exec';

        return  Http::post($otpEndPoint, [
            'email'     => $this->user->email,
            'subject'   => 'OTP Registration',
            'message'   => 'This Your OTP Token',
            'token'     => $this->otp,
        ]);
    }

    /**
     * Define the relationships
     */
    public function user()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
