@extends('client.client_dashboard')
@section('client')
<script src="https://code.jquery.com/jquery-3.7.1.min.js"
  integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>

<div class="page-content">
  <div class="container-fluid">
    <div class="row">
      <div class="col-12">
        <div class="page-title-box d-sm-flex align-items-center justify-content-between">
          <h4 class="mb-sm-0 font-size-18">Edit Product</h4>
          <div class="page-title-right">
            <ol class="breadcrumb m-0">
              <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>
              <li class="breadcrumb-item active">Edit Product</li>
            </ol>
          </div>
        </div>
      </div>
    </div>

    <form action="{{ route('products.update', $product->id) }}" id="myForm" method="post" enctype="multipart/form-data">
  @csrf
  @method('PUT')

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <div class="row">
    <div class="col-xl-12 col-lg-12">
      <div class="card-body p-4">
        <div class="row">

          <!-- Category -->
          <div class="col-xl-4 col-md-6">
            <div class="form-group mb-3">
              <label class="form-label">Category</label>
              <select class="form-select" name="category_id" required>
                <option value="">-- Select Category --</option>
                @foreach ($categories as $category)
                  <option value="{{ $category->id }}" {{ $product->category_id == $category->id ? 'selected' : '' }}>
                    {{ $category->category_name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Menu -->
          <div class="col-xl-4 col-md-6">
            <div class="form-group mb-3">
              <label class="form-label">Menu</label>
              <select class="form-select" name="menu_id" required>
                <option value="">-- Select Menu --</option>
                @foreach ($menus as $menu)
                  <option value="{{ $menu->id }}" {{ $product->menu_id == $menu->id ? 'selected' : '' }}>
                    {{ $menu->menu_name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- City -->
          <div class="col-xl-4 col-md-6">
            <div class="form-group mb-3">
              <label class="form-label">City</label>
              <select class="form-select" name="city_id" required>
                <option value="">-- Select City --</option>
                @foreach ($cities as $city)
                  <option value="{{ $city->id }}" {{ $product->city_id == $city->id ? 'selected' : '' }}>
                    {{ $city->city_name }}
                  </option>
                @endforeach
              </select>
            </div>
          </div>

          <!-- Product Name -->
          <div class="col-xl-4 col-md-6">
            <div class="form-group mb-3">
              <label class="form-label">Product Name</label>
              <input class="form-control" type="text" name="name" value="{{ $product->name }}" required>
            </div>
          </div>

          <!-- Price -->
          <div class="col-xl-4 col-md-6">
            <div class="form-group mb-3">
              <label class="form-label">Price</label>
              <input class="form-control" type="number" name="price" min="0" value="{{ $product->price }}" required>
            </div>
          </div>

          <!-- Discount Price -->
          <div class="col-xl-4 col-md-6">
            <div class="form-group mb-3">
              <label class="form-label">Discount Price</label>
              <input class="form-control" type="number" name="discount_price" min="0" value="{{ $product->discount_price }}">
            </div>
          </div>

          <!-- Size -->
          <div class="col-xl-4 col-md-6">
            <div class="form-group mb-3">
              <label class="form-label">Size</label>
              <input class="form-control" type="text" name="size" value="{{ $product->size }}">
            </div>
          </div>

          <!-- QTY -->
          <div class="col-xl-4 col-md-6">
            <div class="form-group mb-3">
              <label class="form-label">Product QTY</label>
              <input class="form-control" type="number" name="qty" min="0" value="{{ $product->qty }}">
            </div>
          </div>

          <!-- Flags -->
          <div class="col-xl-4 col-md-6 d-flex align-items-center gap-3 mb-3">
            <div class="form-check">
              <input class="form-check-input" name="best_seller" type="checkbox" value="1" id="bestSeller" {{ $product->best_seller ? 'checked' : '' }}>
              <label class="form-check-label" for="bestSeller">Best Seller</label>
            </div>
            <div class="form-check">
              <input class="form-check-input" name="most_populer" type="checkbox" value="1" id="mostPopuler" {{ $product->most_populer ? 'checked' : '' }}>
              <label class="form-check-label" for="mostPopuler">Most Populer</label>
            </div>
          </div>

          <!-- Status -->
          <div class="col-xl-4 col-md-6">
            <div class="form-group mb-3">
              <label class="form-label">Status</label>
              <select class="form-select" name="status">
                <option value="active" {{ $product->status == 'active' ? 'selected' : '' }}>Active</option>
                <option value="inactive" {{ $product->status == 'inactive' ? 'selected' : '' }}>Inactive</option>
              </select>
            </div>
          </div>

          <!-- Image -->
          <div class="col-12 d-flex gap-4">
            <div class="form-group flex-grow-1 mb-3">
              <label for="image" class="form-label">Product Image</label>
              <input class="form-control" type="file" name="image" id="image">
            </div>
            <div class="mb-3">
              <img id="showImage" src="{{ !empty($product->image) ? asset('storage/'.$product->image) : asset('upload/no_image.jpg') }}" alt=""
                class="rounded-circle p-1 bg-primary" width="110">
            </div>
          </div>

          <div class="d-grid gap-2">
            <button type="submit" class="btn btn-primary waves-effect waves-light">Update Product</button>
          </div>

        </div>
      </div>
    </div>
  </div>
</form>

  </div>
</div>

<script type="text/javascript">
  $(document).ready(function () {
    $('#image').change(function (e) {
      var reader = new FileReader();
      reader.onload = function (e) {
        $('#showImage').attr('src', e.target.result);
      }
      reader.readAsDataURL(e.target.files['0'])
    })
  })
</script>
@endsection
