<!doctype html>
<html>
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Learning Camp</title>
  @vite(['resources/css/app.css'])
  @stack('scripts')
  <link rel="stylesheet" type="text/css" href="https://unpkg.com/trix@2.0.8/dist/trix.css">
  <script type="text/javascript" src="https://unpkg.com/trix@2.0.8/dist/trix.umd.min.js"></script>
</head>
<body>
    @if ($errors->any())
      <div role="alert" class="alert alert-error">
        <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z" /></svg>
        <span>{{$errors->first()}}</span>
      </div>
    @endif

          
    @if (session('message') && session('alert')=='alert-warning')
    <div role="alert" class="alert alert-warning">
      <span>{{session('message')}}</span>
    </div>
    @endif

    @if (session('message') && session('alert')=='alert-success')
    <div role="alert" class="alert alert-success">
      <span>{{session('message')}}</span>
    </div>
    @endif


    @yield('navbar')
    
    @yield('body')
</body>
</html>