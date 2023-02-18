@extends('web::layouts.grids.12')

@section('title', "Transport Cost")
@section('page_header', "Transport Cost")


@section('full')
    <div class="card">
        <div class="card-body">
            <h5 class="card-header">
                {{ $route->source_location()->name }} ----------> {{ $route->destination_location()->name }}
            </h5>
            <div class="card-text my-3 mx-3">
                <dl>
                    <dt>Collateral</dt>
                    <dd>{{ number($collateral,0) }} ISK</dd>
                    <dt>Volume</dt>
                    <dd>{{ number($volume) }} m<sup>3</sup></dd>
                    <dt>Reward</dt>
                    <dd>{{ number($cost, 0) }} ISK</dd>
                    <dt>Info</dt>
                    <dd>{{ $route->info_text ?? "Your administrator can put a text about how to create the contract here." }}</dd>
                </dl>
            </div>
        </div>
    </div>
@stop
@push("javascript")
    <script>

    </script>
@endpush