<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ArticleCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'article_category';

    protected $guarded = [];

    public function articles(){
        return $this->hasMany(Article::class, 'article_category_id')->withTrashed();
    }
}
