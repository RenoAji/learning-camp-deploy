<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Enrollment;

class HomeController extends Controller
{
    function home() {
        $enrollments = Enrollment::where('user_id',auth()->user()->id)->with('course.sections')->get();
        return view('home',['enrollments'=> $enrollments]);
    }
}
