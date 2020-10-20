@extends('shopify-app::layouts.default')
@section('content')
@include('msg')
<div class="pb-5 mt-1 mb-3">
    <div class="row m-0 p-0">
        <div class="col-12 bg-white text-white p-2">
            <div class="card">
                <div class="card-header bg-white">
                    <div class="col-12 text-center pb-2 ">
                        <a class="nav-link" href="{{ url('handwrytten/view') }}">
                            <button type="button" class="btn btn-primary shadow">
                                Register Handwrytten Account
                            </button>
                        </a>
                    </div>
                    <div class="col-12 bg-dark text-white p-2">
                        <h5> Handwrytten Account Setting</h5>
                    </div>

                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Handrytten Account Name</th>
                                <th scope="col">Email/Username</th>
                                <th scope="col">Status</th>
                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($handwryttens as $handwrytten)
                            <tr>

                                <th scope="row"></th>
                                <td>{{ $handwrytten->fullname }}</td>
                                <td>{{ $handwrytten->email }}</td>
                                <td> @if($handwrytten->status == 1)
                                    Active

                                    @endif </td>


                                <td>
                                    <a class="" href="handwrytten/edit/{{$handwrytten->id}}">
                                   
                                            <button type="submit" class="btn btn-link ml-4 mt-1"> <i class="fa fa-edit fa-2x text-primary"></i> </button>
                                       
                                    </a>
                                    <div class="d-inline-block">
                                        <form action="{{ route('handwrytten.destroy', $handwrytten->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link ml-4 mt-1" id="destroyTrigger1" onclick="deleteTrigger()"> <i class="fa fa-trash-o fa-2x text-danger"></i> </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>



    <div class="row m-0 p-0">
        <div class="col-12 bg-white text-white p-2">
            <div class="card pt-3">
                <div class="card-header bg-white">
                    <div class="col-12 bg-dark text-white p-2">
                        <h5> Trigger Settings</h5>
                    </div>

                    <div class="float-left mt-4 mb-4">

                        <h5 class="text-dark"> <i class="fa fa-info-circle mr-2"></i>Create a New Triggers</h5>

                    </div>
                    <a class="nav-link" href="{{ url('trigger/createview') }}">
                        <button type="button" class="btn btn-primary shadow float-right mt-4 mb-4">
                            Create Trigger
                        </button>
                    </a>


                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">#</th>
                                <th scope="col">Trigger Name</th>

                                <th scope="col">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($triggers as $trigger)
                            <tr>
                                <th scope="row"></th>

                                <td>{{ $trigger->trigger_name }}</td>
                                <td>
                                    <button type="button" class="btn btn-danger shadow">
                                        edit
                                    </button>
                                    <div class="d-inline-block">
                                        <form action="{{ route('triggers.destroy', $trigger->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-link ml-4 mt-1" id="destroyTrigger" onclick="deleteTrigger()"> <i class="fa fa-trash-o fa-2x text-danger"></i> </button>
                                        </form>
                                    </div>

                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>


            </div>
        </div>
    </div>
</div>




@endsection

@section('scripts')
@parent

<script type="text/javascript">
    var AppBridge = window['app-bridge'];
    var actions = AppBridge.actions;
    var TitleBar = actions.TitleBar;
    var Button = actions.Button;
    var Redirect = actions.Redirect;
    var titleBarOptions = {
        title: 'Administrator',
    };
    var myTitleBar = TitleBar.create(app, titleBarOptions);
</script>




@endsection