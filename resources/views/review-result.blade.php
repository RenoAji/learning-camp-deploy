@extends('layouts.app')
@section('body')
@parent

<div class="navbar bg-neutral text-neutral-content">
    <a href="/course/{{$section->course_id}}/section/{{$section->id}}" class="btn btn-ghost text-xl">
    {{$section->title}}
    </a>
</div>

<div class="stats stats-vertical lg:stats-horizontal shadow mt-3"> 
      @php
          $jumlah_soal = $section->questions->count();
          $jumlah_benar = $result->correct_answer;
          $nilai = floor($jumlah_benar/$jumlah_soal*100);
      @endphp
    <div class="stat">
      <div class="stat-title">Jumlah Soal</div>
      <div class="stat-value">{{$jumlah_soal}}</div>
    </div>
    
    <div class="stat">
      <div class="stat-title">Soal Terjawab Benar</div>
      <div class="stat-value">{{$jumlah_benar}}</div>
    </div>
    
    <div class="stat">
      <div class="stat-title">Nilai</div>
      <div class="stat-value">{{$nilai}}</div>
    </div>
    
  </div>
  
<div class="my-4 ml-5">
@foreach ($section->questions as $i => $question)
    <div class="my-4 border-b py-3">
        <h1 class="text-4xl mb-2">
            {{$question->question}}
        </h1>

        <div class="space-y-1">
            @foreach ($question->answers as $answer)
                <div class="content-center flex gap-2 p-3 border-2 w-56 rounded-md
                @if ($user_answers->contains($answer->id))
                    @if ($answer->is_correct)
                        border-success
                    @else
                        border-error
                    @endif
                @endif
                "> 
                    <h2>{{$answer->text}}</h2>        
                </div>
            @endforeach
        </div>
    </div>
@endforeach
    <a href="/course/section/{{$section->id}}" class="btn btn-primary my-5">Kembali</a>
</div>

@endsection