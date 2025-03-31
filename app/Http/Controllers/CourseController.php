<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Section;
use App\Models\Result;
use App\Models\Answer;
use App\Models\Enrollment;
use App\Models\UserAnswer;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;

class CourseController extends Controller
{

    function index(){ //show all course
        $courses = Course::whereNotNull('published_at')->with(['enrollments','sections'])->get();
        return view('course',['courses' => $courses]);
    }

    function show(Course $course){ //show specified course detail
        if (Auth::check()) {
            $enrollment = $course->enrollments->where('user_id',auth()->user()->id)->first();
        }else{
            $enrollment = null;
        }
        return view('course-detail',[
                                    'course' => $course->load('sections'), 
                                    'enrollment'=> $enrollment]);
    }

    function learn(Request $request ,Section $section){ //learn course, access specified section content
        $course = Course::where('id', $section->course_id)->first();
        if (! Gate::allows('learn-course', $course)) {
            return redirect()->back();
        }
        $previous_section = Section::where('chapter', $section->chapter-1)->where('course_id', $section->course_id)->first();
        $enrollment = Enrollment::where('course_id', $course->id)->where('user_id', auth()->user()->id);
        $results = Result::where('section_id', $section->id)->where('user_id', auth()->user()->id)->get();

        if($enrollment->first()->sections_completed == $section->chapter-2){
            if($previous_section->questions->count() > 0 && $previous_section->results->where('correct_answer', '>=', $section->minimum_grade/100 * $section->questions->count())->count()==0){
                $request->session()->flash('message', 'Selesaikan Quiz Sebelum Melanjutkan');
                $request->session()->flash('alert', 'alert-warning');
                return redirect()->back();
            }
            $enrollment->increment('sections_completed');
        }

        if($enrollment->first()->sections_completed < $section->chapter-2){
            $request->session()->flash('message', 'Selesaikan Section Sebelumnya Terlebih Dahulu');
            $request->session()->flash('alert', 'alert-warning');
            return redirect()->back();
        }
        return view('learn',['course' => $course, 'section' => $section, 'enrollment' => $enrollment->first(), 'results'=>$results]);
    }

    function quiz(Section $section){ //access quiz
        $section->load('questions.answers');
        return view('quiz',['section' => $section]);
    }

    function submitQuiz(Request $request){ //submit quiz and generate result
        $validated = $request->validate([
            'section_id' => 'required',
            'answers' => 'required|array',
            'answers.*' => 'required',
        ]);

        $section = Section::where('id',$validated['section_id'])->first();
        $correct = Answer::whereIn('id',$validated['answers'])->where('is_correct', true)->count();
        $result_id = Result::insertGetId(['correct_answer' => $correct,
                        'section_id' => $validated['section_id'],
                        'user_id' => auth()->user()->id,
                        'enrollment_id' => Enrollment::where('course_id', $section->course_id)->where('user_id',auth()->user()->id)->value('id'),
                        'created_at' => date('Y-m-d H:i:s'),
                        'updated_at' => date('Y-m-d H:i:s'),]);

        $records = [];
        foreach($validated['answers'] as $answer){
            $records[] = [
                'user_id' => auth()->user()->id,
                'answer_id' => $answer,
                'result_id' => $result_id,
                'created_at' => date('Y-m-d H:i:s'),
                'updated_at' => date('Y-m-d H:i:s'),
            ];
        }

        UserAnswer::insert($records);

        return redirect('course/section/'.$validated['section_id']);
    }

    function result(Result $result){
        $section = $result->section->load('questions.answers');
        return view('review-result', ['section' => $section, 'result' => $result, 'user_answers' => $result->user_answers->pluck('answer_id')]);
    }

    function finish(Request $request ,Course $course) { //Finish Course
        $enrollment = Enrollment::where('course_id', $course->id)->where('user_id',auth()->user()->id)->first();
        $section = Section::where('course_id', $course->id)->orderBy('chapter','desc')->with('questions')->first(); //Last Section in this course
        if ($enrollment->sections_completed !== $course->sections->count()-1) {
            $request->session()->flash('message', 'Selesaikan Section Sebelumnya');
            $request->session()->flash('alert', 'alert-warning');
            return redirect()->back();
        }

        $result = Result::where('section_id', $section->id)->where('user_id', auth()->user()->id)->where('correct_answer', '>=', $section->minimum_grade/100 * $section->questions->count());
        if($section->questions->count() > 0 && $result->doesntExist()){
            $request->session()->flash('message', 'Selesaikan Quiz Sebelum Melanjutkan');
            $request->session()->flash('alert', 'alert-warning');
            return redirect()->back();
        }



        $enrollment->increment('sections_completed');
        return redirect("course/$course->id");
    }

}
