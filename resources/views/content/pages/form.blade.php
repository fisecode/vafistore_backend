@php
    $configData = Helper::appClasses();
    $isEdit = isset($page);
@endphp

@extends('layouts/layoutMaster')

@section('title', $isEdit ? 'Edit Page' : 'Add Page')

@section('vendor-style')
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/toastr/toastr.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/animate-css/animate.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/typography.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/katex.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/vendor/libs/quill/editor.css') }}" />
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
    <script src="{{ asset('assets/vendor/libs/quill/katex.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/quill/quill.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/toastr/toastr.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/bundle/popular.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-bootstrap5/index.min.js') }}"></script>
    <script src="{{ asset('assets/vendor/libs/@form-validation/umd/plugin-auto-focus/index.min.js') }}"></script>
@endsection

@section('page-script')
    <script src="{{ asset('assets/js/pages/content-page-form.js') }}"></script>
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
        <span class="text-muted fw-light">Page /</span><span>{{ $isEdit ? ' Edit' : ' Add' }}</span>
    </h4>

    <div class="add-page">
        <form id="add-page" role="form" action="{{ route('page-list.store') }}" method="post"
            enctype="multipart/form-data">
            @csrf
            <!-- Add Page -->
            @if ($isEdit)
                <input type="hidden" name="id" value="{{ $page->id }}">
            @endif
            <div
                class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">

                <div class="d-flex flex-column justify-content-center">
                    <h4 class="mb-1 mt-3">{{ $isEdit ? ' Edit Page' : ' Add a new Page' }}</h4>
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
                                    name="title" aria-label="Page title" value="{{ $isEdit ? $page->page_name : '' }}">
                                <label for="page-title">Title</label>
                            </div>

                            <!-- Full Editor -->
                            <div>
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
                                        value="{{ $isEdit ? $page->content : '' }}">
                                    <div class="content-editor border-0 pb-1 ql-container ql-snow" id="article-content">
                                        @if ($isEdit)
                                            {!! $page->content !!}
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
                            <h5 class="card-title mb-0">Add Image Page</h5>
                        </div>
                        <div class="card-body">
                            <div class="text-center mb-3">
                                @if ($isEdit)
                                    <img src="{{ asset('storage/assets/img/pages/' . $page->image) }}" alt="page-img"
                                        class="w-100 h-auto rounded" id="uploadedImage" />
                                @else
                                    <img src="" alt="page-img" class="w-100 h-auto hide-item rounded"
                                        id="uploadedImage" />
                                @endif
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
                </div>
            </div>
        </form>
    </div>


@endsection
