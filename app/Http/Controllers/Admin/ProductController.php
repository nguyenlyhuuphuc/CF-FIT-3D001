<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\Product\StoreRequest;
use App\Http\Requests\Admin\Product\UpdateRequest;
use App\Models\Product;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // $products = Product::paginate(10);

        //SELECT product.*, product_category.name as product_category_name
        // FROM product
        // LEFT JOIN product_category ON product_category.id = product.product_category_id;
        //Query Builder
        // $products = DB::table('product')
        // ->leftJoin('product_category','product.product_category_id', '=', 'product_category.id')
        // ->select('product.*', 'product_category.name as product_category_name')
        // ->get();

        //Eloquent
        $products = Product::with('productCategory')->withTrashed()->get();

        // dd($products);

        return view('admin.pages.product.index')->with('products', $products);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productCategories = ProductCategory::all();
        return view('admin.pages.product.create')->with('productCategories', $productCategories);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreRequest $request)
    {
        $fileName = $this->storeImage($request);
        //Mass Assigment
        $arrayData = $request->except('_token', 'image_url');
        $arrayData['image_url'] = $fileName;

        Product::create($arrayData);
        
        return redirect()->route('admin.product.index')->with('msg', 'Them san pham thanh cong');
    }
    
    private function storeImage(Request $request): ?string{
        $fileName = null;
        if ($request->hasFile('image_url')) {
            $originName = $request->file('image_url')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);

            $extension = $request->file('image_url')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('image_url')->move(public_path('images'), $fileName);
        }
        return $fileName;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        $productCategories = ProductCategory::all();

        return view('admin.pages.product.detail')
        ->with('product',$product)
        ->with('productCategories', $productCategories);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRequest $request, string $id)
    {
        $product = Product::find($id);
        $oldImage = $product->image_url;
        
        $arrayData = $request->except('_token', '_method', 'image_url');
    
        $fileName = $this->storeImage($request);
        if(!is_null($fileName)){
            $arrayData['image_url'] = $fileName;

            if(!is_null($oldImage)){
                unlink(public_path('images')."/".$oldImage);
            }
        }
        
        $product->update($arrayData);

        return redirect()->route('admin.product.index')->with('msg', 'Cap nhat san pham thanh cong');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Product::find($id)->delete();
        return redirect()->route('admin.product.index')->with('msg', 'Xoa san pham thanh cong');
    }

    public function uploadImage(Request $request){
        if ($request->hasFile('upload')) {
            $originName = $request->file('upload')->getClientOriginalName();
            $fileName = pathinfo($originName, PATHINFO_FILENAME);
            $extension = $request->file('upload')->getClientOriginalExtension();
            $fileName = $fileName . '_' . time() . '.' . $extension;

            $request->file('upload')->move(public_path('images'), $fileName);

            $url = asset('images/' . $fileName);
            return response()->json(['fileName' => $fileName, 'uploaded'=> 1, 'url' => $url]);
        }
    }

    public function restore($id){
        $product = Product::withTrashed()->find($id);
        $product->restore();
        return redirect()->route('admin.product.index')->with('msg', 'Khoi phuc san pham thanh cong');
    }

    public function forceDelete($id){
        $product = Product::withTrashed()->find($id);
        $product->forceDelete();
        return redirect()->route('admin.product.index')->with('msg', 'Xoa san pham thanh cong');
    }
}

