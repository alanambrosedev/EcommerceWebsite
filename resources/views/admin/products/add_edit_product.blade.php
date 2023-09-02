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
              <form name="productForm" id="productForm" @if(empty($product['id'])) action="{{ url('admin/add-edit-product') }}" @else action="{{ url('admin/add-edit-product/'.$product['id']) }}" @endif method="post" enctype="multipart/form-data">@csrf
                <div class="card-body">
                  <div class="form-group">
                    <label for="category_id">Select Category*</label>
                    <select name="category_id" class="form-control">
                        <option value="">Select</option>
                        @foreach ($getCategories as $cat)
                            <option @if(!empty(old('category_id')) && $cat['id']==old('category_id')) selected="" @endif value="{{ $cat['id'] }}">{{ $cat['category_name'] }}</option>
                            @if(!empty($cat['subcategories']))
                                @foreach ($cat['subcategories'] as $subcat)
                                    <option @if(!empty(old('category_id')) && $subcat['id']==old('category_id')) selected="" @endif value="{{ $subcat['id'] }}">&nbsp;&nbsp;&raquo;&raquo;{{ $subcat['category_name'] }}</option>
                                    @if(!empty($subcat['subcategories']))
                                        @foreach ($subcat['subcategories'] as $subsubcat)
                                            <option @if(!empty(old('category_id')) && $subsubcat['id']==old('category_id')) selected="" @endif  value="{{ $subsubcat['id'] }}" @if(isset($product['category_id']) && $product['category_id'] == $subsubcat['id']) selected @endif>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&raquo;&raquo;{{ $subsubcat['category_name'] }}</option>
                                        @endforeach
                                    @endif
                                @endforeach
                            @endif
                        @endforeach
                    </select>
                    
                  </div>
                  <div class="form-group">
                    <label for="product_name">Product Name*</label>
                    <input type="text" class="form-control" id="product_name" name="product_name" placeholder="Enter Product Name" @if(!empty(old('product_name'))) value="{{ old('product_name') }}" @endif >
                  </div>
                  <div class="form-group">
                    <label for="product_code">Product Code*</label>
                    <input type="text" class="form-control" id="product_code" name="product_code" placeholder="Enter Product Code" @if(!empty(old('product_code'))) value="{{ old('product_code') }}" @endif >
                  </div>
                  <div class="form-group">
                    <label for="product_color">Product Color*</label>
                    <input type="text" class="form-control" id="product_color" name="product_color" placeholder="Enter Product Color" @if(!empty(old('product_color'))) value="{{ old('product_color') }}" @endif >
                  </div>
                  <div class="form-group">
                    <label for="family_color">Family Color*</label>
                    <select name="family_color" class="form-control">
                        <option value="">Select</option>
                        <option value="Red" {{ !empty(old('family_color')) && old('family_color') == 'Red' ? 'selected' : '' }}>Red</option>
                        <option value="Green" {{ !empty(old('family_color')) && old('family_color') == 'Green' ? 'selected' : '' }}>Green</option>
                        <option value="Yellow" {{ !empty(old('family_color')) && old('family_color') == 'Yellow' ? 'selected' : '' }}>Yellow</option>
                        <option value="Black" {{ !empty(old('family_color')) && old('family_color') == 'Black' ? 'selected' : '' }}>Black</option>
                        <option value="White" {{ !empty(old('family_color')) && old('family_color') == 'White' ? 'selected' : '' }}>White</option>
                        <option value="Blue" {{ !empty(old('family_color')) && old('family_color') == 'Blue' ? 'selected' : '' }}>Blue</option>
                        <option value="Grey" {{ !empty(old('family_color')) && old('family_color') == 'Grey' ? 'selected' : '' }}>Grey</option>
                        <option value="Silver" {{ !empty(old('family_color')) && old('family_color') == 'Silver' ? 'selected' : '' }}>Silver</option>
                        <option value="Golden" {{ !empty(old('family_color')) && old('family_color') == 'Golden' ? 'selected' : '' }}>Golden</option>
                        <option value="Orange" {{ !empty(old('family_color')) && old('family_color') == 'Orange' ? 'selected' : '' }}>Orange</option>
                    </select>
                    
                  </div>
                  <div class="form-group">
                    <label for="group_code">Group Code</label>
                    <input type="text" class="form-control" id="group_code" name="group_code" placeholder="Enter Group Code" @if(!empty(old('group_code'))) value="{{ old('group_code') }}" @endif >
                  </div>
                  <div class="form-group">
                    <label for="group_code">Product Price*</label>
                    <input type="text" class="form-control" id="product_price" name="product_price" placeholder="Enter Product Price" @if(!empty(old('product_price'))) value="{{ old('product_price') }}" @endif>
                  </div>
                  <div class="form-group">
                    <label for="product_discount">Product Discount (%)</label>
                    <input type="text" class="form-control" id="product_discount" name="product_discount" placeholder="Enter Product Discount" @if(!empty(old('product_discount'))) value="{{ old('product_discount') }}" @endif>
                  </div>
                  <div class="form-group">
                    <label for="product_weight">Product Weight</label>
                    <input type="text" class="form-control" id="product_video" name="product_weight" placeholder="Enter Product Weight" @if(!empty(old('product_weight'))) value="{{ old('product_weight') }}" @endif>
                  </div>
                  <div class="form-group">
                    <label for="product_video">Product Video</label>
                    <input type="file" class="form-control" id="product_video" name="product_video">
                  </div>
                  <div class="form-group">
                    <label for="fabric">Fabric</label>
                    <select name="fabric" class="form-control">
                        <option value="">Select</option>
                        @foreach ($productsFilters['fabricArray'] as $fabric)
                        <option value="{{ $fabric }}" {{ old('fabric', $product['fabric'] ?? '') == $fabric ? 'selected' : '' }}>
                            {{ $fabric }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="sleeve">Sleeve</label>
                    <select name="sleeve" class="form-control">
                        <option value="">Select</option>
                        @foreach ($productsFilters['sleeveArray'] as $sleeve)
                        <option value="{{ $sleeve }}" {{ old('sleeve', $product['sleeve'] ?? '') == $sleeve ? 'selected' : '' }}>
                            {{ $sleeve }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="pattern">Pattern</label>
                    <select name="pattern" class="form-control">
                        <option value="">Select</option>
                        @foreach ($productsFilters['patternArray'] as $pattern)
                        <option value="{{ $pattern }}" {{ old('pattern', $product['pattern'] ?? '') == $pattern ? 'selected' : '' }}>
                            {{ $pattern }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="fit">Fit</label>
                    <select name="fit" class="form-control">
                        <option value="">Select</option>
                        @foreach ($productsFilters['fitArray'] as $fit)
                        <option value="{{ $fit }}" {{ old('fit', $product['fit'] ?? '') == $fit ? 'selected' : '' }}>
                            {{ $fit }}
                        </option>
                        @endforeach
                    </select>
                </div>
                
                <div class="form-group">
                    <label for="occasion">Occasion</label>
                    <select name="occasion" class="form-control">
                        <option value="">Select</option>
                        @foreach ($productsFilters['occasionArray'] as $occasion)
                        <option value="{{ $occasion }}" {{ old('occasion', $product['occasion'] ?? '') == $occasion ? 'selected' : '' }}>
                            {{ $occasion }}
                        </option>
                        @endforeach
                    </select>
                </div>
                <div class="form-group">
                    <label for="description">Description</label>
                    <textarea class="form-control" rows="3" id="description" name="description" placeholder="Enter Product Description">{{ old('description', $product['description'] ?? '') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="wash_care">Wash Care</label>
                    <textarea class="form-control" rows="3" id="wash_care" name="wash_care" placeholder="Enter Product Wash Care">{{ old('wash_care', $product['wash_care'] ?? '') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="search_keywords">Search Keywords</label>
                    <textarea class="form-control" rows="3" id="search_keywords" name="search_keywords" placeholder="Enter Search Keywords">{{ old('search_keywords', $product['search_keywords'] ?? '') }}</textarea>
                </div>
                
                <div class="form-group">
                    <label for="meta_title">Meta Title</label>
                    <input type="text" class="form-control" id="meta_title" name="meta_title" placeholder="Enter Meta Title" value="{{ old('meta_title', $product['meta_title'] ?? '') }}">
                </div>
                
                <div class="form-group">
                    <label for="meta_description">Meta Description</label>
                    <input type="text" class="form-control" id="meta_description" name="meta_description" placeholder="Enter Meta Description" value="{{ old('meta_description', $product['meta_description'] ?? '') }}">
                </div>
                
                <div class="form-group">
                    <label for="meta_keywords">Meta Keywords</label>
                    <input type="text" class="form-control" id="meta_keywords" name="meta_keywords" placeholder="Enter Meta Keywords" value="{{ old('meta_keywords', $product['meta_keywords'] ?? '') }}">
                </div>
                
                <div class="form-group">
                    <label for="is_featured">Featured Item</label>
                    <input type="checkbox" name="is_featured" value="Yes" {{ old('is_featured', $product['is_featured'] ?? '') == 'Yes' ? 'checked' : '' }}>
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