@extends('web::layouts.grids.12')

@section('title', "Settings")
@section('page_header', "Settings")


@section('full')
    <div class="card">
        <div class="card-body">
            <h5 class="card-header">
                Settings
            </h5>
            <div class="card-text my-3 mx-3">
                <form action="{{ route("transportplugin.saveRouteSettings") }}" method="POST">
                    @csrf
                    <div class="form-row">

                        <div class="form-group col-md-2">
                            <label for="source_location">Source</label>
                            <select class="form-control" id="source_location" name="source_location" required>
                                @foreach($stations as $station)
                                    <option value="{{ $station->station_id }}" >{{ $station->name }}</option>
                                @endforeach
                                @foreach($structures as $structure)
                                    <option value="{{ $structure->structure_id }}">{{ $structure->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="destination_location">Destination</label>
                            <select class="form-control" id="destination_location" name="destination_location" required>
                                @foreach($stations as $station)
                                    <option value="{{ $station->station_id }}" >{{ $station->name }}</option>
                                @endforeach
                                @foreach($structures as $structure)
                                    <option value="{{ $structure->structure_id }}">{{ $structure->name }}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group col-md-2">
                            <label for="collateral">Reward Collateral %</label>
                            <input type="number" class="form-control" id="collateral" name="collateral" required min="0" value="10">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="iskm3">Reward isk/m<sup>3</sup></label>
                            <input type="number" class="form-control" id="iskm3" name="iskm3" required min="0" value="20">
                        </div>

                        <div class="form-group col-md-2">
                            <label for="maxm3">Max m<sup>3</sup></label>
                            <input type="number" class="form-control" id="maxm3" name="maxm3" min="0" value="20">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="info_text">Info Text</label>
                        <textarea class="form-control" name="info_text" id="info_text" rows="5" placeholder="Write anything users might want to know when they see their estimate, for example how to submit the contract.">{{ $info_text }}</textarea>
                    </div>

                    <button type="submit" class="btn btn-primary form-control">Add</button>
                </form>

                <table class="table table-hover mt-4">
                    <thead>
                        <tr>
                            <th>From</th>
                            <th>To</th>
                            <th>Reward Collateral %</th>
                            <th>Reward isk/m<sup>3</sup></th>
                            <th>Max Volume m<sup>3</sup></th>
                            <th>Info Text</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($routes as $route)
                            <tr>
                                <td>
                                    {{ $route->source_location()->name }}
                                </td>
                                <td>
                                    {{ $route->destination_location()->name }}
                                </td>
                                <td>
                                    {{ $route->collateral_percentage }}%
                                </td>
                                <td>
                                    {{ $route->isk_per_m3 }}
                                </td>
                                <td>
                                    {{ $route->maxvolume }}
                                </td>
                                <td>
                                    {{$route->info_text}}
                                </td>
                                <td>
                                    <form action="{{ route("transportplugin.deleteRoute") }}" method="POST">
                                        @csrf
                                        <button type="submit" class="btn btn-danger confirmdelete">Delete</button>
                                        <input type="hidden" name="id" value="{{$route->id}}">
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
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