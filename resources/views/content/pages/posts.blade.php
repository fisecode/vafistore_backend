@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Post')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/typography.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/katex.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/quill/editor.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/dropzone/dropzone.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/flatpickr/flatpickr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/tagify/tagify.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/quill/katex.js')}}"></script>
<script src="{{asset('assets/vendor/libs/quill/quill.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/dropzone/dropzone.js')}}"></script>
<script src="{{asset('assets/vendor/libs/jquery-repeater/jquery-repeater.js')}}"></script>
<script src="{{asset('assets/vendor/libs/flatpickr/flatpickr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/tagify/tagify.js')}}"></script>
@endsection

@section('page-script')
<script src="{{ asset('assets/js/forms-editors.js') }}"></script>
{{-- <script src="{{ asset('assets/js/form-validation.js') }}"></script> --}}
@endsection

@section('content')
<h4 class="py-3 mb-4">
  Article
</h4>

<div class="row">
  @if (session('success'))
  <div class="alert alert-success" role="alert">
    {{ session('success') }}
  </div>
  @endif
  @if (session('error'))
  <div class="alert alert-danger" role="alert">
    {{ session('error') }}
  </div>
  @endif
  <div class="col-12 col-lg-8">
    <div class="card mb-4">
      <div class="card-header">
        <h5 class="card-tile mb-0">Add New Article</h5>
      </div>
      <div class="card-body">
        <form id="add-post" class="browser-default-validation" role="form" action="{{ route('post.store') }}"
          method="post" enctype="multipart/form-data">
          @csrf
          <div class="form-floating form-floating-outline mb-4">
            <input type="file" name="image" class="form-control" id="image-post" />
            <label for="image-post">Add Image</label>
          </div>
          <div class="form-floating form-floating-outline mb-4">
            <input type="text" class="form-control" id="article-title" placeholder="Add Title" name="title"
              aria-label="Article title">
            <label for="article-title">Title</label>
          </div>

          <!-- Full Editor -->
          <div class="mb-4">
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
              </div>
            </div>
          </div>
          <!-- /Full Editor -->

          <div class="row">
            <div class="col-12 d-flex justify-content-end">
              <button type="submit" class="btn btn-primary">Submit</button>
            </div>
          </div>

          <form>
      </div>
    </div>
  </div>
</div>


@endsection