<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    public function products()
    {
        $products = Product::with('category')->get()->toArray();

        return view('admin.products.products')->with(compact('products'));
    }

    public function updateProductStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            Product::where('id', $data['product_id'])->update(['status' => $status]);

            return response()->json(['status' => $status, 'product_id' => $data['product_id']]);
        }
    }

    public function deleteProduct($id)
    {
        //Delete Product
        Product::where('id', $id)->delete();

        return redirect()->back()->with('success_message', 'Product Deleted Successfully');
    }

    public function addEditProduct(Request $request, $id = null)
    {
        if ($id == '') {
            //Add product
            $title = 'Add Product';
            $product = new Product;
            $productData = [];
            $message = 'Product Added Successfully';
        } else {
            //Edit product
            $title = 'Edit Product';
        }

        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'category_id' => 'required',
                'product_name' => 'required|regex:/^[\pL\s\-]+$/u|max:200',
                'product_code' => 'required|regex:/^[\w-]*$/|max:30',
                'product_price' => 'required|numeric',
                'product_color' => 'required|regex:/^[\pL\s\-]+$/u|max:200',
                'family_color' => 'required|regex:/^[\pL\s\-]+$/u|max:200',
            ];

            $customMessages = [
                'category_id.required' => 'Category is required',
                'product_name.required' => 'Product Name is required',
                'product_name.regex' => 'Valid Product Name is required',
                'product_code.required' => 'Product Code is required',
                'product_code.regex' => 'Valid Product Code is required',
                'product_price.required' => 'Product Price is required',
                'product_price.numeric' => 'Valid Product Price is required',
                'product_color.required' => 'Product Color is required',
                'product_color.regex' => 'Valid Product Color is required',
                'family_color.required' => 'Family Color is required',
                'family_color.regex' => 'Valid Family Color is required',
            ];
            $this->validate($request, $rules, $customMessages);

            //Upload Product video
            if ($request->hasFile('product_video')) {
                $video_tmp = $request->file('product_video');
                if ($video_tmp->isValid()) {
                    //upload Video
                    // $videoName = $video_tmp->getClientOriginalName();
                    $video_extension = $video_tmp->getClientOriginalExtension();
                    $videoName = rand().'.'.$video_extension;
                    $videoPath = 'front/videos/';
                    $video_tmp->move($videoPath, $videoName);
                    //Save video in products table
                    $product->product_video = $videoName;
                }
            }
            if (! isset($data['product_discount'])) {
                $data['product_discount'] = 0;
            }
            if (! isset($data['product_weight'])) {
                $data['product_weight'] = 0;
            }
            $product->category_id = $data['category_id'];
            $product->product_name = $data['product_name'];
            $product->product_code = $data['product_code'];
            $product->product_color = $data['product_color'];
            $product->family_color = $data['family_color'];
            $product->group_code = $data['group_code'];
            $product->product_price = $data['product_price'];
            $product->product_discount = $data['product_discount'];

            if(!empty($data['product_discount'] && $data['product_discount']>0)){
                $product->discount_type = 'product';
                $product->final_price = $data['product_price'] - ($data['product_price'] * $data['product_discount'])/100;
            }
            else{
                $getCategoryDiscount = Category::select('category_discount')->where('id',$category_id)->first();
                if($getCategoryDiscount->category_discount == 0){
                    $product->discount_type = "";
                    $product->final_price = $data['product_price'] ;
                }
            }
            $product->product_weight = $data['product_weight'];
            $product->description = $data['description'];
            $product->wash_care = $data['wash_care'];
            $product->fabric = $data['fabric'];
            $product->pattern = $data['pattern'];
            $product->sleeve = $data['sleeve'];
            $product->fit = $data['fit'];
            $product->occasion = $data['occasion'];
            $product->search_keywords = $data['search_keywords'];
            $product->meta_title = $data['meta_title'];
            $product->meta_keywords = $data['meta_keywords'];
            $product->meta_description = $data['meta_description'];
            if (! empty($data['is_featured'])) {
                $product->is_featured = $data['is_featured'];
            } else {
                $product->is_featured = 'No';
            }
            $product->status = 1;
            $product->save();

            return redirect('admin/products')->with('success_message', $message);

            $category->save();
        }
        //Get Categories and their sub categoriesx`
        $getCategories = Category::getCategories();

        //Product filters
        $productsFilters = Product::productFilters();

        return view('admin.products.add_edit_product')->with(compact('title', 'getCategories', 'productsFilters'));
    }
}
