@extends('layouts.app')
@section('body')
<div class="flex min-h-full flex-col justify-center px-6 py-12 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-sm">
      <h2 class="mt-10 text-center text-2xl font-bold leading-9 tracking-tight text-gray-900">Sign in to your account</h2>
    </div>
  
    <div class="mt-10 sm:mx-auto sm:w-full sm:max-w-sm">
      <form class="space-y-6" action="" method="POST">
        @csrf

        <label class="form-control w-full max-w-xs">
            <div class="label">
              <span class="label-text">Username :</span>
            </div>
            <input type="text" class="w-full max-w-xs input input-bordered grow @error('email')input-error @enderror" placeholder="username" name="username" value="{{old('username')}}" />
        </label>
  
        <label class="form-control w-full max-w-xs">
            <div class="label">
              <span class="label-text">Email :</span>
            </div>
            <input type="email" class="w-full max-w-xs input input-bordered grow @error('email')input-error @enderror" placeholder="example.site.com" name="email" value="{{old('email')}}"/>
        </label>
  
        <label class="form-control w-full max-w-xs">
            <div class="label">
                <span class="label-text">Password :</span>
            </div>
            <input type="password" class="w-full max-w-xs input input-bordered grow @error('email')input-error @enderror" name="password" />
        </label>


        <label class="form-control w-full max-w-xs">
            <div class="label">
                <span class="label-text">Confirm Password :</span>
            </div>
            <input type="password" class="w-full max-w-xs input input-bordered grow @error('email')input-error @enderror" name="confirm_password" />
        </label>
  
        <div>
            <button class="btn btn-wide btn-primary flex justify-center m-auto" type="submit">Sign Up</button>
        </div>
      </form>
  
    <p class="mt-10 text-center text-sm text-gray-500">
        Already have an account?
        <a href="/login" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">login</a>
    </p>
    </div>
  </div>
@endsection