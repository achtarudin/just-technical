<?php

namespace App\Models\Type;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeUserModel extends Model
{
    use HasFactory;

    protected $table = "type_users";

    protected $guarded = [];

    public $timestamps = false;

    /**
     * Define the relationship
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(UserModel::class, 'user_id')->withDefault();
    }

    public function type(): BelongsTo
    {
        return $this->belongsTo(TypeModel::class, 'types_id', 'id')->withDefault();
    }
}
