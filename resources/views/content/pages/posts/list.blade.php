@php
$configData = Helper::appClasses();
@endphp

@extends('layouts/layoutMaster')

@section('title', 'Post List')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/toastr/toastr.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/animate-css/animate.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-buttons-bs5/buttons.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-checkboxes-jquery/datatables.checkboxes.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/select2/select2.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.css')}}" />
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/jquery/jquery.js')}}"></script>
<script src="{{asset('assets/vendor/libs/toastr/toastr.js')}}"></script>
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/select2/select2.js')}}"></script>
<script src="{{asset('assets/vendor/libs/sweetalert2/sweetalert2.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/pages/post-list.js')}}"></script>
{{-- <script>
  $(document).ready(function() {
            $('#datatables-post').DataTable({
                responsive: true,
                ajax: 'list',
                columns: [{
                        data: 'title',
                        name: 'content',
                        render: function (data, type, full) {
            const $id = full['id'];
            const $content = full['meta_desc'];
            const $image = full['image'];
            let $output = '';

            if ($image) {
              $output = `<img src="../storage/assets/img/posts/${$image}" alt="Product-${$id}" class="rounded-2">`;
            } else {
              const states = ['success', 'danger', 'warning', 'info', 'dark', 'primary', 'secondary'];
              const stateNum = Math.floor(Math.random() * 6);
              const $state = states[stateNum];
              const $kategori = full['kategori'];
              const $initials = ($kategori.match(/\b\w/g) || []).map(match => match.toUpperCase()).join('');
              $output = `<span class="avatar-initial rounded-2 bg-label-${$state}">${$initials}</span>`;
            }

            const maxContentLength = 50;
            const truncatedContent = truncated($content, 30);
            const truncatedTitle = truncated(data, 30);
            const $row_output = `
              <div class="d-flex justify-content-start align-items-center product-name">
                <div class="avatar-wrapper me-3">
                  <div class="avatar rounded-2 bg-label-secondary">
                    ${$output}
                  </div>
                </div>
                <div class="d-flex flex-column">
                  <span class="text-nowrap text-heading fw-medium">
                    ${truncatedTitle}
                  </span>
                  <small class="text-truncate d-none d-sm-block">
                    ${truncatedContent}
                  </small>
                </div>
              </div>`;
            return $row_output;
          }
                    },
                    {
                        data: 'kategori',
                        name: 'kategori'
                    },
                    {
                        data: 'author',
                        name: 'author'
                    },
                    {
                        data: 'status',
                        name: 'status'
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: true,
                        searchable: false
                    }
                ]
            });
        });
</script> --}}
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

<!-- Product List Table -->
<div class="card">
  <div class="card-header">
    <h5 class="card-title">Filter</h5>
    <div class="d-flex justify-content-between align-items-center row py-3 gap-3 gap-md-0">
      <div class="col-md-4 product_status"></div>
      <div class="col-md-4 product_category"></div>
      <div class="col-md-4 product_stock"></div>
    </div>
  </div>
  <div class="card-datatable table-responsive">
    <table class="table" id="datatables-post">
      <thead class="table-light">
        <tr>
          <th>content</th>
          <th>category</th>
          <th>author</th>
          <th>status</th>
          <th>action</th>
        </tr>
      </thead>
      <tbody>

      </tbody>
    </table>
    <!-- Modal -->
    <div class="modal fade" id="commonModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel"
      aria-hidden="true">
      <div class="modal-dialog" role="document">
        <form class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title" id="exampleModalLabel"></h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close">
              <span aria-hidden="true">&times;</span>
            </button>
          </div>
          <div class="modal-body">

          </div>
        </form>
      </div>
    </div>
  </div>
</div>
@endsection