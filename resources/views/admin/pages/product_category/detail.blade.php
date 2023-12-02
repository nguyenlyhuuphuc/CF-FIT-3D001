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
                  <h3 class="card-title">Product Category Create</h3>
                </div>
                <!-- /.card-header -->
                <!-- form start -->
                {{ $errors ?? dd($errors->all()) }}
                <form role="form" method="POST" action="{{ route('admin.product_category.update', ['id' => $productCategory->id ]) }}">
                  <div class="card-body">
                    <div class="form-group">
                      <label for="name">Name</label>
                      <input value="{{ $productCategory->name }}" type="text" name="name" class="form-control" id="name" placeholder="Enter name">
                        @error('name')
                          <span style="color: red">{{ $message }}</span>
                        @enderror
                      
                    </div>
                    <div class="form-group">
                      <label for="slug">Slug</label>
                      <input value="{{ $productCategory->slug }}" type="text" name="slug" class="form-control" id="slug" placeholder="Enter slug">
                        @error('slug')
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

@section('js-custom')
  <script type="text/javascript">
    $(document).ready(function(){
      //selector
      $('#name').on('keyup', function(){
        var nameValue = $(this).val();
        
        $.ajax({
          method: 'POST', //method of form
          url : '{{ route('admin.product_category.slug') }}', // action of form
          data: {
            name: nameValue,
            _token: '{{ csrf_token() }}'
          },
          success: function(response){
            //Fill data to input
            $('#slug').val(response.slug);
          }
        });
      });
    });
  </script>
@endsection