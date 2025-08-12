<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Meal;
use App\Models\WalletGroup;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class WalletGroupController extends Controller
{
    public function index() {
        $wallet_groups = WalletGroup::orderBy('id','desc')->paginate(Utility::PAGINATE_COUNT);
        return view('admin.wallet_groups.index',compact('wallet_groups'));
    }

    public function create() {
        return view('admin.wallet_groups.add');
    }

    public function store () {
        $validated = request()->validate([
            'name' => 'required|unique:wallet_groups,name',
            'display_name' => 'required|unique:wallet_groups,display_name',
        ]);
        $input = request()->only(['name','display_name']);
        $input['user_id'] =Auth::id();
        $wallet_group = WalletGroup::create($input);
        return redirect()->route('admin.wallet_groups.index')->with(['success'=>'New WalletGroup Added Successfully']);
    }

    public function edit($id) {
        $wallet_group = WalletGroup::findOrFail(decrypt($id));
        return view('admin.wallet_groups.add',compact('wallet_group'));
    }

    public function update () {
        $id = decrypt(request('wallet_group_id'));
        $wallet_group = WalletGroup::find($id);
        //return WalletGroup::DIR_PUBLIC . $wallet_group->image;
        $validated = request()->validate([
            'name' => 'required|unique:wallet_groups,name,'. $id,
            'display_name' => 'required|unique:wallet_groups,display_name,'. $id,
        ]);
        $input = request()->only(['name','display_name']);
        $input['user_id'] =Auth::id();
        $wallet_group->update($input);
        return redirect()->route('admin.wallet_groups.index')->with(['success'=>'WalletGroup Updated Successfully']);
    }

    public function destroy($id) {
        $wallet_group = WalletGroup::find(decrypt($id));
        $wallet_group->delete();
        return redirect()->route('admin.wallet_groups.index')->with(['success'=>'WalletGroup Deleted Successfully']);
    }

    public function changeStatus($id) {
        $wallet_group = WalletGroup::find(decrypt($id));
        $currentStatus = $wallet_group->status;
        $status = $currentStatus ? 0 : 1;
        $wallet_group->update(['status'=>$status]);
        return redirect()->route('admin.wallet_groups.index')->with(['success'=>'Status changed Successfully']);
    }

    public function products($id) {
        $wallet_group = WalletGroup::find(decrypt($id));
        // $status = request('status');
        // $count_pending = Product::where('is_approved',Utility::ITEM_INACTIVE)->count();
        // $is_approved = isset($status)? decrypt(request('status')) : ($count_pending==0 ? 1: 0);
        // $count_new = $count_pending<99? $count_pending:'99+';
        $products = Meal::orderBy('id','desc')->where('wallet_group_id',decrypt($id))->paginate(Utility::PAGINATE_COUNT);
        return view('admin.wallet_groups.products',compact('products','wallet_group'));
    }
}
