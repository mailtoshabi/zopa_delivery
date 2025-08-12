<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\MealStoreRequest;
use App\Http\Requests\MealUpdateRequest;
use App\Http\Utilities\Utility;
use App\Helpers\FileHelper;
use App\Models\Category;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Meal;
use App\Models\Ingredient;
use App\Models\MessCategory;
use App\Models\Remark;
use App\Models\WalletGroup;

class MealController extends Controller
{
    public function index()
    {
        $meals = Meal::orderBy('id', 'desc')->paginate(Utility::PAGINATE_COUNT);
        return view('admin.meals.index', compact('meals'));
    }

    public function create()
    {
        $categories = Category::where('status', Utility::ITEM_ACTIVE)->get();
        $mess_categories = MessCategory::where('status', Utility::ITEM_ACTIVE)->get();
        $wallet_groups = WalletGroup::where('status', Utility::ITEM_ACTIVE)->get();
        $ingredients = Ingredient::where('status', Utility::ITEM_ACTIVE)->pluck('name', 'id');
        $remarks = Remark::where('status', Utility::ITEM_ACTIVE)->pluck('name', 'id');
        return view('admin.meals.create', compact('ingredients','remarks','categories','wallet_groups','mess_categories'));
    }

    public function store(MealStoreRequest $request)
    {
        $input = $request->except(['_token', 'image', 'additional_images', 'ingredient_ids','remark_ids']);

        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name, 'image');
        }

        if ($request->hasFile('additional_images')) {
            $input['additional_images'] = json_encode($this->handleMultipleImagesUpload($request));
        }

        $input['user_id'] = Auth::id();

        $meal = Meal::create($input);
        if ($request->ingredient_ids) {
            $meal->ingredients()->sync($request->ingredient_ids ?? []);
        }
        if ($request->remark_ids) {
            $meal->remarks()->sync($request->remark_ids ?? []);
        }

        return redirect()->route('admin.meals.index')->with(['success' => 'New Meal Added Successfully']);
    }

    public function edit($id)
    {
        $categories = Category::where('status', Utility::ITEM_ACTIVE)->get();
        $mess_categories = MessCategory::where('status', Utility::ITEM_ACTIVE)->get();
        $wallet_groups = WalletGroup::where('status', Utility::ITEM_ACTIVE)->get();
        $meal = Meal::findOrFail(decrypt($id));
        $ingredients = Ingredient::where('status', 1)->pluck('name', 'id');
        $remarks = Remark::where('status', Utility::ITEM_ACTIVE)->pluck('name', 'id');
        return view('admin.meals.create', compact('meal', 'ingredients','remarks', 'categories','mess_categories','wallet_groups'));
    }

    public function update(MealUpdateRequest $request, $id)
    {
        // if ($request->remark_ids === [null]) {
        //     return "null";
        // }else {
        //     return "not null";
        // }

        $id = decrypt($id);
        $meal = Meal::findOrFail($id);
        $input = $request->except(['_token', '_method', 'image', 'additional_images', 'ingredient_ids', 'remark_ids', 'isImageDelete']);


        if ($request->isImageDelete == 1 && $meal->image_filename) {
            FileHelper::deleteFile(Meal::DIR_PUBLIC, $meal->image_filename);
            $input['image_filename'] = null;
        }

        if ($request->hasFile('image')) {
            $input['image_filename'] = $this->handleImageUpload($request, $request->name, 'image');
        }

        if ($request->hasFile('additional_images')) {
            $input['additional_images'] = json_encode($this->handleMultipleImagesUpload($request));
        }

        $meal->update($input);
        $meal->ingredients()->sync(array_filter($request->ingredient_ids ?? []));
        $meal->remarks()->sync(array_filter($request->remark_ids ?? []));

        return redirect()->route('admin.meals.index')->with(['success' => 'Meal Updated Successfully']);
    }

    public function destroy($id)
    {
        $meal = Meal::findOrFail(decrypt($id));

        if (!empty($meal->image_filename)) {
            FileHelper::deleteFile(Meal::DIR_PUBLIC, $meal->image_filename);
        }

        if (!empty($meal->additional_images)) {
            foreach (json_decode($meal->additional_images ?? '[]') as $filename) {
                FileHelper::deleteFile(Meal::DIR_PUBLIC, $filename);
            }
        }

        $meal->delete();
        return redirect()->route('admin.meals.index')->with(['success' => 'Meal Deleted Successfully']);
    }

    public function changeStatus($id)
    {
        $meal = Meal::findOrFail(decrypt($id));
        $status = $meal->status ? 0 : 1;
        $meal->update(['status' => $status]);

        return redirect()->route('admin.meals.index')->with(['success' => 'Status changed Successfully']);
    }

    private function handleImageUpload(Request $request, string $name, string $key): string
    {
        $fileName = Utility::generateFileName($name, $request->file($key)->extension());
        $request->file($key)->storeAs(Meal::DIR_PUBLIC, $fileName);
        return $fileName;
    }

    private function handleMultipleImagesUpload(Request $request): array
    {
        $imageNames = [];
        foreach ($request->file('additional_images') as $index => $file) {
            $imageName = Utility::generateFileName('meal-' . $index, $file->extension());
            $file->storeAs(Meal::DIR_PUBLIC, $imageName);
            $imageNames[] = $imageName;
        }
        return $imageNames;
    }
}
