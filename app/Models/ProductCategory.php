<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_category';

    //Field allow insert|update into database
    protected $fillable = ['name', 'slug'];

    //Field NOT allow insert|update into database
    // protected $guarded = [];
}
