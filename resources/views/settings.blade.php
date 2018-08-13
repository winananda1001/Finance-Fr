@extends('layout')

@section('title', 'Settings')

@section('body')
    <div class="wrapper spacing-top-large spacing-bottom-large">
        <div class="spacing-bottom-large color-dark">Account</div>
        <div class="box">
            <div class="section">
                <form method="POST">
                    {{ csrf_field() }}
                    <label>Name</label>
                    <input type="text" name="name" value="{{ Auth::user()->name }}" />
                    <label>E-mail</label>
                    <input type="text" name="email" value="{{ Auth::user()->email }}" />
                    <label>Language</label>
                    <select name="language">
                        @foreach ($languages as $language)
                            <option value="{{ $language }}" @if (Auth::user()->language === $language) selected @endif>{{ $language }}</option>
                        @endforeach
                    </select>
                    <button>Update</button>
                </form>
            </div>
        </div>
        <div class="spacing-top-large spacing-bottom-large color-dark">Tags</div>
        <div class="box">
            @if (count($tags))
                <ul class="box__section">
                    @foreach ($tags as $tag)
                        <li class="row">
                            <div class="row__column">{{ $tag->name }}</div>
                            <div class="row__column row__column--compact">
                                <a href="/tags/{{ $tag->id }}/edit">
                                    <i class="fa fa-pencil"></i>
                                </a>
                            </div>
                        </li>
                    @endforeach
                </ul>
            @else
                <div class="box__section text-center">You don't have any tags</div>
            @endif
            <div class="box__section">
                <form method="POST" action="/tags">
                    {{ csrf_field() }}
                    <div class="row">
                        <div class="row__column" style="max-width: 400px;">
                            <input type="text" name="name" />
                            @include('partials.validation_error', ['payload' => 'name'])
                        </div>
                        <div class="row__column row__column--compact row__column--middle ml-2">
                            <button>Submit</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
