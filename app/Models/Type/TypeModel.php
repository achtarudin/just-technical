<?php

namespace App\Models\Type;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class TypeModel extends Model
{
    use HasFactory, SoftDeletes;

    const ADMIN = 'Admin';

    const USER = 'User';

    protected $table = "types";

    protected $guarded = [];

    public $timestamps = false;

    /**
     * Scope Queries
     */
    public function scopeTypeUser($query): void
    {
        $query->where('name', self::USER);
    }

    public function scopeTypeAdmin($query): void
    {
        $query->where('name', self::ADMIN);
    }
}
