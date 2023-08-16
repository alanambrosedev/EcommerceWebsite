@extends('admin.layout.layout')
@section('content')
<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">
    <!-- Content Header (Page header) -->
    <section class="content-header">
      <div class="container-fluid">
        <div class="row mb-2">
          <div class="col-sm-6">
            <h1>{{ $title }}</h1>
          </div>
          <div class="col-sm-6">
            <ol class="breadcrumb float-sm-right">
              <li class="breadcrumb-item"><a href="#">Home</a></li>
              <li class="breadcrumb-item active">{{ $title }}</li>
            </ol>
          </div>
        </div>
      </div><!-- /.container-fluid -->
    </section>

    <!-- Main content -->
    <section class="content">
      <div class="container-fluid">

        <div class="card card-default">
          <div class="card-header">
            <h3 class="card-category_name">{{ $title }}</h3>

            <div class="card-tools">
              <button type="button" class="btn btn-tool" data-card-widget="collapse">
                <i class="fas fa-minus"></i>
              </button>
              <button type="button" class="btn btn-tool" data-card-widget="remove">
                <i class="fas fa-times"></i>
              </button>
            </div>
          </div>
          <!-- /.card-header -->
          <div class="card-body">
            <div class="row">
              <div class="col-12">
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
              @endif
               <!-- form start -->
              <form name="categoryForm" id="categoryForm" @if(empty($category['id'])) action="{{ url('admin/add-edit-category') }}" @else action="{{ url('admin/add-edit-category/'.$category['id']) }}" @endif method="post" enctype="multipart/form-data">@csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="category_name">Category Name*</label>
                    <input type="text" class="form-control" id="category_name" name="category_name" placeholder="Enter Category Name" value="{{ old('category_name') }}">
                  </div>
                  <div class="form-group">
                    <label for="category_name">Category Level*</label>
                    <select name="parent_id" class="form-control">
                      <option value="">Select</option>
                      <option value="">Main Category</option>
                      @foreach ($getCategories as $cat)
                      <option value="{{ $cat['id'] }}">{{ $cat['category_name'] }}</option>
                      @if(!empty($cat['subcategories']))
                          @foreach ($cat['subcategories'] as $subcat)
                              <option value="{{ $subcat['id'] }}">&nbsp;&nbsp;&raquo;&raquo;{{ $subcat['category_name'] }}</option>
                              @if(!empty($subcat['subcategories']))
                                  @foreach ($subcat['subcategories'] as $subsubcat)
                                      <option value="{{ $subsubcat['id'] }}">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;{{ $subsubcat['category_name'] }}</option>
                                  @endforeach
                              @endif
                          @endforeach
                      @endif
                    @endforeach                      
                    </select>
                  </div>
                  <div class="form-group">
                    <label for="category_image">Category Image</label>
                    <input type="file" class="form-control" id="category_image" name="category_image">
                  </div>
                  <div class="form-group">
                    <label for="category_discount">Category Discount</label>
                    <input type="text" class="form-control" id="category_discount" name="category_discount" placeholder="Enter Category Discount" value="{{ old('category_discount') }}">
                  </div>
                  <div class="form-group">
                    <label for="url">Category URL*</label>
                    <input type="text" class="form-control" id="url" name="url" placeholder="Enter Category Url" value="{{ old('url') }}">
                  </div>
                  <div class="form-group">
                    <label for="description">Category Description</label>
                    <textarea class="form-control" rows="3" id="description" name="description" placeholder="Enter Category Description" value="{{ old('description') }}"></textarea>
                  </div>
                  <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Enter Meta Title" value="{{ old('meta_title') }}">
                  </div>
                  <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <input type="text" class="form-control" id="meta_description" name="meta_description" placeholder="Enter Meta Description" value="{{ old('meta_description') }}" >
                  </div>
                  <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" placeholder="Enter Meta Keywords" value="{{ old('meta_keywords') }}">
                  </div>
                </div>
                <!-- /.card-body -->

                <div>
                  <button type="submit" class="btn btn-primary">Submit</button>
                </div>
              </form>
                <!-- /.form-group -->
              </div>
              <!-- /.col -->
            </div>
            <!-- /.row -->
          </div>
          <!-- /.card-body -->
          <div class="card-footer">
          </div>
        </div>

      </div>
      <!-- /.container-fluid -->
    </section>
    <!-- /.content -->
  </div>
@endsection