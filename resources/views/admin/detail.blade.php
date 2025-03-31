@extends('layouts.dashboard')
@section('body')
   @parent
<h1 class="text-xl font-semibold mb-1 ml-1 ">Course Detail :</h1>
<div class="overflow-x-auto w-full lg:w-1/2">
    <table class="table">
        <tbody>
            <tr>
                <th>Course Name</th>
                <td>{{$course->name}}</td>
            </tr>

            <tr>
                <th>Course Description</th>
                <td>{{$course->description}}</td>
            </tr>

            <tr>
                <th>Course Price</th>
                <td>{{$course->price}}</td>
            </tr>

            <tr>
                <th>Published at</th>
                <td>
                    @if (is_null($course->published_at))
                    <h1>Unpublished</h1>                      
                    @else
                    {{$course->published_at}}
                    @endif
                </td>
            </tr>

            <tr>
                <th>Number of Sections</th>
                <td>{{count($course->sections)}}</td>
            </tr>

            <tr>
                <th>User Enrolled</th>
                <td>
                    @if (is_null($course->enrollment))
                    <h1>0</h1>                      
                    @else
                    {{count($course->enrollment)}}
                    @endif
                </td>
            </tr>
        </tbody>
    </table> 
</div>

<h1 class="text-xl font-semibold mb-1 ml-1">Sections List :</h1>
<div class="overflow-x-auto w-full lg:w-1/2">
    <table class="table">
        <tbody>
            <tr>
                <th>Chapter</th>
                <th>Section Title</th>
                <th>Section's Quiz</th>
            </tr>
            @foreach ($course->sections as $s)
            <tr>
                <td>{{$s->chapter}}</td>
                <td>{{$s->title}}</td>
                <td>
                    @if (count($s->questions)>0)
                    <div class="overflow-x-auto">
                        <table class="table">
                          <!-- head -->
                          <thead>
                            <tr>
                              <th></th>
                              <th>Question</th>
                              <th>Answers</th>
                              <th>Correct Answer</th>
                            </tr>
                          </thead>
                          <tbody>
                            @foreach ($s->questions as $i=>$q)
                            <tr>
                                <th>{{$i+1}}</th>
                                <td>{{$q->question}}</td>
                                <td>{{$q->answers->pluck('text')}}</td>
                                <td>{{$q->answers->where('is_correct',true)->value('text')}}</td>
                            </tr>
                            @endforeach
                          </tbody>
                        </table>
                      </div>
                    @else
                        -
                    @endif
                </td>
            </tr>
            @endforeach
        </tbody>
    </table> 
</div>

@endsection
