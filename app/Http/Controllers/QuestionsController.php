<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Soap\Soap;

class QuestionsController extends Controller
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
    public function index($id = null)
    {
        $soap = new Soap('CP_TestQuestions');
        if ($id == null) {
            $check = false;
            $questions = $soap->ReadMultiple()->ReadMultiple_Result;
        } else {
            $questions = $soap->ReadMultiple(0, ['Test_Id' => $id])->ReadMultiple_Result;
            if (empty($questions->CP_TestQuestions)) {
                return redirect()->back();
            }
            $check = true;
        }

        if (is_array($questions->CP_TestQuestions)) {
            $questions = $questions->CP_TestQuestions;
        }
        
        foreach ($questions as $question) {
            $qst = $question;
            if($question->Type != 'Text') {
                $soap = new Soap('CP_VariantAnswers');
                $question->answers = $soap->ReadMultiple(0, ['Question_Id' => $question->Id])->ReadMultiple_Result;
                if(isset($question->answers->CP_VariantAnswers)) {
                    if (is_array($question->answers->CP_VariantAnswers)) {
                        $question->answers = $question->answers->CP_VariantAnswers;
                    }
                }
            }
            $soap = new Soap('CP_Test');
            $question->test = $soap->Read(['Id' => $question->Test_Id])->CP_Test->Title;
        }

        return view('admin.questions.index', compact('questions', 'qst', 'check', 'id'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $soap = new Soap('CP_Test');
        if ($id != null) {
            $tests = $soap->Read(['Id' => $id]);
            if ($tests == null) {
                return abort(404);
            }
        } else {
            $tests = $soap->ReadMultiple()->ReadMultiple_Result;
            if (is_array($tests->CP_Test)) {
                $tests = $tests->CP_Test;
            }
        }
        return view('admin.questions.create', compact('tests'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $question = [
            'CP_TestQuestions' => [
                'Test_Id' => $request->test_id,
                'Text' => $request->text,
                'Type' => $request->type,
            ],
        ];
        $soap = new Soap('CP_TestQuestions');
        $questions = $soap->ReadMultiple()->ReadMultiple_Result;
        if (isset($questions->CP_TestQuestions)) {
            if (is_array($questions->CP_TestQuestions)) {
                $questions = $questions->CP_TestQuestions;
            }
        }
        $id = 0;
        foreach ($questions as $q) {
            $aux = explode('Q', $q->Id)[1];
            if ($aux > $id) {
                $id = $aux;
            }
        }
        $id += 1;
        $question['CP_TestQuestions']['Id'] = 'Q' . $id;

        $soap->soapClient->Create($question);

        return redirect()->back();
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
        $soap = new Soap('CP_TestQuestions');
        $question = $soap->Read(['Id' => $id]);
        if ($question == null) {
            return abort(404);
        }
        $question = $question->CP_TestQuestions;
        
        $soap = new Soap('CP_Test');
        $tests = $soap->ReadMultiple()->ReadMultiple_Result;
        if (is_array($tests->CP_Test)) {
            $tests = $tests->CP_Test;
        }

        return view('admin.questions.edit', compact('question', 'tests'));
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
        $soap = new Soap('CP_TestQuestions');
        $question = $soap->Read(['Id' => $id]);
        if ($question == null) {
            return abort(404);
        }

        $question->CP_TestQuestions->Test_Id = $request->test_id;
        $question->CP_TestQuestions->Text = $request->text;
        $question->CP_TestQuestions->Type = $request->type;

        $soap->soapClient->Update($question);

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
        $soap = new Soap('CP_TestQuestions');
        $question = $soap->Read(['Id' => $id]);
        if ($question == null) {
            return abort(404);
        }
        
        if ($this->notSafeToModify($question->CP_TestQuestions->Test_Id)) {
            return redirect()->back();
        }

        $soap->soapClient->Delete(['Key' => $question->CP_TestQuestions->Key]);
        \Session::flash('alert', 'Stergere efectuata cu succes!'); 
        \Session::flash('alert-class', 'alert-success'); 

        return redirect()->back();
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
