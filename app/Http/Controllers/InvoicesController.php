<?php

namespace App\Http\Controllers;

use App\Exports\InvoiceExport;
use App\Models\Invoice;
use App\Models\Invoice_attachment;
use App\Models\Invoice_details;
use App\Models\Product;
use App\Models\Section;
use App\Models\User;
use App\Notifications\AddInvoice;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Facades\Storage;
use Maatwebsite\Excel\Facades\Excel;


class InvoicesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $invoices = Invoice::all();
        return view('invoices.invoices', compact('invoices'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $sections = Section::all();
        return view('invoices.add_invoice', compact('sections'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        Invoice::create([
            'invoice_number' => $request->invoice_number,
            'invoice_Date' => $request->invoice_Date,
            'due_date' => $request->Due_date,
            'product' => $request->product,
            'section_id' => $request->Section,
            'amount_collection' => $request->Amount_collection,
            'amount_commission' => $request->Amount_Commission,
            'discount' => $request->Discount,
            'value_vat' => $request->Value_VAT,
            'rate_vat' => $request->Rate_VAT,
            'total' => $request->Total,
            'status' => 'غير مدفوعة',
            'value_status' => 2,
            'note' => $request->note,
        ]);
        $invoice_id = Invoice::latest()->first()->id;

        Invoice_details::create([
            'invoice_id'=>$invoice_id,
            'invoice_number'=>$request->invoice_number,
            'product'=>$request->product,
            'Section'=>$request->Section,
            'Status'=>'غير مدفوعة',
            'Value_Status'=>2,
            'note'=>$request->note,
            'user'=>auth()->user()->name,
        ]);

        if ($request->hasFile('pic')) { // اسم الحقل pic

            $invoice_id = Invoice::latest()->first()->id;
            $image = $request->file('pic');
            $file_name = $image->getClientOriginalName();
            $invoice_number = $request->invoice_number;

            $attachments = new Invoice_attachment();
            $attachments->file_name = $file_name;
            $attachments->invoice_number = $invoice_number;
            $attachments->Created_by = auth()->user()->name;
            $attachments->invoice_id = $invoice_id;
            $attachments->save();

            // move pic
            $imageName = $request->pic->getClientOriginalName();
            $request->pic->move(public_path('Attachments/' . $invoice_number), $imageName);
        }

//        $user = User::first();
////        Notification::send($user, new AddInvoice($invoice_id));

        //يعني ابعت اشعار فقط لليوزر لعمل الفاتورة
        $user = User::get();

        $invoice = Invoice::latest()->first();

        //$user->notify(new \App\Notifications\AddInvoice($invoice));//يا هاي الطريقة يا اما الي تحتها
        //يعني ابعت اشعار فقط لليوزر لعمل الفاتورة
        Notification::send($user, new AddInvoice($invoice));

        session()->flash('Add', 'تمت إضافة الفاتورة بنجاح');
        return redirect()->back();

    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $invoices = Invoice::where('id',$id)->first();
        return view('invoices.status_update',compact('invoices'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $invoices = Invoice::findOrFail($id)->where('id','=',$id)->first();
        $sections = Section::all();
        return view('invoices.invoice_edit', compact('invoices','sections'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request,$id)
    {
        $invoiceID = Invoice::findOrFail($id);
        $invoiceID -> update([
            'invoice_number' => $request->invoice_number,
            'invoice_date' => $request->invoice_date,
            'due_date' => $request->due_date,
            'section_id' => $request->Section,
            'product' => $request->product,
            'amount_collection' => $request->amount_collection,
            'amount_commission' => $request->amount_commission,
            'discount' => $request->discount,
            'rate_vat' => $request->rate_vat,
            'value_vat' => $request->value_vat,
            'total' => $request->total,
            'note' => $request->note,
        ]);
        session()->flash('edit','لقد تم تعديل البيانات بنجاح');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
////        $id = $request->invoice_id;
//        $inv_del = Invoice::where('id',$id)->first();
//       $details = Invoice_attachment::where('invoice_id',$id)->first();
//
//
//
//            if (!empty($details->invoice_number)) {
//                //Storage::disk('public_uploads')->delete($details->invoice_number . '/' . $details->file_name);
//                // هاد الكود فقط بيحذف يلي جوات الملف وما بيحذف الملف الي هو رقم الفاتورة
//                Storage::disk('public_uploads')->deleteDirectory($details->invoice_number);
//                // هاد الكود بيحذف الملف باللي فيه يعني بيحذف ملف رقم الفاتورة باللي فيه كلو
//            }
//
//            $inv_del->forceDelete();
//            session()->flash('delete_invoice');
//            return redirect('/invoices')->with('invoices',$inv_del);
//            // الwith هون عملت شغل الcompact مشان نغضر نمرر بالview المتغير


        $id = $request->invoice_id;
        $invoices = Invoice::where('id', $id)->first();
        $Details = Invoice_attachment::where('invoice_id', $id)->first();

        $id_page =$request->id_page;


        if (!$id_page==2) {

            if (!empty($Details->invoice_number)) {

                Storage::disk('public_uploads')->deleteDirectory($Details->invoice_number);


            }
            $invoices->forceDelete();
            session()->flash('delete_invoice');
            return back();


        }

        else {

            $invoices->delete();
            session()->flash('archive_invoice');
            return back();
        }





    }

    public function getProducts($id){

        $product = Product::where('section_id', '=', $id) -> pluck('product_name', 'id');
        return json_encode($product);
    }

    public function updateStatus($id, Request $request){
        $upd_status = Invoice::where('id',$id)->findOrFail($id);
        if ($request->status === 'مدفوعة'){
            $upd_status->update([
               'value_status'=>1,
                'status'=>$request->status,
            ]);
            Invoice_details::create([
                'invoice_id'=>$request->invoice_id,
                'invoice_number'=>$request->invoice_number,
                'product'=>$request->product,
                'Section'=>$request->Section,
                'Status'=>$request->status,
                'Value_Status'=>1,
                'note'=>$request->note,
                'Payment_Data'=>$request->Payment_Date,
                'user'=>auth()->user()->name,
            ]);
        }
        else {
            $upd_status->update([
                'value_status' => 3,
                'status' => $request->status,
            ]);
            Invoice_details::create([
                'invoice_id' => $request->invoice_id,
                'invoice_number' => $request->invoice_number,
                'product' => $request->product,
                'Section' => $request->Section,
                'Status' => $request->status,
                'Value_Status' => 3,
                'note' => $request->note,
                'Payment_Data' => $request->Payment_Date,
                'user' => auth()->user()->name,
            ]);
            return redirect('invoices');
        }
    }
    public function invPaid(){
        $inv_paid = Invoice::where('value_status', 1)->get();
        return view('invoices.invoice_paid',compact('inv_paid'));
    }
    public function invUnPaid(){
        $inv_un_paid = Invoice::where('value_status', 2)->get();
        return view('invoices.invoice_unpaid',compact('inv_un_paid'));
    }
    public function invPartial(){
        $inv_partial = Invoice::where('value_status', 3)->get();
        return view('invoices.invoice_partial',compact('inv_partial'));
    }

    public function printInvoice($id){
        $invoices = Invoice::where('id',$id)->first();
        return view('invoices.print_invoice',compact('invoices'));
    }

    public function export()
    {
        return Excel::download(new InvoiceExport, 'invoices.xlsx');
    }

    public function markAllAsRead(){

        $user = User::findorFail(auth() -> user() -> id);
        foreach ($user -> unreadNotifications as $noti) {

            $noti -> markAsRead();
        }
        return redirect() -> back();
    }
}
