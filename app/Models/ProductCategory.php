<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ProductCategory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'product_category';

    //Field allow insert|update into database
    protected $fillable = ['name', 'slug'];

    //Field NOT allow insert|update into database
    // protected $guarded = [];
    public function products(){
        return $this->hasMany(Product::class, 'product_category_id')->withTrashed();
    }
}
