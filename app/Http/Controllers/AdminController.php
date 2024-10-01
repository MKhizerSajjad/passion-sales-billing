<?php

namespace App\Http\Controllers;

use Hash;
use Auth;
use App\Models\Admin;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Illuminate\Http\Request;

class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $users = Admin::where('id', '!=', Auth::user()->id)->where('user_type', 1)->orderBy('first_name','DESC');

        if ($request->has('first_name') && $request->first_name != '') {
            $first_name = $request->first_name;
            $users = $users->where('first_name', 'LIKE', $first_name.'%');
        }

        if ($request->has('last_name') && $request->last_name != '') {
            $last_name = $request->last_name;
            $users = $users->where('last_name', 'LIKE', $last_name.'%');
        }

        if ($request->has('email') && $request->email != '') {
            $email = $request->email;
            $users = $users->where('email', 'LIKE', $email.'%');
        }

        if ($request->has('mobile_number') && $request->mobile_number != '') {
            $mobile_number = $request->mobile_number;
            $users = $users->where('mobile_number', 'LIKE', $mobile_number.'%');
        }

        if ($request->has('basic_salary') && $request->basic_salary != '') {
            $basic_salary = $request->basic_salary;
            $users = $users->where('basic_salary', 'LIKE', $basic_salary.'%');
        }

        if ($request->has('status') && $request->status != '') {
            $status = $request->status;
            $users = $users->where('status', $status);
        }

        if ($request->has('address') && $request->address != '') {
            $address = $request->address;
            $users = $users->where('address', 'LIKE', '%'.$address.'%');
        }

        if ($request->has('zipcode') && $request->zipcode != '') {
            $zipcode = $request->zipcode;
            $users = $users->where('zipcode', 'LIKE', '%'.$zipcode.'%');
        }

        $data = $users->paginate(100);

        return view('admin.admin.index',compact('data'))
            ->with('i', ($request->input('page', 1) - 1) * 1);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'picture' => 'file|mimes:jpeg,jpg,gif,png|max:2048',
            'first_name' => 'required|regex:/^[\pL\s]+$/u',
            'last_name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|max:255|unique:users',
            'mobile_number' => 'min:12|max:18|unique:users',
            'nic' => 'unique:users',
            'basic_salary' => 'numeric|min:0',
            'status' => 'required',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $data = $request->all();

        // Picture
        if (isset($data['picture'])) {
            $imageStorage = public_path('images/admins');
            $imageExt = array('jpeg', 'gif', 'png', 'jpg', 'webp');
            $picture = $request->picture;
            $extension = $picture->getClientOriginalExtension();

            if(in_array($extension, $imageExt)) {
                $sluggedName = Str::slug($request->first_name).'-'.Str::slug($request->last_name);
                $data['picture'] = $image = $sluggedName.'.'.$extension;
                $picture->move($imageStorage, $image); // Move File
            }
        }

        $data['password'] = Hash::make($data['password']);
        unset($data['password_confirmation']);
        $data['user_type'] = 1;
        $data['status'] = 1;
        $user = Admin::create($data);

        return redirect()->route('admins.index')->with('success','Admin created successfully');
    }

    /**
     * Display the specified resource.
     */
    public function show(Admin $admin)
    {
        $data = [
            'admin' => $admin,
        ];
        return view('admin.admin.show', compact($data));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Admin $admin)
    {
        $admin = Admin::find($admin->id);
        return view('admin.admin.edit',compact('admin'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Admin $admin)
    {
        $this->validate($request, [
            'picture' => 'file|mimes:jpeg,jpg,gif,png|max:2048',
            'first_name' => 'required|regex:/^[\pL\s]+$/u',
            'last_name' => 'required|regex:/^[\pL\s]+$/u',
            'email' => 'required|email|max:255|unique:users,email,'.$admin->id,
            'mobile_number' => 'min:12|max:18|unique:users,mobile_number,'.$admin->id,
            'nic' => 'unique:users,nic,'.$admin->id,
            'status' => 'required',
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->all();

        // Picture
        if (isset($data['picture'])) {
            $imageStorage = public_path('images/admins');
            $imageExt = array('jpeg', 'gif', 'png', 'jpg', 'webp');
            $picture = $request->picture;
            $extension = $picture->getClientOriginalExtension();

            if(in_array($extension, $imageExt)) {
                $sluggedName = Str::slug($request->first_name).'-'.Str::slug($request->last_name);
                $data['picture'] = $image = $sluggedName.'.'.$extension;
                $picture->move($imageStorage, $image); // Move File
            }
        }

        if(!empty($data['password'])){
            $data['password'] = Hash::make($data['password']);
            unset($data['password_confirmation']);
        }else{
            $data = Arr::except($data,array('password'));
            $data = Arr::except($data,array('password_confirmation'));
        }

        $user = Admin::find($admin->id);
        $user->update($data);

        return redirect()->route('admins.index')->with('success','Admin updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Admin $admin)
    {
        $admin->delete();
        return redirect()->route('admins.index')->with('success','Admin deleted successfully');
    }
}
