@extends('layouts.app')
@section('body')
@parent
<div class="drawer drawer-end lg:drawer-open">
    <input id="my-drawer-2" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col">
      <!-- Page content here -->
        <div class="navbar bg-neutral">
            <div class="navbar-start">
                <a class="btn btn-ghost sm:text-l md:text-xl text-neutral-content" href="/course/{{$course->id}}">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
                    </svg>
                    <h1 class="invisible sm:visible">
                        {{$course->name}}
                    </h1>          
                </a>
            </div>

            <div class="navbar-end">
                <label for="my-drawer-2" class="btn btn-neutral-content w-fit lg:hidden">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 6.75h16.5M3.75 12h16.5m-16.5 5.25h16.5" />
                    </svg>                  
                </label>
            </div>

        </div>
        <div class="text-xl p-3"> 
            <h1 class="text-2xl font-semibold text-center">{{$section->title}}</h1>
            {!! $section->content !!} 
            @if ($section->questions->count() > 0)

            <div>
                <h1 class="text-xl">Quiz</h1>
                <ul class="list-disc pl-5">
                    <li>Syarat Nilai Kelulusan : {{$section->minimum_grade}}</li>
                    <li>Jumlah Soal : {{$section->questions->count()}}</li>
                </ul>
            </div>

            <div class="text-right">
                <a class="btn btn-neutral text-xl hover:scale-105 transition m-5" href="/course/quiz/{{$section->id}}" >
                    Kerjakan Quiz                
                </a>
            </div> 
            @endif

            @if ($results->count() > 0)
                <div class="overflow-x-auto mt-5">
                    <h1 class="text-xl"> Riwayat pengerjaan quiz </h1>
                    <table class="table">
                    <!-- head -->
                    <thead>
                        <tr>
                        <th></th>
                        <th>Tanggal Pengerjaan</th>
                        <th>Jumlah Soal Terjawab Benar</th>
                        <th>Nilai</th>
                        <th>Keterangan</th>
                        <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($results as $result)                  
                        <tr>
                            <th>{{$loop->index + 1}}</th>
                            <td>{{$result->created_at}}</td>
                            <td>{{$result->correct_answer}}</td>
                            <td>
                                @php
                                    $nilai = floor($result->correct_answer / $section->questions->count() * 100)
                                @endphp
                                {{$nilai}}
                            </td>
                            <td>
                                @if ($nilai >= $section->minimum_grade)
                                <span class="badge badge-success">Lulus</span>
                                @else
                                <span class="badge badge-error">Belum Lulus</span>
                                @endif
                            </td>
                            <td><a href="/course/review/{{$result->id}}" class="btn btn-info">Review</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                    </table>
                </div>
                @endif
        </div>

                    {{-- NEXT BUTTON --}}
        @if ($course->sections->count() > $section->chapter)
        <div>
            <a class="btn btn-neutral text-xl" href="/course/section/{{$course->sections->where('chapter',$section->chapter+1)->value('id')}}" >
                {{$course->sections->where('chapter',$section->chapter+1)->value('title')}}
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                </svg>                  
            </a>
        </div>         
        @endif
                    {{-- END NEXT BUTTON --}}

                    {{-- FINISH COURSE BUTTON --}}
        @if ($course->sections->count() == $section->chapter)
        <div>
            <a class="btn btn-neutral text-xl" href="/course/finish/{{$course->id}}" >
                Finish Course
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>                    
            </a>
        </div>         
        @endif
                    {{-- END FINISH COURSE BUTTON --}}
    
    </div> 
    <div class="drawer-side">
      <label for="my-drawer-2" aria-label="close sidebar" class="drawer-overlay"></label> 
      <ul class="menu p-4 w-80 min-h-full bg-neutral border-slate-300 border text-neutral-content gap-y-1">
        <!-- Sidebar content here -->
            <li class="flex-col pb-2 content-center">
                <h1 class="text-xl">Course Progress</h1>
                <div class="flex">
                    <progress class="progress progress-success border w-56 border-slate-300" value={{$enrollment->sections_completed}} max={{$course->sections->count()}}></progress>
                    <h2>{{floor($enrollment->sections_completed / $course->sections->count() * 100)}}%</h2>
                </div>
            </li>
        @foreach ($course->sections as $s)
            <li class="border border-slate-600 rounded-md hover:bg-slate-600">
                <a href="/course/section/{{$s->id}}">{{$s->title}}
                @if ($s->chapter <= $enrollment->sections_completed)
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6 stroke-green-500">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                    </svg>
                @endif
                </a>
            </li>
        @endforeach
      </ul>  
    </div>
</div>



@endsection