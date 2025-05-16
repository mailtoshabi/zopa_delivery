<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Remark;
use Illuminate\Http\Request;
use App\Http\Requests\RemarkStoreRequest;
use App\Http\Requests\RemarkUpdateRequest;
use App\Http\Utilities\Utility;
use Illuminate\Support\Facades\Auth;

class RemarkController extends Controller
{
    public function index()
    {
        $remarks = Remark::orderBy('id', 'desc')->paginate(Utility::PAGINATE_COUNT);
        return view('admin.remarks.index', compact('remarks'));
    }

    public function create()
    {
        return view('admin.remarks.create');
    }

    public function store(RemarkStoreRequest $request)
    {
        $data = $request->validated();
        $data['user_id'] = Auth::id();
        Remark::create($data);

        return redirect()->route('admin.remarks.index')->with('success', 'Remark created successfully.');
    }

    public function edit($id)
    {
        $remark = Remark::findOrFail(decrypt($id));
        return view('admin.remarks.create', compact('remark'));
    }

    public function update(RemarkUpdateRequest $request, Remark $remark)
    {
        $data = $request->validated();
        $remark->update($data);

        return redirect()->route('admin.remarks.index')->with('success', 'Remark updated successfully.');
    }

    public function destroy(Remark $remark)
    {
        $remark->delete();
        return back()->with('success', 'Remark deleted successfully.');
    }
}
