<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Soap\Soap;
use App\Http\Requests\StudentsRequest;
use App\Http\Requests\PasswordUpdateRequest;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class StudentsController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
        $this->middleware('admin', ['except' => ['changePassword', 'updatePassword']]);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $soap = new Soap('CP_Student');
        $students = $soap->ReadMultiple()->ReadMultiple_Result;

        if (is_array($students->CP_Student)) {
            $students = $students->CP_Student;
        }
        foreach ($students as $student) {
            $soap = new Soap('CP_User');
            $student->user = $soap->Read(['Id' => $student->User_Id])->CP_User;
        }

        return view('admin.students.index', compact('students'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $soap = new Soap('CP_Faculties');
        $faculties = $soap->ReadMultiple()->ReadMultiple_Result;
        if (is_array($faculties->CP_Faculties)) {
            $faculties = $faculties->CP_Faculties;
        }
        return view('admin.students.create', compact('faculties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StudentsRequest $request)
    {        
        $user = new User();
        $user->name = $request->first_name . " " . $request->last_name;
        $user->email = $request->email;
        $user->password = bcrypt($request->password);
        $user->admin = $request->admin == 'on' ? true : false;
        $user->save();

        $user = [
            'CP_User' => [
                'Id' => $user->id,
                'Name' => $user->name,
                'Email' => $user->email,
                'Password' => $user->password,
                'Admin' => $user->admin
            ],
        ];
        $soap = new Soap('CP_User');
        $soap->soapClient->Create($user);

        $soap = new Soap('CP_Student');
        $students = $soap->ReadMultiple()->ReadMultiple_Result;
        if (is_array($students->CP_Student)) {
            $students = $students->CP_Student;
            $id = 1;
            foreach ($students as $std) {
                if ($std->Id > $id) {
                    $id = $std->Id;
                }
            }
        } else {
            $id = $students->CP_Student->Id;
        }
        $id += 1;

        $student = [
            'CP_Student' => [
                'Id' => $id,
                'User_Id' => $user['CP_User']['Id'],
                'First_Name' => $request->first_name,
                'Last_Name' => $request->last_name,
                'Email' => $request->email,
                'Faculty_Id' => $request->faculty,
                'Year_Of_Study' => "_x003" . $request->year_of_study ."_",
                'Enrollment_Year' => $request->enrollment_year,
                'Graduation_Year' => $request->graduation_year,
            ],
        ];

        $newStd = $soap->soapClient->Create($student);

        \Session::flash('alert', 'Studentul a fost adaugat cu succes'); 
        \Session::flash('alert-class', 'alert-success'); 
        
        return redirect()->route('students.index');
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
        $soap = new Soap('CP_Student');
        $student = $soap->Read(['Id' => $id]);
        if ($student == null) {
            return abort(404);
        }
        $student = $student->CP_Student;

        $soap = new Soap('CP_User');
        $user = $soap->Read(['Id' => $student->User_Id])->CP_User;

        $soap = new Soap('CP_Faculties');
        $faculties = $soap->ReadMultiple()->ReadMultiple_Result;
        if (is_array($faculties->CP_Faculties)) {
            $faculties = $faculties->CP_Faculties;
        }

        return view('admin.students.edit', compact('user', 'student', 'faculties'));
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
        $soap = new Soap('CP_Student');
        $student = $soap->Read(['Id' => $id]);
        if ($student == null) {
            return abort(404);
        }

        $student->CP_Student->First_Name = $request->first_name;
        $student->CP_Student->Last_Name = $request->last_name;
        $student->CP_Student->Email = $request->email;
        $student->CP_Student->Faculty_Id = $request->faculty_id;
        $student->CP_Student->Year_Of_Study = "_x003" . $request->year_of_study ."_";
        $student->CP_Student->Enrollment_Year = $request->enrollment_year;
        $student->CP_Student->Graduation_Year = $request->graduation_year;

        $soap->soapClient->Update($student);
        
        $user = User::where('id', $student->CP_Student->User_Id)->first();
        $user->update([
            'name' => $request->first_name . " " . $request->last_name,
            'email' => $request->email,
            'admin' => $request->admin == 'on' ? true : false,
        ]);

        $soap = new Soap('CP_User');
        $user = $soap->Read(['Id' => $student->CP_Student->User_Id]);
        
        $user->CP_User->Name = $request->first_name . " " . $request->last_name;
        $user->CP_User->Email = $request->email;
        $user->CP_User->Admin = $request->admin == 'on' ? true : false;

        $soap->soapClient->Update($user);

        \Session::flash('alert', 'Datele studentului au fost modificate cu succes'); 
        \Session::flash('alert-class', 'alert-success'); 

        return redirect()->route('students.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $soap = new Soap('CP_Student');
        $student = $soap->Read(['Id' => $id]);
        if ($student == null) {
            return abort(404);
        }

        if ($this->notSafeToModify($id)) {
            return redirect()->route('students.index');
        }
        
        $soap->soapClient->Delete(['Key' => $student->CP_Student->Key]);
        
        $soap = new Soap('CP_User');
        $user = $soap->Read(['Id' => $student->CP_Student->User_Id]);
        $soap->soapClient->Delete(['Key' => $user->CP_User->Key]);
        
        $user = User::where('id', $student->CP_Student->User_Id)->first();
        if ($user != null) {
            $user->delete();
        }

        \Session::flash('alert', 'Studentul a fost sters cu succes!'); 
        \Session::flash('alert-class', 'alert-success'); 

        return redirect()->route('students.index');
    }

    public function notSafeToModify($id)
    {
        $soap = new Soap('CP_StudentTest');
        $studentTests = $soap->ReadMultiple(0, ['Student_Id' => $id])->ReadMultiple_Result;
        if (isset($studentTests->CP_StudentTest)) {
            \Session::flash('alert', 'Stergerea nu poate fi efectuata!'); 
            \Session::flash('alert-class', 'alert-danger'); 

            return true;
        }
        return false;
    }

    public function changePassword($id)
    {
        if (!$this->hasPermission($id)) {
            return abort(403);
        }
        $soap = new Soap('CP_User');
        $user = $soap->Read(['Id' => $id]);

        if ($user == null) {
            return abort(404);
        }

        return view('admin.students.change-password', compact('id'));
    }

    public function updatePassword(PasswordUpdateRequest $request, $id)
    {
        if (!$this->hasPermission($id)) {
            return abort(403);
        }
        $soap = new Soap('CP_User');
        $user = $soap->Read(['Id' => $id]);

        if ($user == null) {
            return abort(404);
        }

        $password = bcrypt($request->password);

        $user->CP_User->Password = $password;
        User::where('id', $id)->update([
            'password' => $password,
        ]);

        \Session::flash('alert', 'Parola a fost modificata cu succes'); 
        \Session::flash('alert-class', 'alert-success');

        if (!Auth::user()->admin) {
            return redirect()->route('home');
        }
        return redirect()->route('students.index');
    }

    public function hasPermission($id)
    {
        return Auth::user()->id == $id || Auth::user()->admin;
    }
}
