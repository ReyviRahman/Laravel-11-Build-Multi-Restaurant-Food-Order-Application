@extends('client.client_dashboard')
@section('client')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">All Menus</h4>

            <div class="page-title-right">
              <a href="{{ route('menus.create') }}" class="btn btn-primary waves-effect waves-light">Add Menu</a>
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
                    <th>Menu Name</th>
                    <th>Image</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($menus as $key=> $item)
                  <tr>
                    <td>{{ $key+1 }}</td>
                    <td>{{ $item->menu_name }}</td>
                    <td><img src="{{ asset('storage/'.$item->image) }}" alt="" style="width: 70px; height: 40px;"></td>
                    <td>
                      <a href="{{ route('menus.edit', $item->id) }}" class="btn btn-info waves-effect waves-light">Edit</a>
                      <button type="button" class="btn btn-danger waves-effect waves-light" data-id="{{ $item->id }}">Delete</button>
                      <form id="delete-form-{{ $item->id }}" action="{{ route('menus.destroy', $item->id) }}" method="POST" style="display: none;">
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