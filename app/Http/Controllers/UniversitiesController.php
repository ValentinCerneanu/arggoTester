<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Soap\Soap;

class UniversitiesController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */ 
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $soap = new Soap('CP_Universities');
        $universities = $soap->ReadMultiple()->ReadMultiple_Result;

        if (is_array($universities->CP_Universities)) {
            $universities = $universities->CP_Universities;
        }

        foreach ($universities as $university) {
            $soap = new Soap('CP_Faculties');
            $university->faculties = $soap->ReadMultiple(0, ['University_Id' => $university->Id])->ReadMultiple_Result;
            if (!empty($university->faculties->CP_Faculties)) {
                if (is_array($university->faculties->CP_Faculties)) {
                    $university->faculties = $university->faculties->CP_Faculties;
                }
            }
        }   

        return view('admin.universities.index', compact('universities'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.universities.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $university = [
            'CP_Universities' => [
                'Name' => $request->name,
                'Address' => $request->address,
            ],
        ];

        $soap = new Soap('CP_Universities');
        $universities = $soap->ReadMultiple()->ReadMultiple_Result;
        if (isset($universities->CP_Universities)) {
            if (is_array($universities->CP_Universities)) {
                $universities = $universities->CP_Universities;
            }
        }
        $id = 0;
        foreach ($universities as $univ) {
            if ($univ->Id > $id) {
                $id = $univ->Id;
            }
        }
        $id += 1;
        $university['CP_Universities']['Id'] = $id;

        $soap->soapClient->Create($university);

        return redirect()->route('universities.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $soap = new Soap('CP_Universities');
        $university = $soap->Read(['Id' => $id]);
        if ($university == null) {
            return abort(404);
        }
        $university = $university->CP_Universities;
        return view('admin.universities.edit', compact('university'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $soap = new Soap('CP_Universities');
        $university = $soap->Read(['Id' => $id]);
        if ($university == null) {
            return abort(404);
        }
        $university->CP_Universities->Name = $request->name;
        $university->CP_Universities->Address = $request->address;

        $soap->soapClient->Update($university);

        return redirect()->route('universities.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {        
        $soap = new Soap('CP_Universities');
        $university = $soap->Read(['Id' => $id]);
        if ($university == null) {
            return abort(404);
        }
        
        if ($this->notSafeToModify($id)) {
            return redirect()->route('universities.index');
        }

        $soap->soapClient->Delete(['Key' => $university->CP_Universities->Key]);
        return redirect()->route('universities.index');
    }

    public function notSafeToModify($id)
    {
        $soap = new Soap('CP_Faculties');
        $faculties = $soap->ReadMultiple(0, ['University_Id' => $id])->ReadMultiple_Result;
        if (isset($faculties->CP_Faculties)) {
            \Session::flash('alert', 'Stergerea nu poate fi efectuata!'); 
            \Session::flash('alert-class', 'alert-danger'); 

            return true;
        }
        return false;
    }
}
