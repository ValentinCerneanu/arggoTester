<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Soap\Soap;

class CategoryController extends Controller
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

        $soap = new Soap('Parametrizare_categories');
        $categories = $soap->ReadMultiple()->ReadMultiple_Result;

        if (is_array($categories->Parametrizare_categories)) {
            $categories = $categories->Parametrizare_categories;
        }
        //die(var_dump($categories));

        return view('admin.categories.index', compact('categories'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin.categories.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $category = [
            'Parametrizare_categories' => [
                'Category' => $request->name   
            ],
        ];
        
        $soap = new Soap('Parametrizare_categories');
        $soap->soapClient->Create($category);

        return redirect()->route('categories.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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

        return redirect()->route('admin.category.index');
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
            return redirect()->route('admin.category.index');
        }

        $soap->soapClient->Delete(['Key' => $test->CP_Test->Key]);
        \Session::flash('alert', 'Stergerea a fost efectuata cu succes!'); 
        \Session::flash('alert-class', 'alert-success'); 
        return redirect()->route('admin.category.index');
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
