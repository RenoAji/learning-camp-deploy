@extends('layouts.dashboard')

{{-- JS SCRIPT --}}
@push('scripts')
    @vite('resources/js/script.js')
@endpush

@section('body')
@parent
<h2 class="text-xl font-semibold mb-4 ml-1">Create Course:</h2>
<form action="/admin-dashboard/{{$course->id}}" method="POST" class="space-y-4 w-full lg:w-3/4 p-2" id="courseForm" >
    @method('put')
    @csrf
    <label class="input input-bordered flex items-center gap-2">
        Price:
        <input type="number" class="grow" placeholder="0 or leave it empty if free" name="price" value="{{$course->price}}" />
    </label>

    <label class="input input-bordered flex items-center gap-2">
        Course Name:
        <input type="text" class="grow" placeholder="Course Name" name="name" required value="{{$course->name}}" />
    </label>

    <div>
        <label class="input flex items-center">Course Description:</label>
        <textarea class="textarea textarea-bordered textarea-lg w-full max-w-xl" id="desc" placeholder="Course Description" name="description" required maxlength="500">{{$course->description}}</textarea>
    </div>

    @foreach ($course->sections as $i=>$section)
        <div class="border-b-2 border-slate-500" data-element="section">
            <h1 class="text-xl">
                Section {{$i+1}}
                <div class="tooltip" data-tip="Delete Section">
                    <button class="btn btn-square btn-error mt-1 btn-xs" data-action="delete-section" type="button" >
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                    </button>  
                </div>
            </h1>
            <label class="input input-bordered flex items-center gap-2">
                Section Title:
                <input type="text" class="grow" placeholder="Title" name="sections[{{$i}}][title]" required value="{{$section->title}}" />
            </label>
            <label class="input flex items-center">Section Content</label>
            <input id="section-{{$i}}-content" type="hidden" name="sections[{{$i}}][content]" required value="{{$section->content}}" >
            <trix-editor input="section-{{$i}}-content"></trix-editor>
        
            
        @if (count($section->questions) > 0)
            <div class="border border-slate-400 ml-3 m-2 space-y-2 p-2" data-element="quiz">
                <h1 class="text-xl mb-2">
                    Quiz
                    <div class="tooltip" data-tip="Delete Quiz">
                        <button type="button" class="btn btn-square btn-error mt-1 btn-xs" data-action="delete-quiz">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                        </button>  
                    </div>
                </h1>
                <label class="input input-bordered flex items-center gap-2">
                    Minimum Grade:
                    <input type="number" class="grow" placeholder="minimum grade for student to pass" name="sections[{{$i}}][minimum_grade]" value="{{$section->minimum_grade}}" min=0 max=100 required />
                </label>
                <p>Check the radio button to set the correct answer</p>
            @foreach ($section->questions as $j=>$question)
                
                <div class="border-b border-black p-2" data-element="question">
                    <h1 class="text-l mb-2">
                        Question {{$j+1}}:
                        <div class="tooltip" data-tip="Delete Question">
                            <button type="button" class="btn btn-square btn-error mt-1 btn-xs" data-action="delete-question">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>  
                        </div>
                    </h1>
                    <label class="input input-bordered flex items-center gap-2">
                        Question {{$j+1}}:
                        <input type="text" class="grow" placeholder="Title" name="sections[{{$i}}][questions][{{$j}}][question]" required value="{{$question->question}}" />
                    </label>
                @foreach ($question->answers as $k=>$answer)
                    <label class="input input-bordered flex items-center gap-2" data-element="answer">
                        Answer {{$k+1}}:
                        <input type="text" class="grow" placeholder="Title" name="sections[{{$i}}][questions][{{$j}}][answers][{{$k}}][text]" required value="{{$answer->text}}" />
                        <input type="radio" name="sections[{{$i}}][questions][{{$j}}][correct]" class="radio" @checked($answer->is_correct) value={{$k}} />
                        <div class="tooltip" data-tip="Delete Answer">
                            <button type="button" class="btn btn-square btn-error mt-1 btn-xs" data-action="delete-answer">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" /></svg>
                            </button>  
                        </div>
                        <input type="hidden" name="sections[{{$i}}][questions][{{$j}}][answers][{{$k}}][answerId]" value="{{$answer->id}}">
                    </label>   
                @endforeach
                    <button class="btn btn-active btn-neutral mt-1" type="button" data-action="add-answer">
                        Add Answer
                    </button>
                    <input type="hidden" name="sections[{{$i}}][questions][{{$j}}][questionId]" value="{{$question->id}}">
                </div>
            @endforeach
                <button class="btn btn-active btn-neutral mt-1" type="button" data-action="add-question">
                    Add Question
                </button>      
            </div>
            <button class="btn btn-active btn-neutral mt-1 hidden" data-action="add-quiz" type="button">
                Add Quiz to this section
            </button> 
        @else
            <button class="btn btn-active btn-neutral mt-1" data-action="add-quiz" type="button">
                Add Quiz to this section
            </button> 
        @endif
            <input type="hidden" name="sections[{{$i}}][sectionId]" value="{{$section->id}}">
        </div>
    @endforeach

    <button class="btn btn-active btn-neutral" data-action="add-section" data-section-count={{ count($course->sections)-1 }} type="button">
        Add Section
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>      
    </button>
    <br>
    <button type="submit" class="btn btn-active btn-primary" name="published_at">Update Course</button>
@if (is_null($course->published_at))
    <button type="submit" class="btn btn-active btn-primary" name="published_at" value="{{date('Y-m-d H:i:s')}}">Update and Publish Course</button>
@endif
</form>
<a class="btn btn-active btn-error" href="/admin-dashboard">Cancel</a>

@endsection