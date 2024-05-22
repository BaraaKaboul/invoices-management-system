<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Section;
use Illuminate\Http\Request;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $products = Product::all();
        $sections = Section::all(); // بصفحة الblade حطينا ضمن value الid مشان يحفظا لانو في عنا علاقة بين جدولين
        return view('products.products', compact('sections', 'products'));
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
        $request->validate([
           'product_name' => 'required',
            'section_id' => 'required',
        ],[
            'product_name.required' => 'يرجى اضافة اسم المنتج المطلوب',
            'section_id.required' => 'يرجى اختيار القسم المطلوب',
        ]);

        Product::create([
            'product_name' => $request -> product_name,
            'description' => $request -> description,
            'section_id' => $request -> section_id,
        ]);
        session()->flash('Add','تم اضافة المنتج بنجاح');
        return redirect('products');
    }

    /**
     * Display the specified resource.
     */
    public function show(Product $product)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Product $product)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {

        $request->validate([
            'product_name' => 'required',
            'section_name' => 'required',
        ],[
            'product_name.required' => 'يرجى اضافة اسم المنتج المطلوب',
            'section_name.required' => 'يرجى اختيار القسم المطلوب',
        ]);

        $id = Section::where('section_name', $request->section_name)->first()->id;
        $products = Product::findOrFail($request->pro_id);
        $products->update([
            'product_name' => $request -> product_name,
            'description' => $request -> description,
            'section_id' => $id
        ]);

        session()->flash('Edit', 'تم تعديل البيانات بنجاح');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $products = Product::findOrFail($request->pro_id);
        $products ->delete();
        session()->flash('Delete','لقد تم حذف المنتج بنجاح');
        return redirect()->back();
    }
}
