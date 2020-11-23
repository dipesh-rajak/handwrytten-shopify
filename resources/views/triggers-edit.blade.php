@extends('shopify-app::layouts.default')

@section('content')
<div class="pb-5 mt-1">
    <div class="row m-0 p-0">
        <div class="col-12">
            <div class="card pt-3 pb-5">

                <div class="card-body">
                    @include('msg')
                    <form action="{{ route('trigger.update', $trigger->id) }}" method="POST" enctype="multipart/form-data" class="d-inline-block float-left" style="width:100%">

                        @csrf
                        @method('PUT')
                        <div class="card">
                            <div class="card-header bg-white">
                                <div class="float-left">
                                    <p class="font-weight-bold">{{ $trigger->trigger_name }}</p>
                                </div>
                                <div class="float-right">




                                    <div class="mt-3">
                                        <div class="custom-control custom-switch">
                                            <input type="checkbox" name="trigger_status" class="custom-control-input" id="trigger_status" value="{{ $trigger->trigger_status}}" @if($trigger->trigger_status == 1) checked @endif onclick="triggeerStatus()">
                                            <label class="custom-control-label" for="trigger_status" id="triggerStatus"> @if($trigger->trigger_status == 1) Enable @else Disable @endif</label>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="card-body">
                                <div class="row">



                                    <div class="col-4">
                                        <div class="form-group row">

                                            <div class="col-sm-12">
                                                <label for="trigger_card" class="col-form-label">Card:</label>
                                                @if($trigger->trigger_card == null)
                                                <img src="{{ asset('img/download.png') }}" class="cardImg" alt="Card Image" width="150" height="150" style="object-fit: contain;">
                                                @else
                                                <img src="{{ $trigger->trigger_card }}" class="cardImg" alt="Card Image" width="150" height="150" style="object-fit: contain;">
                                                @endif
                                                <div class="text-center ml-5">
                                                    <input type="hidden" name="card_id" value="{{ $trigger->card_id}}" class="cardId">
                                                    <input type="hidden" name="trigger_card" value="" class="cardInputFile">
                                                    <input type="hidden" name="old_trigger_card" value="{{ $trigger->trigger_card }}" class="cardInputFile">
                                                    {{-- <label for="files" class="text-primary" style="text-decoration:underline;cursor: pointer;">Change</label>
                                                    <input type="file"name="trigger_card" id="files" style="visibility:hidden;">
                                                    <input type="hidden"name="old_trigger_card" value="{{ $trigger->trigger_card}}"> --}}

                                                    <!-- Modal -->
                                                    <!-- <div class="modal-dialog modal-dialog-scrollable">
                                                        <div class="modal fade" id="triggerCard" tabindex="-1" aria-labelledby="triggerCardLabel" aria-hidden="true">
                                                            <div class="modal-dialog">
                                                                <div class="modal-content">
                                                                    <div class="modal-header">
                                                                        <h5 class="modal-title" id="triggerCardLabel">Select Category</h5>
                                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                            <span aria-hidden="true">&times;</span>
                                                                        </button>
                                                                    </div>
                                                                    <div class="modal-body pb-5">
                                                                        <div class="container pb-5">
                                                                            <select class="custom-select" name="category" id="category_id" onchange="categoryFun()">
                                                                                <option value="" selected>Please Select</option>
                                                                                @foreach($category->categories as $data)
                                                                                <option value="{{ $data->id}}">{{ $data->name}}</option>
                                                                                @endforeach
                                                                            </select>
                                                                            <div id="overlay">
                                                                                <div class="cv-spinner">
                                                                                    <span class="spinner"></span>
                                                                                </div>
                                                                            </div>
                                                                            <div id="cardInfo" class="container-fluid mt-5">

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                    <div class="modal-footer">
                                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                        <button type="button" class="btn btn-primary" data-dismiss="modal">Save Changes</button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div> -->

                                                </div>
                                            </div>
                                            <div class="col-md-12">
                                                <p class="modal-title font-weight-bold" id="triggerCardLabel" style="    font-size: 11px;">Select a Category from the dropdownlist to browse cards</p>
                                                <select class="custom-select" name="category" id="category_id" onchange="categoryFun()">
                                                    <option value="" selected>Please Select</option>
                                                    @foreach($category->categories as $data)
                                                    <option value="{{ $data->id}}">{{ $data->name}}</option>
                                                    @endforeach
                                                </select>
                                                <div id="overlay">
                                                    <div class="cv-spinner">
                                                        <span class="spinner"></span>
                                                    </div>
                                                </div>

                                                <div class="modal-dialog modal-dialog-scrollable">
                                                    <div class="modal fade" id="triggerCard" tabindex="-1" aria-labelledby="triggerCardLabel" aria-hidden="true">
                                                        <div class="modal-dialog">
                                                            <div class="modal-content">
                                                                <div class="modal-header">
                                                                    <h5 class="modal-title" id="triggerCardLabel">Select Card</h5>
                                                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                                        <span aria-hidden="true">&times;</span>
                                                                    </button>
                                                                </div>
                                                                <div class="modal-body pb-5">
                                                                    <div class="container pb-5">

                                                                        <div id="cardInfo" class="container-fluid mt-5">

                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="modal-footer">
                                                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                                    <button type="button" class="btn btn-primary" data-dismiss="modal">Save Changes</button>
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </div>
                                            </div>

                                        </div>
                                    </div>




                                    <div class="col-4">
                                        <div class="form-group row">
                                            <label for="trigger_message" class="col-sm-4 col-form-label">Message:</label>
                                            <div class="col-sm-8 pl-0">
                                                <textarea name="trigger_message" class="form-control" id="trigger_message" cols="24" rows="4" placeholder="Enter a message">{{ old('trigger_message') ?? $trigger->trigger_message }}</textarea>
                                            </div>
                                        </div>
                                        <div class="form-group row">
                                            <label for="trigger_signoff" class="col-sm-4 col-form-label">Sign Off:</label>
                                            <div class="col-sm-8">
                                                <textarea name="trigger_signoff" class="float-right mr-3 form-control" id="trigger_signoff" cols="15" rows="3" placeholder="Enter a message">{{ old('trigger_signoff') ?? $trigger->trigger_signoff }}</textarea>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-4">
                                        <div class="form-group row">
                                            <label for="trigger_handwriting_style" class="col-sm-4 col-form-label">Handwriting Style</label>
                                            <div class="col-sm-8">
                                                <select class="custom-select" name="trigger_handwriting_style" id="trigger_handwriting_style">
                                                    <option value="">Please Select</option>
                                                    @foreach($style->fonts as $data)
                                                    <option value="{{  $data->label }}" @if($data->label == $trigger->trigger_handwriting_style) selected @endif>{{ $data->label}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="trigger_insert" class="col-sm-4 col-form-label">Insert:</label>
                                            <div class="col-sm-5">
                                                <select class="custom-select" name="trigger_insert" id="trigger_insert">
                                                    <option value="">Please Select</option>
                                                    <option value="Sticker" @if($trigger->trigger_insert == 'Sticker') selected @endif>Sticker</option>
                                                    @foreach($insertData->inserts as $data)
                                                    <option value="{{ $data->name }}" @if($data->name == $trigger->trigger_insert) selected @endif>{{ $data->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                        <div class="form-group row">
                                            <label for="trigger_gift_card" class="col-sm-4 col-form-label">Gift Card:</label>
                                            <div class="col-sm-7">
                                                <select class="custom-select" name="trigger_gift_card" id="trigger_gift_card">
                                                    <option value="">Please Select</option>
                                                    @foreach($giftCard->gcards as $data)
                                                    <option value="{{ $data->name }}" @if($data->name == $trigger->trigger_gift_card) selected @endif>{{ $data->name}}</option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>

                                    </div>
                                </div>

                            </div>
                            <div class="form-group">
                                <button type="submit" class="btn btn-primary float-right" id="updateTriggerID" onclick="updateTrigger()">Save</button>
                            </div>
                    </form>
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
        title: 'Triggers',
    };
    var myTitleBar = TitleBar.create(app, titleBarOptions);
</script>

<script>
    function triggeerStatus() {
        var newStatus = document.getElementById("trigger_status").value;
        if (newStatus == '1') {
            document.getElementById("trigger_status").value = '0';
            document.getElementById("triggerStatus").innerHTML = "Disabled";
        } else {
            document.getElementById("trigger_status").value = '1';
            document.getElementById("triggerStatus").innerHTML = "Enabled";
        }
    }
</script>

<script>
    function submitTrigger() {
        document.getElementById("submitIcon").innerHTML = "<i class='fa fa-spinner fa-spin'></i> Adding Trigger";
    }
</script>

<script>
    function updateTrigger() {
        document.getElementById("updateTriggerID").innerHTML = "<i class='fa fa-spinner fa-spin'></i> Saving Trigger";
    }
</script>

<script>
    function deleteTrigger() {
        document.getElementById("destroyTrigger").innerHTML = "<i class='fa fa-spinner fa-2x fa-spin text-danger'></i>";
    }
</script>

<script>
    function categoryFun() {

        var categoryID = $("#category_id :selected").val();

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $("#cardInfo").empty();
        $("#overlay").fadeIn(300);


        $.ajax({
            type: 'POST',
            url: "{{ route('ajaxRequest.post') }}",
            data: {
                id: categoryID
            },
            success: function(data) {
                jQuery("#triggerCard").modal('show');
                var newres = JSON.parse(data);
                console.log(newres.cards);
                var cardinfo = newres.cards;
                for (i = 0; i < cardinfo.length; i++) {
                    $('#cardInfo').append("<div class='row'><div class='col-md-4'><img src='" + cardinfo[i].cover + "' style='width:120px;height:120px'></div><div class='col-md-7'><div class='custom-control custom-radio'><input type='radio' class='custom-control-input' name='cardlist' value='" + cardinfo[i].cover + "' id='cardLabel" + cardinfo[i].id + "' onclick='cardFormClick()'><label class='custom-control-label' for='cardLabel" + cardinfo[i].id + "'>'" + cardinfo[i].name + "'</label></div></div></div><hr class='my-3'>");
                }
                $("#overlay").fadeOut(300);
            }
        });
    }
</script>

<script>
    function cardFormClick() {
        var Cardvalue = $('input[name=cardlist]:checked').val();
        var id = $('input[name=cardlist]:checked').attr("id");
        var cardid = id.slice(9);
        $('.cardId').val(cardid);
        $('.cardImg').attr('src', Cardvalue);
        $(".cardInputFile").val(Cardvalue);
    }
</script>
@endsection