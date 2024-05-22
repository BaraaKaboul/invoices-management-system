<?php

namespace App\Http\Controllers;

use App\Models\Invoice_attachment;
use Illuminate\Http\Request;

class InvoiceAttachmentController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return 'ok';
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'file_name'=>'unique:invoice_attachments|mimes:pdf,txt'
        ],[
            'file_name.mimes'=>'صيغة الملف يجب أن تكون من نوع pdf, jpeg, png, jpg',
            'file_name.unique'=>'هذا المرفق موجود من قبل'
        ]);


        $image = $request->file('file_name');//اسم الحقل بالفورم
        $file_name = $image->getClientOriginalName();

        $att = new Invoice_attachment();
//        $att -> file_name = $request -> file('file_name')->getClientOriginalName();// ياهيك الكود يا اما منحط= $file_name;
        $att -> file_name = $file_name;
        $att -> invoice_number = $request -> invoice_number;
        $att -> invoice_id = $request -> invoice_id;
        $att -> Created_by = auth()->user()->name;
        $att -> save();

        $inv_num = $request->input('invoice_number');
        $fileName = $request->file_name->getClientOriginalName();
        $request->file_name->move(public_path('/Attachments/'.$request->invoice_number), $fileName);

        session()->flash('Add','تمت إضافة المرفق بنجاح!');
        return back();







    }

    /**
     * Display the specified resource.
     */
    public function show(Invoice_attachment $invoice_attachment)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Invoice_attachment $invoice_attachment)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Invoice_attachment $invoice_attachment)
    {
        //
    }
}
