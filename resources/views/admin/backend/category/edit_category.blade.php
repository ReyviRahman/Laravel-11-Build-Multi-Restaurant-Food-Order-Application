@extends('admin.admin_dashboard')
@section('admin')
<script
  src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo="
  crossorigin="anonymous"></script>

  <div class="page-content">
    <div class="container-fluid">

        <!-- start page title -->
        <div class="row">
            <div class="col-12">
                <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                    <h4 class="mb-sm-0 font-size-18">Edit Category</h4>

                    <div class="page-title-right">
                        <ol class="breadcrumb m-0">
                            <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
                            <li class="breadcrumb-item active">Edit Category</li>
                        </ol>
                    </div>

                </div>
            </div>
        </div>
        <!-- end page title -->

        <form action="{{ route('update.category') }}" id="myForm" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <input type="hidden" value="{{ $category->id }}" name="id">
            <div class="row">
                <div class="col-xl-12 col-lg-12">
                    <div class="card-body p-4">
                        <div class="row">
                            <div class="col-lg-12">
                                <div>
                                    <div class="form-group mb-3">
                                        <label for="example-text-input" class="form-label">Category Name</label>
                                        <input class="form-control" type="text" value="{{ $category->category_name }}" name="category_name"  id="example-text-input">
                                    </div>
                                </div>
                                <div class="mt-3 mt-lg-0">
                                    <div class="form-group mb-3">
                                        <label for="image" class="form-label">Category Image</label>
                                        <input class="form-control" type="file" name="image" id="image">
                                    </div>
                                    <div class="mb-3">
                                        <img id="showImage" 
                                            src="{{ asset('storage/'.$category->image) }}" 
                                            alt="" 
                                            class="rounded-circle p-1 bg-primary" 
                                            width="110">
                                    </div>
                                    <div class="mt-4">
                                        <button type="submit" class="btn btn-primary waves-effect waves-light">Edit Category</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
        <!-- end row -->
        
    </div> <!-- container-fluid -->
</div>
<script type="text/javascript">
    $(document).ready(function() {
        $('#image').change(function(e){
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#showImage').attr('src', e.target.result);
            }
            reader.readAsDataURL(e.target.files['0'])
        })
    })
</script>

<script type="text/javascript">
    $(document).ready(function (){
        $('#myForm').validate({
            rules: {
                category_name: {
                    required : true,
                }, 
            },
            messages :{
                category_name: {
                    required : 'Please Enter Category Name',
                }, 
            },
            errorElement : 'span', 
            errorPlacement: function (error,element) {
                error.addClass('invalid-feedback');
                element.closest('.form-group').append(error);
            },
            highlight : function(element, errorClass, validClass){
                $(element).addClass('is-invalid');
            },
            unhighlight : function(element, errorClass, validClass){
                $(element).removeClass('is-invalid');
            },
        });
    });
    
</script>
@endsection