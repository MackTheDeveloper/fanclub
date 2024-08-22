@section('title', 'Sign In')
@extends('frontend.layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{ asset('public/assets/frontend/css/jquery.ccpicker.css') }}">
@endsection
@section('content')
    <!--------------------------
                SIGN UP START
        --------------------------->
    <div class="payment-page">
        <h4>Pay for Subscription</h4>
        <p>Please complete the fields below</p>
        <form id="paymentForm" method="POST" action="{{ route('thirdSignup') }}">
            @csrf
            <div class="card-detail">
                <h6>Card Details</h6>
                <div class="inputs-group">
                    <input type="text" name="card_name">
                    <span>Name on Card*</span>
                    <!-- <label class="error">Error</label> -->
                </div>
                <div class="inputs-group">
                    <input type="number" name="card_number" maxlength="16">
                    <span>Card Number*</span>
                    <!-- <label class="error">Error</label> -->
                </div>
                <div class="row">
                    <div class="col-6">
                        <div class="inputs-group">
                            <input type="text" maxlength="5" name="card_exp">
                            <span>Expiry Date*</span>
                            <!-- <label class="error">Error</label> -->
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="inputs-group">
                            <input type="number" maxlength="3" name="card_cvv">
                            <span>CVV*</span>
                            <!-- <label class="error">Error</label> -->
                        </div>
                    </div>
                </div>
                <p class="caption note">Note<span>*</span>: fanclub does not store your bank card details</p>
            </div>
            <div class="billing-address">
                <h6>Billing Address</h6>
                <div class="inputs-group">
                    <input type="text" name="billing_address_1">
                    <span>Address 1*</span>
                    <!-- <label class="error">Error</label> -->
                </div>
                <div class="inputs-group">
                    <input type="text" name="billing_address_2">
                    <span>Address 2</span>
                    <!-- <label class="error">Error</label> -->
                </div>
                <div class="inputs-group">
                    <input type="text" name="city">
                    <span>City*</span>
                    <!-- <label class="error">Error</label> -->
                </div>
                <div class="inputs-group">
                    <input type="text" name="country">
                    <span>Country*</span>
                    <!-- <label class="error">Error</label> -->
                </div>
                <div class="inputs-group">
                    <input type="text" name="state">
                    <span>State</span>
                    <!-- <label class="error">Error</label> -->
                </div>
                <div class="inputs-group">
                    <input type="text" name="zipcode">
                    <span>Zip Code*</span>
                    <!-- <label class="error">Error</label> -->
                </div>
            </div>
            {{-- <a href="subscription.html" class="fill-btn">Pay Now</a> --}}
            <button class="fill-btn" type="submit">Pay Now</button>
        </form>
    </div>

    <!--------------------------
                SIGN UP END
        --------------------------->
@endsection
@section('footscript')
    {{-- <script src="{{asset('public/assets/frontend/js/jquery.ccpicker.js')}}"></script> --}}
    <script type="text/javascript">
        $(document).ready(function() {
            $('input[name="card_number"]').inputFilter(function(value) {
                return /^\d*$/.test(value); // Allow digits only, using a RegExp
            });
        })
        $(document).on('change', '.introduce-radio-btn', function() {
            var value = $(this).val();
            if (value == "1") {
                $('.yesLabel').removeClass('d-none');
                $('.noLabel').addClass('d-none');
            } else {
                $('.yesLabel').addClass('d-none');
                $('.noLabel').removeClass('d-none');

            }
        });

        $("#paymentForm").validate({
            ignore: [],
            rules: {
                card_name: "required",
                card_number: {
                    required: true,
                    maxlength: 16
                },
                card_exp: {
                    required: true,
                    validExp: true,
                    pattern: "^[0-9/]+$"
                },
                card_cvv: "required",
                billing_address_1: "required",
                // billing_address_2: "required",
                country: "required",
                //state: "required",
                city: "required",
                zipcode: "required",
            },
            messages: {
                card_name: "Name on card is required",
                card_number: {
                    required: "Card number is required",
                    maxlength: "Card number is invalid"
                },
                card_exp: {
                    required: "Exp Date is required",
                    pattern: "Please Add Valid Expire Date",
                    validExp: "Please Add Valid Expire Date"
                },
                card_cvv: "CVV is required",
                billing_address_1: "Billing Address is required",
                billing_address_2: "billing_address_2 is required",
                country: "Country is required",
                state: "State is required",
                city: "City is required",
                zipcode: "Zipcode is required",
            },
            errorPlacement: function(error, element) {
                if (element.prop("type") === "checkbox") {
                    error.insertAfter(element.next("label"));
                } else {
                    error.insertAfter(element);
                }
            },
        });

        $.validator.addMethod(
            "validExp",
            function(value, element) {
                var returnIs = true
                console.log(value)
                if ((value.indexOf('/') != -1) && value.length == 5) {
                    var arr = value.split('/');
                    let dateIs = new Date();
                    let year = dateIs.getFullYear().toString().substr(-2)
                    if (arr[1] < year) {
                        returnIs = false;
                    } else if (arr[1] == year) {
                        let month = dateIs.getMonth() + 1;
                        if (arr[0] < month || arr[0] > 12) {
                            returnIs = false;
                        }
                    } else {
                        if (arr[0] > 12) {
                            returnIs = false;
                        }
                    }
                }
                // return this.optional(element) || re.test(value);
                return returnIs
            },
            "Please check your input."
        );


        $(document).on('keyup', 'input[name="card_exp"]', function(e) {
            var value = $(this).val();
            if (value.length > 2) {
                if (value.indexOf('/') == -1) {
                    var b = "/";
                    var position = 2;
                    var output = [value.slice(0, position), b, value.slice(position)].join('');
                    $(this).val(output)
                }

            }
        });
        $(document).on('keypress', 'input', function(e) {
            var maxLength = $(this).attr('maxlength');
            if (maxLength) {
                var value = $(this).val();
                if (value.length >= maxLength) {
                    e.preventDefault();
                }
            }
        });
    </script>
@endsection
