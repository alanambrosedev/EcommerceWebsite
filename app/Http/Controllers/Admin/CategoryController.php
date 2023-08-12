<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;
use Session;

class CategoryController extends Controller
{
    public function categories()
    {
        Session::put('page', 'categories');
        $categories = Category::with('parentcategory')->get();
        return view('admin.categories.categories')->with(compact('categories'));
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
        if($id==""){
            $title = "Add Category";
        }else{
            $title = "Edit Category";
        }
        return view('admin.categories.add_edit_category')->with(compact('title'));
    }
}
