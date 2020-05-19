@extends('layout')

@section('title', __('actions.edit') . ' ' . __('models.spending'))

@section('body')
    <div class="wrapper my-3">
        <h2>{{ __('actions.edit') }} {{ __('models.spending') }}</h2>
        <div class="box mt-3">
            <form method="POST" action="/spendings/{{ $spending->id }}" autocomplete="off">
                {{ method_field('PATCH') }}
                {{ csrf_field() }}
                <div class="box__section">
                    <div class="input input--small">
                        <label>Date</label>
                        <DatePicker start-date="{{ $spending->happened_on }}"></DatePicker>
                        @include('partials.validation_error', ['payload' => 'date'])
                    </div>
                    <div class="input input--small">
                        <label>Description</label>
                        <input type="text" name="description" value="{{ $spending->description }}" />
                        @include('partials.validation_error', ['payload' => 'description'])
                    </div>
                    <div class="input input--small mb-0">
                        <label>Amount</label>
                        <input type="text" name="amount" value="{{ $spending->formatted_amount }}" />
                        @include('partials.validation_error', ['payload' => 'amount'])
                    </div>
                </div>
                <div class="box__section box__section--highlight row row--right">
                    <div class="row__column row__column--compact row__column--middle">
                        <a href="/spendings">{{ __('actions.cancel') }}</a>
                    </div>
                    <div class="row__column row__column--compact ml-2">
                        <button class="button">@lang('actions.save')</button>
                    </div>
                </div>
            </form>
        </div>
    </div>
@endsection
