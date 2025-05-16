<?php

namespace App\Models;

use App\Http\Utilities\Utility;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;

class Sale extends Model
{
    use HasFactory;

    protected $hidden = ['id'];

    protected $guarded = [];

    protected $casts = ['date_confirmed' => 'datetime','date_production' => 'datetime','date_out_delivery' => 'datetime','date_delivered' => 'datetime','date_closed' => 'datetime','date_onhold' => 'datetime','date_cancelled' => 'datetime'];

    public function customer()
    {
        return $this->belongsTO(Customer::class);
    }

    public function products() {
        return $this->belongsToMany(Product::class,'product_sale')->withPivot('id','price','quantity')->withTimestamps();
    }

    public function getpaymentMethodsTextAttribute() {
        if($this->pay_method==Utility::PAYMENT_ONLINE) return '<i class="fab fa-cc-visa me-1"></i> Online Payment';
        else if($this->pay_method==Utility::PAYMENT_COD) return '<i class="fas fa-money-bill-alt me-1"></i> Cash On Delivery';
        else return '-';
    }

    // public function scopeActive($query) {
    //     return $query->where('status',Utility::ITEM_ACTIVE);
    // }

    // public function scopeArchive($query) {
    //     return $query->where('status',Utility::ITEM_INACTIVE);
    // }

    public function getsubTotalAttribute() {
        // $total = 0;
        // foreach($this->products as $product) {

        //     $quantity = $product->pivot->quantity;
        //     $price = $product->pivot->price;

        //     // $price = $profit + $sum_price_ingredients;
        //     $total += $quantity*$price;
        // }
        // return $total;

        return $this->products->sum(function ($product) {
            return $product->pivot->price * $product->pivot->quantity;
        });
    }

    public function getsubQuantityAttribute() {
        // $total = 0;
        // foreach($this->products as $product) {

        //     $quantity = $product->pivot->quantity;

        //     // $price = $profit + $sum_price_ingredients;
        //     $total += $quantity;
        // }
        // return $total;
        return $this->products->sum(function ($product) {
            return $product->pivot->quantity;
        });
    }



    public function payments()
    {
        return $this->hasMany(Payment::class);
    }

    public function getTotalPaidAttribute()
    {
        return $this->payments()->where('status', Utility::PAYMENT_COMPLETED)->sum('amount');
    }

    public function getPaymentStatusAttribute() {
        if($this->total_paid==0) {
            return 'Not Paid';
        }
        if($this->total_paid>=($this->sub_total+$this->total_igst+$this->delivery_charge-$this->round_off-$this->discount)) {
            $status = 'Paid';
        }else { $status = 'Not Completed'; }
        return $status;
    }
    // public function branch()
    // {
    //     return $this->belongsTO(Branch::class);
    // }

    public function getStatusAttribute() {
        $sale_statuse = DB::table('sale_statuses')->select('status')->where('sale_id',$this->id)->where('is_current',Utility::ITEM_ACTIVE)->first();
        $status=$sale_statuse?$sale_statuse->status:Utility::STATUS_NEW;
        return $status;
    }

    public function getReasonAttribute() {
        $sale_statuse = DB::table('sale_statuses')->select('description')->where('sale_id',$this->id)->where('is_current',Utility::ITEM_ACTIVE)->first();
        $description=$sale_statuse?$sale_statuse->description:'';
        return $description;
    }

}
