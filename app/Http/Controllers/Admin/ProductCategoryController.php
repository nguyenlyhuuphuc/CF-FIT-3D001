<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProductCategoryController extends Controller
{
    public function index(){
        return view('admin.pages.product_category.index');
    }

    public function create(){
        return view('admin.pages.product_category.create');
    }

    public function store(Request $request){
        $name = $request->name;
        //Validate
        $request->validate([
            'name' => ['required', 'min: 5', 'max: 255']
        ]);

        dd(3);
        
    }
}
