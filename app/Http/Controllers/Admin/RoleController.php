<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        $roles = Role::orderBy('id','desc')->paginate(Utility::PAGINATE_COUNT);
        return view('admin.roles.index',compact('roles'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $permissions = Permission::orderBy('id','asc')->get();
        return view('admin.roles.add',compact('permissions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {

        //return request('permissions');
        $validated = request()->validate([
            'name' => 'required|unique:roles,display_name',
            'permissions' => 'required',
        ]);
        $input = request()->except(['_token','name','permissions']);
        $input['name']= Str::replace(' ', '_', request('name'));
        $input['display_name']= request('name');
        $input['user_id'] =Auth::id();
        $role = Role::create($input);

        $permissionIds = [];
        foreach(request('permissions') as $permission_id) {
            $permissionIds[] = decrypt($permission_id);
        }
        $role->permissions()->attach($permissionIds);

        return redirect()->route('admin.roles.index')->with(['success'=>'New Role Added Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $role
     * @return \Illuminate\Http\Response
     */
    public function show()
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $role
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $permissions = Permission::orderBy('id','asc')->get();
        $role = Role::findOrFail(decrypt($id));
        return view('admin.roles.add',compact('role','permissions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $role
     * @return \Illuminate\Http\Response
     */
    public function update($id)
    {
        $id = decrypt($id);
        $role = Role::find($id);

        $validated = request()->validate([
            'name' => 'required|unique:roles,display_name,'. $id,
            'permissions' => 'required',
        ]);
        $input = request()->except(['_token','_method','name','permissions']);
        $input['name']= Str::replace(' ', '_', request('name'));
        $input['display_name']= request('name');
        $input['user_id'] =Auth::id();
        $role->update($input);

        $permissionIds = [];
        foreach(request('permissions') as $permission_id) {
            $permissionIds[] = decrypt($permission_id);
        }
        $role->permissions()->sync($permissionIds);

        return redirect()->route('admin.roles.index')->with(['success'=>'User Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $role
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $role = Role::find(decrypt($id));
        $role->delete();
        return redirect()->route('admin.roles.index')->with(['success'=>'User Deleted Successfully']);
    }
}
