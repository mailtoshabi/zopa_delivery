<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Utilities\Utility;
use App\Models\Branch;
use App\Models\Permission;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;

class UserController extends Controller
{
    public function index() {
        $users = User::orderBy('id','desc')->where('branch_id',default_branch()->id)->paginate(Utility::PAGINATE_COUNT);
        return view('admin.users.index',compact('users'));
    }

    public function create()
    {
        $roles = Role::ofType('user')->orderBy('id','asc')->get();
        $branches = Branch::where('status',Utility::ITEM_ACTIVE)->orderBy('id','desc')->get();
        return view('admin.users.add',compact('roles','branches'));
    }

    public function store()
    {
        $validated = request()->validate([
            'name' => 'required',
            // 'last_name' => 'required',
            'email' => 'required|email|unique:users,email',
            'phone' => 'required|string|max:10|min:10|unique:customers,phone',
            'password' => 'required|min:6',
            'role_id' => 'required',
        ]);
        $input = request()->except(['_token','email_verified_at','password','avatar','user_id']);
        if(request()->hasFile('avatar')) {
            $extension = request('avatar')->extension();
            // $fileName = 'user_pic_' . date('YmdHis') . '.' . $extension;
            $fileName = Utility::cleanString(request()->name) . date('YmdHis') . '.' . $extension;
            request('avatar')->storeAs('users', $fileName);
            $input['avatar'] =$fileName;
        }
        $input['user_id'] =Auth::id();
        $input['password'] =Hash::make(request('password'));
        $input['branch_id'] = default_branch()->id;
        $user = User::create($input);
        $role_id = decrypt(request('role_id'));
        $role = Role::where('id', $role_id)->where('user_type', 'user')->first();
        if ($role) {
            // $user->roles()->attach($role->id);
            $data = ['role_id' => $role->id, 'user_id' => $user->id, 'user_type' => 'user'];
            DB::table('role_user')->insert($data);
        } else {
            return redirect()->back()->withErrors(['role_id' => 'Invalid role for this user type']);
        }
        // $user->roles()->attach($role_id);
        return redirect()->route('admin.users.index')->with(['success'=>'New User Added Successfully']);
    }

    public function edit($id)
    {
        $roles = Role::ofType('user')->orderBy('id','asc')->get();
        $user = User::findOrFail(decrypt($id));
        if(decrypt($id)!=Utility::SUPER_ADMIN_ID) {
            $branches = Branch::where('status',Utility::ITEM_ACTIVE)->orderBy('id','desc')->get();
            return view('admin.users.add',compact('user','roles','branches'));
        }else {
            abort(404);
        }
    }

    public function update()
    {
        $id = decrypt(request('user_id'));
        if($id!=Utility::SUPER_ADMIN_ID) {
        $user = User::find($id);
        $validated = request()->validate([
            'name' => 'required',
            // 'last_name' => 'required',
            'email' => 'required|unique:users,email,'. $id,
            'phone' => 'required|string|max:10|min:10|unique:users,phone,'. $id,
            'password' => 'nullable|min:6',
            'role_id' => 'required',
        ]);

        $input = request()->except(['_token','_method','email_verified_at','password']);

        if(!empty(request('password'))) {
            $input['password']=Hash::make(request('password'));
        }

        if(request('isImageDelete')==1) {
            Storage::delete(User::DIR_PUBLIC . $user->avatar);
            $input['avatar'] =null;
        }
        if(request()->hasFile('avatar')) {
            $extension = request('avatar')->extension();
            $fileName = 'user_pic_' . date('YmdHis') . '.' . $extension;
            request('avatar')->storeAs('users', $fileName);
            $input['avatar'] =$fileName;
        }
        // $input['user_id'] =Auth::id();
        $user->update($input);
        // $role_id = decrypt(request('role_id'));
        $role_id = decrypt(request('role_id'));
        $role = Role::where('id', $role_id)->where('user_type', 'user')->first();
        if ($role) {
            // $user->roles()->sync($role_id);
            DB::table('role_user')->where('user_id', $user->id)->where('user_type', 'user')->delete();
            $data = ['role_id' => $role->id, 'user_id' => $user->id, 'user_type' => 'user'];
            DB::table('role_user')->insert($data);
        } else {
            return redirect()->back()->withErrors(['role_id' => 'Invalid role for this user type']);
        }
        return redirect()->route('admin.users.index')->with(['success'=>'User Updated Successfully']);
        }else {
            abort(404);
        }
    }

    public function destroy($id)
    {
        if(decrypt($id)!=Utility::SUPER_ADMIN_ID || decrypt($id)!=Auth::id()) {
            $user = User::find(decrypt($id));
        if(!empty($user->avatar)) {
            Storage::delete(User::DIR_PUBLIC . $user->avatar);
            $input['avatar'] =null;
        }
        $user->delete();
        return redirect()->route('admin.users.index')->with(['success'=>'User Deleted Successfully']);
        }

    }

    public function changeStatus($id) {
        $user = User::find(decrypt($id));
        $currentStatus = $user->status;
        $status = $currentStatus ? 0 : 1;
        $user->update(['status'=>$status]);
        return redirect()->route('admin.users.index')->with(['success'=>'Status changed Successfully']);
    }

    public function addRole() {
        $permissions = Permission::all();
        return view('admin.users.add-role');
    }
}
