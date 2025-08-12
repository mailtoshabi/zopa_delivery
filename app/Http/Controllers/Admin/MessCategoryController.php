<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\MessCategory;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class MessCategoryController extends Controller
{
    public function index() {
        $mess_categories = MessCategory::orderBy('id','desc')->paginate(Utility::PAGINATE_COUNT);
        return view('admin.mess_categories.index',compact('mess_categories'));
    }

    public function create() {
        return view('admin.mess_categories.add');
    }

    public function store () {
        $validated = request()->validate([
            'name' => 'required|unique:mess_categories,name',
        ]);
        $input = request()->only(['name','display_order']);
        $input['slug'] = Str::slug($input['name']);
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = Utility::cleanString(request()->name) . date('YmdHis') . '.' . $extension;
            request('image')->storeAs('mess_categories', $fileName);
            $input['image'] =$fileName;
        }
        $input['user_id'] =Auth::id();
        $mess_category = MessCategory::create($input);
        // activity()->log('Created MessCategory');

//         activity()
//    ->performedOn($mess_category)
//    ->withProperties(['id' => $mess_category->id, 'name' =>$mess_category->name])
//    ->event('created')
//    ->log('New MessCategory Created');
        return redirect()->route('admin.mess_categories.index')->with(['success'=>'New MessCategory Added Successfully']);
    }

    public function edit($id) {
        $mess_category = MessCategory::findOrFail(decrypt($id));
        return view('admin.mess_categories.add',compact('mess_category'));
    }

    public function update () {
        $id = decrypt(request('mess_category_id'));
        $mess_category = MessCategory::find($id);
        //return MessCategory::DIR_PUBLIC . $mess_category->image;
        $validated = request()->validate([
            'name' => 'required|unique:mess_categories,name,'. $id,
        ]);
        $input = request()->only(['name','display_order']);
        $input['slug'] = Str::slug($input['name']);
        if(request('isImageDelete')==1) {
            Storage::delete(MessCategory::DIR_PUBLIC . $mess_category->image);
            $input['image'] =null;
        }
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = Utility::cleanString(request()->name) . date('YmdHis') . '.' . $extension;
            request('image')->storeAs('mess_categories', $fileName);
            $input['image'] =$fileName;
        }
        $input['user_id'] =Auth::id();
        $mess_category->update($input);
        return redirect()->route('admin.mess_categories.index')->with(['success'=>'MessCategory Updated Successfully']);
    }

    public function destroy($id) {
        $mess_category = MessCategory::find(decrypt($id));
        if(!empty($mess_category->image)) {
            Storage::delete(MessCategory::DIR_PUBLIC . $mess_category->image);
            $input['image'] =null;
        }
        $mess_category->delete();
        return redirect()->route('admin.mess_categories.index')->with(['success'=>'MessCategory Deleted Successfully']);
    }

    public function changeStatus($id) {
        $mess_category = MessCategory::find(decrypt($id));
        $currentStatus = $mess_category->status;
        $status = $currentStatus ? 0 : 1;
        $mess_category->update(['status'=>$status]);
        return redirect()->route('admin.mess_categories.index')->with(['success'=>'Status changed Successfully']);
    }

    public function products($id) {
        $mess_category = MessCategory::find(decrypt($id));
        // $status = request('status');
        // $count_pending = Product::where('is_approved',Utility::ITEM_INACTIVE)->count();
        // $is_approved = isset($status)? decrypt(request('status')) : ($count_pending==0 ? 1: 0);
        // $count_new = $count_pending<99? $count_pending:'99+';
        $products = Product::orderBy('id','desc')->where('mess_category_id',decrypt($id))->paginate(Utility::PAGINATE_COUNT);
        return view('admin.mess_categories.products',compact('products','mess_category'));
    }
}
