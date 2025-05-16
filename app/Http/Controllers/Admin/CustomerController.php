<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Utilities\Utility;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Helpers\FileHelper;
use App\Models\Kitchen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customer::orderBy('id', 'desc')->paginate(Utility::PAGINATE_COUNT);
        return view('admin.customers.index', compact('customers'));
    }

    public function create()
    {
        $kitchens = Kitchen::select('id', 'name')->get();
        $states = DB::table('states')->orderBy('name', 'asc')->select('id', 'name')->get();
        return view('admin.customers.create', compact('kitchens', 'states'));
    }

    public function store(CustomerStoreRequest $request)
    {
        $input = $request->except(['_token', 'image', 'isImageDelete','kitchen_id']);
        $input['password'] = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name);
        }

        $input['kitchen_id'] = decrypt($request->kitchen_id);
        $input['user_id'] = Auth::id();
        $input['is_approved'] = 1;

        Customer::create($input);

        return redirect()->route('admin.customers.index')->with(['success' => 'New Customer Added Successfully']);
    }

    public function show($id)
    {
        $customer = Customer::findOrFail(decrypt($id));
        return view('admin.customers.show', compact('customer'));
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail(decrypt($id));
        $kitchens = Kitchen::select('id', 'name')->get();
        $states = DB::table('states')->orderBy('name', 'asc')->select('id', 'name')->get();
        return view('admin.customers.create', compact('customer', 'kitchens', 'states'));
    }

    public function update(CustomerUpdateRequest $request, $id)
    {
        $id = decrypt($id);
        $customer = Customer::findOrFail($id);

        $input = $request->except(['_method', '_token', 'image', 'password', 'customer_id', 'isImageDelete','kitchen_id']);

        if ($request->password) {
            $input['password'] = Hash::make($request->password);
        }

        if ($request->isImageDelete == 1 && $customer->image_filename) {
            FileHelper::deleteFile(Customer::DIR_PUBLIC, $customer->image_filename);
            $input['image_filename'] = null;
        }

        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name);
        }
        $input['kitchen_id'] = decrypt($request->kitchen_id);

        $customer->update($input);

        return redirect()->route('admin.customers.index')->with(['success' => 'Customer Updated Successfully']);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail(decrypt($id));

        if (!empty($customer->image_filename)) {
            FileHelper::deleteFile(Customer::DIR_PUBLIC, $customer->image_filename);
        }

        $customer->delete();
        return redirect()->route('admin.customers.index')->with(['success' => 'Customer Deleted Successfully']);
    }

    public function changeStatus($id)
    {
        $customer = Customer::findOrFail(decrypt($id));
        $is_approved = $customer->is_approved ? 0 : 1;
        $customer->update(['is_approved' => $is_approved]);
        return redirect()->route('admin.customers.index')->with(['success' => 'Status changed Successfully']);
    }

    private function handleImageUpload(Request $request, string $name): string
    {
        $fileName = Utility::generateFileName($name, $request->file('image')->extension());
        $request->image->storeAs(Customer::DIR_PUBLIC, $fileName);
        return $fileName;
    }

}
