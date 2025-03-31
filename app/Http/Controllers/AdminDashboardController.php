<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Course;
use App\Models\Section;
use App\Models\Question;
use App\Models\Answer;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Gate;
use App\Rules\AnswerTextUnique;
use Illuminate\Database\Query\Builder;

class AdminDashboardController extends Controller
{
        /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $courses = Course::select('id','name','published_at')->get();
        return view('admin.index',['courses'=>$courses]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
            'published_at' => 'nullable|date_format:Y-m-d H:i:s',
            'price' => 'nullable|numeric|min:0',
            'name' => 'required',
            'description' => 'required',
            'sections' => 'array|min:1|required',
            'sections.*' => 'array:title,questions,content,minimum_grade',
            'sections.*.minimum_grade' => 'sometimes|required',
            'sections.*.content' => 'required|max:65500',
            'sections*.questions' => 'nullable',
            'sections.*.questions.*' => 'sometimes|required|array:answers,correct,question',
            'sections.*.*' => 'required',
            'sections.*.questions.*.correct' => 'required',
            'sections.*.questions.*.answers' =>  'array|min:2',
            'sections.*.questions.*.answers.*' => ['sometimes', new AnswerTextUnique, 'array'],
        ]);

        if ($validator->stopOnFirstFailure()->fails()) {
            $errors = $validator->errors();
            return response()->json(['message' => $errors->first()]);
        }
        $validated = $validator->validated();


        $course = Course::create([
            'name' => $validated['name'],
            'price' => is_null($validated['price'])? 0 : $validated['price'] ,
            'description' => $validated['description'],
            'published_at' => $validated['published_at'],
        ]);

        foreach($validated['sections'] as $i=>$section){
            Log::info($section);
            $section_model = Section::create([
                'chapter' => $i+1,
                'title' => $section['title'],
                'content' => $section['content'],
                'minimum_grade' => isset($section['minimum_grade'])? $section['minimum_grade'] : null,
                'course_id' => $course->id,
            ]);
            if(isset($section['questions'])){
                foreach($section['questions'] as $question){
                    $question_model = Question::create([
                        'section_id' => $section_model->id,
                        'question' => $question['question'],
                    ]);
                    foreach($question['answers'] as $j=>$answer){
                        $answer = Answer::create([
                            'question_id' => $question_model->id,
                            'text' => $answer['text'],
                            'is_correct' => $j==$question['correct'],
                        ]);
                    }
                }
            }
        }

        $request->session()->flash('message', 'Course berhasil ditambahkan');
        $request->session()->flash('alert', 'alert-success');

        // Respond with a JSON indicating a redirect
        return response()->json(['redirect' => url('/admin-dashboard')]);
        //return redirect('/admin-dashboard');
    }

    /**
     * Display the specified resource.
     */
    public function show(Course $course)
    {
        //$course->load(['sections.questions.answers', 'enrollment']);
        return view('admin.detail',['course'=>$course]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Course $course)
    {
        Gate::authorize('edit-course', $course);
        return view('admin.edit',['course'=>$course]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Course $course)
    {
        Gate::authorize('edit-course', $course);
        //$course->load(['sections.questions.answers']);
        $validator = Validator::make($request->all(),[
            'sections.*.questions.*.answers.*answerId' => 'sometimes',
            'sections.*.questions.*.questionId' => 'sometimes',
            'sections.*.sectionId' => 'sometimes',
            'published_at' => 'nullable|date_format:Y-m-d H:i:s',
            'price' => 'nullable|numeric|min:0',
            'name' => 'required',
            'description' => 'required',
            'sections' => 'array|min:1|required',
            'sections*' => 'array:title,questions,content,id,sectionId,minimum_grade',
            'sections*.questions' => 'nullable',
            'sections.*.content' => 'required|max:65500',
            'sections.*.minimum_grade' => 'sometimes|required',
            'sections.*.questions.*' => 'sometimes|array:answers,correct,question,questionId',
            'sections.*.*' => 'sometimes|required',
            'sections.*.questions.*.answers' => [new AnswerTextUnique, 'array', 'min:2'],
            'sections.*.questions.*.answers.*' => 'required|sometimes|array:text,answerId',
        ]);

        
        if ($validator->stopOnFirstFailure()->fails()) {
                $errors = $validator->errors();
                return response()->json(['message' => $errors->first()]);
            }
            
        $validated = $validator->validated();
        $data = [
            'name' => $validated['name'],
            'price' => is_null($validated['price'])? 0 : $validated['price'] ,
            'description' => $validated['description'],
        ];

        if(!is_null($validated['published_at'])){
            $data['published_at'] = $validated['published_at'];
        }

        $course->update($data);

        $sectionIds = [];
        $questionIds = [];
        $answerIds = [];

        foreach($validated['sections'] as $s){
            if(isset($s['sectionId'])){
                $sectionIds[] = $s['sectionId'];
            }
            if (isset($s['questions'])) {
                foreach($s['questions'] as $q){
                    if(isset($q['questionId'])){
                        $questionIds[] = $q['questionId'];
                    }
                    foreach($q['answers'] as $a){
                        if(isset($a['answerId'])){
                            $answerIds[] = $a['answerId'];
                        }
                    } 
                } 
            }
        }

        $sections = Section::where('course_id',$course->id)->whereNotIn('id',$sectionIds)->delete();

        //$questionIds = array_column(array_merge(...array_column($validated['sections'],'questions')),'questionId');
        $questions = Question::whereIn('section_id', function (Builder $query) use ($course) {
            $query->select('id')
                  ->from('sections')
                  ->where('course_id', $course->id);
        });
        $questions->whereNotIn('id',$questionIds)->delete();

        //$answerIds = array_column(array_merge(...array_column(array_merge(...array_column($validated['sections'],'questions')),'answers')),'answerId');

        $answers = Answer::whereIn('question_id',$questions->pluck('id'))->whereNotIn('id',$answerIds)->delete();

    foreach($validated['sections'] as $i=>$section){
        Log::info($section);
        if(isset($section['sectionId'])){
            $section_model = Section::where('id',$section['sectionId'])->first();
            $section_model->update([
                'chapter' => $i+1,
                'title' => $section['title'],
                'content' => $section['content'],
                'minimum_grade' => isset($section['minimum_grade'])? $section['minimum_grade'] : null,
            ]);
        }else{
            $section_model = Section::create([
                'chapter' => $i+1,
                'title' => $section['title'],
                'content' => $section['content'],
                'minimum_grade' => isset($section['minimum_grade'])? $section['minimum_grade'] : null,
                'course_id' => $course->id,
            ]);
        }

        if(isset($section['questions'])){
            foreach($section['questions'] as $j => $question){
                if (isset($question['questionId'])) {
                    $question_model = Question::where('id',$question['questionId'])->first();
                    $question_model->update([
                        'question' => $question['question'],
                    ]);

                }else{
                    $question_model = Question::create([
                        'section_id' => $section_model->id,
                        'question' => $question['question'],
                    ]);
                }

                foreach($question['answers'] as $k=>$answer){
                    if (isset($answer['answerId'])) {
                        $answer = Answer::where('id',$answer['answerId'])->update([
                            'text' => $answer['text'],
                            'is_correct' => $k==$question['correct'],
                        ]);
                    }else{
                        $answer = Answer::create([
                            'question_id' => $question_model->id,
                            'text' => $answer['text'],
                            'is_correct' => $k==$question['correct'],
                        ]);
                    }
                }
            }
        }
    }
 

        $request->session()->flash('message', 'Course berhasil diupdate');
        $request->session()->flash('alert', 'alert-success');
        return response()->json(['redirect' => url('/admin-dashboard')]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Course $course)
    {
        $course->delete();
        return redirect()->back();
    }
}
