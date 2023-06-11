@extends('layouts.app')

@section('content')
    <section class="auth-form">
        <div id="message" class="message"></div>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-md-offset-2">
                    <div class="panel panel-default">
                        <div class="panel-heading">@lang('app.register')</div>
                        <div class="panel-body">

                            @if(session('error'))
                                <div class="alert alert-danger">
                                    {{session('error')}}
                                </div>
                            @endif

                            <form class="form-horizontal" role="form" method="POST" action="{{ route('register') }}">
                                {{ csrf_field() }}

                                <div class="form-group{{ $errors->has('name') ? ' has-error' : '' }}">
                                    <label for="name" class="col-md-4 control-label">@lang('app.name')</label>

                                    <div class="col-md-6">
                                        <input id="name" type="text" class="form-control" name="name" value="{{ old('name') }}" required autofocus>

                                        @if ($errors->has('name'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('name') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('email') ? ' has-error' : '' }}">
                                    <label for="email" class="col-md-4 control-label">@lang('app.email_address')</label>

                                    <div class="col-md-6">
                                        <input id="email" type="email" class="form-control" name="email" value="{{ old('email') }}" required>

                                        @if ($errors->has('email'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group{{ $errors->has('password') ? ' has-error' : '' }}">
                                    <label for="password" class="col-md-4 control-label">@lang('app.password')</label>

                                    <div class="col-md-6">
                                        <input id="password" type="password" class="form-control" name="password" required>

                                        @if ($errors->has('password'))
                                            <span class="help-block">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                        @endif
                                    </div>
                                </div>

                                <div class="form-group">
                                    <label for="password-confirm" class="col-md-4 control-label">@lang('app.confirm_password')</label>

                                    <div class="col-md-6">
                                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required>
                                    </div>
                                </div>


                                @if(get_option('enable_recaptcha_registration') == 1)
                                    <div class="form-group {{ $errors->has('g-recaptcha-response') ? ' has-error' : '' }}">
                                        <div class="col-md-6 col-md-offset-4">
                                            <div class="g-recaptcha" data-sitekey="{{get_option('recaptcha_site_key')}}"></div>
                                            @if ($errors->has('g-recaptcha-response'))
                                                <span class="help-block">
                                                    <strong>{{ $errors->first('g-recaptcha-response') }}</strong>
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endif


                                <div class="form-group">
                                    <div class="col-md-6 col-md-offset-4">
                                        <button type="submit" class="btn btn-primary" style="background-image: linear-gradient(to bottom, #db59e1 0%, #b133b7 100%); background-repeat: inherit;">
                                            @lang('app.register')
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@if(get_option('enable_recaptcha_registration') == 1)
    <script src='https://www.google.com/recaptcha/api.js'></script>
@endif
<script type="text/javascript">
    document.addEventListener('DOMContentLoaded', function() {
    const passwordInputs = document.querySelectorAll('input[type="password"]');
passwordInputs.forEach((input) => {
  input.setAttribute('readonly', 'readonly');
});

// Get the email input field
const emailInput = document.getElementById('email');

// Get the message element
const messageElement = document.getElementById('message');

// Debounce function to delay API request
let debounceTimeout;

// Function to handle email input change
function handleEmailChange() {
  clearTimeout(debounceTimeout);

  // Delay the API request by 500 milliseconds after the user stops typing
  debounceTimeout = setTimeout(() => {
    const email = emailInput.value;

    // API request
    fetch(`https://user-intel.{{env('PANGEA_DOMAIN')}}/v1/user/breached`, {
      method: 'POST',
      headers: {
        'Authorization': `Bearer {{env('USER_INTEL_AUTH_TOKEN')}}`,
        'Content-Type': 'application/json',
      },
      body: JSON.stringify({
        provider: 'spycloud',
        email: email,
      }),
    })
      .then((response) => response.json())
      .then((data) => {
        // Check the response data
        if (data.result.data.found_in_breach) {
          // Password found in breach, enable password inputs and enforce stricter password requirements
          passwordInputs.forEach((input) => {
            input.removeAttribute('readonly');
            input.setAttribute(
              'pattern',
              '^(?=.*[a-z])(?=.*[A-Z])(?=.*\\d)(?=.*[@$!%*?&])[A-Za-z\\d@$!%*?&]{8,}$'
            );
            input.setAttribute(
              'title',
              'Password must contain at least 8 characters including at least one uppercase letter, one lowercase letter, one number, and one symbol.'
            );
          });

          // Show breach found message in the message element
          messageElement.textContent = `${data.result.data.breach_count} Data breach found! use a strong and unique password`;
          messageElement.style.display = 'block';
          messageElement.style.position = 'fixed';
          messageElement.style.top = '10px';
          messageElement.style.right = '10px';
          messageElement.style.backgroundColor = '#ff0000';
          messageElement.style.color = '#fff';
          messageElement.style.padding = '10px';
          messageElement.style.margin = '5px';
          messageElement.style.borderRadius = '5px';
          // Set timeout to hide the message after 30 seconds
            setTimeout(() => {
              messageElement.style.display = 'none';
            }, 30000);
        } else {
          // Password not found in breach, set password inputs back to normal
          passwordInputs.forEach((input) => {
            input.removeAttribute('readonly');
            input.removeAttribute('pattern');
            input.removeAttribute('title');
          });

          // Hide the message element
          messageElement.style.display = 'none';
        }
      })
      .catch((error) => {
        console.log('An error occurred:', error);
      });
  }, 500);
}

// Attach event listener to the email input field
if (emailInput) {
  emailInput.addEventListener('input', handleEmailChange);
  emailInput.addEventListener('change', handleEmailChange);
}
});
</script>
