<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

// Route::get('/', 'MainController@getDataFromPostServices');

Route::get('/', function() {
    return redirect(route('home'));
});

Auth::routes();

Route::get('/home', 'HomeController@index')->name('home');
Route::get('/assigned-tests', 'HomeController@assignedTests')->middleware('student')->name('assigned-tests');
Route::get('/assignment/{id}', 'HomeController@completeAssignment')->middleware('student')->name('complete-assignment');
Route::post('/submit-assignment/{id}', 'HomeController@submitAssignment')->middleware('student')->name('submit-assignment');
Route::get('/assignment-warning/{id}', 'HomeController@assignmentWarning')->middleware('student')->name('assignment-warning');


Route::get('/universities', 'HomeController@universities')->middleware('admin')->name('universities');
Route::get('/faculties', 'HomeController@faculties')->middleware('admin')->name('faculties');
Route::get('/students', 'HomeController@students')->middleware('admin')->name('students');
Route::get('/tests', 'HomeController@tests')->middleware('admin')->name('tests');
Route::get('/questions', 'HomeController@questions')->middleware('admin')->name('questions');
Route::get('/answers', 'HomeController@answers')->middleware('admin')->name('answers');
Route::get('/assignments', 'HomeController@assignments')->middleware('admin')->name('assignments');
Route::get('/users', 'HomeController@users')->middleware('admin')->name('users');

Route::get('/categories', 'HomeController@users')->middleware('admin')->name('categories');

Route::resources([
    'universities' => 'UniversitiesController',
    'faculties' => 'FacultiesController',
    'students' => 'StudentsController',
    'categories' => 'CategoryController',
    'tests' => 'TestsController',
    'questions' => 'QuestionsController',
    'answers' => 'AnswersController',
    'assignments' => 'AssignmentsController'
]);

Route::get('/answers/{answer_id}/{question_id}/edit', 'AnswersController@edit')->name('answers.edit');
Route::put('/answers/{answer_id}/{question_id}/edit', 'AnswersController@update')->name('answers.update');
Route::delete('/answer/{answer_id}/{question_id}', 'AnswersController@destroy')->name('answers.destroy');

Route::get('/change-password/{id}', 'StudentsController@changePassword')->name('change-password');
Route::post('/update-password/{id}', 'StudentsController@updatePassword')->name('update-password');

Route::post('/grade-assignment/{id}', 'AssignmentsController@gradeAssignment')->name('assignments.grade');

Route::get('/test-questions/{test_id}', 'QuestionsController@index')->name('questions.for-test');
Route::get('/questions/create/{test_id?}', 'QuestionsController@create')->name('questions.create');
Route::get('/answers/create/{question_id?}', 'AnswersController@create')->name('answers.create');
Route::get('/faculties/create/{university_id?}', 'FacultiesController@create')->name('faculties.create');