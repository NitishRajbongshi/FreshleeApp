@extends('agriexpert.common.layout')

@section('title', 'agriexpert Dashboard')

@section('custom_header')    
@endsection

@section('main')
<div class="row">
    <div class="col-lg-8 mb-4 order-0">

      <div class="card mb-3">
        <div class="d-flex align-items-end row">
          <div class="col-sm-7">
            <div class="card-body">
              <h5 class="card-title text-primary">Congratulations, {{ auth()->user()->name }}</h5>
              <p class="mb-4">Welcome {{ auth()->user()->name }}, to the Dashboard of 
                Crop Health Diagnosis System (BETA). Here are some key system information at a glance.</p>
              {{-- <a href="javascript:;" class="btn btn-sm btn-outline-primary">View Badges</a> --}}
            </div>
          </div>
          <div class="col-sm-5 text-center text-sm-left">
            <div class="card-body pb-0 px-0 px-md-4">
              <img src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template-free/demo/assets/img/illustrations/man-with-laptop-light.png" height="140" alt="View Badge User" data-app-dark-img="illustrations/man-with-laptop-dark.png" data-app-light-img="illustrations/man-with-laptop-light.png">
            </div>
          </div>
        </div>
      </div>

      <div class="card">
        <div class="card-header" id=""><h3 id="queryHeader">Header Text</h3></div>  
        <div class="card-body" id="queryChart">
          {{-- Java Script Chart on queries --}}
        </div>
      </div>  
    </div>
    <div class="col-lg-4 col-md-4 order-1">
      <div class="row">
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0 text-success">
                  {{-- <img src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template-free/demo/assets/img/icons/unicons/chart-success.png" alt="chart success" class="rounded"> --}}
                  <i class='bx bx-user-pin'></i>
                </div>
              </div>
              
              <span class="fw-semibold d-block mb-1">Moderators</span>
              <h3 class="card-title mb-2">18</h3>
              <small class="text-success fw-semibold"><i class='bx bx-user-plus' ></i> +4 This Month</small>
            </div>
          </div>
        </div>
        <div class="col-lg-6 col-md-12 col-6 mb-4">
          <div class="card">
            <div class="card-body">
              <div class="card-title d-flex align-items-start justify-content-between">
                <div class="avatar flex-shrink-0  text-info">
                  {{-- <img src="https://demos.themeselection.com/sneat-bootstrap-html-laravel-admin-template-free/demo/assets/img/icons/unicons/wallet-info.png" alt="Credit Card" class="rounded"> --}}
                  <i class='bx bxs-user-pin'></i>
                </div>
                
              </div>
              <span class="fw-semibold d-block mb-1">Agri-Expert</span>
              <h3 class="card-title mb-2">28</h3>
              <small class="text-info fw-semibold"><i class='bx bxs-user-plus' ></i> +10 This Month</small>
            </div>
          </div>
        </div>
      </div>


      
        <div class="row">
          <div class="card">
            <div class="card-header" id=""><h3 id="newsHeader">Agri News</h3></div>  
            <div class="card-body" id="newsChart">
              {{-- Java Script Chart on queries --}}
            </div>
          </div>  
        </div>

    </div>

    

</div>
@endsection

@section('custom_js')    
<script src="{{asset('admin_assets/js/dashboard/admin.js')}}"></script>
@endsection