@php
    $configData = Helper::appClasses();
    $isEdit = isset($productCategory);
@endphp

@extends('layouts/layoutMaster')

@section('title', $isEdit ? 'Edit Product' : 'Add Product')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/@form-validation/umd/styles/index.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/select2/select2.css') }}" />

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
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/select2/select2.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/pages/category-product-form.js') }}"></script>
    <script>
        const successMessage = @json(session('success'));
        const errorMessage = @json(session('error'));
        if (successMessage) {
            toastr.success(successMessage, '', {
                timeOut: 5000,
                "positionClass": "toast-top-center",
                "showDuration": "300",
                "hideDuration": "1000"
            });
        } else if (errorMessage) {
            toastr.error(errorMessage, '', {
                timeOut: 5000,
                "positionClass": "toast-top-center",
                "showDuration": "300",
                "hideDuration": "1000"
            });
        };
    </script>
@endsection

@section('content')
    <h4 class="py-3 mb-4">
        <span class="text-muted fw-light">Product / Category /</span><span>{{ $isEdit ? ' Edit' : ' Add' }}</span>
    </h4>

    <div class="add-page">
        <form id="add-page" role="form" action="{{ route('page-list.store') }}" method="post"
            enctype="multipart/form-data" class="form-repeater">
            @csrf
            <!-- Add Page -->
            @if ($isEdit)
                <input type="hidden" name="id" value="{{ $productCategory->id }}">
            @endif
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">

                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1 mt-3">{{ $isEdit ? ' Edit Product' : ' Add a new Product' }}</h4>
                </div>
                <div class="d-flex align-content-center flex-wrap gap-3">
                    <button class="btn btn-outline-secondary" id="discard">Discard</button>
                    <button type="submit" name="{{ $isEdit ? 'update' : 'publish' }}"
                        class="btn btn-primary">{{ $isEdit ? 'Update' : 'Publish' }}</button>
                </div>
            </div>
            <div class="row">
                <div class="col-12 col-lg-8">
                    <div class="card mb-4">
                        <div class="card-body">

                            <div class="form-floating form-floating-outline mb-4">
                                <input type="text" class="form-control" id="page-title" placeholder="Add Title"
                                    name="title" aria-label="Page title"
                                    value="{{ $isEdit ? $productCategory->name : '' }}">
                                <label for="page-title">Product Name</label>
                            </div>

                            <!-- Full Editor -->
                            <div class="mb-4">
                                <label class="form-label">Description :</label>
                                <div class="form-control p-0 pt-1">
                                    <div class="content-toolbar border-0 border-bottom ql-toolbar ql-snow">
                                        <div class="d-flex justify-content-start">
                                            <span class="ql-formats me-0">
                                                <select class="ql-size">
                                                    <option value="small"></option>
                                                    <!-- Note a missing, thus falsy value, is used to reset to default -->
                                                    <option selected></option>
                                                    <option value="large"></option>
                                                    <option value="huge"></option>
                                                </select>
                                                <button class="ql-bold" type="button"></button>
                                                <button class="ql-italic" type="button"></button>
                                                <button class="ql-underline" type="button"></button>
                                                <button class="ql-list" value="ordered" type="button"></button>
                                                <button class="ql-list" value="bullet" type="button"></button>
                                                <button class="ql-link" type="button"></button>
                                                <button class="ql-image" type="button"></button>
                                            </span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="content" id="quill-content"
                                        value="{{ $isEdit ? $productCategory->description : '' }}">
                                    <div class="content-editor border-0 pb-1 ql-container ql-snow" id="article-content">
                                        @if ($isEdit)
                                            {!! $productCategory->description !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- /Full Editor -->

                            <!-- Full Editor -->
                            <div>
                                <label class="form-label">Help Text or Image :</label>
                                <div class="form-control p-0 pt-1">
                                    <div class="ht-toolbar border-0 border-bottom ql-toolbar ql-snow">
                                        <div class="d-flex justify-content-start">
                                            <span class="ql-formats me-0">
                                                <select class="ql-size">
                                                    <option value="small"></option>
                                                    <!-- Note a missing, thus falsy value, is used to reset to default -->
                                                    <option selected></option>
                                                    <option value="large"></option>
                                                    <option value="huge"></option>
                                                </select>
                                                <button class="ql-bold" type="button"></button>
                                                <button class="ql-italic" type="button"></button>
                                                <button class="ql-underline" type="button"></button>
                                                <button class="ql-list" value="ordered" type="button"></button>
                                                <button class="ql-list" value="bullet" type="button"></button>
                                                <button class="ql-link" type="button"></button>
                                                <button class="ql-image" type="button"></button>
                                            </span>
                                        </div>
                                    </div>
                                    <input type="hidden" name="content" id="quill-content"
                                        value="{{ $isEdit ? $productCategory->help_text : '' }}">
                                    <div class="help-text-editor border-0 pb-1 ql-container ql-snow" id="article-content">
                                        @if ($isEdit)
                                            {!! $productCategory->help_text !!}
                                        @endif
                                    </div>
                                </div>
                            </div>
                            <!-- /Full Editor -->
                        </div>
                    </div>
                </div>

                <div class="col-12 col-lg-4">
                    <!-- Image Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Image Product</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if ($isEdit)
                                    <img src="{{ asset('storage/assets/img/product/category/' . $productCategory->image) }}"
                                        alt="page-img" class="w-25 h-auto rounded" id="uploadedImage" />
                                @else
                                    <img src="" alt="product-img" class="w-100 h-auto hide-item rounded"
                                        id="uploadedImage" />
                                @endif
                            </div>
                            <div class="button-wrapper text-center">
                                <label for="upload" class="btn btn-primary" tabindex="0">
                                    <span class="d-none d-sm-block">Browse</span>
                                    <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                    <input type="file" id="upload" class="account-file-input" name="image"
                                        hidden accept="image/png, image/jpeg" />
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
                    <!-- Sub Image Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Sub-image Product</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if ($isEdit)
                                    <img src="{{ asset('storage/assets/img/product/category/subimage/' . $productCategory->subimage) }}"
                                        alt="page-img" class="w-25 h-auto rounded" id="uploadedImage" />
                                @else
                                    <img src="" alt="product-img" class="w-100 h-auto hide-item rounded"
                                        id="uploadedImage" />
                                @endif
                            </div>
                            <div class="button-wrapper text-center">
                                <label for="upload" class="btn btn-primary" tabindex="0">
                                    <span class="d-none d-sm-block">Browse</span>
                                    <i class="mdi mdi-tray-arrow-up d-block d-sm-none"></i>
                                    <input type="file" id="upload" class="account-file-input" name="image"
                                        hidden accept="image/png, image/jpeg" />
                                </label>
                                <button type="button" class="btn-outline-danger account-image-reset hide-item">
                                    <i class="mdi mdi-reload d-block d-sm-none"></i>
                                    <span class="d-none d-sm-block">Reset</span>
                                </button>

                                <div class="small mt-3">Allowed JPG or PNG. Max size of 800K</div>
                            </div>
                        </div>
                    </div>
                    <!-- /Sub Image Card -->
                    <!-- Organize Card -->
                    <div class="card mb-4">
                        <div class="card-header">
                            <h5 class="card-title mb-0">Organize</h5>
                        </div>
                        <div class="card-body">
                            <!-- Category -->
                            <div class="mb-4 col category-select2-dropdown d-flex justify-content-between">
                                <div class="form-floating form-floating-outline w-100 me-3">
                                    <select id="category-org" class="select2 form-select"
                                        data-placeholder="Select Category" name="category">
                                        <option value="">Select Type</option>
                                        @foreach ($productTypes as $type)
                                            <option value="{{ $type->id }}"
                                                {{ $isEdit && $productCategory->type === $type->id ? 'selected' : '' }}>
                                                {{ $type->name }}
                                            </option>
                                        @endforeach
                                    </select>
                                    <label for="category">Type</label>
                                </div>
                            </div>
                            <div class="d-flex mb-3">
                                <div class="flex-grow-1 row">
                                    <div class="col-9">
                                        <span class="mb-0">Option Server?</span>
                                    </div>
                                    <div class="col-3 text-end">
                                        <label class="switch">
                                            <input type="checkbox" class="switch-input">
                                            <span class="switch-toggle-slider">
                                                <span class="switch-on"></span>
                                                <span class="switch-off"></span>
                                            </span>
                                            <span class="switch-label"></span>
                                        </label>
                                    </div>
                                </div>
                            </div>
                            <div data-repeater-list="group-a">
                                <div data-repeater-item>
                                    <div class="row">
                                        <div class="mb-3 col-9 mb-0">
                                            <div class="form-floating form-floating-outline">
                                                <input type="text" id="form-repeater-1-1" class="form-control"
                                                    placeholder="Athena" />
                                                <label for="form-repeater-1-1">Server Name</label>
                                            </div>
                                        </div>
                                        <div class="mb-3 col-1 d-flex align-items-center mb-0">
                                            <button class="btn btn-outline-danger" data-repeater-delete>
                                                <i class="mdi mdi-close me-1"></i>
                                            </button>
                                        </div>
                                    </div>
                                    <hr>
                                </div>
                            </div>
                            <div class="mb-0">
                                <button class="btn btn-primary btn-repeater" type="button" data-repeater-create>
                                    <i class="mdi mdi-plus me-1"></i>
                                    <span class="align-middle">Add</span>
                                </button>
                            </div>
                        </div>
                    </div>
                    <!-- /Organize Card -->

                </div>
            </div>

        </form>
    </div>


@endsection
