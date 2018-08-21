<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Soap\Soap;

class FacultiesController extends Controller
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
        $soap = new Soap('CP_Faculties');
        $faculties = $soap->ReadMultiple()->ReadMultiple_Result;

        if (is_array($faculties->CP_Faculties)) {
            $faculties = $faculties->CP_Faculties;
        }

        return view('admin.faculties.index', compact('faculties'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id)
    {
        $soap = new Soap('CP_Universities');
        if ($id != null) {
            $universities = $soap->Read(['Id' => $id]);
            if ($universities == null) {
                return abort(404);
            }
        } else {
            $universities = $soap->ReadMultiple()->ReadMultiple_Result;
            if (isset($universities->CP_Universities)) {
                if (is_array($universities->CP_Universities)) {
                    $universities = $universities->CP_Universities;
                }
            }
        }
        return view('admin.faculties.create', compact('universities'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $faculty = [
            'CP_Faculties' => [
                'University_Id' => $request->university_id,
                'Name' => $request->name,
            ],
        ];

        $soap = new Soap('CP_Faculties');
        $faculties = $soap->ReadMultiple()->ReadMultiple_Result;
        if (isset($faculties->CP_Faculties)) {
            if (is_array($faculties->CP_Faculties)) {
                $faculties = $faculties->CP_Faculties;
            }
        }
        $id = 0;
        foreach ($faculties as $fac) {
            if ($fac->Id > $id) {
                $id = $fac->Id;
            }
        }
        $id += 1;
        $faculty['CP_Faculties']['Id'] = $id;

        $soap->soapClient->Create($faculty);

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
        $soap = new Soap('CP_Faculties');
        $faculty = $soap->Read(['Id' => $id]);
        if ($faculty == null) {
            return abort(404);
        }
        $faculty = $faculty->CP_Faculties;
        
        $soap = new Soap('CP_Universities');
        $universities = $soap->ReadMultiple()->ReadMultiple_Result;
        if (isset($universities->CP_Universities)) {
            if (is_array($universities->CP_Universities)) {
                $universities = $universities->CP_Universities;
            }
        }

        return view('admin.faculties.edit', compact('faculty', 'universities'));
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
        $soap = new Soap('CP_Faculties');
        $faculty = $soap->Read(['Id' => $id]);
        if ($faculty == null) {
            return abort(404);
        }
        $faculty->CP_Faculties->University_Id = $request->university_id;
        $faculty->CP_Faculties->Name = $request->name;
        $soap->soapClient->Update($faculty);

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
        $soap = new Soap('CP_Faculties');
        $faculty = $soap->Read(['Id' => $id]);
        if ($faculty == null) {
            return abort(404);
        }
        
        if ($this->notSafeToModify($id)) {
            return redirect()->route('universities.index');
        }
        
        $soap->soapClient->Delete(['Key' => $faculty->CP_Faculties->Key]);
        return redirect()->route('universities.index');
    }

    public function notSafeToModify($id)
    {
        $soap = new Soap('CP_Student');
        $students = $soap->ReadMultiple(0, ['Faculty_Id' => $id])->ReadMultiple_Result;
        if (isset($students->CP_Student)) {
            \Session::flash('alert', 'Stergerea nu poate fi efectuata!'); 
            \Session::flash('alert-class', 'alert-danger'); 

            return true;
        }
        \Session::flash('alert', 'Stergerea a fost efectuata cu succes!'); 
        \Session::flash('alert-class', 'alert-success'); 
        return false;
    }
}
