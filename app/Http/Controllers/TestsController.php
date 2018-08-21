<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Soap\Soap;

class TestsController extends Controller
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
        $soap = new Soap('CP_Test');
        $tests = $soap->ReadMultiple()->ReadMultiple_Result;

        if (is_array($tests->CP_Test)) {
            $tests = $tests->CP_Test;
        }

        return view('admin.tests.index', compact('tests'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.tests.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $test = [
            'CP_Test' => [
                'Title' => $request->title,
                'Description' => $request->description,
                'Category' => $request->category,
                'Multiple_Submissions' => $request->multiple_submissions,
                'Allocated_Time' => $request->allocated_time,      
            ],
        ];

        $soap = new Soap('CP_Test');
        $tests = $soap->ReadMultiple()->ReadMultiple_Result;
        if (is_array($tests->CP_Test)) {
            $tests = $tests->CP_Test;
        }
        $id = 1;
        foreach ($tests as $t) {
            $aux = explode('T', $t->Id)[1];
            if ($aux > $id) {
                $id = $aux;
            }
        }
        $id += 1;
        $test['CP_Test']['Id'] = 'T' . $id;
        $soap->soapClient->Create($test);

        return redirect()->route('tests.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        return $this->edit($id);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $soap = new Soap('CP_Test');
        $test = $soap->Read(['Id' => $id]);
        if($test == null) {
            return abort(404);
        }
        $test = $test->CP_Test;

        return view('admin.tests.edit', compact('test'));
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
        $soap = new Soap('CP_Test');
        $test = $soap->Read(['Id' => $id]);
        if($test == null) {
            return abort(404);
        }
        
        $test->CP_Test->Title = $request->title;
        $test->CP_Test->Description = $request->description;
        $test->CP_Test->Category = $request->category;
        $test->CP_Test->Multiple_Submissions = $request->multiple_submissions;
        $test->CP_Test->Allocated_Time = $request->allocated_time;

        $soap->soapClient->Update($test);

        return redirect()->route('tests.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $soap = new Soap('CP_Test');
        $test = $soap->Read(['Id' => $id]);
        if ($test == null) {
            return abort(404);
        }
        if ($this->notSafeToModify($id)) {
            return redirect()->route('tests.index');
        }

        $soap->soapClient->Delete(['Key' => $test->CP_Test->Key]);
        \Session::flash('alert', 'Stergerea a fost efectuata cu succes!'); 
        \Session::flash('alert-class', 'alert-success'); 
        return redirect()->route('tests.index');
    }

    public function notSafeToModify($id)
    {
        $soap = new Soap('CP_StudentTest');
        $studentTests = $soap->ReadMultiple(0, ['Test_Id' => $id])->ReadMultiple_Result;
        if (isset($studentTests->CP_StudentTest)) {
            \Session::flash('alert', 'Stergerea nu poate fi efectuata!'); 
            \Session::flash('alert-class', 'alert-danger'); 

            return true;
        }
        return false;
    }
}
