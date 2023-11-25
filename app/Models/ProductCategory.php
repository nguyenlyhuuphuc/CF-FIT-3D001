<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    use HasFactory;

    protected $table = 'product_category';

    //Filed allow insert into database
    protected $fillable = ['name'];

    //Filed NOT allow insert into database
    // protected $guarded = [];
}
