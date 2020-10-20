@extends('shopify-app::layouts.default')

@section('content')
<div class="card">

    <div class="card-body">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-md-9">
                    <div class="card">
                        <div class="card-header">
                            <h5>Register Existing Handwrytten Account</h5>
                        </div>
                        <div class="card-body">
                            @include('msg')
                            <form action="{{ route('handwrytten.update', $handwryttens->id)}}" method="POST" class="form-sm">
                                @csrf
                                @method('PUT')
                                <div class="form-group">
                                    <label for="accountName">Account Name</label>
                                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" id="accountName" value="{{$handwryttens->name}}" aria-describedby="accountName" required>
                                </div>

                                <div class="form-group">
                                    <label for="exampleInputEmail1">Email address</label>
                                    <p>{{$handwryttens->email}}</p>
                                </div>

                                <div class="form-group row">
                                    <label for="status" class="col-sm-4 col-form-label">status:</label>
                                    <div class="col-sm-5">
                                        <select class="custom-select" name="status" id="status">
                                         
                                            <option value="1" @if($handwryttens->status == 1)
                                                selected

                                                @endif >Active</option>
                                            <option value="0" @if($handwryttens->status == 0)
                                                selected

                                                @endif>Inactive</option>
                                        </select>
                                    </div>
                                </div>





                                <button type="submit" class="btn btn-primary" id="submitLoginId" onclick="submitLogin()">Save</button>
                            </form>
                        </div>
                    </div>
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
        title: 'Configuration',
    };
    var myTitleBar = TitleBar.create(app, titleBarOptions);
</script>

<script>
    function submitLogin() {
        document.getElementById("submitLoginId").innerHTML = "<i class='fa fa-spinner fa-spin'></i> Saving Please wait";
    }
</script>
@endsection