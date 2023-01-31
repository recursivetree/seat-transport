@extends('web::layouts.grids.12')

@section('title', "Settings")
@section('page_header', "Settings")


@section('full')
    <div class="card">
        <div class="card-body">
            <h5 class="card-header">
                Transport Costs
            </h5>
            <div class="card-text my-3 mx-3">
                <form action="{{ route("transportplugin.submitCalculate") }}" method="POST">
                    @csrf

                    <div class="form-group">
                        <label for="route">Route</label>
                        <select id="route" name="route" class="form-control">
                            @foreach($routes as $route)
                                <option value="{{$route->id}}">
                                    {{ $route->source_location()->name }} ----------> {{ $route->destination_location()->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label for="items">Items</label>
                        <textarea
                                class="form-control"
                                id="items"
                                name="items"
                                placeholder="{{"MULTIBUY:\nTristan 100\nOmen 100\nTritanium 30000\n\nFITTINGS:\n[Pacifier, 2022 Scanner]\n\nCo-Processor II\nCo-Processor II\n\nMultispectrum Shield Hardener II\nMultispectrum Shield Hardener II\n\nSmall Tractor Beam II\nSmall Tractor Beam II"}}"
                                rows="20"></textarea>
                    </div>

                    <button type="submit" class="btn btn-success form-control">Calculate</button>
                </form>
            </div>
        </div>
    </div>
@stop
@push("javascript")
    <script>
        $(document).ready( function () {
            $("#source_location").select2()
            $("#destination_location").select2()
            $('.data-table').DataTable();
        });
    </script>
@endpush