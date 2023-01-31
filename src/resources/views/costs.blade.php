@extends('web::layouts.grids.12')

@section('title', "Settings")
@section('page_header', "Settings")


@section('full')
    <div class="card">
        <div class="card-body">
            <h5 class="card-header">
                {{ $route->source_location()->name }} ----------> {{ $route->destination_location()->name }}
            </h5>
            <div class="card-text my-3 mx-3">
                <dl>
                    <dt>Collateral</dt>
                    <dd>{{ number($collateral) }} ISK</dd>
                    <dt>Volume</sup></dt>
                    <dd>{{ number($volume) }} m<sup>3</sup></dd>
                    <dt>Reward</dt>
                    <dd>{{ number($cost) }} ISK</dd>
                </dl>
            </div>
        </div>
    </div>
@stop
@push("javascript")
    <script>

    </script>
@endpush