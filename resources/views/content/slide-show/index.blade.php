@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Slide List')

@section('vendor-style')
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.css') }}" />
<link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
<style>
  .hide-item {
    display: none;
  }

  .hide-button {
    display: none;
  }
</style>
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
<script src="{{ asset('assets/js/pages/slide-page.js') }}"></script>
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
  <span class="text-muted fw-light">Slide /</span><span> List</span>
</h4>

<!-- Product List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title mb-0">Search Filter</h5>
  </div>
  <div class="card-datatable table-responsive">
    <table class="datatables-slide table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th>Image</th>
          <th>Description</th>
          <th>Sort Order</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
  <!-- Offcanvas to add new slide -->
  <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddSlide" aria-labelledby="offcanvasAddSlideLabel">
    <div class="offcanvas-header">
      <h5 id="offcanvasAddSlideLabel" class="offcanvas-title">Add Slide</h5>
      <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
    </div>
    <div class="offcanvas-body mx-0 flex-grow-0">
      <form class="add-new-slide pt-0" id="addNewSlideForm" enctype="multipart/form-data">
        <input type="hidden" name="id" id="slide_id">
        <!-- Image Card -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">Image Slide</h5>
          </div>
          <div class="card-body">
            <div class="text-center mb-3">
              <img src="" alt="post-img" class="w-100 h-auto hide-item rounded" id="uploadedImage" />
            </div>
            <div class="button-wrapper text-center">
              <label for="upload" class="btn btn-primary" tabindex="0">
                <span class="d-none d-sm-block">Browse</span>
                <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                <input type="file" id="upload" class="account-file-input" name="image" hidden
                  accept="image/png, image/jpeg" />
              </label>
              <button type="button" class="btn-outline-danger account-image-reset hide-item">
                <i class="mdi mdi-reload d-block d-sm-none"></i>
                <span class="d-none d-sm-block">Reset</span>
              </button>

              <div class="small mt-3">Allowed JPG or PNG. Max size of 800K</div>
            </div>
          </div>
        </div>
        <!-- /Image Card -->
        <div class="form-floating form-floating-outline mb-4">
          <textarea class="form-control" id="add-slide-description" placeholder="Description" name="description"
            aria-label="Description" style="height: 100px"></textarea>
          <label for="description">Description</label>
        </div>
        <div class="form-floating form-floating-outline mb-4">
          <input type="number" class="form-control" id="add-slide-sort" placeholder="Sort Order" name="sortOrder"
            aria-label="Sort Order" />
          <label for="sortOrder">Sort Order</label>
        </div>
        <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
        <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
      </form>
    </div>
  </div>
</div>
@endsection
