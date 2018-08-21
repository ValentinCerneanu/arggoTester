<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Soap\Soap;

class AnswersController extends Controller
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
        $soap = new Soap('CP_VariantAnswers');
        $answers = $soap->ReadMultiple()->ReadMultiple_Result;

        if (is_array($answers->CP_VariantAnswers)) {
            $answers = $answers->CP_VariantAnswers;
        }

        return view('admin.answers.answers', compact('answers'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create($id = null)
    {
        $soap = new Soap('CP_TestQuestions');
        if ($id != null) {
            $questions = $soap->Read(['Id' => $id]);
            if ($questions == null) {
                return abort(404);
            }
        } else {
            $questions = $soap->ReadMultiple(0, ['Type' => 'Single'])->ReadMultiple_Result;
            if (is_array($questions->CP_TestQuestions)) {
                $questions = $questions->CP_TestQuestions;
            }
        }
        return view('admin.answers.create', compact('questions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $answer = [
            'CP_VariantAnswers' => [
                'Question_Id' => $request->question_id,
                'Text' => $request->text,
                'Validation' => $request->validation,
            ],
        ];
        $soap = new Soap('CP_VariantAnswers');
        $answers = $soap->ReadMultiple()->ReadMultiple_Result;
        if (is_array($answers->CP_VariantAnswers)) {
            $answers = $answers->CP_VariantAnswers;
        }

        $id = 0;
        foreach ($answers as $ans) {
            if ($ans->Id > $id) {
                $id = $ans->Id;
            }
        }
        $id += 1;
        $answer['CP_VariantAnswers']['Id'] = $id;

        $soap->soapClient->Create($answer);

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
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($answer_id, $question_id)
    {
        $soap = new Soap('CP_VariantAnswers');
        $answer = $soap->Read(['Id' => $answer_id, 'Question_Id' => $question_id]);
        if ($answer == null) {
            abort(404);
        }
        $answer = $answer->CP_VariantAnswers;

        $soap = new Soap('CP_TestQuestions');
        $questions = $soap->ReadMultiple(0, ['Type' => 'Single'])->ReadMultiple_Result;
        if (is_array($questions->CP_TestQuestions)) {
            $questions = $questions->CP_TestQuestions;
        }
        return view('admin.answers.edit', compact('answer', 'questions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $answer_id, $question_id)
    {
        $soap = new Soap('CP_VariantAnswers');
        $answer = $soap->Read(['Id' => $answer_id, 'Question_Id' => $question_id]);
        if ($answer == null) {
            abort(404);
        }

        $answer->CP_VariantAnswers->Question_Id = $request->question_id;
        $answer->CP_VariantAnswers->Text = $request->text;
        $answer->CP_VariantAnswers->Validation = $request->validation;

        $soap->soapClient->Update($answer);

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($answer_id, $question_id)
    {
        $soap = new Soap('CP_TestQuestions');
        $question = $soap->Read(['Id' => $question_id]);
        if ($question == null) {
            return abort(404);
        }
        
        if ($this->notSafeToModify($question->CP_TestQuestions->Test_Id)) {
            return redirect()->back();
        }

        $soap = new Soap('CP_VariantAnswers');
        $answer = $soap->Read(['Id' => $answer_id, 'Question_Id' => $question_id]);
        if ($answer == null) {
            abort(404);
        }
        $soap->soapClient->Delete(['Key' => $answer->CP_VariantAnswers->Key]);

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
