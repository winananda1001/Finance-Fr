@extends('settings.layout')

@section('settings_title')
    <h2 class="mb-3">{{ __('general.preferences') }}</h2>
@endsection

@section('settings_body')
    <div class="box">
        <div class="box__section">
            <div class="input input--small">
                <label>{{ __('fields.language') }}</label>
                <searchable
                    name="language"
                    :items='@json($languages)'
                    initial="{{ Auth::user()->language }}"></searchable>
                @include('partials.validation_error', ['payload' => 'language'])
            </div>
            <div class="input input--small">
                <label>{{ __('fields.theme') }}</label>
                <select name="theme">
                    <option value="light" {{ Auth::user()->theme == 'light' ? 'selected' : '' }}>Light</option>
                    <option value="dark" {{ Auth::user()->theme == 'dark' ? 'selected' : '' }}>Dark (Experimental)</option>
                </select>
                @include('partials.validation_error', ['payload' => 'theme'])
            </div>
            <div class="input input--small">
                <label>{{ __('fields.weekly_report') }}</label>
                <div>
                    <input type="radio" name="weekly_report" value="true" {{ Auth::user()->weekly_report ? 'checked' : '' }} /> {{ __('actions.yes') }}
                </div>
                <div>
                    <input type="radio" name="weekly_report" value="false" {{ Auth::user()->weekly_report ? '' : 'checked' }} /> {{ __('actions.no') }}
                </div>
                @include('partials.validation_error', ['payload' => 'weekly_report'])
            </div>
            <button class="button">{{ __('actions.save') }}</button>
        </div>
    </div>
@endsection
