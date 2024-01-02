<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Article extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'article';

    protected $guarded = [];

    public function article_category(){
        return $this->belongsTo(ArticleCategory::class, 'article_category_id')->withTrashed();
    }

    public function user(){
        return $this->belongsTo(User::class, 'author_id')->withTrashed();
    }
}
