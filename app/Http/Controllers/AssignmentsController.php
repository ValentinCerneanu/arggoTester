<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Soap\Soap;
use App\Models\User;

class AssignmentsController extends Controller
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
        $soap = new Soap('CP_StudentTest');
        $assignments = $soap->ReadMultiple()->ReadMultiple_Result;

        if (is_array($assignments->CP_StudentTest)) {
            $assignments = $assignments->CP_StudentTest;
        }
        
        foreach ($assignments as $assignment) {
            $soap = new Soap('CP_Students');
            $student = $soap->Read(['Id' => $assignment->Student_Id]);
            $assignment->Student_Email = strtolower($student->CP_Students->Email);
        }
        return view('admin.assignments.index', compact('assignments'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $soap = new Soap('CP_Students');
        $students = $soap->ReadMultiple()->ReadMultiple_Result;
        if (isset($students->CP_Students)) {
            if (is_array($students->CP_Students)) {
                $students = $students->CP_Students;
            }
        }

        $soap = new Soap('CP_Test');
        $tests = $soap->ReadMultiple()->ReadMultiple_Result;
        if (isset($tests->CP_Test)) {
            if (is_array($tests->CP_Test)) {
                $tests = $tests->CP_Test;
            }
        }

        return view('admin.assignments.create', compact('students', 'tests'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $assignment = [
            'CP_StudentTest' => [
                'Student_Id' => $request->student_id,
                'Test_Id' => $request->test_id,
                'Due_Date' => $request->due_date,
            ],
        ];
        
        $soap = new Soap('CP_StudentTest');
        $assignments = $soap->ReadMultiple()->ReadMultiple_Result;
        if (isset($assignments->CP_StudentTest)) {
            if (is_array($assignments->CP_StudentTest)) {
                $assignments = $assignments->CP_StudentTest;
            }
        }
        $id = 0;

        foreach ($assignments as $assign) {
            $assignId = explode('ST', $assign->Id)[1];
            if($assignId > $id) {
                $id = $assignId;
            }
        }
        $id += 1;

        $assignment['CP_StudentTest']['Id'] = 'ST' . $id;

        $soap->soapClient->Create($assignment);

        return redirect()->route('assignments.index');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $soap = new Soap('CP_StudentTest');
        $assignment = $soap->Read(['Id' => $id]);
        if ($assignment == null) {
            return abort(404);
        }
        $assignment = $assignment->CP_StudentTest;

        $soap = new Soap('CP_StudentTestAnswers');
        $answers = $soap->ReadMultiple(0, ['Student_Test_Id' => $id])->ReadMultiple_Result;
        if (isset($answers->CP_StudentTestAnswers)) {
            if (is_array($answers->CP_StudentTestAnswers)) {
                $answers = $answers->CP_StudentTestAnswers;
            }
        }

        $soap = new Soap('CP_VariantAnswers');
        $options = $soap->ReadMultiple()->ReadMultiple_Result;
        if (isset($options->CP_VariantAnswers)) {
            if (is_array($options->CP_VariantAnswers)) {
                $options = $options->CP_VariantAnswers;
            }
        }

        foreach ($answers as $answer) {
            foreach ($options as $option) {
                if (isset($answer->Answer)) {
                    if ($answer->Question_Id == $option->Question_Id && $answer->Answer == $option->Id) {
                        $answer->Text = $option->Text;
                        $answer->Validation = $option->Validation;
                    }
                }
            }
        }
        
        // dd($assignment, $answers, $options);

        return view('admin.assignments.view', compact('assignment', 'answers'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        return redirect()->back();
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
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $soap = new Soap('CP_StudentTest');
        $assignment = $soap->Read(['Id' => $id]);
        if ($assignment == null) {
            return abort(404);
        }
        if ($this->notSafeToModify($id)) {
            return redirect()->back();
        }
        
        $soap->soapClient->Delete(['Key' => $assignment->CP_StudentTest->Key]);
        \Session::flash('alert', 'Stergere efectuata cu succes!'); 
        \Session::flash('alert-class', 'alert-success');

        return redirect()->back();
    }

    public function gradeAssignment(Request $request, $id)
    {
        $soap = new Soap('CP_StudentTest');
        $assignment = $soap->Read(['Id' => $id]);
        if ($assignment == null) {
            return abort(404);
        }
        $assignment->CP_StudentTest->Grade = $request->grade;
        $assignment->CP_StudentTest->Status = $request->status;
        $soap->soapClient->Update($assignment);

        return redirect()->route('assignments.index');
    }

    public function notSafeToModify($id)
    {
        $soap = new Soap('CP_StudentTestAnswers');
        $answers = $soap->ReadMultiple(0, ['Student_Test_Id' => $id])->ReadMultiple_Result;
        if (isset($answers->CP_StudentTestAnswers)) {
            if (is_array($answers->CP_StudentTestAnswers)) {
                $answers = $answers->CP_StudentTestAnswers;
            }
        }
        foreach ($answers as $answer) {
            if (isset($answer->Answer) || isset($answer->Question_Input)) {
                \Session::flash('alert', 'Stergerea nu poate fi efectuata!'); 
                \Session::flash('alert-class', 'alert-danger'); 
                
                return true;
            }
        }
        return false;
    }
}
