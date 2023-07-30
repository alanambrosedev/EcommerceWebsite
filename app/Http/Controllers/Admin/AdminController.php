<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Admin;
use App\Models\AdminsRole;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Image;
use Session;

class AdminController extends Controller
{
    public function dashboard()
    {
        Session::put('page', 'dashboard');

        return view('admin.dashboard');
    }

    public function login(Request $request)
    {
        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'email' => 'required|email|max:255',
                'password' => 'required|max:30',
            ];
            $customMessages = [
                'email.required' => 'Email is required',
                'email.email' => 'Valid email is required',
                'password.required' => 'Password is required',
            ];
            $this->validate($request, $rules, $customMessages);
            if (Auth::guard('admin')->attempt(['email' => $data['email'], 'password' => $data['password']])) {

                if (isset($data['remember']) && ! empty($data['remember'])) {
                    setcookie('email', $data['email'], time() + 3600);
                    setcookie('password', $data['password'], time() + 3600);
                } else {
                    setcookie('email', '', time() - 3600);
                    setcookie('password', '', time() - 3600);
                }

                return redirect('admin/dashboard');
            } else {
                return redirect()->back()->with('error_message', 'Invalid email or password!');
            }

        }

        return view('admin.login');
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        return redirect('admin/login');
    }

    public function updatePassword(Request $request)
    {
        Session::put('page', 'update-password');
        if ($request->isMethod('post')) {
            $data = $request->all();
            if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
                if ($data['new_pwd'] == $data['confirm_pwd']) {
                    Admin::where('id', Auth::guard('admin')->user()->id)->update(['password' => bcrypt($data['new_pwd'])]);

                    return redirect()->back()->with('success_message', 'Password has been updated successfully!');
                } else {
                    return redirect()->back()->with('error_message', 'New password & re-type password are incorrect!');
                }
            } else {
                return redirect()->back()->with('error_message', 'Your current password is incorrect!');
            }
        }

        return view('admin.update_password');
    }

    public function checkCurrentPassword(Request $request)
    {
        $data = $request->all();
        if (Hash::check($data['current_pwd'], Auth::guard('admin')->user()->password)) {
            return 'true';
        } else {
            return 'false';
        }
    }

    public function updateDetails(Request $request)
    {
        Session::put('page', 'update-details');
        if ($request->isMethod('post')) {
            $data = $request->all();
            $rules = [
                'admin_name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'admin_mobile' => 'required|numeric|digits:10',
                'admin_image' => 'image',
            ];
            $customMessages = [
                'admin_name.required' => 'Name is required',
                'admin_name.regex' => 'Valid name is required',
                'admin_name.max' => 'Valid name is required',
                'admin_mobile.required' => 'Mobile is required',
                'admin_mobile.numeric' => 'Vaild mobile is required',
                'admin_mobile.digits' => 'Vaild mobile is required',
                'admin_image.image' => 'Vaild image is required',
            ];
            $this->validate($request, $rules, $customMessages);
            if ($request->hasFile('admin_image')) {
                $imageTmp = $request->file('admin_image');
                if ($imageTmp->isValid()) {
                    $extension = $imageTmp->getClientOriginalExtension();
                    $imageName = rand(111, 99999).'.'.$extension;
                    $imagePath = 'admin/images/photos/'.$imageName;
                    Image::make($imageTmp)->save($imagePath);
                }
            } elseif (! empty($data['current_image'])) {
                $imageName = $data['current_image'];
            } else {
                $imageName = '';
            }
            Admin::where('email', Auth::guard('admin')->user()->email)->update(['name' => $data['admin_name'], 'mobile' => $data['admin_mobile'], 'image' => $imageName]);

            return redirect()->back()->with('success_message', 'Admin details has been updated successfully!');
        }

        return view('admin.update_details');
    }

    public function subAdmins()
    {
        Session::put('page', 'subadmins');
        $subAdmins = Admin::where('type', 'subadmin')->get();

        return view('admin.subadmins.subadmins')->with(compact('subAdmins'));
    }

    public function updateSubAdminStatus(Request $request)
    {
        if ($request->ajax()) {
            $data = $request->all();
            if ($data['status'] == 'Active') {
                $status = 0;
            } else {
                $status = 1;
            }
            Admin::where('id', $data['subadmin_id'])->update(['status' => $status]);

            return response()->json(['status' => $status, 'subadmin_id' => $data['subadmin_id']]);
        }
    }

    public function deleteSubadmin($id)
    {
        //Delete Sub Admin
        Admin::where('id', $id)->delete();

        return redirect()->back()->with('success_message', 'Sub Admin Deleted Successfully');
    }

    public function addEditSubAdmin(Request $request, $id = null)
    {
        if ($id == '') {
            $title = 'Add Subadmin';
            $subadmindata = new Admin;
            $message = 'Subadmin added successfully!';
        } else {
            $title = 'Edit Subadmin';
            $subadmindata = Admin::find($id);
            $message = 'Subadmin updated successfully!';
        }
        if ($request->isMethod('post')) {
            $data = $request->all();

            if ($id == '') {
                $subadminCount = Admin::where('email', $data['email'])->count();
                if ($subadminCount > 0) {
                    return redirect()->back()->with('error_message', 'Subadmin already exists!');
                }
            }
            //Subadmin validation
            $rules = [
                'name' => 'required|regex:/^[\pL\s\-]+$/u|max:255',
                'mobile' => 'required|numeric|digits:10',
                'image' => 'image',
            ];
            $customMessages = [
                'name.required' => 'Name is required',
                'name.regex' => 'Valid name is required',
                'name.max' => 'Valid name is required',
                'mobile.required' => 'Mobile is required',
                'mobile.numeric' => 'Vaild mobile is required',
                'mobile.digits' => 'Vaild mobile is required',
                'image.image' => 'Vaild image is required',
            ];
            $this->validate($request, $rules, $customMessages);
            //Upload subadmin image
            if ($request->hasFile('image')) {
                $imageTmp = $request->file('image');
                if ($imageTmp->isValid()) {
                    $extension = $imageTmp->getClientOriginalExtension();
                    $imageName = rand(111, 99999).'.'.$extension;
                    $imagePath = 'admin/images/photos/'.$imageName;
                    Image::make($imageTmp)->save($imagePath);
                }
            } elseif (! empty($data['current_image'])) {
                $imageName = $data['current_image'];
            } else {
                $imageName = '';
            }

            $subadmindata->image = $imageName;
            $subadmindata->name = $data['name'];
            $subadmindata->mobile = $data['mobile'];
            if ($id == '') {
                $subadmindata->email = $data['email'];
                $subadmindata->type = 'subadmin';
            }
            if ($data['password'] != '') {
                $subadmindata->password = bcrypt($data['password']);
            }
            $subadmindata->save();

            return redirect('admin/subadmins')->with('success_message', $message);

        }

        return view('admin.subadmins.add_edit_subadmin')->with(compact('title', 'subadmindata'));
    }

    public function updateRole($id, Request $request)
    {

        if ($request->isMethod('post')) {
            $data = $request->all();

            //Delete all earlier roles  for Subadmmins
            AdminsRole::where('subadmin_id', $id)->delete();

            foreach ($data as $key => $value) {
                if (isset($value['view'])) {
                    $view = $value['view'];
                } else {
                    $view = 0;
                }
                if (isset($value['edit'])) {
                    $edit = $value['edit'];
                } else {
                    $edit = 0;
                }
                if (isset($value['full'])) {
                    $full = $value['full'];
                } else {
                    $full = 0;
                }
            }

            $role = new AdminsRole;
            $role->subadmin_id = $id;
            $role->module = $key;
            $role->view_access = $view;
            $role->edit_access = $edit;
            $role->full_access = $full;
            $role->save();

            $message = 'Subadmin Role updated successfully!';

            return redirect()->back()->with('success_message', $message);

        }
        $subadminRoles = AdminsRole::where('subadmin_id', $id)->get()->toArray();
        $subadminDetails = Admin::where('id', $id)->first()->toArray();
        $title = 'Update '.$subadminDetails['name'].' Subadmin Roles/Permissions';

        return view('admin.subadmins.update_roles')->with(compact('title', 'id', 'subadminRoles'));

    }
}
