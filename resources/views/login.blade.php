@extends('layout')

@section('body')
    <div class="wrapper wrapper--narrow my-4">
        <h2 class="text-center mb-4">Log in</h2>
        @if (session('status'))
            @include('partials.status_bar', ['payload' => ['classes' => 'mb-2', 'status' => session('status')]])
        @endif
        <div class="box">
            <div class="box__section">
                <form method="POST">
                    {{ csrf_field() }}
                    <div class="input">
                        <label>E-mail</label>
                        <input type="email" name="email" value="{{ old('email') }}" />
                    </div>
                    <div class="input">
                        <label>Password</label>
                        <input type="password" name="password" />
                    </div>
                    <button>Log in</button>
                </form>
            </div>
        </div>
        <div class="mt-2 text-center">
            <a href="/register">New to Budget?</a>
        </div>
    </div>
@endsection
