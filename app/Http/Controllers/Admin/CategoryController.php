<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Image;
use Session;
use App\Models\AdminsRole;
use Auth;

class CategoryController extends Controller
{
    public function categories()
    {
        Session::put('page', 'categories');
        $categories = Category::with('parentcategory')->get();

        //Set Subadmins Permission for Categories
        $categoriesModuleCount = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'categories'])->count();
        $categoriesModule = [];
        if (Auth::guard('admin')->user()->type == 'admin') {
            $categoriesModule['view_access'] = 1;
            $categoriesModule['edit_access'] = 1;
            $categoriesModule['full_access'] = 1;
        } elseif ($categoriesModuleCount == 0) {
            $message = 'This feature is restricted for you!';

            return redirect('admin/dashboard')->with('error_message', $message);
        } else {
            $categoriesModule = AdminsRole::where(['subadmin_id' => Auth::guard('admin')->user()->id, 'module' => 'categories'])->first()->toArray();

        }

        return view('admin.categories.categories')->with(compact('categories','categoriesModule'));
    }

    public function updateCategoryStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            Category::where('id', $data['category_id'])->update(['status' => $status]);

            return response()->json(['status' => $status, 'category_id' => $data['category_id']]);
        }
    }

    public function deleteCategory($id)
    {
        //Delete CMS Page
        Category::where('id', $id)->delete();

        return redirect()->back()->with('success_message', 'Category Deleted Successfully');
    }

    public function addEditCategory(Request $request, $id = null)
    {
        $getCategories = Category::getCategories();
        if ($id == '') {
            $title = 'Add Category';
            $category = new Category;
            $message = 'Category added Successfully';
        } else {
            $title = 'Edit Category';
            $category = Category::find($id);
            $message = 'Category updated Successfully';
        }
        if ($request->isMethod('post')) {
            $data = $request->all();

            if ($id = '') {
                $rules = [
                    'category_name' => 'required',
                    'parent_id' => 'required',
                    'url' => 'required|unique:categories',
                ];
            } else {
                $rules = [
                    'category_name' => 'required',
                    'parent_id' => 'required',
                    'url' => 'required',
                ];
            }

            $customMessages = [
                'category_name.required' => 'Category Name is required',
                'parent_id.required' => 'Category Level is required',
                'url.required' => 'Category Url is required',
                'url.unique' => 'Unique Category Url is required',
            ];
            $this->validate($request, $rules, $customMessages);

            //Upload Category Image
            if ($request->hasFile('category_image')) {
                $imageTmp = $request->file('category_image');
                if ($imageTmp->isValid()) {
                    //Get Image Extension
                    $extension = $imageTmp->getClientOriginalExtension();
                    $imageName = rand(111, 99999).'.'.$extension;
                    $imagePath = 'front/images/categories/'.$imageName;
                    //Upload Category Image
                    Image::make($imageTmp)->save($imagePath);
                    $category->category_image = $imageName;

                }
            } else {
                $category->category_image = '';
            }

            if (empty($data['category_discount'])) {
                $data['category_discount'] = 0;
            }

            $category->category_name = $data['category_name'];
            $category->parent_id = $data['parent_id'];
            $category->category_discount = $data['category_discount'];
            $category->description = $data['description'];
            $category->url = $data['url'];
            $category->meta_title = $data['meta_title'];
            $category->meta_description = $data['meta_description'];
            $category->meta_keywords = $data['meta_keywords'];
            $category->status = 1;
            $category->save();

            return redirect('admin/categories')->with('success_message', $message);
        }

        return view('admin.categories.add_edit_category')->with(compact('title', 'getCategories', 'category'));
    }

    public function deleteCategoryImage($id)
    {
        //Get Category Image
        $categoryImage = Category::select('category_image')->where('id', $id)->first();

        //Get Category Image Path
        $category_image_path = 'front/images/categories/';

        //Delete Category Image from Categories folder if exists
        if (file_exists($category_image_path.$categoryImage->category_image)) {
            unlink($category_image_path.$categoryImage->category_image);
        }

        // Delete Category Image from categories table
        Category::where('id', $id)->update(['category_image' => '']);

        return redirect()->back()->with('success_message', 'Category Image deleted Successfully');
    }
}
