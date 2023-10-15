@extends('layouts/layoutMaster')

@section('title', 'Dashboard')

@section('vendor-style')
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-bs5/datatables.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/datatables-responsive-bs5/responsive.bootstrap5.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/apex-charts/apex-charts.css')}}" />
<link rel="stylesheet" href="{{asset('assets/vendor/libs/swiper/swiper.css')}}" />
@endsection

@section('page-style')
<!-- Page -->
<link rel="stylesheet" href="{{asset('assets/vendor/css/pages/cards-statistics.css')}}">
@endsection

@section('vendor-script')
<script src="{{asset('assets/vendor/libs/datatables-bs5/datatables-bootstrap5.js')}}"></script>
<script src="{{asset('assets/vendor/libs/apex-charts/apexcharts.js')}}"></script>
<script src="{{asset('assets/vendor/libs/swiper/swiper.js')}}"></script>
@endsection

@section('page-script')
<script src="{{asset('assets/js/dashboard.js')}}"></script>
@endsection

@section('content')
<div class="row gy-4 mb-4">
  <!-- Cards with few info -->
  <div class="col-lg-3 col-sm-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center flex-wrap gap-2">
          <div class="avatar me-3">
            <div class="avatar-initial bg-label-primary rounded">
              <i class="mdi mdi-account-outline mdi-24px">
              </i>
            </div>
          </div>
          <div class="card-info">
            <div class="d-flex align-items-center">
              <h4 class="mb-0">0</h4>
            </div>
            <small class="text-muted">Pelanggan</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center flex-wrap gap-2">
          <div class="avatar me-3">
            <div class="avatar-initial bg-label-danger rounded">
              <i class="mdi mdi-cart-outline mdi-24px">
              </i>
            </div>
          </div>
          <div class="card-info">
            <div class="d-flex align-items-center">
              <h4 class="mb-0">0</h4>
            </div>
            <small class="text-muted">Produk</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center flex-wrap gap-2">
          <div class="avatar me-3">
            <div class="avatar-initial bg-label-info rounded">
              <i class="mdi mdi-cash-multiple mdi-24px">
              </i>
            </div>
          </div>
          <div class="card-info">
            <div class="d-flex align-items-center">
              <h4 class="mb-0">Rp. 0</h4>
            </div>
            <small class="text-muted">Penjualan</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="col-lg-3 col-sm-6">
    <div class="card">
      <div class="card-body">
        <div class="d-flex align-items-center flex-wrap gap-2">
          <div class="avatar me-3">
            <div class="avatar-initial bg-label-success rounded">
              <i class="mdi mdi-currency-usd mdi-24px">
              </i>
            </div>
          </div>
          <div class="card-info">
            <div class="d-flex align-items-center">
              <h4 class="mb-0">Rp. 0</h4>
            </div>
            <small class="text-muted">Pendapatan</small>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Cards with few info -->

  <!-- Weekly Sales with bg-->
  {{-- <div class="col-lg-5">
    <div class="swiper-container swiper-container-horizontal swiper text-bg-primary" id="swiper-weekly-sales-with-bg">
      <div class="swiper-wrapper">
        <div class="swiper-slide">
          <div class="row">
            <div class="col-8">
              <h5 class="text-white mb-2">Weekly Sales</h5>
              <div class="d-flex align-items-center gap-2">
                <small>Total $23.5k Earning</small>
                <div class="d-flex text-success">
                  <small class="fw-medium">+62%</small>
                  <i class="mdi mdi-chevron-up"></i>
                </div>
              </div>
            </div>
            <div class="row">
              <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
                <h6 class="text-white mt-0 mt-md-3 mb-3 py-1">Mobiles & Computers</h6>
                <div class="row">
                  <div class="col-6">
                    <ul class="list-unstyled mb-0">
                      <li class="d-flex mb-3 align-items-center">
                        <p class="mb-0 me-2 weekly-sales-text-bg-primary">24</p>
                        <p class="mb-0">Mobiles</p>
                      </li>
                      <li class="d-flex align-items-center">
                        <p class="mb-0 me-2 weekly-sales-text-bg-primary">12</p>
                        <p class="mb-0">Tablets</p>
                      </li>
                    </ul>
                  </div>
                  <div class="col-6">
                    <ul class="list-unstyled mb-0">
                      <li class="d-flex mb-3 align-items-center">
                        <p class="mb-0 me-2 weekly-sales-text-bg-primary">50</p>
                        <p class="mb-0">Accessories</p>
                      </li>
                      <li class="d-flex align-items-center">
                        <p class="mb-0 me-2 weekly-sales-text-bg-primary">38</p>
                        <p class="mb-0">Computers</p>
                      </li>
                    </ul>
                  </div>
                </div>
              </div>
              <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-2 my-md-0 text-center">
                <img src="{{asset('assets/img/products/card-weekly-sales-phone.png')}}" alt="weekly sales" width="230"
                  class="weekly-sales-img">
              </div>
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="text-white mb-2">Weekly Sales</h5>
              <div class="d-flex align-items-center gap-2">
                <small>Total $23.5k Earning</small>
                <div class="d-flex text-success">
                  <small class="fw-medium">+62%</small>
                  <i class="mdi mdi-chevron-up"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
              <h6 class="text-white mt-0 mt-md-3 mb-3 py-1">Appliances & Electronics</h6>
              <div class="row">
                <div class="col-6">
                  <ul class="list-unstyled mb-0">
                    <li class="d-flex mb-3 align-items-center">
                      <p class="mb-0 me-2 weekly-sales-text-bg-primary">16</p>
                      <p class="mb-0">TV's</p>
                    </li>
                    <li class="d-flex align-items-center">
                      <p class="mb-0 me-2 weekly-sales-text-bg-primary">40</p>
                      <p class="mb-0">Speakers</p>
                    </li>
                  </ul>
                </div>
                <div class="col-6">
                  <ul class="list-unstyled mb-0">
                    <li class="d-flex mb-3 align-items-center">
                      <p class="mb-0 me-2 weekly-sales-text-bg-primary">9</p>
                      <p class="mb-0">Cameras</p>
                    </li>
                    <li class="d-flex align-items-center">
                      <p class="mb-0 me-2 weekly-sales-text-bg-primary">18</p>
                      <p class="mb-0">Consoles</p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-2 my-md-0 text-center">
              <img src="{{asset('assets/img/products/card-weekly-sales-controller.png')}}" alt="weekly sales"
                width="230" class="weekly-sales-img">
            </div>
          </div>
        </div>
        <div class="swiper-slide">
          <div class="row">
            <div class="col-12">
              <h5 class="text-white mb-2">Weekly Sales</h5>
              <div class="d-flex align-items-center gap-2">
                <small>Total $23.5k Earning</small>
                <div class="d-flex text-success">
                  <small class="fw-medium">+62%</small>
                  <i class="mdi mdi-chevron-up"></i>
                </div>
              </div>
            </div>
            <div class="col-lg-7 col-md-9 col-12 order-2 order-md-1">
              <h6 class="text-white mt-0 mt-md-3 mb-3 py-1">Fashion</h6>
              <div class="row">
                <div class="col-6">
                  <ul class="list-unstyled mb-0">
                    <li class="d-flex mb-3 align-items-center">
                      <p class="mb-0 me-2 weekly-sales-text-bg-primary">16</p>
                      <p class="mb-0">T-shirts</p>
                    </li>
                    <li class="d-flex align-items-center">
                      <p class="mb-0 me-2 weekly-sales-text-bg-primary">29</p>
                      <p class="mb-0">Watches</p>
                    </li>
                  </ul>
                </div>
                <div class="col-6">
                  <ul class="list-unstyled mb-0">
                    <li class="d-flex mb-3 align-items-center">
                      <p class="mb-0 me-2 weekly-sales-text-bg-primary">43</p>
                      <p class="mb-0">Shoes</p>
                    </li>
                    <li class="d-flex align-items-center">
                      <p class="mb-0 me-2 weekly-sales-text-bg-primary">7</p>
                      <p class="mb-0">Sun Glasses</p>
                    </li>
                  </ul>
                </div>
              </div>
            </div>
            <div class="col-lg-5 col-md-3 col-12 order-1 order-md-2 my-2 my-md-0 text-center">
              <img src="{{asset('assets/img/products/card-weekly-sales-watch.png')}}" alt="weekly sales" width="230"
                class="weekly-sales-img">
            </div>
          </div>
        </div>
      </div>
      <div class="swiper-pagination"></div>
    </div>
  </div> --}}
  <!--/ Weekly Sales with bg-->

  <!-- Revenue Report -->
  <div class="col-lg-7">
    <div class="card h-100">
      <div class="card-header pb-1">
        <div class="d-flex justify-content-between">
          <h5 class="mb-1">Laporan Penjualan</h5>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="revenueReportDropdown" data-bs-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              <i class="mdi mdi-dots-vertical mdi-24px"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="revenueReportDropdown">
              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
              <a class="dropdown-item" href="javascript:void(0);">Update</a>
              <a class="dropdown-item" href="javascript:void(0);">Share</a>
            </div>
          </div>
        </div>
        <p class="mb-0 text-muted">Penghasilan Dalam 1 Bulan</p>
      </div>
      <div class="card-body">
        <div class="row">
          <div class="col-12 col-md-4 d-flex flex-column align-self-end">
            <div class="d-flex gap-2 align-items-center mb-2 pb-1 flex-wrap">
              <h1 class="mb-0">Rp. </h1>
              <div class="badge rounded bg-label-success"></div>
            </div>
            <small class="text-muted"></small>
          </div>
          <div class="col-12 col-md-8">
            <div id="salesByDayChart"></div>
          </div>
        </div>
        <div class="border rounded p-3 mt-2">
          <div class="row gap-4 gap-sm-0">
            <div class="col-12 col-sm-4">
              <div class="d-flex gap-2 align-items-center">
                <div class="badge rounded bg-label-primary p-1">
                  <i class="mdi mdi-currency-usd mdi-20px"></i>
                </div>
                <h6 class="mb-0">Total<br>Penjualan</h6>
              </div>
              <h5 class="my-2 pt-1">Rp. </h5>
              <div class="progress w-75" style="height: 4px">
                <div class="progress-bar" role="progressbar" style="width: 65%" aria-valuenow="65" aria-valuemin="0"
                  aria-valuemax="100"></div>
              </div>
            </div>
            <div class="col-12 col-sm-4">
              <div class="d-flex gap-2 align-items-center">
                <div class="badge rounded bg-label-info p-1">
                  <i class="mdi mdi-chart-timeline-variant-shimmer mdi-20px"></i>
                </div>
                <h6 class="mb-0">Total<br>Profit</h6>
              </div>
              <h5 class="my-2 pt-1">Rp. </h5>
              <div class="progress w-75" style="height: 4px">
                <div class="progress-bar bg-info" role="progressbar" style="width: 50%" aria-valuenow="50"
                  aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
            <div class="col-12 col-sm-4">
              <div class="d-flex gap-2 align-items-center">
                <div class="badge rounded bg-label-danger p-1">
                  <i class="mdi mdi-wallet mdi-20px"></i>
                </div>
                <h6 class="mb-0">Saldo<br>Member</h6>
              </div>
              <h5 class="my-2 pt-1">Rp. </h5>
              <div class="progress w-75" style="height: 4px">
                <div class="progress-bar bg-danger" role="progressbar" style="width: 65%" aria-valuenow="65"
                  aria-valuemin="0" aria-valuemax="100"></div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <!--/ Revenue Report -->

  <!-- Last Transaction -->
  <div class="col-lg-5 col-12">
    <div class="card ">
      <div class="card-header pb-1">
        <div class="d-flex justify-content-between">
          <h5 class="mb-1">Pembayaran Terakhir</h5>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="lastTransactionDropdown" data-bs-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              <i class="mdi mdi-dots-vertical mdi-24px"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="lastTransactionDropdown">
              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
              <a class="dropdown-item" href="javascript:void(0);">Update</a>
              <a class="dropdown-item" href="javascript:void(0);">Share</a>
            </div>
          </div>
        </div>
        <p class="mb-0 text-muted">Total Penjualan Rp. 0</p>
      </div>
      <div class="card-body">
        <div class="table-responsive rounded-3">
          <table class="datatables-last-transaction table table-sm">
            <thead class="table-light">
              <tr>
                <th class="py-3">Tanggal</th>
                <th class="py-3">No. Transaksi</th>
                <th class="py-3">Total</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--/ Last Transaction -->

  <!-- Top Up Products -->
  <div class="col-lg-4 col-12">
    <div class="card ">
      <div class="card-header pb-1">
        <div class="d-flex justify-content-between">
          <h5 class="mb-1">Produk Top Up Terlaris</h5>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="lastTransactionDropdown" data-bs-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              <i class="mdi mdi-dots-vertical mdi-24px"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="lastTransactionDropdown">
              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
              <a class="dropdown-item" href="javascript:void(0);">Update</a>
              <a class="dropdown-item" href="javascript:void(0);">Share</a>
            </div>
          </div>
        </div>
        <p class="mb-0 text-muted">Total Penjualan Rp. 0</p>
      </div>
      <div class="card-body">
        <div class="table-responsive rounded-3">
          <table class="datatables-topup-products table table-sm">
            <thead class="table-light">
              <tr>
                <th class="py-3">Produk</th>
                <th class="py-3">Total</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--/ Last Transaction -->

  <!-- Prepaid Products -->
  <div class="col-lg-4 col-12">
    <div class="card ">
      <div class="card-header pb-1">
        <div class="d-flex justify-content-between">
          <h5 class="mb-1">Produk Prepaid Terlaris</h5>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="lastTransactionDropdown" data-bs-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              <i class="mdi mdi-dots-vertical mdi-24px"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="lastTransactionDropdown">
              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
              <a class="dropdown-item" href="javascript:void(0);">Update</a>
              <a class="dropdown-item" href="javascript:void(0);">Share</a>
            </div>
          </div>
        </div>
        <p class="mb-0 text-muted">Total Penjualan Rp. 0</p>
      </div>
      <div class="card-body">
        <div class="table-responsive rounded-3">
          <table class="datatables-topup-products table table-sm">
            <thead class="table-light">
              <tr>
                <th class="py-3">Produk</th>
                <th class="py-3">Total</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--/ Last Transaction -->

  <!-- SMM Products -->
  <div class="col-lg-4 col-12">
    <div class="card ">
      <div class="card-header pb-1">
        <div class="d-flex justify-content-between">
          <h5 class="mb-1">Produk SMM Terlaris</h5>
          <div class="dropdown">
            <button class="btn p-0" type="button" id="lastTransactionDropdown" data-bs-toggle="dropdown"
              aria-haspopup="true" aria-expanded="false">
              <i class="mdi mdi-dots-vertical mdi-24px"></i>
            </button>
            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="lastTransactionDropdown">
              <a class="dropdown-item" href="javascript:void(0);">Refresh</a>
              <a class="dropdown-item" href="javascript:void(0);">Update</a>
              <a class="dropdown-item" href="javascript:void(0);">Share</a>
            </div>
          </div>
        </div>
        <p class="mb-0 text-muted">Total Penjualan Rp. 0</p>
      </div>
      <div class="card-body">
        <div class="table-responsive rounded-3">
          <table class="datatables-topup-products table table-sm">
            <thead class="table-light">
              <tr>
                <th class="py-3">Produk</th>
                <th class="py-3">Total</th>
              </tr>
            </thead>
          </table>
        </div>
      </div>
    </div>
  </div>
  <!--/ Last Transaction -->

</div>
@endsection