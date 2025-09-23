@extends('admin.admin_dashboard')
@section('admin')
  <div class="page-content">
    <div class="container-fluid">

      <!-- start page title -->
      <div class="row">
        <div class="col-12">
          <div class="page-title-box d-sm-flex align-items-center justify-content-between">
            <h4 class="mb-sm-0 font-size-18">All Cities</h4>

            <div class="page-title-right">
              <button type="button" class="btn btn-primary waves-effect waves-light" data-bs-toggle="modal"
                data-bs-target="#myModal">Add City</button>
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
                    <th>City Name</th>
                    <th>City Slug</th>
                    <th>Action</th>
                  </tr>
                </thead>
                <tbody>
                  @foreach ($city as $key => $item)
                    <tr>
                      <td>{{ $key + 1 }}</td>
                      <td>{{ $item->city_name }}</td>
                      <td>{{ $item->city_slug }}</td>
                      <td>
                        
                        <button type="button" class="btn-edit btn btn-primary waves-effect waves-light" data-bs-toggle="modal" data-bs-target="#myEdit" data-name="{{ $item->city_name }}" data-id="{{ $item->id }}">Edit</button>
                        <button type="button" class="btn btn-danger waves-effect waves-light"
                          data-id="{{ $item->id }}">Delete</button>
                        <form id="delete-form-{{ $item->id }}" action="{{ route('cities.destroy', $item->id) }}"
                          method="POST" style="display: none;">
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
  <div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true"
    data-bs-scroll="true">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="myModalLabel">Add City</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <form action="{{ route('cities.store') }}" id="myForm" method="post" enctype="multipart/form-data">
            @csrf
            <div class="row">
              <div class="col-xl-12 col-lg-12">
                <div class="card-body">
                  <div class="row">
                    <div class="col-lg-12">
                      <div>
                        <div class="form-group mb-3">
                          <label for="example-text-input" class="form-label">City Name</label>
                          <input class="form-control" type="text" name="city_name" id="example-text-input">
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
            </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary waves-effect waves-light">Add City</button>
        </div>
        </form>
      </div><!-- /.modal-content -->
    </div><!-- /.modal-dialog -->
  </div>

  <!-- Edit City Modal -->
<div id="myEdit" class="modal fade" tabindex="-1" aria-labelledby="myEditLabel" aria-hidden="true"
  data-bs-scroll="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <form id="editForm" method="POST">
        @csrf
        @method('PUT')
        <div class="modal-header">
          <h5 class="modal-title" id="myEditLabel">Edit City</h5>
          <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
        </div>
        <div class="modal-body">
          <div class="form-group mb-3">
            <label for="edit-city-name" class="form-label">City Name</label>
            <input class="form-control" type="text" name="city_name" id="edit-city-name">
          </div>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-secondary waves-effect" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary waves-effect waves-light">Update City</button>
        </div>
      </form>
    </div>
  </div>
</div>

@endsection

@section('script')
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
  <script>
    document.addEventListener('DOMContentLoaded', function () {
      document.querySelectorAll('.btn-danger').forEach(function (button) {
        button.addEventListener('click', function () {
          const cityId = this.getAttribute('data-id');
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
              document.getElementById('delete-form-' + cityId).submit();
            }
          });
        });
      });

      // Edit City
    document.querySelectorAll('.btn-edit').forEach(function (button) {
      button.addEventListener('click', function () {
        const cityId = this.getAttribute('data-id');
        const cityName = this.getAttribute('data-name');

        // isi form edit
        document.getElementById('edit-city-name').value = cityName;

        // ubah action form sesuai id
        const form = document.getElementById('editForm');
        form.action = "/admin/cities/" + cityId; // pastikan route sesuai
      });
    });
    });
  </script>


@endsection