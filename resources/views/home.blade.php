@extends('layouts.navbar')
@section('body')
    @parent
    <h1 class="text-2xl m-3">Course Yang Telah Terdaftar</h1>
    <div class="p-2 border m-4 flex">
        @foreach ($enrollments as $enrollment)
        <div class="card w-96 bg-base-100 shadow-xl border">
            <div class="card-body">
                <h2 class="card-title">{{$enrollment->course->name}}</h2>
                <p>{{$enrollment->course->description}}</p>
                <div class="p-3">
                    <h2>Progress : </h2>
                    <progress class="progress progress-success border w-56 border-slate-300" value={{$enrollment->sections_completed}} max={{$enrollment->course->sections->count()}}></progress>
                    <h2>{{floor($enrollment->sections_completed / $enrollment->course->sections->count() * 100)}}%</h2>
                </div>
                <div class="card-actions justify-end">
                    <a class="btn btn-primary" href="/course/{{$enrollment->course_id}}">Detail</a>
                </div>
            </div>
        </div>
        @endforeach
    </div>
@endsection