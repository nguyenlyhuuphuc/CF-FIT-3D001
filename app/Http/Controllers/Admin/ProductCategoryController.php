<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function index(){
        //Cach 1 : SQL RAW
        // $productCategoires = DB::select("select * from product_category");

        //Cach 2 : Query Builder
        // $productCategoires = DB::table('product_category')->select('*')->get();

        //Cach 3 : Eloquent
        $productCategories = ProductCategory::all();

        //Cach 1 : Pass variable to view
        return view('admin.pages.product_category.index', [
            'productCategories' => $productCategories,
            'test' => 'TEST'
        ]);

        //Cach 2 : Pass variable to view
        // return view('admin.pages.product_category.index')
        // ->with('productCategories', $productCategoires)
        // ->with('test', 'TEST');

        //Cach 2 : Pass variable to view
        // return view('admin.pages.product_category.index', compact('productCategories'));
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

    public function destroy(Request $request, $id){
        //Cach 1 : SQL RAW
        // $check = DB::delete("DELETE FROM product_category WHERE id = ?", [$id]);

        //Cach 2 : Query Builder
        // $check = DB::table('product_category')->where('id', '=', $id)->delete();

        //Cach 3 : Eloquent
        // $check = ProductCategory::where('id', '=', $id)->delete();
        // $check = ProductCategory::where('id', $id)->delete();
        $check = ProductCategory::find($id)->delete();

        $msg = $check ? 'xoa thanh cong' : 'xoa that bai';

        return redirect()->route('admin.product_category')->with('msg', $msg);
    }

    public function detail($id) {
        //Cach 1 : SQL RAW
        // $productCategory = DB::select("SELECT * FROM product_category WHERE id = ?", [$id]);
        // $productCategory = $productCategory[0];

        //Cach 2 : Query Builder
        // $productCategory = DB::table('product_category')->where('id', $id)->first();     
        
        //Cach 3 : Eloquent
        // $productCategory = ProductCategory::find($id);
        $productCategory = ProductCategory::findOrFail($id);

        return view('admin.pages.product_category.detail')->with('productCategory', $productCategory);
    }
}
