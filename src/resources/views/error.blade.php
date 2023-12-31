@extends('web::layouts.grids.12')

@section('title', "Transport Cost")
@section('page_header', "Transport Cost")


@section('full')
    <div class="card">
        <div class="card-body">
            <h5 class="card-header">
                Plugin not configured
            </h5>
            <div class="alert alert-danger" role="alert">
                Please ask an administrator to configure a price provider for this plugin.
            </div>
        </div>
    </div>
@stop
@push("javascript")
    <script>

    </script>
@endpush