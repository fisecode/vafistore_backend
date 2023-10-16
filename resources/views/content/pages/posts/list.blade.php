@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Post List')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/toastr/toastr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/ui-toasts.js')}}"></script>
<script>
  const successMessage = @json(session('success'));
  if (successMessage) {
      toastr.success(successMessage, '', { timeOut: 5000, "positionClass": "toast-top-center", "showDuration": "300",
  "hideDuration": "1000"});
  };

  const errorMessage = @json(session('error'));
  if (errorMessage) {
      toastr.success(successMessage, '', { timeOut: 5000, "positionClass": "toast-top-center", "showDuration": "300",
  "hideDuration": "1000"});
  };
</script>
@endsection

@section('content')
<h4 class="py-3 mb-4">
  <span class="text-muted fw-light">Post /</span><span> List</span>
</h4>

@endsection