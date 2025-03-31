@extends('layouts.navbar')
@section('body')
<div class="m-4 border-b-2">
    <h2 class="text-2xl font-semibold mb-2">Admin Dashboard</h2>
    <div role="tablist" class="tabs tabs-lifted w-full sm:w-1/2 md:w-1/3">
        <a role="tab" class="tab {{Route::is('admin-dashboard.index')? 'tab-active' : ''}}" href="/admin-dashboard">Course List</a>
        <a role="tab" class="tab {{Route::is('admin-dashboard.create')? 'tab-active': ''}}" href="/admin-dashboard/create">Create New Course</a>
    </div>
</div>
@endsection