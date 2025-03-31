@extends('layouts.app')
@section('body')
<div class="hero min-h-screen bg-base-200">
    <div class="hero-content text-center">
      <div class="max-w-md">
        <h1 class="text-5xl font-bold">Verifikasi akun anda</h1>
        <p class="py-6">Kami telah mengirim link verifikasi ke email anda</p>
        <form action="/email/verification-notification" method="POST">
            <button class="btn btn-primary" type="submit">Kirim Ulang Email</button>
        </form>
        <p class="py-6">sudah? <a href="/login">silahkan login</a></p>
      </div>
    </div>
</div>
@endsection