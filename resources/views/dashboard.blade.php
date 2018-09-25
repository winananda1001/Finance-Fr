@extends('layout')

@section('title', 'Dashboard')

@section('body')
    <div class="wrapper my-3">
        <h2>{{ __('general.dashboard') }}</h2>
        <p class="mt-1">{{ __('calendar.months.' . $month) }} {{ date('Y') }}</p>
        <div class="row row--gutter row--responsive mt-3 mb-4">
            <div class="row__column">
                <div class="card card--blue">
                    <h2 style="font-size: 20px;">{!! $currency->symbol !!} {{ number_format($totalSpendings / 100, 2) }}</h2>
                    <div class="mt-1" style="color: #A7AEBB;">{{ __('general.total_spent') }}</div>
                </div>
            </div>
            <div class="row__column">
                <!-- EMPTY -->
            </div>
            <div class="row__column">
                <!-- EMPTY -->
            </div>
        </div>
        <div class="row row--gutter row--responsive mb-4">
            <div class="row__column">
                <div class="box">
                    @if (count($recentSpendings))
                        <div class="box__section box__section--header">{{ __('general.recent') }} {{ __('general.spendings') }}</div>
                        @foreach ($recentSpendings as $spending)
                            <div class="box__section row row--seperate">
                                <div class="row__column">
                                    <div style="color: #000;">{{ $spending->description }}</div>
                                    <div style="margin-top: 10px; font-size: 14px;">{{ $spending->formatted_happened_on }}</div>
                                </div>
                                <div class="row__column row__column--compact" style="color: #000; align-self: center;">{!! $currency->symbol !!} {{ $spending->formatted_amount }}</div>
                            </div>
                        @endforeach
                    @else
                        <div class="box__section">You don't have any spendings</div>
                    @endif
                </div>
            </div>
            <div class="row__column">
                <div class="box">
                    <div class="box__section box__section--header">{{ __('general.analysis') }}</div>
                    <div class="box__section">
                        @if (count($tagsBreakdown))
                            <div class="ct-chart ct-perfect-fifth" style="max-width: 500px; margin-left: auto; margin-right: auto;"></div>
                        @else
                            Not enough data
                        @endif
                    </div>
                </div>
            </div>
        </div>
        <div class="row row--gutter row--responsive spacing-bottom-large">
            <div class="row__column">
                <div class="box">
                    <div class="box__section text-center" style="padding: 15px;">
                        <a href="/earnings">{{ __('general.earnings') }} ({{ $earningsCount }}) <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="row__column">
                <div class="box">
                    <div class="box__section text-center" style="padding: 15px;">
                        <a href="/spendings">{{ __('general.spendings') }} ({{ $spendingsCount }}) <i class="fas fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        var labels = [{!! implode(',', array_map(function ($entry) { return '\'' . $entry->name . '\''; }, $tagsBreakdown)) !!}];

        var data = {
            series: [{!! implode(',', array_map(function ($entry) { return $entry->amount; }, $tagsBreakdown)) !!}]
        };

        var sum = function(a, b) { return a + b };

        new Chartist.Pie('.ct-chart', data, {
            donut: true,
            donutWidth: 10,
            donutSolid: true,
            startAngle: 270,
            showLabel: true,

            labelInterpolationFnc: function (value, x) {
                return labels[x] + ' (' + Math.round(value / data.series.reduce(sum) * 100) + '%)';
            }
        });
    </script>
@endsection
