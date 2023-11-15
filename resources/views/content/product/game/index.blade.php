@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Post List')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
<link rel="stylesheet"
  href="{{ asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
@endsection

@section('vendor-script')
{{-- <script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script> --}}
<script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
<script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/pages/product-game.js') }}"></script>
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
</script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Post /</span><span> List</span>
</h4>

<!-- Post List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-games table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>#</th>
          <th>Image</th>
          <th>Code</th>
          <th>Item</th>
          <th>Category</th>
          <th>Capital</th>
          <th>Selling</th>
          <th>Reseller</th>
          <th>Provider</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to edit product -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasEditProduct"
    aria-labelledby="offcanvasEditProductLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasEditProductLabel" class="offcanvas-title">Edit Product</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="edit-product pt-0" id="editProductForm">
        <input type="hidden" name="id" id="product_id">
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="edit-code" name="code" aria-label="Code Product" disabled />
          <label for="code">Code Product</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="edit-item" placeholder="Item Name" name="item"
            aria-label="Item Name" />
          <label for="item">Item Name</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="edit-category" name="category" aria-label="Category" disabled />
          <label for="category">Category</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="edit-capital" name="capital" aria-label="Capital Price"
            disabled />
          <label for="capital">Capital Price</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="number" class="form-control" id="edit-selling" placeholder="Insert Selling Price" name="selling"
            aria-label="Selling Price" />
          <label for="selling">Selling Price</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="number" class="form-control" id="edit-reseller" placeholder="Insert Reseller Price"
            name="reseller" aria-label="Reseller Price" />
          <label for="reseller">Reseller Price</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="text" class="form-control" id="edit-provider" name="provider" aria-label="Provider" disabled />
          <label for="provider">Provider</label>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
