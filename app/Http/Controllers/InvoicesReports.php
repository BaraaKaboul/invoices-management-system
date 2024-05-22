<?php

namespace App\Http\Controllers;

use App\Models\Invoice;
use Illuminate\Http\Request;

class InvoicesReports extends Controller
{
    public function index(){
        return view('Reports.invoices_reports');
    }


public function search_invoice(Request $request)
{

    $rdio = $request->rdio;


    // في حالة البحث بنوع الفاتورة

    if ($rdio == 1) {


        // في حالة عدم تحديد تاريخ
        if ($request->type && $request->start_at == '' && $request->end_at == '') {
            //النجمة ( * ) يعني انو جيب كلشي all
            $invoices = Invoice::select('*')->where('status', '=', $request->type)->get();
            $type = $request->type;
            return view('Reports.invoices_reports', compact('type'))->withDetails($invoices);
        } // في حالة تحديد تاريخ استحقاق
        else {

            $start_at = date($request->start_at);
            $end_at = date($request->end_at);
            $type = $request->type;
            //لما بدي ابحث عن تاريخ بين تاريخين بستخدم whereBetween ولازم نساخدم متل السطرين لفوق تعليمة date
                                                            //ابحثلي عن الريكوردز بين تاريخ كذا و تاريخ كذا
            $invoices = Invoice::whereBetween('invoice_date', [$start_at, $end_at])->where('status', '=', $request->type)->get();
            return view('Reports.invoices_reports', compact('type', 'start_at', 'end_at'))->withDetails($invoices);

        }

    }

//====================================================================

// في البحث برقم الفاتورة
    else {
        //النجمة ( * ) يعني انو جيب كلشي all
        $invoices = Invoice::select('*')->where('invoice_number', '=', $request->invoice_number)->get();
        return view('Reports.invoices_reports')->withDetails($invoices);

    }

}

//مو شغال هاد الكود
public function allReports(Request $request){
        $type = $request->type;
        if ($type == 'جميع الفواتير') {
            $invoices = Invoice::all();
            return view('Reports.invoices_reports', compact('type'))->withDetails($invoices);
        }
        }
}
