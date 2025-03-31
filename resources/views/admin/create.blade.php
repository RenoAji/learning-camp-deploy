@extends('layouts.dashboard')

{{-- JS SCRIPT --}}
@push('scripts')
    @vite('resources/js/script.js')
@endpush

@section('body')
@parent
<h2 class="text-xl font-semibold mb-4 ml-1">Create Course:</h2>
<form action="/admin-dashboard" method="POST" class="space-y-4 w-full lg:w-3/4 p-2" id="courseForm">
    @csrf
    <label class="input input-bordered flex items-center gap-2 max-w-sm">
        Price:
        <input type="number" class="grow" placeholder="0 or leave it empty if free" name="price"/>
    </label>

    <label class="input input-bordered flex items-center gap-2 max-w-xl">
        Course Name:
        <input type="text" class="grow" placeholder="Course Name" name="name" required/>
    </label>

    <div>
        <label class="input flex items-center">Course Description:</label>
        <textarea class="textarea textarea-bordered textarea-lg w-full max-w-xl" id="desc" placeholder="Course Description" name="description" required maxlength="500"></textarea>
    </div>

    <button class="btn btn-active btn-neutral" data-action="add-section" data-section-count=0 type="button">
        Add Section
        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="w-6 h-6">
            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
        </svg>      
    </button>
    <br>
    <button type="submit" class="btn btn-active btn-primary" id="create" name="published_at">Create Course</button>
    <button type="submit" class="btn btn-active btn-primary" id="create_publish" name="published_at" value="{{date('Y-m-d H:i:s')}}">Create and Publish Course</button>
</form>
<a class="btn btn-active btn-error" href="/admin-dashboard">Cancel</a>

@endsection