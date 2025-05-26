<!doctype html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Invoice</title>
<style>
    body{
        margin: 0px;
        padding: 0px;
        font-family: Verdana, Geneva, Tahoma, sans-serif;
    }
    h2{
        margin: 0;
    }
    h4 {
        margin: 0;
    }
    @page {
        margin-top:25px;
    }
    .w-full {
        width: 100%;
    }
    .w-half {
        width: 50%;
    }
    .w-quarter {
        width: 25%;
    }
    .w-three-quarter {
        width: 75%;
    }

    .w-3 {
        width: 3%;
    }

    .w-5 {
        width: 5%;
    }

    .w-35 {
        width: 35%;
    }

    table tr.height-30 td {
        height: 30px;
    }

    table tr.height-50 td {
        height: 40px;
    }

    .margin-top {
        margin-top: 0.25rem;
    }
    .footer {
        font-size: 0.875rem;
        padding: 1rem;
        /* background-color: rgb(241 245 249); */
    }
    table {
        width: 100%;
        border-spacing: 0;
        font-size: 13px;
    }
    table tr td {
        vertical-align: top;
    }
    table tr td.vertical-b {
        vertical-align: bottom !important;
    }
    table tr td.vertical-m {
        vertical-align: middle !important;
    }
    table td.has-border {
        border: 1px solid lightgrey;
        border-collapse: collapse;
    }

    table td.has-border.noright {
        border-right: none !important;
    }
    table td.has-border.nobottom {
        border-bottom: none !important;
    }
    table td.has-border.noleft {
        border-left: none !important;
    }
    table td.has-border.notop {
        border-top: none !important;
    }

    /* table tr td.height-big {
        height: 68px;
    } */
    table.products {
        font-size: 0.875rem;
    }
    table.products tr {
        background-color: rgb(96 165 250);
    }
    table.products th {
        color: #ffffff;
        padding: 0.5rem;
    }
    table tr.items {
        background-color: rgb(241 245 249);
    }
    table tr.items td {
        padding: 0.5rem;
        text-align: center;
    }
    table tr.left td {
        text-align: left;
    }

    table tr.center td {
        text-align: center;
    }

    table tr td.left-align {
        text-align: left !important;
    }

    table tr td.right-align {
        text-align: right !important;
    }

    .total {
        text-align: right;
        margin-top: 1rem;
        font-size: 0.875rem;
    }

</style>
</head>
<body>
    <table class="w-full">
        <tr class="center">

            <td class="w-full">
                <h2>Proforma Invoice</h2>
            </td>
        </tr>
    </table>

    <div class="margin-top">
        <table cellpadding="0" cellspacing="0"  class="w-full">
            <tr>
                <td class="w-half">
                    <table class="w-full">
                        <tr class="left">
                            <td class="w-quarter has-border"><img src="data:image/png;base64,<?php echo e(base64_encode(file_get_contents(public_path('images/azzet_logo.jpg')))); ?>" class="w-full" ></td>
                            <td class="w-three-quarter has-border">
                                <b>Foco Creativo</b><br>
                                Building No:13/1080 A Amina Arcade,Kolathara<br>
                                Road Near Falcon Tiles,Kozhikode,Kerala-673019<br>
                                Mob-7510310132<br>
                                GSTIN/UIN : 32AAJFF6743Q1ZW<br>
                                State Name : Kerala, Code : 32

                            </td>
                        </tr>
                        <tr>
                            <td colspan="2" class="w-full has-border notop nobottom">
                                <small>Buyer (Bill to)</small><br>
                                <b>Fresco Print Pack Private Limited</b><br>
                                Plot No. 1659, Sector -38, HSIDC Industrial Estate<br>
                                Rai Sonepat (Haryana)<br>
                                PH NO:-9971636767, 9643813843<br>
                                MSME/Udyam no: UDYAM-DL-11-0009186<br>
                                GSTIN/UIN: 06AACCF6875F1ZI<br>
                                State Name :  Haryana, Code : 06<br>
                                CIN: U21000DL2015PTC287479<br>
                                E-Mail : 1659.frescoprintpack@gmail.com
                            </td>
                        </tr>
                    </table>
                </td>
                <td class="w-half has-border noleft nobottom notop">
                    <table class="w-full">
                        <tr class="left height-30">
                            <td class="w-half has-border noleft nobottom">Invoice No. <br>F/24-25/1468</td>
                            <td class="w-half has-border noleft nobottom noright">Dated <br>9-Aug-24</td>
                        </tr>
                        <tr class="left height-30">
                            <td class="w-half has-border noleft nobottom">Deleivery Note</td>
                            <td class="w-half has-border noleft nobottom noright">Mode/Terms of Payment <br><b>Advance</b></td>
                        </tr>
                        <tr class="left height-30">
                            <td class="w-half has-border noleft nobottom">Reference No. & Date.</td>
                            <td class="w-half has-border noleft nobottom noright">Other References</td>
                        </tr>
                        <tr class="left height-30">
                            <td class="w-half has-border noleft nobottom">Buyer's Order No.</td>
                            <td class="w-half has-border noleft nobottom noright">Dated</td>
                        </tr>
                        <tr class="left height-30">
                            <td class="w-half has-border noleft nobottom">Dispatch Doc No.</td>
                            <td class="w-half has-border noleft nobottom noright">Delivery Note Date</td>
                        </tr>
                        <tr class="left height-30">
                            <td class="w-half has-border noleft nobottom">Dispatched through</td>
                            <td class="w-half has-border noleft nobottom noright">Destination</td>
                        </tr>
                        <tr class="left">
                            <td colspan="2" class="w-half has-border noleft nobottom noright">Terms of Delivery</td>
                        </tr>
                    </table>
                </td>
            </tr>
            <tr>
                <td class="w-full" colspan="2">
                    <table class="w-full">
                        <tr class="center height-30" >
                            <td class="has-border noright w-3 vertical-m">SI No</td>
                            <td class="has-border noright w-35 vertical-m">Description of Goods</td>
                            <td class="has-border noright vertical-m">HSN/SAC</td>
                            <td class="has-border noright vertical-m">Quantity</td>
                            <td class="has-border noright vertical-m">Rate</td>
                            <td class="has-border noright vertical-m">Per</td>
                            <td class="has-border vertical-m">Amount</td>
                        </tr>
                        <tr class="center height-30" >
                            <td class="has-border notop noright">1</td>
                            <td class="has-border notop noright">Paper Bag Rope Handles<br><small>White Satin Ribbon 15mm 11 inches</small></td>
                            <td class="has-border notop noright">56075040</td>
                            <td class="has-border notop noright">1,000 Pairs</td>
                            <td class="has-border notop noright">1.85</td>
                            <td class="has-border notop noright">Pairs</td>
                            <td class="has-border notop"><b>1,850.00</b></td>
                        </tr>
                        <tr class="center height-30" >
                            <td class="has-border notop noright">2</td>
                            <td class="has-border notop noright">Paper Bag paper<br><small>White Satin paper</small></td>
                            <td class="has-border notop noright">56075040</td>
                            <td class="has-border notop noright">5,000 Pairs</td>
                            <td class="has-border notop noright">5</td>
                            <td class="has-border notop noright">Pairs</td>
                            <td class="has-border notop"><b>25,000.00</b></td>
                        </tr>

                        <tr class="center height-50" >
                            <td class="has-border notop noright"></td>
                            <td class="has-border notop noright right-align vertical-m">Total</td>
                            <td class="has-border notop noright"></td>
                            <td class="has-border notop noright vertical-m">6,000 Pairs</td>
                            <td class="has-border notop noright"></td>
                            <td class="has-border notop noright"></td>
                            <td class="has-border notop vertical-m"><b>31,000.00</b></td>
                        </tr>

                        <tr class="center height-30" >
                            <td colspan="6" class="has-border notop noright left-align"><small>Amount Chargeable (in words)</small><br>
                                <b>INR Two Thousand Four Hundred Sixty Four Only</b>
                            </td>
                            <td class="has-border notop noleft right-align">E. & O.E</td>
                        </tr>

                        <tr class="center height-30" >
                            <td colspan="3" class="has-border notop noright vertical-m">HSN/SAC</td>
                            <td class="has-border notop noright vertical-m">Taxable Value</td>
                            <td class="has-border notop norigh vertical-m">Rate</td>
                            <td class="has-border notop noright vertical-m">Amount</td>
                            <td class="has-border notop vertical-m"><b>Total Tax Amount</b></td>
                        </tr>
                        <tr class="center height-30" >
                            <td colspan="3" class="has-border notop noright left-align">56075040</td>
                            <td class="has-border notop noright">2,200.00</td>
                            <td class="has-border notop noright">12%</td>
                            <td class="has-border notop noright">264.00</td>
                            <td class="has-border notop">264.00</td>
                        </tr>
                        <tr class="center height-50" >
                            <td colspan="3" class="has-border notop noright right-align  vertical-m"><b>Total</b></td>
                            <td class="has-border notop noright  vertical-m"><b>2,200.00</b></td>
                            <td class="has-border notop noright"></td>
                            <td class="has-border notop noright  vertical-m"><b>264.00</b></td>
                            <td class="has-border notop  vertical-m"><b>264.00</b></td>
                        </tr>
                        <tr class="center height-30" >
                            <td colspan="7" class="has-border notop left-align"><small>Tax Amount (in words)  : </small> INR Two Hundred Sixty Four Only</td>
                        </tr>
                        <tr class="center" >
                            <td colspan="3" class="w-half has-border notop left-align vertical-b">Company's PAN : AACCF6875F</td>
                            <td colspan="4" class="w-half has-border notop left-align">
                                Company's Bank Details<br>
                                Bank Name : ICICI BANK<br>
                                A/c No. : 016005008083<br>
                                Branch & IFS Code: MODEL TOWN NEW DELHI & ICIC0000160</td>
                        </tr>
                        <tr class="center" >
                            <td colspan="3" class="w-half has-border notop left-align">
                                <u><small>Declaration</small></u><br>
                                We declare that this invoice shows the actual price of the
                                goods described and that all particulars are true and
                                correct.
                            </td>
                            <td colspan="4" class="w-half has-border notop right-align">
                                for Fresco Print Pack Private Limited<br>
                                <br>
                                <br>
                                Authorised Signatory</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

    <div class="footer margin-top ">
        <div style="text-align: center">This is a Computer Generated Invoice</div>
        
    </div>
</body>
</html>
<?php /**PATH C:\xampp\htdocs\zopa_delivery\resources\views\admin\sales\pdf_gst_format.blade.php ENDPATH**/ ?>