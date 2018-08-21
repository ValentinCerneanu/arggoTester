<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Soap\Soap;
use Carbon\Carbon;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        return view('home', compact('user'));
    }

    public function assignedTests()
    {
        $soap = new Soap('CP_Student');
        $student = $soap->ReadMultiple(1, ['User_Id' => Auth::user()->id])->ReadMultiple_Result;
        if (!empty($student->CP_Student)) {
            $student = $student->CP_Student;

            $soap = new Soap('CP_StudentTest');
            $studentTests = $soap->ReadMultiple(0, ['Student_Id' => $student->Id])->ReadMultiple_Result;
            if (isset($studentTests->CP_StudentTest)) {
                if (is_array($studentTests->CP_StudentTest)) {
                    $studentTests = $studentTests->CP_StudentTest;
                }
            } else {
                $studentTests = null;
            }
        } else {
            return abort(500);
        }
        if (!empty($studentTests)) {
            foreach ($studentTests as $studentTest) {
                
                $soap = new Soap('CP_Test');
                $test = $soap->ReadMultiple(1, ['Id' => $studentTest->Test_Id])->ReadMultiple_Result->CP_Test;

                $studentTest->Test_Obj = $test;

                // TODO: do i need this later?
                // $studentTest->pastDueDate = Carbon::now()->gt(Carbon::parse($studentTest->Due_Date)->addDay());
                $studentTest->submitted = Carbon::parse($studentTest->Submit_Date)->gte(Carbon::parse($studentTest->Assign_Date));
            }
        }
        
        return view('student.assigned-tests', compact('studentTests'));
    }

    public function assignmentWarning($id)
    {
        $soap = new Soap('CP_StudentTest');
        $studentTest = $soap->Read(['Id' => $id]);
        if ($studentTest == null) {
            return abort(404);
        }
        $studentTest = $studentTest->CP_StudentTest;

        $soap = new Soap('CP_Test');
        $test = $soap->ReadMultiple(1, ['Id' => $studentTest->Test_Id])->ReadMultiple_Result->CP_Test;

        if (Carbon::now()->gt(Carbon::parse($studentTest->Due_Date)->addDay())) {
            return redirect()->route('complete-assignment', ['id' => $studentTest->Id]);
        } else if ($test->Multiple_Submissions) {
            if (Carbon::parse($studentTest->Submit_Date)->gte(Carbon::parse($studentTest->Assign_Date))) {
                return redirect()->route('complete-assignment', ['id' => $studentTest->Id]);
            }
        } else {
            if (Carbon::parse($studentTest->Started_At)->gte(Carbon::parse($studentTest->Assign_Date))) {
                return redirect()->route('complete-assignment', ['id' => $studentTest->Id]);
            }
        }
        
        $studentTest->Test_Obj = $test;
         return view('student.assignment-time-warning', compact('id', 'studentTest'));
    }
    
    public function completeAssignment(Request $request, $id)
    {
        $soap = new Soap('CP_StudentTest');
        $studentTest = $soap->Read(['Id' => $id]);
        if ($studentTest == null) {
            return abort(404);
        }

        if (Carbon::parse($studentTest->CP_StudentTest->Started_At)->lt(Carbon::parse($studentTest->CP_StudentTest->Assign_Date))) {
            $studentTest->CP_StudentTest->Started_At = Carbon::now()->toAtomString();
            $soap->soapClient->Update($studentTest);
        }
        $studentTest = $studentTest->CP_StudentTest;

        $soap = new Soap('CP_Student');
        $student = $soap->Read(['Id' => $studentTest->Student_Id])->CP_Student;
        if($student->User_Id != Auth::user()->id) {
            return abort(403);
        }
        $soap = new Soap('CP_Test');
        $test = $soap->Read(['Id' => $studentTest->Test_Id])->CP_Test;

        if(!$test->Multiple_Submissions && !$test->Allocated_Time && Carbon::parse($studentTest->Submit_Date)->lt(Carbon::parse($studentTest->Assign_Date))) {
            
            $soap = new Soap('CP_StudentTest');
            $studentTest = $soap->Read(['Id' => $id]);
            $studentTest->CP_StudentTest->Submit_Date = Carbon::now()->toDateString();
            $soap->soapClient->Update($studentTest);
            $studentTest = $studentTest->CP_StudentTest;
            $studentTest->Submit_Date = Carbon::parse($studentTest->Assign_Date)->subMonth();
        }

        $soap = new Soap('CP_TestQuestions');
        $questions = $soap->ReadMultiple(0, ['Test_Id' => $test->Id]);
        if (is_array($questions->ReadMultiple_Result->CP_TestQuestions)) {
            $questions = $questions->ReadMultiple_Result->CP_TestQuestions;
        } else {
            $questions = $questions->ReadMultiple_Result;
        }
        foreach ($questions as $question) {
            if($question->Type != 'Text') {
                $soap = new Soap('CP_VariantAnswers');
                $question->answers = $soap->ReadMultiple(0, ['Question_Id' => $question->Id]);

                $soap = new Soap('CP_StudentTestAnswers');
                $question->answered = $soap->ReadMultiple(1, ['Student_Test_Id' => $studentTest->Id, 'Question_Id' => $question->Id])->ReadMultiple_Result->CP_StudentTestAnswers;

                
                if(isset($question->answers->ReadMultiple_Result->CP_VariantAnswers)) {
                    if (is_array($question->answers->ReadMultiple_Result->CP_VariantAnswers)) {
                        $question->answers = $question->answers->ReadMultiple_Result->CP_VariantAnswers;
                    } else {
                        $question->answers = $question->answers->ReadMultiple_Result;
                    }
                }
            } else {
                $soap = new Soap('CP_StudentTestAnswers');
                $question->answered = $soap->ReadMultiple(1, ['Student_Test_Id' => $studentTest->Id, 'Question_Id' => $question->Id])->ReadMultiple_Result->CP_StudentTestAnswers;
                if (!isset($question->answered->Question_Input)) {
                    $question->answered->Question_Input = '';
                }
            }
        }

        $pastDueDate = Carbon::now()->gt(Carbon::parse($studentTest->Due_Date)->addDay());
        $submitted = Carbon::parse($studentTest->Submit_Date)->gte(Carbon::parse($studentTest->Assign_Date));
        
        return view('student.complete-assignment', compact('studentTest', 'test', 'questions', 'pastDueDate', 'submitted', 'date', 'now'));
    }

    public function submitAssignment(Request $request, $id)
    {
        $soap = new Soap('CP_StudentTest');
        $studentTest = $soap->Read(['Id' => $id]);
        if ($studentTest == null) {
            return abort(404);
        }
        $studentTest = $studentTest->CP_StudentTest;

        if (Carbon::today()->gt(Carbon::parse($studentTest->Due_Date))) {
            return redirect()->route('complete-assignment', ['id' => $studentTest->Id]);
        }

        $soap = new Soap('CP_TestQuestions');
        $questions = $soap->ReadMultiple(0, ['Test_Id' => $studentTest->Test_Id]);
        if (is_array($questions->ReadMultiple_Result->CP_TestQuestions)) {
            $questions = $questions->ReadMultiple_Result->CP_TestQuestions;
        } else {
            $questions = $questions->ReadMultiple_Result;
        }

        foreach ($questions as $question) {
            $soap = new Soap('CP_StudentTestAnswers');
            $answer = $soap->ReadMultiple(1, ['Student_Test_Id' => $studentTest->Id, 'Question_Id' => $question->Id])->ReadMultiple_Result;
            if($question->Type != 'Text') {
                $answer->CP_StudentTestAnswers->Answer = $request[$question->Id];
                $soap->soapClient->Update($answer);
            } else {
                $answer->CP_StudentTestAnswers->Question_Input = $request[$question->Id];
                $soap->soapClient->Update($answer);
            }
        }
        
        return redirect()->route('assigned-tests');
    }

    function users()
    {
        $soap = new Soap('CP_User');
        $users = $soap->ReadMultiple()->ReadMultiple_Result;

        if (is_array($users->CP_User)) {
            $users = $users->CP_User;
        }

        return view('admin.users', compact('users'));
    }
}
