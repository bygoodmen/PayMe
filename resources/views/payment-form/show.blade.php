<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <!-- Title -->
    <title>{{ config('app.name', 'Laravel') }} - Make a payment</title>

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Required Meta Tags Always Come First -->
    <meta charset="utf-8"/>
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

    <!-- Favicon -->
    <link rel="shortcut icon" href="{{ asset('favicon.png') }}">

    <!-- App CSS -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    <script src="https://js.stripe.com/v3/"></script>
</head>

<body class="gradient-half-primary-body-v1">
<!-- ========== HEADER ========== -->
<header id="header" class="u-header u-header--bg-transparent u-header--abs-top pt-3">
    <div class="u-header__section">
        <div id="logoAndNav" class="container">
            <!-- Nav -->
            <nav class="navbar navbar-expand u-header__navbar">
                <!-- Logo -->
                <a class="navbar-brand u-header__navbar-brand u-header__navbar-brand-center u-header__navbar-brand-text-white"
                   href="/" aria-label="PayMe">
                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         width="36px"
                         height="36px" viewBox="0 0 36 36" xml:space="preserve" style="margin-bottom: 0;">
              <path fill="#FFFFFF"
                    d="M18,0h11.4C33,0,36,3,36,6.6c0,0,0,0,0,0V18c0,9.9-8.1,18-18,18l0,0C8.1,36,0,27.9,0,18l0,0C0,8.1,8.1,0,18,0z"/>
                        <path fill="#377DFF" d="M17,26.4c-2.1-0.1-4.1-0.5-6-1.3v-4.5c1.1,0.6,2.3,1,3.6,1.4c1.1,0.3,2.2,0.5,3.4,0.5c0.6,0,1.2-0.1,1.8-0.3
                            c0.4-0.2,0.6-0.5,0.6-0.9c0-0.3-0.2-0.6-0.4-0.8c-0.4-0.3-0.9-0.5-1.3-0.7c-0.6-0.3-1.5-0.6-2.6-1c-1-0.3-2-0.8-2.9-1.4
                            c-0.7-0.5-1.2-1.1-1.6-1.8c-0.4-0.8-0.5-1.6-0.5-2.5c-0.1-1.3,0.5-2.6,1.5-3.5C13.8,8.6,15.4,8,17,8V6h3v1.9c2,0.1,4,0.6,5.8,1.3
                            l-1.7,3.9c-1.7-0.8-3.6-1.2-5.5-1.3c-0.6,0-1.1,0.1-1.6,0.3c-0.3,0.2-0.5,0.5-0.5,0.8c0,0.3,0.1,0.6,0.3,0.8
                            c0.4,0.3,0.8,0.5,1.2,0.7c0.6,0.2,1.3,0.5,2.3,0.8c1.6,0.4,3.1,1.2,4.3,2.3c0.9,0.9,1.4,2.1,1.3,3.4c0.1,1.4-0.5,2.8-1.5,3.8
                            c-1.3,1.1-2.8,1.7-4.5,1.8V29h-3L17,26.4z"/>
            </svg>
                    <span class="u-header__navbar-brand-text">PayMe</span>
                </a>
                <!-- End Logo -->

            </nav>
            <!-- End Nav -->
        </div>
    </div>
</header>
<!-- ========== END HEADER ========== -->

<!-- ========== MAIN ========== -->
<main id="content" role="main">
    <!-- Hero Section -->
    <div class="d-lg-flex" style="background: url('{{ asset('img/bg-world.png') }}') no-repeat right;">
        <div class="container d-lg-flex align-items-lg-center min-height-lg-100vh space-bottom-2 space-top-4 space-bottom-lg-1 space-lg-1">
            <div class="w-lg-40 w-xl-35 mt-lg-9">
                <div class="card shadow-sm">
                    <div class="card-body p-5">
                        <span class="d-block text-muted font-weight-medium mb-1">Pay to</span>
                        <div class="media d-block d-sm-flex align-items-sm-center mb-3 border p-2 rounded">
                            <div class="u-avatar mb-3 mb-sm-0 mr-3 ">
                                @if($form->user->avatar)
                                    <img class="img-fluid rounded-circle  shadow-sm border"
                                     src="{{ url('storage/' . $form->user->avatar) }}">
                                @else
                                    <span class="btn btn-light btn-icon text-muted gradient-half-primary-v2 rounded-circle shadow-sm border">
                                        <span class="btn-icon__inner">{{ substr($form->user->name, 0, 1) }}</span>
                                    </span>
                                @endif
                            </div>
                            <div class="media-body">
                                <span class="d-block font-weight-medium mb-1">{{ $form->user->name }}</span>
                                <span class="d-block small text-muted">{{ $form->user->company }}</span>
                            </div>
                        </div>
                        <div class="mb-3 border-bottom">
                            <span class="d-block mb-1 small font-weight-medium">Payment Description</span>
                            <span class="d-block pb-2 text-muted">{{ $form->description }}</span>
                        </div>
                        <div id="payment-alert" style="display:none"
                             class="alert alert-light border rounded text-center animated pulse"
                             role="alert">
                            <div class="display-1 text-success"><i class="far fa-check-circle"></i></div>
                            <div class="h2">Thank you!</div>
                            <div class="text-muted">Your payment has been received.</div>
                        </div>
                        <div id="payment-error" style="display:none"
                             class="alert alert-light border rounded text-center animated pulse"
                             role="alert">
                            <div class="display-1 text-danger"><i class="far fa-times-circle"></i></div>
                            <div class="h2">Oops!</div>
                            <div class="text-muted">Something went wrong with your payment.</div>
                        </div>
                        <form method="post" id="payment-form" class="payment-form">
                            @csrf
                            <input type="hidden" name="stripeToken" id="stripeToken" value="">
                            <div id="card-errors" class="text-danger small"></div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-user"></i></span>
                                        </div>
                                        <input name="customer_name" required type="text" class="form-control" placeholder="Full Name"
                                               aria-label="Name">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-envelope"></i></span>
                                        </div>
                                        <input name="customer_email" type="email" required class="form-control" placeholder="Email" aria-label="Email">
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-12 mb-3">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-credit-card"></i></span>
                                        </div>
                                        <span id="card-number" class="form-control"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="far fa-calendar"></i></span>
                                        </div>
                                        <span id="card-expiration" class="form-control"></span>
                                    </div>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <div class="input-group input-group-sm">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-lock"></i></span>
                                        </div>
                                        <span id="card-cvc" class="form-control"></span>
                                    </div>
                                </div>
                            </div>
                            <button id="paybtn" type="submit" class="btn btn-block btn-primary transition-3d-hover">
                                Pay {{ $form->amountFormattedWithCurrency() }}
                            </button>
                            <div class="text-center mt-3">
                                <a href="https://stripe.com" target="_blank"><img
                                        src="{{ asset('img/powered_by_stripe.svg') }}"></a>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End Hero Section -->
</main>
<!-- ========== END MAIN ========== -->

<!-- ========== FOOTER ========== -->
<footer>
    <div class="container space-bottom-1">
        <div class="row justify-content-between align-items-center">
            <div class="col-sm-5 mb-3 mb-sm-0">
                <!-- Copyright -->
                <p class="small text-white-70 mb-0">
                    &copy; {{ date('Y') }} <a class="link-white" href="/">{{ config('app.name', 'Laravel') }}</a> &mdash; All rights reserved.
                </p>
                <!-- End Copyright -->
            </div>

            <div class="col-sm-6 text-sm-right">
                <p class="small text-white-70 mb-0">
                    ⚡️ A project by <a class="link-white" href="https://maxkostinevich.com" target="_blank">Max
                    Kostinevich</a>
                    &amp; <a class="link-white" href="https://laravel101.com" target="_blank">Laravel
                    101</a> ⚡️
                </p>
            </div>
        </div>
    </div>
</footer>
<!-- ========== END FOOTER ========== -->

<!-- JS Core -->
<script src="{{ asset('js/app.js') }}"></script>
<!-- JS -->
<script src="{{ asset('js/vendor.js') }}"></script>

<script>
    var stripe = Stripe('{{ config('stripe.publishable_key') }}');
    var elements = stripe.elements();
    var style = {};

    // Card number
    var cardNumber = elements.create('cardNumber', {
        'placeholder': '0000 0000 0000 0000',
        'style': style
    });
    cardNumber.mount('#card-number');

    // Expiration Date
    var expDate = elements.create('cardExpiry', {
        'placeholder': 'DD/YY',
        'style': style
    });
    expDate.mount('#card-expiration');

    // Expiration Date
    var cardCVC = elements.create('cardCvc', {
        'placeholder': 'CVC',
        'style': style
    });
    cardCVC.mount('#card-cvc');

    $('#payment-form').on('submit', function (e) {

        e.preventDefault();
        // Clear error message
        $('#card-errors').html('');

        stripe.createToken(cardNumber).then(function(result) {
            if (result.error) {
                // Show Card error message
                $('#card-errors').html(result.error.message);
            } else {
                stripeTokenHandler(result.token);
            }
        });

    });

    // Send Stripe Token to Server
    function stripeTokenHandler(token) {

        // Update Stripe Token ID
        var form = $('#payment-form');
        $('#stripeToken').val(token.id);

        // Submit the form via AJAX
        var button = $('#paybtn');
        button.attr('disabled', true);
        button.html('<i class="fas fa-spinner fa-spin"></i> Please wait..');
        $.ajax({
            url     : form.attr('action'),
            type    : form.attr('method'),
            dataType: 'json',
            data    : form.serialize(),
            success : function( data ) {
                var hand = setTimeout(function(){
                    $('#payment-form').hide();
                    $('#payment-alert').show();
                    clearTimeout(hand);
                }, 1000);

            },
            error   : function( xhr, err ) {
                // Log errors if AJAX call is failed
                console.log(xhr);
                console.log(err);
                $('#payment-form').hide();
                $('#payment-error').show();
            }
        });
        return false;
    }
</script>
</body>

</html>