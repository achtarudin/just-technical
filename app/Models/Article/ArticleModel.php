<?php

namespace App\Models\Article;

use App\Models\UserModel;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ArticleModel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = "articles";

    protected $guarded = [];

    public $timestamps = false;


    /**
     * Define the relationships
     */
    public function author()
    {
        return $this->belongsTo(UserModel::class, 'user_id', 'id');
    }
}
