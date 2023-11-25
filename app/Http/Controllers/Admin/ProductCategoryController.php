<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

use function PHPSTORM_META\map;

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
            'name' => 'required|min:3|max:10'
        ],[
            'name.required' => 'Ten buoc phai nhap !',
            'name.min' => 'Ten buoc phai nhap tren :min ky tu',
            'name.max' => 'Ten buoc phai nhap duoi :max ky tu'
        ]);

        //Fresh data
        //Communicate with Model

        //Cach 1 : SQL RAW
        // $check = DB::insert("INSERT INTO product_category (name) VALUES (?)", [$name]);

        //Cach 2 : Query Builder
        // $check = DB::table('product_category')->insert([
        //     'name' => $name
        // ]);

        //Cach 3 : Model -> Eloquent
      
        //Cach 3.1 => New Model
        // $productCategory = new ProductCategory();
        // $productCategory->name = $name;
        // $check = $productCategory->save();

        //Cach 3.2 => Mass Assignment
        $check = ProductCategory::create([
            'name' => $name
        ]);

        $message = $check ? 'them danh muc san pham thanh cong' : 'them danh muc san pham that bai';

        //Sesison Flash
        return redirect()
        ->route('admin.product_category')
        ->with('msg', $message);
    }

    public function createSlug(Request $request){
        return response()->json([
            'slug' => Str::slug($request->name ?? '')
        ]);
    }
}
