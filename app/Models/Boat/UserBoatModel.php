<?php

namespace App\Models\Boat;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class UserBoatModel extends Model
{
    use HasFactory, SoftDeletes;

    const PENDING = 'pending';

    const APROVED = 'approved';

    const REJECTED = 'rejected';

    protected $table = "user_boats";

    protected $guarded = [];

    public $timestamps = false;

    /**
     * Getters
     */
    public function getImageStorageAttribute()
    {
        return $this->image ? url("/storage/{$this->image}") : null;
    }

    public function getDocumentStorageAttribute()
    {
        return $this->document ? url("/storage/{$this->document}") : null;
    }

    /**
     * Define the relationships
     */
    public function author()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id')->withDefault();
    }

    public function admin()
    {
        return $this->belongsTo(UserModel::class, 'admin_id', 'id')->withDefault();
    }
}
