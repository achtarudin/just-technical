<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;

use Laravel\Sanctum\HasApiTokens;
use App\Models\Type\TypeUserModel;
use Spatie\Permission\Traits\HasRoles;
use App\Models\Otp\OtpRegistrationModel;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Filament\Models\Contracts\FilamentUser;
use Illuminate\Database\Eloquent\Relations\HasOne;
use PHPOpenSourceSaver\JWTAuth\Contracts\JWTSubject;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class UserModel extends Authenticatable implements JWTSubject, FilamentUser
{
    use HasApiTokens, HasFactory, Notifiable, HasRoles;

    protected $table = "users";

    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * Filament Config User
     */
    public function canAccessFilament(): bool
    {
        return true;
    }

    /**
     * Query Scope
     */
    public function scopeUserValidateOtp(Builder $query, string $otpCode)
    {
        $query->whereNull(['email_verified_at'])
            ->whereHas('user_type', function ($query) {
                $query->whereHas('type', function ($query) {
                    $query->typeUser();
                });
            })
            ->whereHas('otp_registration', function ($query) use ($otpCode) {
                $query->whereOtp($otpCode);
            });
    }

    public function scopeUserIsVerifed(Builder $query)
    {
        $query->whereNotNull(['email_verified_at'])
            ->whereHas('user_type', function ($query) {
                $query->whereHas('type', function ($query) {
                    $query->typeUser();
                });
            })
            ->whereHas('otp_registration', function ($query) {
                $query->where('status', OtpRegistrationModel::APROVED);
            });
    }

    public function scopeUserIsAdmin(Builder $query)
    {
        $query->whereNotNull(['email_verified_at'])
            ->whereHas('user_type', function ($query) {
                $query->whereHas('type', function ($query) {
                    $query->typeAdmin();
                });
            });
    }


    /**
     * Define the relationship
     */
    public function user_type(): HasOne
    {
        return $this->hasOne(TypeUserModel::class, 'user_id', 'id')->withDefault();
    }

    public function otp_registration(): HasOne
    {
        return $this->hasOne(OtpRegistrationModel::class, 'user_id', 'id')->withDefault();
    }
}
