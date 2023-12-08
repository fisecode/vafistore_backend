@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Social Media Product')

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
    <script src="{{ asset('assets/js/pages/product-social-media.js') }}"></script>
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
        <span class="text-muted fw-light">Product /</span><span> Social Media</span>
    </h4>

    <!-- Post List Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Filter</h5>
            <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
                <div class="col-md-4 product_category">
                    <select id="filterCategory" class="select2 form-select filter" name="filterCategory" data-column="5"
                        data-allow-clear="true">
                        <option value="">Category</option>
                        @foreach ($categories as $category)
                            <option value="{{ $category }}">
                                {{ $category }}
                            </option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-4 product_provider">
                    <select id="filterProvider" class="select2 form-select filter" name="filterProvider" data-column="9"
                        data-allow-clear="true">
                        <option value="">Provider</option>
                        <option value="4">
                            Vip Reseller
                        </option>
                        <option value="5">
                            Digiflazz
                        </option>
                    </select>
                </div>
                <div class="col-md-4 product_status">
                    <select id="filterStatus" class="select2 form-select filter" name="status" data-column="10"
                        data-allow-clear="true">
                        <option value="">Status</option>
                        <option value="1">
                            Active
                        </option>
                        <option value="0">
                            Deactive
                        </option>
                    </select>
                </div>
                <div class="col-md-4 product_provider"></div>
                <div class="col-md-4 product_status"></div>
            </div>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-product table">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th></th>
                        <th>Product</th>
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
                        <input type="text" class="form-control" id="edit-code" name="code" aria-label="Code Product"
                            disabled />
                        <label for="code">Code Product</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="edit-item" placeholder="Item Name" name="item"
                            aria-label="Item Name" />
                        <label for="item">Item Name</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="edit-category" name="category"
                            aria-label="Category" />
                        <label for="category">Category</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="edit-capital" name="capital"
                            aria-label="Capital Price" disabled />
                        <label for="capital">Capital Price</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="number" class="form-control" id="edit-selling" placeholder="Insert Selling Price"
                            name="selling" aria-label="Selling Price" />
                        <label for="selling">Selling Price</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="number" class="form-control" id="edit-reseller"
                            placeholder="Insert Reseller Price" name="reseller" aria-label="Reseller Price" />
                        <label for="reseller">Reseller Price</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="edit-provider" name="provider"
                            aria-label="Provider" disabled />
                        <label for="provider">Provider</label>
                    </div>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>
        <!-- Modal -->
        <div class="modal fade" id="bulkEditModal" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title" id="exampleModalLabel1">Modal title</h4>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <form class="edit-bulk pt-0" id="editBulkForm">
                            <input type="hidden" name="id" id="ids">
                            <div class="row">
                                <div class="col mb-4 mt-2">
                                    <div class="form-floating form-floating-outline">
                                        <input type="text" id="bulkCategory" class="form-control"
                                            placeholder="Top Up" name="bulkCategory">
                                        <label for="bulkCategory">Category</label>
                                    </div>
                                </div>
                                <div class="form-floating form-floating-outline">
                                    <select id="bulkStatus" class="select2 form-select" name="bulkStatus"
                                        data-allow-clear="true">
                                        <option value="">Status</option>
                                        <option value="1">
                                            Active
                                        </option>
                                        <option value="0">
                                            Deactive
                                        </option>
                                    </select>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-save-changes">Save changes</button>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
