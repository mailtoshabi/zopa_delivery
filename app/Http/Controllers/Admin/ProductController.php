<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Category;
use App\Models\Component;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\File;

class ProductController extends Controller
{
    public function index() {
        $status = request('status');
        $count_pending = Product::where('is_approved',Utility::ITEM_INACTIVE)->count();
        $is_approved = isset($status)? decrypt(request('status')) : ($count_pending==0 ? 1: 0);
        $count_new = $count_pending<99? $count_pending:'99+';
        $products = Product::orderBy('id','desc')->where('is_approved',$is_approved)->paginate(Utility::PAGINATE_COUNT);
        return view('admin.products.index',compact('products','is_approved','count_new'));
    }

    public function create() {
        $categories = Category::where('status',Utility::ITEM_ACTIVE)->orderBy('id','desc')->get();

        $ingredients = Component::where('status',Utility::ITEM_ACTIVE)->orderBy('id','asc')->get();
        return view('admin.products.add',compact('categories','ingredients'));
    }

    public function store () {
        $validated = request()->validate([
            'name' => 'required|unique:products,name',
            'code' => 'required',
            'category_id' => 'required',
        ]);
        $input = request()->only(['name','code','category_id','description','handle_type','width','height','gusset']);
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = Utility::cleanString(request()->name) . date('YmdHis') . '.' . $extension;
            request('image')->storeAs('products', $fileName);
            $input['image'] =$fileName;
        }
        $profit = empty(request('profit')) ? 0 : request('profit');
        $branch_id = (Auth::id()==Utility::SUPER_ADMIN_ID)? default_branch()->id : Auth::user()->branch_id;
        $input['profit'] =$profit;
        $input['is_approved'] =1;
        $input['user_id'] =Auth::id();
        $input['branch_id'] = $branch_id;
        $product = Product::create($input);

        if(!empty(request('component_names'))) {
            foreach(request('component_names') as $index => $component_id) {
                if(!empty($component_id)) {
                    $product->ingredients()->attach($component_id, ['cost' => request('costs')[$index], 'o_cost' => request('o_costs')[$index]]);
                }
            }
        }


        return redirect()->route('admin.products.index')->with(['success'=>'New Product Added Successfully']);
    }

    public function edit($id) {
        $product = Product::findOrFail(decrypt($id));
        $categories = Category::where('status',Utility::ITEM_ACTIVE)->orderBy('id','desc')->get();


        $ingredients = Component::where('status',Utility::ITEM_ACTIVE)->orderBy('id','asc')->get();
        return view('admin.products.add',compact('categories','ingredients','product'));
    }

    public function update () {
        $id = decrypt(request('product_id'));
        $product = Product::find($id);
        //return Product::DIR_PUBLIC . $product->image;
        $validated = request()->validate([
            'name' => 'required|unique:products,name,'. $id,
            'code' => 'required',
            'category_id' => 'required',
        ]);
        $input = request()->only(['name','code','category_id','description','handle_type','width','height','gusset']);
        if(request('isImageDelete')==1) {
            Storage::delete(Product::DIR_PUBLIC . $product->image);
            $input['image'] =null;
        }
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = Utility::cleanString(request()->name) . date('YmdHis') . '.' . $extension;
            request('image')->storeAs('products', $fileName);
            $input['image'] =$fileName;
        }
        $profit = empty(request('profit')) ? 0 : request('profit');
        $input['profit'] =$profit;
        $input['is_approved'] =1;
        // $input['user_id'] =Auth::id();
        $product->update($input);
        $product->ingredients()->detach();
        if(!empty(request('component_names'))) {
            foreach(request('component_names') as $index => $component_id) {
                if(!empty($component_id)) {
                    $product->ingredients()->attach($component_id, ['cost' => request('costs')[$index], 'o_cost' => request('o_costs')[$index]]);
                }
            }
        }

        return redirect()->route('admin.products.index')->with(['success'=>'Product Updated Successfully']);
    }

    public function destroy($id) {
        $product = Product::find(decrypt($id));
        if(!$product->is_approved) {
        if(!empty($product->image)) {
            Storage::delete(Product::DIR_PUBLIC . $product->image);
            $input['image'] =null;
        }
        $product->delete();
        return redirect()->route('admin.products.index')->with(['success'=>'Product Deleted Successfully']);
    }else{
        abort(404);
    }
    }

    public function changeStatus($id) {
        $product = Product::find(decrypt($id));
        if($product->is_approved) {
        $currentStatus = $product->status;
        $status = $currentStatus ? 0 : 1;
        $product->update(['status'=>$status]);
        return redirect()->route('admin.products.index')->with(['success'=>'Status changed Successfully']);
        }else{
            abort(404);
        }
    }

    public function getCost(Request $request) {
        $component_id = $request->component_id;
        $position = $request->position;
        $component = Component::find($component_id);
        return $component->cost;
    }
}
