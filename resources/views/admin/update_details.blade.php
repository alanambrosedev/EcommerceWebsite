@extends('admin.layout.layout')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <div class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1 class="m-0">Settings</h1>
          </div><!-- /.col -->
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">Update Admin Details</li>
            </ol>
          </div><!-- /.col -->
        </div><!-- /.row -->
      </div><!-- /.container-fluid -->
    </div>
    <!-- /.content-header -->
    <section class="content">
      <div class="container-fluid">
        <div class="row">
          <!-- left column -->
          <div class="col-md-6">
            <!-- general form elements -->
            <div class="card card-primary">
              <div class="card-header">
                <h3 class="card-title">Update Admin Details</h3>
              </div>
              @if(Session::has('error_message'))
              <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <strong>Error: </strong>{{ Session::get('error_message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              @endif
              @if(Session::has('success_message'))
              <div class="alert alert-success alert-dismissible fade show" role="alert">
                <strong>Success: </strong>{{ Session::get('success_message') }}
                <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                  <span aria-hidden="true">&times;</span>
                </button>
              </div>
              @endif
              @if ($errors->any())
              <div class="alert alert-danger">
                  <ul>
                      @foreach ($errors->all() as $error)
                          <li>{{ $error }}</li>
                      @endforeach
                  </ul>
              </div>
            @endif
              <!-- /.card-header -->
              <!-- form start -->
              <form method="post" action="{{ url('admin/update-details') }}" enctype="multipart/form-data">@csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="admin_email">Email Address</label>
                    <input  class="form-control" id="admin_email" value="{{ Auth::guard('admin')->user()->email }}" readonly="" style="background-color: #666;" >
                  </div>
                  <div class="form-group">
                    <label for="admin_name">Name</label>
                    <input type="text" class="form-control" id="admin_name" value="{{ Auth::guard('admin')->user()->name }}"name="admin_name" placeholder="Name">
                  </div>
                  <div class="form-group">
                    <label for="admin_mobile">Mobile</label>
                    <input type="text" class="form-control" id="admin_mobile" value="{{ Auth::guard('admin')->user()->mobile }}"name="admin_mobile" placeholder="Mobile">
                  </div>     
                  <div class="form-group">
                    <label for="admin_image">Photo</label>
                    <input type="file" class="form-control" id="admin_image" name="admin_image" placeholder="Image">
                    @if(!empty(Auth::guard('admin')->user()->image))
                      <a target="_blank" href="{{ url('admin/images/photos/'.Auth::guard('admin')->user()->image) }}">View Photo</a>
                      <input type="hidden" name="current_image" value="{{ Auth::guard('admin')->user()->image }}">
                    @endif
                  </div>                
                </div>
                <!-- /.card-body -->

                <div class="card-footer">
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
            </div>
          </div>
          <!--/.col (right) -->
        </div>
        <!-- /.row -->
      </div><!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->
  @endsection