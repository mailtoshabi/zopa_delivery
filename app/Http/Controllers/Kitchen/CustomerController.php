<?php

namespace App\Http\Controllers\Kitchen;

use App\Http\Controllers\Controller;
use App\Http\Requests\CustomerStoreRequest;
use App\Http\Requests\CustomerUpdateRequest;
use App\Http\Utilities\Utility;
use Illuminate\Support\Facades\Auth;
use App\Models\Customer;
use App\Helpers\FileHelper;
use App\Models\AddonWallet;
use App\Models\Kitchen;
use App\Models\MealWallet;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class CustomerController extends Controller
{
    public function index()
    {
        $kitchenId = auth('kitchen')->id();
        $individuals = Customer::where('customer_type', 'individual')
            ->where('kitchen_id',$kitchenId)->orderBy('id', 'desc')
            ->paginate(Utility::PAGINATE_COUNT, ['*'], 'individuals');

        $institutions = Customer::where('customer_type', 'institution')
            ->where('kitchen_id',$kitchenId)->orderBy('id', 'desc')
            ->paginate(Utility::PAGINATE_COUNT, ['*'], 'institutions');

        return view('kitchen.customers.index', compact('individuals', 'institutions'));
    }

    public function create()
    {
        $states = DB::table('states')->orderBy('name', 'asc')->select('id', 'name')->get();
        return view('kitchen.customers.create', compact('states'));
    }

    public function store(CustomerStoreRequest $request)
    {
        $kitchenId = auth('kitchen')->id();
        $input = $request->except(['_token', 'image', 'isImageDelete','kitchen_id']);
        $input['password'] = Hash::make($request->password);

        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name);
        }

        $input['kitchen_id'] = decrypt($request->kitchen_id);
        $input['user_id'] = null;
        $input['is_approved'] = 1;

        Customer::create($input);

        return redirect()->route('kitchen.customers.index')->with(['success' => 'New Customer Added Successfully']);
    }

    public function show($id)
    {
        try {
            $decryptedId = decrypt($id);
            $customer = Customer::findOrFail($decryptedId);
            return view('kitchen.customers.show', compact('customer'));
        } catch (\Exception $e) {
            return abort(400, 'Invalid customer ID: ' . $e->getMessage());
        }
    }

    public function edit($id)
    {
        $customer = Customer::findOrFail(decrypt($id));
        $states = DB::table('states')->orderBy('name', 'asc')->select('id', 'name')->get();
        return view('kitchen.customers.create', compact('customer', 'states'));
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

        $customer->update($input);

        return redirect()->route('kitchen.customers.index')->with(['success' => 'Customer Updated Successfully']);
    }

    public function destroy($id)
    {
        $customer = Customer::findOrFail(decrypt($id));

        if (!empty($customer->image_filename)) {
            FileHelper::deleteFile(Customer::DIR_PUBLIC, $customer->image_filename);
        }

        $customer->delete();
        return redirect()->route('kitchen.customers.index')->with(['success' => 'Customer Deleted Successfully']);
    }

    public function changeStatus($id)
    {
        $customer = Customer::findOrFail(decrypt($id));
        $is_approved = $customer->is_approved ? 0 : 1;
        $customer->update(['is_approved' => $is_approved]);
        return redirect()->route('kitchen.customers.index')->with(['success' => 'Status changed Successfully']);
    }

    private function handleImageUpload(Request $request, string $name): string
    {
        $fileName = Utility::generateFileName($name, $request->file('image')->extension());
        $request->image->storeAs(Customer::DIR_PUBLIC, $fileName);
        return $fileName;
    }

    public function wallets(Request $request)
    {
        $kitchenId = auth('kitchen')->id();
        // $query = MealWallet::with(['customer', 'walletGroup']);
        $query = MealWallet::with(['customer' => function ($q) use ($kitchenId) {
            $q->where('kitchen_id', $kitchenId);
        }, 'walletGroup'])
        ->whereHas('customer', function ($q) use ($kitchenId) {
            $q->where('kitchen_id', $kitchenId);
        });

        $selectedCustomerId = null;

        if ($request->filled('customer_id')) {
            try {
                $selectedCustomerId = decrypt($request->customer_id);
                $query->where('customer_id', $selectedCustomerId);
            } catch (\Exception $e) {
                // optional: flash error or ignore
            }
        }

        $wallets = $query->orderBy('quantity', 'asc')->paginate(Utility::PAGINATE_COUNT);
        $customers = Customer::where('kitchen_id',$kitchenId)->orderBy('name')->get(['id', 'name', 'phone']);

        return view('kitchen.wallets.index', compact('wallets', 'customers', 'selectedCustomerId'));
    }


    public function addon_wallets(Request $request)
    {
        // $wallets = AddonWallet::with('customer')
        //             ->orderBy('quantity', 'asc')
        //             ->paginate(Utility::PAGINATE_COUNT);

        $kitchenId = auth('kitchen')->id();
        $query = AddonWallet::with(['customer' => function ($q) use ($kitchenId) {
            $q->where('kitchen_id', $kitchenId);
        }])
        ->whereHas('customer', function ($q) use ($kitchenId) {
            $q->where('kitchen_id', $kitchenId);
        });

        $selectedCustomerId = null;

        if ($request->filled('customer_id')) {
            try {
                $selectedCustomerId = decrypt($request->customer_id);
                $query->where('customer_id', $selectedCustomerId);
            } catch (\Exception $e) {
                // optional: flash error or ignore
            }
        }

        // $wallets = AddonWallet::with('customer')
        //             ->orderBy('quantity', 'asc')
        //             ->paginate(Utility::PAGINATE_COUNT);
        $wallets = $query->orderBy('quantity', 'asc')->paginate(Utility::PAGINATE_COUNT);

        $customers = Customer::where('kitchen_id',$kitchenId)->orderBy('name')->get(['id', 'name', 'phone']);

        return view('kitchen.wallets.addons', compact('wallets', 'customers', 'selectedCustomerId'));
    }

    public function toggleWalletStatus($encryptedId)
    {
        $id = decrypt($encryptedId);
        $wallet = MealWallet::findOrFail($id);
        $wallet->status = !$wallet->status;
        $wallet->save();

        if (($wallet->wallet_group_id!=Utility::WALLET_GROUP_MEAL) && ($wallet->is_on)) {
            $customerId = $wallet->customer->id;
            // Deactivate current wallet
            $wallet->update(['is_on' => 0]);

            // Activate default wallet (wallet_group_id = 1)
            MealWallet::where('customer_id', $customerId)
                ->where('wallet_group_id', Utility::WALLET_GROUP_MEAL)
                ->update(['is_on' => 1]);
        }

        return redirect()->back()->with('success', 'Wallet status updated successfully.');
    }

    public function toggleAddonWalletStatus($encryptedId)
    {
        $id = decrypt($encryptedId);
        $wallet = AddonWallet::findOrFail($id);
        $wallet->status = !$wallet->status;
        $wallet->save();

        return redirect()->back()->with('success', 'Wallet status updated successfully.');
    }

    public function bulkToggleWalletStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        MealWallet::whereIn('id', $ids)->each(function ($wallet) {
            $wallet->status = !$wallet->status;
            $wallet->save();
        });

        return response()->json(['success' => true]);
    }

    public function bulkToggleAddonWalletStatus(Request $request)
    {
        $ids = $request->input('ids', []);
        AddonWallet::whereIn('id', $ids)->each(function ($wallet) {
            $wallet->status = !$wallet->status;
            $wallet->save();
        });

        return response()->json(['success' => true]);
    }

}
