<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Laravel</title>

        <!-- Fonts -->
        <link href="https://fonts.googleapis.com/css2?family=Nunito:wght@200;600&display=swap" rel="stylesheet">

        <!-- Styles -->
        <script src="https://code.jquery.com/jquery-2.2.4.js" integrity="sha256-iT6Q9iMJYuQiMWNd9lDyBUStIq/8PuOW33aOqmvFpqI=" crossorigin="anonymous"></script>
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
        <script src="https://www.paypal.com/sdk/js?client-id={{env('APP_CLIENT_ID')}}&enable-funding=venmo&currency=USD&components=messages,buttons" data-sdk-integration-source="button-factory"></script>
        <style>
            .products{
                margin-top: 2em;
            }
        </style>
          <script src="{{ asset('public/js/swal.js') }}"></script>
        
    </head>
    <body style="height: auto;">
  
    
        @include('layouts.header')

            <div class="container" style="margin-top: 2em;">
            <h1 class="display-3">Checkout Page</h1>
                <div class="row">
                    <div class="col-md-8">
                        @forelse($products as $field)
                        <div class="card container products">
                            <div class="card-body">
                                <input type="radio" name="product_id" id="product_id" data-price="{{$field->price}}" value="{{$field->id}}" {{$loop->first ? 'checked' : ''}}>
                                <h5>{{$field->name}} - $ {{number_format($field->price,2)}}</h5>
                                <p>{{$field->description}}</p>
                            </div>
                        </div>
                        
                        @empty  

                        @endforelse
                        
                    </div>
                    <div class="col-md-4">
                        <div id="smart-button-container" style="margin-top: 5em;">
                            <div >
                                <div id="paypal-button-container"></div>
                            </div>
                        </div>
                                
                                @forelse($products as $field)
                                    <div class="productBtn" style="{{$loop->first ? 'display:block;' : 'display:none;' }}" id="productBtn{{$field->id}}" data-id="{{$field->id}}">
                                    <div>
                                        <a style="width: 100%; max-width: 416px; height: 45px;" class="btn btn-ligh buy-with-crypto"
                                            href="https://commerce.coinbase.com/checkout/{{$field->checkout_id}}">
                                            Buy with Crypto 
                                        </a>
                                        <script src="https://commerce.coinbase.com/v1/checkout.js?version=201807">
                                        </script>
                                    </div>
                                    </div>  
                                @empty  

                                @endforelse

                        </div>

                    </div>
                </div>
            </div>

           <script>
               
        function initPayPalButton() {
            paypal.Buttons({

                style: {
                    shape: 'rect',
                    color: 'gold',
                    layout: 'vertical',
                    label: 'paypal',

                },

                createOrder: function(data, actions) {
                    return actions.order.create({
                        purchase_units: [
                            {
                                "amount":
                                    {
                                        "currency_code":"USD",
                                        "value":$("input[name='product_id']:checked").attr('data-price')
                                    }
                            }]
                    });
                },

                onApprove: function(data, actions) {
                    
                    return actions.order.capture().then(function(orderData) {
                        
                        console.log('Capture result', orderData, JSON.stringify(orderData, null, 2));

                        const element = document.getElementById('paypal-button-container');
                        
                        element.innerHTML = '';

                        element.innerHTML = '<h3>Thank you for your payment!</h3>';
                    
                    });

                },

                onError: function(err) {
                    console.log(err);
                }

                }).render('#paypal-button-container');
            }

            initPayPalButton();


           </script>
           


            <script>

                $('input:radio').change(function() {
                    var id = $(this).val();
                    $(".productBtn").hide();

                    $("#productBtn"+id).show();
                });

            </script>


            @include('layouts.footer')


    </body>
</html>
