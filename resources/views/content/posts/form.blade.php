@php
$configData = Helper::appClasses();
$isEdit = isset($post);
@endphp

@extends('layouts/layoutMaster')

@section('title', $isEdit ? 'Edit Post' : 'Add Post')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/toastr/toastr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/dropzone/dropzone.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/tagify/tagify.css')}}" />
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
<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/tagify/tagify.js')}}"></script>
<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/pages/post-form.js') }}"></script>
<script>
  const successMessage = @json(session('success'));
  const errorMessage = @json(session('error'));
  if (successMessage) {
      toastr.success(successMessage, '', { timeOut: 5000, "positionClass": "toast-top-center", "showDuration": "300",
  "hideDuration": "1000"});
  } else if(errorMessage){
      toastr.error(errorMessage, '', { timeOut: 5000, "positionClass": "toast-top-center", "showDuration": "300",
  "hideDuration": "1000"});
  };
</script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Post /</span><span>{{ $isEdit ? ' Edit' : ' Add' }}</span>
</h4>

<div class="post-add">
  <form id="add-post" role="form" action="{{ $isEdit ? route('post.update', $post->id) : route('post.store') }}"
    method="post" enctype="multipart/form-data">
    @csrf
    <!-- Add Post -->
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center mb-3">

      <div class="d-flex flex-column justify-content-center">
        <h4 class="mb-1 mt-3">{{ $isEdit ? ' Edit Post' : ' Add a new Post' }}</h4>
      </div>
      <div class="d-flex align-content-center flex-wrap gap-3">
        @if (!$isEdit)

        <button class="btn btn-outline-secondary">Discard</button>
        @endif
        <button type="submit" name="{{ $isEdit ? 'unpublish' : 'draft' }}" class="btn btn-outline-primary">{{ $isEdit ?
          'Unpublish' : 'Save Draft' }}</button>
        <button type="submit" name="publish" class="btn btn-primary">{{ $isEdit ? 'Update' : 'Publish' }}</button>
        <input type="hidden" name="status" id="status" value="0">
      </div>
    </div>
    <div class="row">
      <div class="col-12 col-lg-8">
        <div class="card mb-4">
          <div class="card-body">
            {{-- <div class="form-floating form-floating-outline mb-4">
              <input type="file" name="image" class="form-control" id="image-post" />
              <label for="image-post">Add Image</label>
            </div> --}}
            <div class="form-floating form-floating-outline mb-4">
              <input type="text" class="form-control" id="post-title" placeholder="Add Title" name="title"
                aria-label="Post title" value="{{ $isEdit ? $post->title : '' }}">
              <label for="post-title">Title</label>
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
                <input type="hidden" name="content" id="quill-content">
                <div class="content-editor border-0 pb-1 ql-container ql-snow" id="article-content">
                  @if ($isEdit)
                  {!! $post->content !!}
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
            <h5 class="card-title mb-0">Add Image Post</h5>
          </div>
          <div class="card-body">
            <div class="text-center mb-3">
              @if ($isEdit)
              <img src="{{ asset('storage/assets/img/posts/' . $post->image) }}" alt="user-avatar"
                class="w-100 h-auto rounded" id="uploadedAvatar" />
              @else
              <img src="" alt="user-avatar" class="w-100 h-auto hide-item rounded" id="uploadedAvatar" />
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
        <!-- Organize Card -->
        <div class="card mb-4">
          <div class="card-header">
            <h5 class="card-title mb-0">Organize</h5>
          </div>
          <div class="card-body">
            <!-- Category -->
            <div class="mb-4 col category-select2-dropdown d-flex justify-content-between">
              <div class="form-floating form-floating-outline w-100 me-3">
                {{-- <select id="category-org" class="select2 form-select" data-placeholder="Select Category"
                  name="category">
                  <option value="">Select Category</option>
                  @foreach($categories as $category)
                  <option value="{{ $category->id }}" {{ $isEdit && $post->category_id === $category->id ? 'selected' :
                    '' }}>
                    {{ $category->name }}
                  </option>
                  @endforeach
                </select> --}}

                <select id="category-org" class="select2 form-select" data-placeholder="Select Category"
                  name="category">
                  <option value="">Select Category</option>
                  <option value="Blog" {{ $isEdit && $post->kategori === 'Blog' ? 'selected' : '' }}>Blog</option>
                  <option value="Tutorial" {{ $isEdit && $post->kategori === 'Tutorial' ? 'selected' : '' }}>Tutorial
                  </option>
                  <!-- Tambahkan opsi kategori lainnya sesuai kebutuhan -->
                </select>
                <label for="category">Category</label>
              </div>
              <div>
                <button class="btn btn-outline-primary btn-icon btn-lg h-px-50">
                  <i class="mdi mdi-plus"></i>
                </button>
              </div>
            </div>
            <!-- Tags -->
            <div class="mb-3">
              <div class="form-floating form-floating-outline">
                <input id="post-tags" class="form-control h-auto" name="postTags" aria-label="Product Tags"
                  value="{{ $isEdit ? $post->tags : '' }}" />
                <label for="postTags">Tags</label>
              </div>
            </div>
          </div>
        </div>
        <!-- /Organize Card -->
      </div>
    </div>
  </form>
</div>


@endsection