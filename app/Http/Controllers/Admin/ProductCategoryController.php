<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ProductCategoryController extends Controller
{
    public function indexPagination (Request $request){
        $itemPerPage = 3;
        $totalItems = DB::table('product_category')->count();
        $totalPage = ceil($totalItems / $itemPerPage);
        $page = $request->page ?? 1;

        $index = ($page - 1) * $itemPerPage;
        //limit 0,10
        $productCategories = DB::table('product_category')
        ->offset($index)
        ->limit($itemPerPage)
        ->get();

        return view('admin.pages.product_category.index', [
            'productCategories' => $productCategories,
            'totalPage' => $totalPage,
            'itemPerPage' => $itemPerPage,
            'page' => $page
        ]);
    }
    public function index(Request $request){
        //Cach 1 : SQL RAW
        // $productCategoires = DB::select("select * from product_category");

        //Cach 2 : Query Builder
        // $productCategoires = DB::table('product_category')->select('*')->get();

        //Cach 3 : Eloquent
        // $productCategories = ProductCategory::all();

        // $productCategories = DB::table('product_category')->paginate(5);
        // $productCategoires = ProductCategory::paginate(5);
        $keyword = $request->keyword ?? "";
        $keyword = '%'.$keyword.'%';

        $sort = $request->sort ?? 'latest';
        $direction = $sort === 'latest' ? "DESC" : "ASC";

        // $productCategoires = DB::table('product_category')
        // ->where('name', 'like', $keyword)
        // ->orWhere('slug', 'like', $keyword)
        // ->orderBy('created_at', $direction)
        // ->paginate(5);
        $productCategoires = ProductCategory::withTrashed()
          ->where('name', 'like', $keyword)
        ->orWhere('slug', 'like', $keyword)
        ->orderBy('created_at', $direction)
        ->paginate(env('PAGINATION_ITEM', 10));

        //Cach 1 : Pass variable to view
        return view('admin.pages.product_category.index', [
            'productCategories' => $productCategoires,
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

    public function update(Request $request, $id){
        //Find record
        //Query Builder
        // $productCategory = DB::table('product_category')
        // ->where('id', '=', $id)
        // ->update([
        //     'name' => $request->name,
        //     'slug' => $request->slug,
        //     'updated_at' => Carbon::now()
        // ]);

        //Eloquent
        // $productCategory = ProductCategory::find($id);
        // $productCategory->name = $request->name;
        // $productCategory->slug = $request->slug;
        // $productCategory->updated_at = Carbon::now();
        // $productCategory->save();

        //ELoquent Mass Assignment
        $productCategory = ProductCategory::find($id)
        ->update([
            'name' => $request->name,
            'slug' => $request->slug,
        ]);

        //Redirect List with flash session
        return redirect()->route('admin.product_category')->with('msg', 'Update thanh cong');
    }
}
