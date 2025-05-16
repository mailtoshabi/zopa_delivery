<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddonStoreRequest;
use App\Http\Requests\AddonUpdateRequest;
use App\Http\Utilities\Utility;
use App\Helpers\FileHelper;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Addon;
use App\Models\Ingredient;
use App\Models\Remark;

class AddonController extends Controller
{
    public function index()
    {
        $addons = Addon::orderBy('id', 'desc')->paginate(Utility::PAGINATE_COUNT);
        return view('admin.addons.index', compact('addons'));
    }

    public function create()
    {
        return view('admin.addons.create');
    }

    public function store(AddonStoreRequest $request)
    {
        $input = $request->except(['_token', 'image', 'additional_images']);

        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name, 'image');
        }

        if ($request->hasFile('additional_images')) {
            $input['additional_images'] = json_encode($this->handleMultipleImagesUpload($request));
        }

        $input['user_id'] = Auth::id();

        $addon = Addon::create($input);
        return redirect()->route('admin.addons.index')->with(['success' => 'New Addon Added Successfully']);
    }

    public function edit($id)
    {
        $addon = Addon::findOrFail(decrypt($id));
        return view('admin.addons.create', compact('addon'));
    }

    public function update(AddonUpdateRequest $request, $id)
    {

        $id = decrypt($id);
        $addon = Addon::findOrFail($id);
        $input = $request->except(['_token', '_method', 'image', 'additional_images', 'isImageDelete']);


        if ($request->isImageDelete == 1 && $addon->image_filename) {
            FileHelper::deleteFile(Addon::DIR_PUBLIC, $addon->image_filename);
            $input['image_filename'] = null;
        }

        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name, 'image');
        }

        if ($request->hasFile('additional_images')) {
            $input['additional_images'] = json_encode($this->handleMultipleImagesUpload($request));
        }

        $addon->update($input);
        return redirect()->route('admin.addons.index')->with(['success' => 'Addon Updated Successfully']);
    }

    public function destroy($id)
    {
        $addon = Addon::findOrFail(decrypt($id));

        if (!empty($addon->image_filename)) {
            FileHelper::deleteFile(Addon::DIR_PUBLIC, $addon->image_filename);
        }

        if (!empty($addon->additional_images)) {
            foreach (json_decode($addon->additional_images ?? '[]') as $filename) {
                FileHelper::deleteFile(Addon::DIR_PUBLIC, $filename);
            }
        }

        $addon->delete();
        return redirect()->route('admin.addons.index')->with(['success' => 'Addon Deleted Successfully']);
    }

    public function changeStatus($id)
    {
        $addon = Addon::findOrFail(decrypt($id));
        $status = $addon->status ? 0 : 1;
        $addon->update(['status' => $status]);

        return redirect()->route('admin.addons.index')->with(['success' => 'Status changed Successfully']);
    }

    private function handleImageUpload(Request $request, string $name, string $key): string
    {
        $fileName = Utility::generateFileName($name, $request->file($key)->extension());
        $request->file($key)->storeAs(Addon::DIR_PUBLIC, $fileName);
        return $fileName;
    }

    private function handleMultipleImagesUpload(Request $request): array
    {
        $imageNames = [];
        foreach ($request->file('additional_images') as $index => $file) {
            $imageName = Utility::generateFileName('addon-' . $index, $file->extension());
            $file->storeAs(Addon::DIR_PUBLIC, $imageName);
            $imageNames[] = $imageName;
        }
        return $imageNames;
    }
}
