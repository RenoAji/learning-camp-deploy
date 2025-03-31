@extends('layouts.navbar')
@section('body')
@parent
<div class="hero">
    <div class="hero-content flex-col lg:flex-col-reverse min-w-3/4">
      {{-- <img src="https://img.daisyui.com/images/stock/photo-1635805737707-575885ab0820.jpg" class="max-w-sm rounded-lg shadow-2xl" /> --}}
      <div class="border p-4">
        <h1 class="text-5xl font-bold">{{$course->name}}</h1>
        <p class="py-4">{{$course->description}}</p>
        <p class="flex gap-2 py-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M6 6.878V6a2.25 2.25 0 0 1 2.25-2.25h7.5A2.25 2.25 0 0 1 18 6v.878m-12 0c.235-.083.487-.128.75-.128h10.5c.263 0 .515.045.75.128m-12 0A2.25 2.25 0 0 0 4.5 9v.878m13.5-3A2.25 2.25 0 0 1 19.5 9v.878m0 0a2.246 2.246 0 0 0-.75-.128H5.25c-.263 0-.515.045-.75.128m15 0A2.25 2.25 0 0 1 21 12v6a2.25 2.25 0 0 1-2.25 2.25H5.25A2.25 2.25 0 0 1 3 18v-6c0-.98.626-1.813 1.5-2.122" />
            </svg>
            {{count($course->sections)}} module
        </p>

        <p class="flex gap-2 py-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M18 18.72a9.094 9.094 0 0 0 3.741-.479 3 3 0 0 0-4.682-2.72m.94 3.198.001.031c0 .225-.012.447-.037.666A11.944 11.944 0 0 1 12 21c-2.17 0-4.207-.576-5.963-1.584A6.062 6.062 0 0 1 6 18.719m12 0a5.971 5.971 0 0 0-.941-3.197m0 0A5.995 5.995 0 0 0 12 12.75a5.995 5.995 0 0 0-5.058 2.772m0 0a3 3 0 0 0-4.681 2.72 8.986 8.986 0 0 0 3.74.477m.94-3.197a5.971 5.971 0 0 0-.94 3.197M15 6.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0Zm6 3a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Zm-13.5 0a2.25 2.25 0 1 1-4.5 0 2.25 2.25 0 0 1 4.5 0Z" />
            </svg>
            {{count($course->enrollments)}} murid terdaftar
        </p>

        <p class="flex gap-2 py-1">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5M2.25 9h19.5m-16.5 5.25h6m-6 2.25h3m-3.75 3h15a2.25 2.25 0 0 0 2.25-2.25V6.75A2.25 2.25 0 0 0 19.5 4.5h-15a2.25 2.25 0 0 0-2.25 2.25v10.5A2.25 2.25 0 0 0 4.5 19.5Z" />
            </svg>
            Harga Course : {{$course->price}}
        </p>
        @if (!is_null($enrollment))
            <div class="p-3">
                <h2>Progress : </h2>
                <progress class="progress progress-success border w-56 border-slate-300" value={{$enrollment->sections_completed}} max={{$course->sections->count()}}></progress>
                <h2>{{floor($enrollment->sections_completed / $course->sections->count() * 100)}}%</h2>
            </div>
            @if ($enrollment->sections_completed == count($course->sections))
            Anda sudah menyelesaikan course ini
            <a class="btn btn-info mt-3" href="/course/section/{{$course->sections->first()->id}}">Review</a>
            @else
            <a class="btn btn-info mt-3" href="/course/section/{{$course->sections->where('chapter',$enrollment->sections_completed+1)->first()->id}}">Lanjutkan Belajar</a>
            @endif
        @else
            <a class="btn btn-info mt-3" href="/enroll/{{$course->id}}">Daftar & Mulai Belajar</a>
        @endif
      </div>
    </div>
</div>
<a href="/course" class="btn-neutral btn text-neutral-content ml-4">
    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
        <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
    </svg>
    Kembali ke daftar course
</a>
<p class="p-3">Note : Di Aplikasi Demo ini Anda bisa mensimulasi pembayaran dengan <a target="_blank" class="link" href="https://simulator.sandbox.midtrans.com/">Midtrans Payment Simulator</a></p>
@endsection