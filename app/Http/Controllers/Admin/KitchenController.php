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
use Illuminate\Support\Facades\Hash;

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
        $input = $request->except(['_token', 'image', 'isImageDelete' , 'isLicenseDelete', 'isfssai_certificateDelete','isother_documentsDelete']);

        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name);
        }

        $input['user_id'] = Auth::id();
        $input['latitude'] = $request->latitude;
        $input['longitude'] = $request->longitude;
        $input['location_name'] = $request->location_name;
        $input['password'] = Hash::make($request->password);
        $input['status'] = Utility::ITEM_ACTIVE;
        // $input['is_approved'] = Utility::ITEM_ACTIVE;

        $kitchen = Kitchen::create($input);

        // Single documents
        if ($request->hasFile('license_file')) {
            $kitchen->license_file = $this->handleDocumentUpload($request, 'license_file', Kitchen::DIR_PUBLIC_LICESNSE, $kitchen->name);
        }
        if ($request->hasFile('fssai_certificate')) {
            $kitchen->fssai_certificate = $this->handleDocumentUpload($request, 'fssai_certificate', Kitchen::DIR_PUBLIC_FSSAI, $kitchen->name);
        }
        // Multiple documents
        if ($request->hasFile('other_documents')) {
            $docs = $this->handleMultipleDocumentUpload($request, 'other_documents', Kitchen::DIR_PUBLIC_OTHDOC, $kitchen->name);
            $kitchen->other_documents = json_encode($docs);
        }
        $kitchen->save();
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

        $input = $request->except(['_token', '_method', 'kitchen_id', 'image', 'isImageDelete', 'isLicenseDelete', 'isfssai_certificateDelete','isother_documentsDelete']);

        if ($request->password) {
            $input['password'] = Hash::make($request->password);
        }

        if ($request->isImageDelete == 1 && $kitchen->image_filename) {
            $kitchen->deleteImage();
            $input['image_filename'] = null;
        }


        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name);
        }

        $input['latitude'] = $request->latitude;
        $input['longitude'] = $request->longitude;
        $input['location_name'] = $request->location_name;

        $kitchen->update($input);

        // Single documents
        if ($request->hasFile('license_file')) {
            $kitchen->license_file = $this->handleDocumentUpload($request, 'license_file', Kitchen::DIR_PUBLIC_LICESNSE, $kitchen->name);
        }
        if ($request->hasFile('fssai_certificate')) {
            $kitchen->fssai_certificate = $this->handleDocumentUpload($request, 'fssai_certificate', Kitchen::DIR_PUBLIC_FSSAI, $kitchen->name);
        }
        // Multiple documents
        if ($request->hasFile('other_documents')) {
            $docs = $this->handleMultipleDocumentUpload($request, 'other_documents', Kitchen::DIR_PUBLIC_OTHDOC, $kitchen->name);
            $kitchen->other_documents = json_encode($docs);
        }
        $kitchen->save();

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

    private function handleDocumentUpload(Request $request, string $inputName, string $storageFolder, string $baseName = null): string
    {
        $extension = $request->file($inputName)->extension();
        $fileName = Utility::generateFileName($baseName ?? $inputName, $extension);
        $request->file($inputName)->storeAs($storageFolder, $fileName);
        return $fileName;
    }

    private function handleMultipleDocumentUpload(Request $request, string $inputName, string $storageFolder, string $baseName = null): array
    {
        $fileNames = [];

        foreach ($request->file($inputName) as $index => $file) {
            $extension = $file->extension();
            $generatedName = Utility::generateFileName(($baseName ?? $inputName) . '_' . $index, $extension);
            $file->storeAs($storageFolder, $generatedName);
            $fileNames[] = $generatedName;
        }

        return $fileNames;
    }


}
