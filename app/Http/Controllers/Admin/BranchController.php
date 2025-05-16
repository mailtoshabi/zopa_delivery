<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\ContactPerson;
use App\Models\Branch;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class BranchController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index() {
        // $status = request('status');
        // $count_pending = Branch::where('status',Utility::ITEM_INACTIVE)->count();
        // return $count_pending;
        // $is_approved = isset($status)? decrypt(request('status')) : ($count_pending==0?1:0);
        $branches = Branch::orderBy('id','asc')->paginate(Utility::PAGINATE_COUNT);
        return view('admin.branches.index',compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $states = DB::table('states')->orderBy('name','asc')->select('id','name')->get();
        $banks = DB::table('banks')->where('status',Utility::ITEM_ACTIVE)->orderBy('name','asc')->select('id','name')->get();
        return view('admin.branches.add',compact('states','banks'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store()
    {
        // return request('contact_names');
        $validated = request()->validate([
            'name' => 'required',
            // 'email' => 'nullable|email|unique:branches,email',
            'phone' => 'required|string|max:10|min:10|unique:branches,phone',
            'city' => 'required',
            'district_id' => 'required',
            'state_id' => 'required'
        ]);
        $input = request()->except(['_token','image','isImageDelete']);
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = Utility::cleanString(request()->name) . date('YmdHis') . '.' . $extension;
            request('image')->storeAs('branches', $fileName);
            $input['image'] =$fileName;
        }
        // $input['user_id'] =Auth::id();
        $input['is_approved'] = 1;
        $branch = Branch::create($input);

        // if(!empty(request('contact_names'))) {
        //     foreach(request('contact_names') as $index => $contact_name) {
        //         if(!empty($contact_name)) {
        //             $contactPerson = new ContactPerson();
        //             $contactPerson->create(['name' => $contact_name, 'email' => request('emails')[$index], 'phone' => request('phones')[$index], 'branch_id' => $branch->id, 'user_id' => Auth::id()]);
        //         }
        //     }
        // }

        return redirect()->route('admin.branches.index')->with(['success'=>'New Branch Added Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $branch = Branch::findOrFail(decrypt($id));
        return view('admin.branches.view',compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $branch = Branch::findOrFail(decrypt($id));
        $states = DB::table('states')->orderBy('name','asc')->select('id','name')->get();
        $banks = DB::table('banks')->where('status',Utility::ITEM_ACTIVE)->orderBy('name','asc')->select('id','name')->get();
        return view('admin.branches.add',compact('branch','states','banks'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function update()
    {
        $id = decrypt(request('branch_id'));
        $branch = Branch::find($id);
        //return Branch::DIR_PUBLIC . $branch->image;
        $validated = request()->validate([
            'name' => 'required',
            // 'email' => 'nullable|email|unique:branches,email,'. $id,
            'phone' => 'required|string|max:10|min:10|unique:branches,phone,'. $id,
            'city' => 'required',
            'district_id' => 'required',
            'state_id' => 'required'
        ]);
        $input = request()->except(['_token','_method','branch_id','image','isImageDelete']);
        if(request('isImageDelete')==1) {
            Storage::delete(Branch::DIR_PUBLIC . $branch->image);
            $input['image'] =null;
        }
        if(request()->hasFile('image')) {
            $extension = request('image')->extension();
            $fileName = Utility::cleanString(request()->name) . date('YmdHis') . '.' . $extension;
            request('image')->storeAs('branches', $fileName);
            $input['image'] =$fileName;
        }
        //$input['user_id'] =Auth::id();
        $branch->update($input);
        // $contactPersons = ContactPerson::where('branch_id',$branch->id)->get();
        // if(!empty($contactPersons)) {
        //    foreach($contactPersons as $contactPerson_d)
        //     $contactPerson_d->delete();
        // }
        // if(!empty(request('contact_names'))) {
        //     foreach(request('contact_names') as $index => $contact_name) {
        //         if(!empty($contact_name)) {
        //             $contactPerson = new ContactPerson();
        //             $contactPerson->create(['name' => $contact_name, 'email' => request('emails')[$index], 'phone' => request('phones')[$index], 'branch_id' => $branch->id, 'user_id' => Auth::id()]);
        //         }
        //     }
        // }

        return redirect()->route('admin.branches.index')->with(['success'=>'Branch Updated Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::find(decrypt($id));
        if(!$branch->is_approved) {
        if(!empty($branch->image)) {
            Storage::delete(Branch::DIR_PUBLIC . $branch->image);
            $input['image'] =null;
        }
        $branch->delete();
        return redirect()->route('admin.branches.index')->with(['success'=>'Branch Deleted Successfully']);
    }else{
        abort(404);
    }
    }

    public function changeStatus($id) {
        $branch = Branch::find(decrypt($id));
        $currentStatus = $branch->status;
        $status = $currentStatus ? 0 : 1;
        $branch->update(['status'=>$status]);
        return redirect()->route('admin.branches.index')->with(['success'=>'Status changed Successfully']);
    }

    public function makeDefaultGlobal() {

        $default_branch = session(['default_branch' => request('main_branch_id')]);
        return 1;
    }

    public function makeDefault($id) {
        $default_branch = session(['default_branch' => $id]);
        return redirect()->route('admin.branches.index')->with(['success'=>'Changed to Default Successfully']);
    }

    public function distric_list(Request $request) {
        $districts = DB::table('districts')->orderBy('name','asc')->select('id','name')->where('state_id',$request->s_id)->get();
        $data[]= '<option value="">Select District</option>';
        foreach($districts as $district) {
            $selected = $district->id == $request->d_id ? 'selected' : '';
            $data[] = '<option value="' . $district->id . '"' . $selected . ' >'. $district->name . '</option>';
        }
        return $data;
    }

}
