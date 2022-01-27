@extends('layouts.app')

@section('title') @if(! empty($title)) {{$title}} @endif - @parent @endsection

@section('content')
    <div class="dashboard-wrap">

        <div class="container">
            <div id="wrapper">

                @include('admin.menu')

                <div id="page-wrapper">
                    @if( ! empty($title))
                        <div class="row">
                            <div class="col-lg-12">
                                <h1 class="page-header"> {{ $title }}  </h1>
                            </div> <!-- /.col-lg-12 -->
                        </div> <!-- /.row -->
                    @endif

                    <form action="{{route('save_settings')}}" class="form-horizontal" method="post" enctype="multipart/form-data" > @csrf



                        <legend>@lang('app.commission_settings')</legend>

                        <div class="form-group {{ $errors->has('campaign_owner_commission')? 'has-error':'' }}">
                            <label for="campaign_owner_commission" class="col-sm-4 control-label">@lang('app.campaign_owner_commission') %</label>
                            <div class="col-sm-8">
                                <input type="number" class="form-control" id="campaign_owner_commission" value="{{ old('campaign_owner_commission')? old('campaign_owner_commission') : get_option('campaign_owner_commission') }}" max="100" name="campaign_owner_commission" placeholder="@lang('app.commission_percent')">
                                {!! $errors->has('campaign_owner_commission')? '<p class="help-block">'.$errors->first('campaign_owner_commission').'</p>':'' !!}
                            </div>
                        </div>




                        <hr />

                        <div class="form-group">
                            <div class="col-sm-offset-4 col-sm-8">
                                <button type="submit" id="settings_save_btn" class="btn btn-primary">@lang('app.save_settings')</button>
                            </div>
                        </div>

                    </form>

                </div>   <!-- /#page-wrapper -->

            </div>   <!-- /#wrapper -->


        </div> <!-- /#container -->
    </div>
@endsection


@section('page-js')
    <script>
        $(document).ready(function(){
            $('input[type="checkbox"], input[type="radio"]').click(function(){
                var input_name = $(this).attr('name');
                var input_value = 0;
                if ($(this).prop('checked')){
                    input_value = $(this).val();
                }
                $.ajax({
                    url : '{{ route('save_settings') }}',
                    type: "POST",
                    data: { [input_name]: input_value, '_token': '{{ csrf_token() }}' },
                });
            });

            /**
             * show or hide stripe and paypal settings wrap
             */
            $('#enable_paypal').click(function(){
                if ($(this).prop('checked')){
                    $('#paypal_settings_wrap').slideDown();
                }else{
                    $('#paypal_settings_wrap').slideUp();
                }
            });
            $('#enable_stripe').click(function(){
                if ($(this).prop('checked')){
                    $('#stripe_settings_wrap').slideDown();
                }else{
                    $('#stripe_settings_wrap').slideUp();
                }
            });

            $('#enable_bank_transfer').click(function(){
                if ($(this).prop('checked')){
                    $('.bankPaymetWrap').slideDown();
                }else{
                    $('.bankPaymetWrap').slideUp();
                }
            });


            /**
             * Send settings option value to server
             */
            $('#settings_save_btn').click(function(e){
                e.preventDefault();

                var this_btn = $(this);
                this_btn.attr('disabled', 'disabled');

                var form_data = this_btn.closest('form').serialize();
                $.ajax({
                    url : '{{ route('save_settings') }}',
                    type: "POST",
                    data: form_data,
                    success : function (data) {
                        if (data.success == 1){
                            this_btn.removeAttr('disabled');
                            toastr.success(data.msg, '@lang('app.success')', toastr_options);
                        }
                    }
                });
            });
        });
    </script>
@endsection