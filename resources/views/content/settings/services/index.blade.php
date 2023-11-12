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
@endsection

@section('vendor-script')
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/sweetalert2/sweetalert2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/cleavejs/cleave-phone.js') }}"></script>
@endsection

@section('page-script')
    {{-- <script src="{{ asset('assets/js/pages/post.js') }}"></script> --}}
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
        <span class="text-muted fw-light">Setting /</span><span> Services</span>
    </h4>

    <!-- Content wrapper -->
    <div class="content-wrapper">
        <!-- Content -->
        <div class="container-xxl flex-grow-1 container-p-y">
            <h4 class="fw-bold py-3 mb-4">
                <span class="text-muted fw-light">System /</span> <span class="text-muted fw-light">Settings /</span>
                Services

            </h4>
            @if (!$providers || $providers->count() == 0)
                <div class="alert alert-warning d-flex align-items-center" role="alert">
                    <span class="alert-icon text-warning me-2">
                        <i class="ti ti-bell ti-xs"></i>
                    </span>
                    <span><strong>Warning!</strong>Provider Tidak Tersedia, Silahkan Tambah Provider Terlebih Dahulu!</span>
                </div>
            @else
                <div class="row">

                    @foreach ($providers as $provider)
                        <div class="col-sm-6 mb-2">
                            <div class="card">
                                <div class="card-header">{{ $provider->provider }}</div>
                                <div class="card-datatable table-responsive">
                                    <table id="default-datatable" class="invoice-list-table table border-top">
                                        <thead class="table-light">
                                            <th class="text-center">Services</th>
                                            <th class="text-center">Action</th>
                                        </thead>
                                        <tbody>
                                            @if ($provider->provider === 'Vip Reseller')
                                                <tr>
                                                    <td class="text-left"
                                                        style="vertical-align: middle; white-space: normal;">
                                                        Produk
                                                        Game</td>
                                                    <td class="text-center"
                                                        style="vertical-align: middle; white-space: nowrap;">
                                                        <a href="{{ route('get-service', ['providerId' => $provider->id, 'jenis' => 1]) }}"
                                                            class="btn btn-primary btn-sm">Ambil</a>
                                                        @include('content.settings.services.delete-form', [
                                                            'action' => route('delete-service', [
                                                                'providerId' => $provider->id,
                                                                'jenis' => 1,
                                                            ]),
                                                        ])
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left"
                                                        style="vertical-align: middle; white-space: normal;">
                                                        Produk
                                                        Akun Premium</td>
                                                    <td class="text-center"
                                                        style="vertical-align: middle; white-space: nowrap;">
                                                        <a href="{{ route('get-service', ['providerId' => $provider->id, 'jenis' => 4]) }}"
                                                            class="btn btn-primary btn-sm">Ambil</a>
                                                        @include('content.settings.services.delete-form', [
                                                            'action' => route('delete-service', [
                                                                'providerId' => $provider->id,
                                                                'jenis' => 4,
                                                            ]),
                                                        ])
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left"
                                                        style="vertical-align: middle; white-space: normal;">
                                                        Produk
                                                        Pulsa & Emoney</td>
                                                    <td class="text-center"
                                                        style="vertical-align: middle; white-space: nowrap;">
                                                        <a href="{{ route('get-service', ['providerId' => $provider->id, 'jenis' => 2]) }}"
                                                            class="btn btn-primary btn-sm">Ambil</a>
                                                        @include('content.settings.services.delete-form', [
                                                            'action' => route('delete-service', [
                                                                'providerId' => $provider->id,
                                                                'jenis' => 2,
                                                            ]),
                                                        ])
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left"
                                                        style="vertical-align: middle; white-space: normal;">
                                                        Produk
                                                        Social Media</td>
                                                    <td class="text-center"
                                                        style="vertical-align: middle; white-space: nowrap;">
                                                        <a href="{{ route('get-service', ['providerId' => $provider->id, 'jenis' => 3]) }}"
                                                            class="btn btn-primary btn-sm">Ambil</a>
                                                        @include('content.settings.services.delete-form', [
                                                            'action' => route('delete-service', [
                                                                'providerId' => $provider->id,
                                                                'jenis' => 3,
                                                            ]),
                                                        ])
                                                    </td>
                                                </tr>
                                            @elseif ($provider->provider === 'Digiflazz')
                                                <tr>
                                                    <td class="text-left"
                                                        style="vertical-align: middle; white-space: normal;">
                                                        Produk
                                                        Game & Voucher</td>
                                                    <td class="text-center"
                                                        style="vertical-align: middle; white-space: nowrap;">
                                                        <a href="{{ route('get-service', ['providerId' => $provider->id, 'jenis' => 1]) }}"
                                                            class="btn btn-primary btn-sm">Ambil</a>
                                                        @include('content.settings.services.delete-form', [
                                                            'action' => route('delete-service', [
                                                                'providerId' => $provider->id,
                                                                'jenis' => 1,
                                                            ]),
                                                        ])
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left"
                                                        style="vertical-align: middle; white-space: normal;">
                                                        Produk
                                                        Pulsa & Emoney</td>
                                                    <td class="text-center"
                                                        style="vertical-align: middle; white-space: nowrap;">
                                                        <a href="{{ route('get-service', ['providerId' => $provider->id, 'jenis' => 2]) }}"
                                                            class="btn btn-primary btn-sm">Ambil</a>
                                                        @include('content.settings.services.delete-form', [
                                                            'action' => route('delete-service', [
                                                                'providerId' => $provider->id,
                                                                'jenis' => 2,
                                                            ]),
                                                        ])
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td class="text-left"
                                                        style="vertical-align: middle; white-space: normal;">
                                                        Produk
                                                        Pascabayar</td>
                                                    <td class="text-center"
                                                        style="vertical-align: middle; white-space: nowrap;">
                                                        <a href="{{ route('get-service', ['providerId' => $provider->id, 'jenis' => 5]) }}"
                                                            class="btn btn-primary btn-sm">Ambil</a>
                                                        @include('content.settings.services.delete-form', [
                                                            'action' => route('delete-service', [
                                                                'providerId' => $provider->id,
                                                                'jenis' => 5,
                                                            ]),
                                                        ])
                                                    </td>
                                                </tr>
                                            @elseif ($provider->provider === 'MedanPedia')
                                                <tr>
                                                    <td class="text-left"
                                                        style="vertical-align: middle; white-space: normal;">
                                                        Produk
                                                        Social Media</td>
                                                    <td class="text-center"
                                                        style="vertical-align: middle; white-space: nowrap;">
                                                        <a href="{{ route('get-service', ['providerId' => $provider->id, 'jenis' => 3]) }}"
                                                            class="btn btn-primary btn-sm">Ambil</a>
                                                        @include('content.settings.services.delete-form', [
                                                            'action' => route('delete-service', [
                                                                'providerId' => $provider->id,
                                                                'jenis' => 3,
                                                            ]),
                                                        ])
                                                    </td>
                                                </tr>
                                            @endif
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            @endif
        </div>
        <!-- / Content -->
    </div>
    <!-- Content wrapper -->
@endsection
