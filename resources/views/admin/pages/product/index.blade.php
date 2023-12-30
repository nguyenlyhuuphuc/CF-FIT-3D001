@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        
        @if (session('msg'))
          <div class="alert alert-success" role="alert">
            {{ session('msg') }}
          </div>
        @endif

        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0 text-dark">Product</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Product</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <div class="col-md-12">
            <div class="card">
              <div class="card-header">
                <h3 class="card-title">Product</h3>
              </div>
              <!-- /.card-header -->
              <div class="card-body">
                {{-- {{ dd($productCategories) }} --}}
                <table class="table table-bordered" id="table-product">
                  <thead>                  
                    <tr>
                      <th>#</th>
                      <th>Name</th>
                      <th>Product Category Name</th>
                      <th>Price</th>
                      <th>Created At</th>
                      <th>Deleted At</th>
                      <th>Action</th>
                    </tr>
                  </thead>
                  <tbody>
                    @forelse ($products as $index => $product)
                      <tr>
                        {{-- <td>{{ ($page - 1) * $itemPerPage + $index + 1 }}</td> --}}
                        <td>{{ $loop->iteration }}</td>
                        <td>{{ $product->name }}</td>
                        <td>{{ !is_null($product->productCategory) ? $product->productCategory->name : "" }}</td>
                        {{-- <td>{{ $product->product_category_name }}</td> --}}
                        <td>{{ $product->price }}</td>
                        <td>{{ $product->created_at }}</td>
                        <td>{{ $product->deleted_at }}</td>
                        <td>
                          <a class="btn btn-primary" href="{{ route('admin.product.edit', ['product' => $product->id]) }}">Detail</a>
                          <form action="{{ route('admin.product.destroy', ['product' => $product->id]) }}" method="post">
                              @csrf
                              @method('delete')
                              <button onclick="return confirm('Are you sure ?');" type="submit" class="btn btn-danger">Delete</button>
                          </form>
                          @if($product->trashed())
                            <form action="{{ route('admin.product.store', ['id' => $product->id]) }}" method="POST">
                              @csrf
                              <button type="submit" class="btn btn-success">Restore</button>
                            </form>
                            <form action="{{ route('admin.product.force.delete', ['id' => $product->id]) }}" method="POST">
                              @csrf
                              <button type="submit" class="btn btn-warning">Force Delete</button>
                            </form>    
                          @endif
                        </td>
                      </tr>
                    @empty
                      <tr>
                        <td colspan="4">No data</td>
                      </tr>
                    @endforelse
                  </tbody>
                </table>
              </div>
              <!-- /.card-body -->
              <div class="card-footer clearfix">
                {{-- <ul class="pagination pagination-sm m-0 float-right">
                  <li class="page-item"><a class="page-link" href="#">&laquo;</a></li> --}}
                    {{-- @for ($i = 1; $i <= $totalPage; $i++)
                      <li class="page-item"><a class="page-link" href="?page={{ $i }}">{{ $i }}</a></li>  
                    @endfor --}}
                  {{-- <li class="page-item"><a class="page-link" href="#">&raquo;</a></li>
                </ul> --}}
                {{-- {{ $products->links() }} --}}
              </div>
            </div>
            <!-- /.card -->
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection

@section('js-custom')
  <script>
    $(document).ready(function(){
      let table = new DataTable('#table-product');
    });
  </script>
@endsection