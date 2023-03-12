@extends('web::layouts.grids.12')

@section('title', "Transport Calculator")
@section('page_header', "Transport Calculator")


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
                                placeholder="{{"INGAME INVENTORY WINDOW: (copy paste in list view mode)\n1MN Civilian Afterburner	1	Propulsion Module			5 m3	288.88 ISK\nCivilian Gatling Railgun	5	Energy Weapon			5 m3\nCivilian Relic Analyzer		Data Miners			5 m3\n\nMULTIBUY:\nTristan 100\nOmen 100\nTritanium 30000\n\nFITTINGS:\n[Pacifier, 2022 Scanner]\n\nCo-Processor II\nCo-Processor II\n\nMultispectrum Shield Hardener II\nMultispectrum Shield Hardener II\n\nSmall Tractor Beam II\nSmall Tractor Beam II"}}"
                                rows="22"></textarea>
                    </div>

                    <div class="form-group form-check">
                        <input type="checkbox" class="form-check-input" id="rush_contract" name="rush_contract">
                        <label class="form-check-label" for="rush_contract">Rush Contract</label>
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
            $("#route").select2({
                ajax: {
                    url: "{{ route("transportplugin.routeSuggestions") }}",
                    dataType: "json"
                }
            })
            $('.data-table').DataTable();
        });
    </script>
@endpush