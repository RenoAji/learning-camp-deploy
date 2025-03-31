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
      

        <div class="w-1/2">
          <label class="label cursor-pointer gap-2 justify-normal">
            <span class="label-text">Remember me</span> 
            <input type="checkbox" checked="checked" class="checkbox" />
          </label>
        </div>
  
        <div class="flex justify-center">
          <button type="submit" class="btn btn-wide btn-primary">Sign in</button>
        </div>
      </form>
      <div class="flex mt-5 justify-center">
        <a href="/" class="btn btn-outline">Lanjut Sebagai Guest</a>
      </div>
  
    <p class="mt-10 text-center text-sm text-gray-500">
        Doesn't have an account?
        <a href="/register" class="font-semibold leading-6 text-indigo-600 hover:text-indigo-500">register</a>
    </p>

    </div>
  </div>
@endsection