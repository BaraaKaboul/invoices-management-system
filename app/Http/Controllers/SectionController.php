<?php

namespace App\Http\Controllers;

use App\Http\Requests\ValidateSectionRequest;
use App\Models\Section;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SectionController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $sections = Section::all();
        return view('sections.sections', compact('sections'));
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
    public function store(ValidateSectionRequest $request)
    {
        $input = $request -> all();
        $s_exist = Section::where('section_name', '=', $input['section_name'])->exists();
        if ($s_exist){

            session()->flash('Error','القسم موجود مسبقا');
            return redirect('sections');
        }
        else{

            Section::create([
                'section_name' => $request -> section_name,
                'description' => $request -> description,
                'created_by' => (Auth::user()->name),
            ]);
            session()->flash('Success','تم إضافة القسم بنجاح');
            return redirect('sections');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Section $section)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit()
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $id = $request->id;

        $this->validate($request, [
            'section_name' => 'required|max:255|unique:sections,section_name,'.$id,
        ],[
            'section_name.required' => 'يرجى ملئ "اسم القسم" بالمعلومات المطلوبة',
            'section_name.unique' => 'هذا القسم موجود مسبقا',
        ]);

        $sections = Section::find($id);
        $sections->update([
            'section_name' => $request->section_name,
            'description' => $request->description,
        ]);

        session()->flash('Edite','تم تعديل القسم بنجاج');
        return redirect('/sections');

    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request)
    {
        $id = $request->id;
        Section::find($id)->delete();
        session()->flash('Delete','تم حذف القسم بنجاح');
        return redirect('/sections');
    }
}
