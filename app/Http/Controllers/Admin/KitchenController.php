<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\KitchenStoreRequest;
use App\Http\Requests\KitchenUpdateRequest;
use App\Http\Utilities\Utility;
use App\Models\Kitchen;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class KitchenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $kitchens = Kitchen::orderBy('id', 'asc')->paginate(Utility::PAGINATE_COUNT);
        $states = DB::table('states')->orderBy('name', 'asc')->select('id', 'name')->get();
        return view('admin.kitchens.index', compact('kitchens', 'states'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $states = DB::table('states')->orderBy('name', 'asc')->select('id', 'name')->get();
        return view('admin.kitchens.create', compact('states'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(KitchenStoreRequest $request)
    {
        $input = $request->except(['_token', 'image', 'isImageDelete']);

        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name);
        }

        $input['user_id'] = Auth::id();

        Kitchen::create($input);

        return redirect()->route('admin.kitchens.index')->with(['success' => 'New Kitchen Added Successfully']);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $kitchen = Kitchen::findOrFail(decrypt($id));
        return view('admin.kitchens.view', compact('kitchen'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $kitchen = Kitchen::findOrFail(decrypt($id));
        $states = DB::table('states')->orderBy('name', 'asc')->select('id', 'name')->get();
        return view('admin.kitchens.create', compact('kitchen', 'states'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(KitchenUpdateRequest $request, $id)
    {
        $id = decrypt($id);
        $kitchen = Kitchen::findOrFail($id);

        $input = $request->except(['_token', '_method', 'kitchen_id', 'image', 'isImageDelete']);

        if ($request->isImageDelete == 1 && $kitchen->image_filename) {
            $kitchen->deleteImage();
            $input['image_filename'] = null;
        }


        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name);
        }

        $kitchen->update($input);

        return redirect()->route('admin.kitchens.index')->with(['success' => 'Kitchen Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $kitchen = Kitchen::findOrFail(decrypt($id));

        if (!empty($customer->image_filename)) {
            $kitchen->deleteImage();
        }

        $kitchen->delete();

        return redirect()->route('admin.kitchens.index')->with(['success' => 'Kitchen Deleted Successfully']);
    }

    /**
     * Change the status of a kitchen.
     */
    public function changeStatus($id)
    {
        $kitchen = Kitchen::findOrFail(decrypt($id));
        $status = $kitchen->status ? 0 : 1;
        $kitchen->update(['status' => $status]);
        return redirect()->route('admin.kitchens.index')->with(['success' => 'Status changed Successfully']);
    }

    /**
     * Handle image upload and return the file name.
     */
    private function handleImageUpload(Request $request, string $name): string
    {
        $extension = $request->file('image')->extension();
        $fileName = Utility::generateFileName($name, $extension);
        $request->image->storeAs(Kitchen::DIR_PUBLIC, $fileName);
        return $fileName;
    }
}
