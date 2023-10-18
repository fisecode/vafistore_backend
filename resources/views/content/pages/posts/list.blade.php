@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Post List')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/toastr/toastr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages/post-list.js')}}"></script>
<script>
  const showMessage = (type, message) => {
    if (message) {
      toastr[type](message, '', {
        timeOut: 5000,
        positionClass: "toast-top-center",
        showDuration: "300",
        hideDuration: "1000"
      });
    }
  }

  showMessage('success', @json(session('success')));
  showMessage('error', @json(session('error')));

  $(document).ready(function () {
    $('.dropdown-item[data-toggle="modal"]').click(function () {
        var post_id = $(this).data('post-id');
        var deleteForm = $('#deletePostForm');
        var actionUrl = deleteForm.attr('action');
        actionUrl = actionUrl.replace('__ID__', post_id);
        deleteForm.attr('action', actionUrl);
        $('#post_id').val(post_id);
    });
});

</script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Post /</span><span> List</span>
</h4>

<!-- Product List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title">Filter</h5>
    <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
      <div class="col-md-4 product_status"></div>
      <div class="col-md-4 product_category"></div>
      <div class="col-md-4 product_stock"></div>
    </div>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-products table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th></th>
          <th>content</th>
          <th>category</th>
          <th>author</th>
          <th>status</th>
          <th></th>
        </tr>
      </thead>
    </table>
  </div>
</div>


<div class="modal fade" id="deleteModal" tabindex="-1" role="dialog" aria-labelledby="deleteModalLabel"
  aria-hidden="true">
  <div class="modal-dialog" role="document">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="deleteModalLabel">Konfirmasi Hapus Post</h5>
        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
          <span aria-hidden="true">&times;</span>
        </button>
      </div>
      <div class="modal-body">
        Apakah Anda yakin ingin menghapus post ini?
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
        <form action="{{ route('post.destroy', ['id' => '__ID__']) }}" method="POST" id="deletePostForm">
          @csrf
          @method('DELETE')
          <input type="hidden" name="post_id" id="post_id">
          <button type="submit" class="btn btn-danger">Hapus</button>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection