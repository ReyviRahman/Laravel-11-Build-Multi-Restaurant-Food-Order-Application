@extends('client.client_dashboard')
@section('client')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">All Products</h4>

            <div class="page-title-right">
              <a href="{{ route('products.create') }}" class="btn btn-primary waves-effect waves-light">Add Product</a>
            </div>

          </div>
        </div>
      </div>
      <!-- end page title -->

      <div class="row">
        <div class="col-12">
          <div class="card">

            <div class="card-body">

              <table id="datatable" class="table table-bordered dt-responsive  nowrap w-100">
                <thead>
                  <tr>
                    <th>No</th>
                    <th>Product Name</th>
                    <th>Image</th>
                    <th>Category</th>
                    <th>City</th>
                    <th>Menu</th>
                    <th>Promo Code</th>
                    <th>Qty</th>
                    <th>Size</th>
                    <th>Action</th>
                    <th>Discount Price</th>
                    <th>Most Populer</th>
                    <th>Best Seller</th>
                    <th>Status</th>
                    <th>Price</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($products as $key => $item)
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $item->name }}</td>
                      <td><img src="{{ asset('storage/' . $item->image) }}" alt="" style="width: 70px; height: 40px;"></td>
                      <td>{{ $item->category->category_name }}</td>
                      

                      <td>{{ $item->city->city_name }}</td>
                      <td>{{ $item->menu->menu_name }}</td>
                      <td>{{ $item->code }}</td>
                      <td>{{ $item->qty }}</td>
                      <td>{{ $item->size }}</td>
                      <td>
                        <a href="{{ route('products.edit', $item->id) }}"
                          class="btn btn-info waves-effect waves-light">Edit</a>
                        <button type="button" class="btn btn-danger waves-effect waves-light"
                          data-id="{{ $item->id }}">Delete</button>

                        <form id="delete-form-{{ $item->id }}" action="{{ route('products.destroy', $item->id) }}"
                          method="POST" style="display: none;">
                          @csrf
                          @method('DELETE')
                        </form>
                      </td>
                      
                      <td>{{ $item->discount_price }}</td>
                      <td>
                        @if($item->most_populer)
                          <i class="bx bxs-check-square"></i>
                        @else
                          No
                        @endif
                      </td>

                      <td>
                        @if($item->best_seller)
                          <i class="bx bxs-check-square"></i>
                        @else
                          No
                        @endif
                      </td>


                      <td>{{ $item->status }}</td>
                      <td>{{ $item->price }}</td>
                    </tr>
                  @endforeach

                </tbody>
              </table>

            </div>
          </div>
        </div> <!-- end col -->
      </div> <!-- end row -->

      <!-- end row -->
    </div> <!-- container-fluid -->
  </div>
@endsection

@section('script')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.btn-danger').forEach(function (button) {
        button.addEventListener('click', function () {
          console.log('tes')
          const menuId = this.getAttribute('data-id');
          Swal.fire({
            title: 'Yakin mau hapus?',
            text: "Data yang sudah dihapus tidak bisa dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#d33',
            cancelButtonColor: '#3085d6',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
          }).then((result) => {
            if (result.isConfirmed) {
              document.getElementById('delete-form-' + menuId).submit();
            }
          });
        });
      });
    });
  </script>
@endsection