<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class CategoryController extends Controller
{
    public function index() {
        $categories = Category::orderBy('id','desc')->paginate(Utility::PAGINATE_COUNT);
        return view('admin.categories.index',compact('categories'));
    }

    public function create() {
        return view('admin.categories.add');
    }

    public function store () {
        $validated = request()->validate([
            'name' => 'required|unique:categories,name',
        ]);
        $input = request()->only(['name']);
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = Utility::cleanString(request()->name) . date('YmdHis') . '.' . $extension;
            request('image')->storeAs('categories', $fileName);
            $input['image'] =$fileName;
        }
        $input['user_id'] =Auth::id();
        $category = Category::create($input);
        // activity()->log('Created Category');

//         activity()
//    ->performedOn($category)
//    ->withProperties(['id' => $category->id, 'name' =>$category->name])
//    ->event('created')
//    ->log('New Category Created');
        return redirect()->route('admin.categories.index')->with(['success'=>'New Category Added Successfully']);
    }

    public function edit($id) {
        $category = Category::findOrFail(decrypt($id));
        return view('admin.categories.add',compact('category'));
    }

    public function update () {
        $id = decrypt(request('category_id'));
        $category = Category::find($id);
        //return Category::DIR_PUBLIC . $category->image;
        $validated = request()->validate([
            'name' => 'required|unique:categories,name,'. $id,
        ]);
        $input = request()->only(['name']);
        if(request('isImageDelete')==1) {
            Storage::delete(Category::DIR_PUBLIC . $category->image);
            $input['image'] =null;
        }
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = Utility::cleanString(request()->name) . date('YmdHis') . '.' . $extension;
            request('image')->storeAs('categories', $fileName);
            $input['image'] =$fileName;
        }
        $input['user_id'] =Auth::id();
        $category->update($input);
        return redirect()->route('admin.categories.index')->with(['success'=>'Category Updated Successfully']);
    }

    public function destroy($id) {
        $category = Category::find(decrypt($id));
        if(!empty($category->image)) {
            Storage::delete(Category::DIR_PUBLIC . $category->image);
            $input['image'] =null;
        }
        $category->delete();
        return redirect()->route('admin.categories.index')->with(['success'=>'Category Deleted Successfully']);
    }

    public function changeStatus($id) {
        $category = Category::find(decrypt($id));
        $currentStatus = $category->status;
        $status = $currentStatus ? 0 : 1;
        $category->update(['status'=>$status]);
        return redirect()->route('admin.categories.index')->with(['success'=>'Status changed Successfully']);
    }

    public function products($id) {
        $category = Category::find(decrypt($id));
        // $status = request('status');
        // $count_pending = Product::where('is_approved',Utility::ITEM_INACTIVE)->count();
        // $is_approved = isset($status)? decrypt(request('status')) : ($count_pending==0 ? 1: 0);
        // $count_new = $count_pending<99? $count_pending:'99+';
        $products = Product::orderBy('id','desc')->where('category_id',decrypt($id))->paginate(Utility::PAGINATE_COUNT);
        return view('admin.categories.products',compact('products','category'));
    }
}
