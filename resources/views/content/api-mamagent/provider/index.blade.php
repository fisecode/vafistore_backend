@php
    $configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Provider List')

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
    <script src="{{ asset('assets/js/pages/provider-page.js') }}"></script>
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
        <span class="text-muted fw-light">Provider /</span><span> List</span>
    </h4>

    <!-- Product List Table -->
    <div class="card">
        <div class="card-header">
            <h5 class="card-title mb-0">Search Filter</h5>
        </div>
        <div class="card-datatable table-responsive">
            <table class="datatables-provider table">
                <thead class="table-light">
                    <tr>
                        <th></th>
                        <th>No</th>
                        <th>Provider</th>
                        <th>API</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
            </table>
        </div>
        <!-- Offcanvas to add new provider -->
        <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasAddProvider"
            aria-labelledby="offcanvasAddProviderLabel">
            <div class="offcanvas-header">
                <h5 id="offcanvasAddProvidersLabel" class="offcanvas-title">Add Provider</h5>
                <button type="button" class="btn-close text-reset" data-bs-dismiss="offcanvas" aria-label="Close"></button>
            </div>
            <div class="offcanvas-body mx-0 flex-grow-0">
                <form class="add-new-provider pt-0" id="addNewProviderForm" enctype="multipart/form-data">
                    <input type="hidden" name="id" id="provider_id">
                    <div class="form-floating form-floating-outline mb-4">
                        <select id="provider-org" class="select2 form-select" data-placeholder="Select Provider"
                            name="provider">
                            <option value="">Select Provider</option>
                            {{-- @foreach ($categories as $category)
                                <option value="{{ $category->id }}"
                                    {{ $isEdit && $post->category_id === $category->id ? 'selected' : '' }}>
                                    {{ $category->name }}
                                </option>
                            @endforeach --}}
                            <option value="4">Vip Reseller</option>
                            <option value="5">Digiflazz</option>
                            <option value="6">Medan Pedia</option>
                            <option value="9">Apigames</option>
                            <option value="10">MysticID</option>
                        </select>
                        <label for="provider">Provider</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="add-provider-api"
                            placeholder="Input API Key or Secret Key" name="apikey" aria-label="Api Key / Secret Key :" />
                        <label for="apikey">Api Key / Secret Key :</label>
                    </div>
                    <div class="form-floating form-floating-outline mb-4">
                        <input type="text" class="form-control" id="add-provider-api-id"
                            placeholder="Input UserID / API ID / Merchant ID" name="merchant_code"
                            aria-label="UserID / API ID / Merchant ID :" />
                        <label for="merchant_code">UserID / API ID / Merchant ID :</label>
                    </div>
                    <button type="submit" class="btn btn-primary me-sm-3 me-1 data-submit">Submit</button>
                    <button type="reset" class="btn btn-label-secondary" data-bs-dismiss="offcanvas">Cancel</button>
                </form>
            </div>
        </div>
    </div>
@endsection
