@extends('layouts.app')
@section('navbar')
  <div class="navbar bg-neutral text-neutral-content">
    <div class="navbar-start">
      <div class="dropdown">
        <div tabindex="0" role="button" class="btn btn-neutral lg:hidden">
          <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /></svg>
        </div>
        <ul tabindex="0" class="menu menu-sm dropdown-content mt-3 z-[1] p-2 shadow bg-neutral text-neutral-content rounded-box w-52">
          <li><a href="/home">Home</a></li>
          <li><a href="/course">Course</a></li>
          @auth
            @if (auth()->user()->is_admin)
            <details>
                <summary>
                    {{auth()->user()->username}}
                </summary>
                <ul class="p-2 bg-neutral text-neutral-content rounded-t-none">
                    <li><a href="/admin-dashboard">Admin Dashboard</a></li>
                    <li>
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-error">Logout</button>
                        </form>
                    </li>
                </ul>
              </details>
            @else
            <details>
              <summary>
                  {{auth()->user()->username}}
              </summary>
              <ul class="p-2 bg-neutral text-neutral-content rounded-t-none">
                  <li>
                      <form action="/logout" method="POST">
                          @csrf
                          <button type="submit" class="btn btn-outline btn-error">Logout</button>
                      </form>
                  </li>
              </ul>
            </details>
            @endif
          @endauth

          @guest
          <details>
              <summary>
                  Guest
              </summary>
              <ul class="p-2 bg-neutral text-neutral-content rounded-t-none">
                  <li><a href="/login">Login</a></li>
              </ul>
            </details>
          @endguest
        </ul>
      </div>
      <a class="btn btn-ghost text-xl" href="/">LearningCamp</a>
    </div>
    <div class="navbar-end hidden lg:flex">
      <ul class="menu menu-horizontal px-1">
        <li><a href="/home">Home</a></li>
        <li><a href="/course">Course</a></li>
        <li>
            @auth
              @if (auth()->user()->is_admin)
              <details>
                  <summary>
                      {{auth()->user()->username}}
                  </summary>
                  <ul class="p-2 bg-neutral text-neutral-content rounded-t-none mr-10">
                      <li><a href="/admin-dashboard">Admin Dashboard</a></li>
                      <li>
                          <form action="/logout" method="POST">
                              @csrf
                              <button type="submit" class="btn btn-error">Logout</button>
                          </form>
                      </li>
                  </ul>
                </details>
              @else
              <details>
                <summary>
                    {{auth()->user()->username}}
                </summary>
                <ul class="p-2 bg-neutral text-neutral-content rounded-t-none">
                    <li>
                        <form action="/logout" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-outline btn-error">Logout</button>
                        </form>
                    </li>
                </ul>
              </details>
              @endif
            @endauth
            @guest
            <details>
                <summary>
                    Guest
                </summary>
                <ul class="p-2 bg-neutral text-neutral-content rounded-t-none">
                    <li><a href="/login">Login</a></li>
                </ul>
              </details>
            @endguest
        </li>
      </ul>
    </div>
  </div>
@endsection