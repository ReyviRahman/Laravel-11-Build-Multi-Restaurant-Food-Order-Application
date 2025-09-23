@extends('admin.admin_dashboard')
@section('admin')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">All Categories</h4>

            <div class="page-title-right">
              <a href="{{ route('add.categories') }}" class="btn btn-primary waves-effect waves-light">Add Category</a>
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
                    <th>Category Name</th>
                    <th>Image</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($category as $key=> $item)
                  <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->category_name }}</td>
                    <td><img src="{{ asset('storage/'.$item->image) }}" alt="" style="width: 70px; height: 40px;"></td>
                    <td>
                      <a href="{{ route('edit.category', $item->id) }}" class="btn btn-info waves-effect waves-light">Edit</a>
                      <button type="button" class="btn btn-danger waves-effect waves-light" data-id="{{ $item->id }}">Delete</button>
                      <form id="delete-form-{{ $item->id }}" action="{{ route('delete.category', $item->id) }}" method="POST" style="display: none;">
                        @csrf
                        @method('DELETE')
                      </form>
                    </td>
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
            const categoryId = this.getAttribute('data-id');
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
                    document.getElementById('delete-form-' + categoryId).submit();
                }
            });
        });
    });
});
</script>
@endsection



