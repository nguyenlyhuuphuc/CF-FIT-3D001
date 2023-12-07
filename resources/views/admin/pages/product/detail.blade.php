@extends('admin.layout.master')

@section('content')
<div class="content-wrapper">
    <section class="content">
        <div class="container-fluid">
          <div class="row">
            <div class="col-md-12">
              <!-- general form elements -->
              <div class="card card-primary">
                <div class="card-header">
                  <h3 class="card-title">Product</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{ $errors ?? dd($errors->all()) }}
                <form role="form" method="POST" action="{{ route('admin.product_category.update', ['id' => $product->id ]) }}">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input value="{{ $product->name }}" type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                        @error('name')
                          <span style="color: red">{{ $message }}</span>
                        @enderror
                      
                    </div>
                    
                  </div>
                  <!-- /.card-body -->
                  @csrf
                  <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                  </div>
                </form>
              </div>
              <!-- /.card -->
            </div>
          </div>
        </div>
      </section>
</div>
@endsection
