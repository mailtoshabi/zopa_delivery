<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\IngredientStoreRequest;
use App\Http\Requests\IngredientUpdateRequest;
use App\Http\Utilities\Utility;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Ingredient;
use App\Models\Meal;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredients = Ingredient::orderBy('id', 'desc')->paginate(Utility::PAGINATE_COUNT);
        return view('admin.ingredients.index', compact('ingredients'));
    }

    public function create()
    {
        return view('admin.ingredients.create');
    }

    public function store(IngredientStoreRequest $request)
    {
        $input = $request->except(['_token','user_id']);
        $input['user_id'] = Auth::id();

        $ingredient = Ingredient::create($input);

        return redirect()->route('admin.ingredients.index')->with(['success' => 'New Ingredient Added Successfully']);
    }

    public function edit($id)
    {
        $ingredient = Ingredient::findOrFail(decrypt($id));
        return view('admin.ingredients.create', compact('ingredient'));
    }

    public function update(IngredientUpdateRequest $request, $id)
    {
        $id = decrypt($id);
        $ingredient = Ingredient::findOrFail($id);

        $input = $request->except(['_method', '_token']);

        $ingredient->update($input);

        return redirect()->route('admin.ingredients.index')->with(['success' => 'Ingredient Updated Successfully']);
    }

    public function destroy(Ingredient $ingredient)
    {
        $ingredient->delete();
        return redirect()->route('ingredients.index')->with('success', 'Ingredient deleted!');
    }

    public function changeStatus($id)
    {
        $ingredient = Ingredient::findOrFail(decrypt($id));
        $status = $ingredient->status ? 0 : 1;
        $ingredient->update(['status' => $status]);

        return redirect()->route('admin.ingredients.index')->with(['success' => 'Status changed Successfully']);
    }
}
