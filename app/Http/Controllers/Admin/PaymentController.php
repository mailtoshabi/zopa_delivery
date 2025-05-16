<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Utilities\Utility;
use App\Models\Payment;
use App\Models\Sale;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{


    public function store () {
        $sale = Sale::findOrFail(decrypt(request('sale_id')));
        if($sale->status==Utility::STATUS_CLOSED) {
            abort(403, 'The Proforma has already been closed');
        }
        $validated = request()->validate([
            'amount' => 'required',
            'payment_method' => 'required',
            'paid_at' => 'required',
            'status' => 'required',
        ]);
        // return Carbon::parse(request('paid_at'))->format('Y-m-d');
        $input = request()->only(['amount','payment_method','transaction_id','description','status']);
        $input['sale_id'] = decrypt(request('sale_id'));
        $input['paid_at'] = Carbon::parse(request('paid_at'))->format('Y-m-d');
        $input['user_id'] =Auth::id();
        $payment = Payment::create($input);
        return redirect()->to(route('admin.sales.view',request('sale_id')).'#allPaymentDetails')->with(['success'=>'New Payment Added Successfully']);
    }

    // public function edit($id) {
    //     $payment = Payment::findOrFail(decrypt($id));
    //     return view('admin.payments.add',compact('payment','gst_slabs'));
    // }

    public function update () {
        $id = decrypt(request('payment_id'));
        $sale = Sale::findOrFail(decrypt(request('sale_id')));
        if($sale->status==Utility::STATUS_CLOSED) {
            abort(403, 'The Proforma has already been closed');
        }
        $payment = Payment::find($id);
        if ($payment->sale->estimate->branch_id != default_branch()->id) {
            abort(403, 'This estimate is not associated with this branch.');
        }
        // return $payment;
        $validated = request()->validate([
            'amount' => 'required',
            'payment_method' => 'required',
            'paid_at' => 'required',
            'status' => 'required',
        ]);
        $input = request()->only(['amount','payment_method','transaction_id','description','status']);
        $input['sale_id'] = decrypt(request('sale_id'));
        $input['paid_at'] = Carbon::parse(request('paid_at'))->format('Y-m-d');
        $payment->update($input);
        // return redirect()->route('admin.payments.index')->with(['success'=>'Payment Updated Successfully']);
        return redirect()->to(route('admin.sales.view',request('sale_id')).'#allPaymentDetails')->with(['success'=>'Payment Updated Successfully']);
    }

    public function destroy($id) {
        $payment = Payment::find(decrypt($id));
        if ($payment->sale->estimate->branch_id != default_branch()->id) {
            abort(403, 'This estimate is not associated with this branch.');
        }
        // return request()->all();
        $payment->delete();
        return redirect()->to(route('admin.sales.view',request('sale_id')).'#allPaymentDetails')->with(['success'=>'Payment Deleted Successfully']);
    }
}
