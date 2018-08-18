@extends('layout')

@section('title', 'Dashboard')

@section('body')
    <div class="wrapper spacing-top-large spacing-bottom-large">
        <div class="row row--gutter mb-4">
            <div class="row__column">
                <div class="box">
                    <div class="box__section">
                        <h2>{{ $currency->symbol }} {{ number_format($spendingsToday / 100, 2) }}</h2>
                        <div class="mt-1">Spent today</div>
                    </div>
                </div>
            </div>
            <div class="row__column">
                <div class="box">
                    <div class="box__section">
                        <h2>{{ $currency->symbol }} {{ number_format($spendingsMonth / 100, 2) }}</h2>
                        <div class="mt-1">Spent this month</div>
                    </div>
                </div>
            </div>
            <div class="row__column">
                <div class="box">
                    <div class="box__section">
                        <h2>{{ count($mostExpensiveTag) ? $mostExpensiveTag[0]->name : '-' }}</h2>
                        <div class="mt-1">Most expensive tag</div>
                    </div>
                </div>
            </div>
            <div class="row__column">
                <div class="box">
                    <div class="box__section">
                        <h2>{{ count($mostExpensiveWeekday) ? __('weekdays.' . $mostExpensiveWeekday[0]->weekday) : '-' }}</h2>
                        <div class="mt-1">Most expensive weekday</div>
                    </div>
                </div>
            </div>
        </div>
        @if (count($tagsBreakdown))
            <div class="box spacing-bottom-large">
                <div class="box__section">
                    <div class="ct-chart ct-major-eleventh"></div>
                </div>
            </div>
        @endif
        <div class="row gutter spacing-bottom-large">
            <div class="column">
                <div class="box">
                    <div class="box__section text-center" style="padding: 15px;">
                        <a href="/earnings">Earnings ({{ $earningsCount }}) <i class="fa fa-arrow-right"></i></a>
                    </div>
                </div>
            </div>
            <div class="column">
                <div class="box">
                    <div class="box__section text-center" style="padding: 15px;">
                        <a href="/spendings">Spendings ({{ $spendingsCount }}) <i class="fa fa-arrow-right"></i></a>
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
            labelInterpolationFnc: function (value, x) {
                return labels[x] + ' (' + Math.round(value / data.series.reduce(sum) * 100) + '%)';
            }
        });
    </script>
@endsection
