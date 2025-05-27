<?php

namespace App\Http\Utilities;

use App\Models\Settings;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class Utility{

    //Constants

    const SUPER_ADMIN_ID = 1;
    const ROLE_ADMIN = 1;
    const DEFAULT_BRANCH_ID = 2;

    const FROM_MAIL = 'shabeer@gmail.com';
    const SUPPORT_MAIL = 'support@zopa.in';

    const COUNTRY_CODE = '+91';
    const COUNTRY = 'IN';
    const CURRENCY_DISPLAY = 'INR';
    const CURRENCY_WORD_DISPLAY = 'Rupees';
    const STATE_ID_KERALA = 12;
    const DISTRICT_ID_MPM = 230;
    const KITCHEN_KDY = 1;
    const SINGLE_MEAL_ID = 1;
    const CUTOFF_TIME = "17:30";
    const MAX_LEAVE_DAYS_AHEAD = 30;
    const MAX_MONTHLY_LEAVES = 5;
    const MAX_ACTIVE_LEAVES = 5;
    const WALLET_LOW_BALANCE = 5;
    const OTP_EXPIRY_MINUTE = 5;


    const PAGINATE_COUNT = 15;
    const LOAD_MORE_COUNT = 5;

    const PAYMENT_ONLINE = 1;
    const PAYMENT_COD = 2;
    const PAYMENT_BNK = 3;

    const PAYMENT_CASH = 1;
    const PAYMENT_BANK = 2;
    const PAYMENT_UPI = 3;

    const PAYMENT_PENDING = 0;
    const PAYMENT_COMPLETED = 1;
    const PAYMENT_FAILED = 2;
    const PAYMENT_REFUNDED = 3;

    const ITEM_ACTIVE = 1;
    const ITEM_INACTIVE = 0;

    const STATUS_NEW = 0;
    const STATUS_CONFIRMED = 1;
    const STATUS_PRINTING = 2;
    const STATUS_PRODUCTION = 3;
    const STATUS_OUT = 4;
    const STATUS_DELIVEREDP = 5;
    const STATUS_DELIVERED = 6;
    const STATUS_CLOSED = 7;
    const STATUS_ONHOLD = 8;
    const STATUS_CANCELLED = 9;
    const STATUS_NOTPAID = 15;

    // Method to get CUTOFF HOUR and CUTOFF MINUTE from CUTOFF_TIME
    public static function getCutoffHourAndMinute() {
        list($hour, $minute) = explode(':', self::CUTOFF_TIME);

        // Cast to integers (optional, as `explode` returns strings)
        $hour = (int) $hour;
        $minute = (int) $minute;

        return [
            'hour' => $hour,
            'minute' => $minute
        ];
    }

    public static function generateFileName($name, $extension): string
    {
        $cleanName = self::cleanString($name);
        $timestamp = now()->format('YmdHis');

        return "{$cleanName}_{$timestamp}.{$extension}";
    }

    public static function otp()
    {
        $otp = md5( rand(0,1000) );
        /*$otp = rand(100000, 999999);*/
        return $otp;
    }

    protected  static $saleStatus = [
        self::STATUS_NEW => ['name'=>'New', 'exe'=>'0'],
        self::STATUS_CONFIRMED => ['name'=>'Party Approved', 'exe'=>'0'],
        self::STATUS_PRINTING => ['name'=>'Printing', 'exe'=>'0'],
        self::STATUS_PRODUCTION => ['name'=>'On Production', 'exe'=>'0'],
        self::STATUS_OUT => ['name'=>'Out for Delivery', 'exe'=>'1'],
        self::STATUS_DELIVEREDP => ['name'=>'Delivered Partially', 'exe'=>'1'],
        self::STATUS_DELIVERED => ['name'=>'Delivered', 'exe'=>'1'],
        self::STATUS_CLOSED => ['name'=>'Closed', 'exe'=>'0'],
        self::STATUS_ONHOLD => ['name'=>'On Hold', 'exe'=>'0'],
        self::STATUS_CANCELLED => ['name'=>'Cancelled', 'exe'=>'0'],
    ];
    public static function saleStatus()
    {
        return static::$saleStatus;
    }

    protected  static $paymentStatus = [
        self::PAYMENT_COMPLETED => ['name'=>'Completed'],
        self::PAYMENT_PENDING => ['name'=>'Pending'],

        self::PAYMENT_FAILED => ['name'=>'Failed'],
        self::PAYMENT_REFUNDED => ['name'=>'Refunded'],
    ];
    public static function paymentStatus()
    {
        return static::$paymentStatus;
    }

    protected  static $paymentMethods = [
        self::PAYMENT_CASH => ['name'=>'Cash'],
        self::PAYMENT_BANK => ['name'=>'Bank Transfer'],
        self::PAYMENT_UPI => ['name'=>'UPI'],

    ];
    public static function paymentMethods()
    {
        return static::$paymentMethods;
    }

    protected  static $handleTypes = [
        1 => 'Paper Handle',
        2 => 'Rope Handle'

    ];
    public static function handleTypes()
    {
        return static::$handleTypes;
    }

    public static function settings($term) {
        $value = Settings::where('term', $term)->value('value');
        return $value;
    }

    public static function addUnderScore($data)
    {
        return empty($data) ? '' : $data . '_';
    }

    public static function cleanString($string)
    {
        // Replace spaces and dashes with underscores
        $string = str_replace([' ', '-', '_'], '_', $string);

        // Remove multiple underscores
        $string = preg_replace('/_+/', '_', $string);

        // Remove any character that is not a-z, A-Z, 0-9, dot, or underscore
        $string = preg_replace('/[^A-Za-z0-9._]/', '', $string);

        // Trim leading/trailing underscores or dots
        $string = trim($string, '._');

        // Limit the base name length (before file extension)
        return Str::limit($string, 25, '');
    }

    public static function currencyToWords(float $number)
    {
        $no = floor($number);
        $decimal = round($number - ($no), 2) * 100;
        $hundred = null;
        $digits_length = strlen($no);
        $i = 0;
        $str = array();
        $words = array(0 => '', 1 => 'one', 2 => 'two',
            3 => 'three', 4 => 'four', 5 => 'five', 6 => 'six',
            7 => 'seven', 8 => 'eight', 9 => 'nine',
            10 => 'ten', 11 => 'eleven', 12 => 'twelve',
            13 => 'thirteen', 14 => 'fourteen', 15 => 'fifteen',
            16 => 'sixteen', 17 => 'seventeen', 18 => 'eighteen',
            19 => 'nineteen', 20 => 'twenty', 30 => 'thirty',
            40 => 'forty', 50 => 'fifty', 60 => 'sixty',
            70 => 'seventy', 80 => 'eighty', 90 => 'ninety');
        $digits = array('', 'hundred','thousand','lakh', 'crore');
        while( $i < $digits_length ) {
            $divider = ($i == 2) ? 10 : 100;
            $number = floor($no % $divider);
            $no = floor($no / $divider);
            $i += $divider == 10 ? 1 : 2;
            if ($number) {
                $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
                $hundred = ($counter == 1 && $str[0]) ? ' and ' : null;
                $str [] = ($number < 21) ? $words[$number].' '. $digits[$counter]. $plural.' '.$hundred:$words[floor($number / 10) * 10].' '.$words[$number % 10]. ' '.$digits[$counter].$plural.' '.$hundred;
            } else $str[] = null;
        }
        $Rupees = implode('', array_reverse($str));
        $paise_pre = !empty($Rupees) ? ' and ' : '';
        $paise = ($decimal) ? $paise_pre . ($words[$decimal / 10] . " " . $words[$decimal % 10]) . ' paise ' : '';
        $rupees_word = !empty($paise)?'':self::CURRENCY_WORD_DISPLAY;
        return ($Rupees ? $Rupees : '') . $paise . $rupees_word . ' only' ;
    }

    public static function formatPrice($val,$r=2)
    {
        $n = $val;
        $sign = ($n < 0) ? '-' : '';
        $i = number_format(abs($n),$r);
        return  $sign.$i;
    }

    // public static function stockInBranch($branch,$product)
    // {
    //     $branch_product = DB::table('branch_product')->where(['branch_id'=>$branch, 'product_id'=>$product])->first();
    //     return $branch_product->stock;
    // }

    public static function numToWords($number)
    {
        $words = array(
            '0' => 'Zero', '1' => 'One', '2' => 'Two',
            '3' => 'Three', '4' => 'Four', '5' => 'Five',
            '6' => 'Six', '7' => 'Seven', '8' => 'Eight',
            '9' => 'Nine', '10' => 'Ten', '11' => 'Eleven',
            '12' => 'Twelve', '13' => 'Thirteen', '14' => 'Fourteen',
            '15' => 'Fifteen', '16' => 'Sixteen', '17' => 'Seventeen',
            '18' => 'Eighteen', '19' => 'Nineteen', '20' => 'Twenty',
            '30' => 'Thirty', '40' => 'Forty', '50' => 'Fifty', '60' => 'Sixty',
            '70' => 'Seventy', '80' => 'Eighty', '90' => 'Ninety'
        );

        if ($number <= 20) {
            return $words[$number];
        }
        elseif ($number < 100) {
            return $words[10 * floor($number / 10)]
                . ($number % 10 > 0 ? ' ' . $words[$number % 10] : '');
        }
        else {
            $output = '';
            if ($number >= 1000000000) {
                $output .= static::numToWords(floor($number / 1000000000))
                    . ' Billion ';
                $number %= 1000000000;
            }
            if ($number >= 1000000) {
                $output .= static::numToWords(floor($number / 1000000))
                    . ' Million ';
                $number %= 1000000;
            }
            if ($number >= 1000) {
                $output .= static::numToWords(floor($number / 1000))
                    . ' Thousand ';
                $number %= 1000;
            }
            if ($number >= 100) {
                $output .= static::numToWords(floor($number / 100))
                    . ' Hundred ';
                $number %= 100;
            }
            if ($number > 0) {
                $output .= ($number <= 20) ? $words[$number] :
                $words[10 * floor($number / 10)] . ' '
                    . ($number % 10 > 0 ? $words[$number % 10] : '');
            }
            return trim($output);
        }
    }
}
