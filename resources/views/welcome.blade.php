<!DOCTYPE html>
<html>
<head>
    <title> Cart</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.4.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://js.stripe.com/v3/"></script>
    <style>
            /**
     * The CSS shown here will not be introduced in the Quickstart guide, but shows
     * how you can use CSS to style your Element's container.
     */
    .StripeElement {
      box-sizing: border-box;

      height: 40px;

      padding: 10px 12px;

      border: 1px solid transparent;
      border-radius: 4px;
      background-color: white;

      box-shadow: 0 1px 3px 0 #e6ebf1;
      -webkit-transition: box-shadow 150ms ease;
      transition: box-shadow 150ms ease;
    }

{
      box-shadow: 0 1px 3px 0 #cfd7df;
    }

    .StripeElement--invalid {
      border-color: #fa755a;
    }

    .StripeElement--webkit-autofill {
      background-color: #fefde5 !important;
    }
    </style>
</head>
<body>
    
    <h1>Checkout</h1>
    <h2>Product : Beras</h2>
    <h2>Price : 500 USD</h2>

    @if(session()->has('message'))
        {{ session()->get('message') }}
    @endif

    @if(count($errors)>0)
        @foreach($errors->all() as $error)
            <li>{{$error}}</li>
        @endforeach
    @endif

    <h3>Payment Details</h3>
    <form action="api/v1/checkout/1" method="POST" id="payment-form">
        {{csrf_field()}}
        <label>Name:</label>
        <input type="text" name="name" id="name" >

        <label>CardHolder Name:</label>
        <input type="text" name="cardName" id="cardName" >

        <label>Email Address:</label>
        <input type="text" name="email" >

        <label>Address:</label>
        <input type="text" name="address" id="address" >

        <label>City:</label>
        <input type="text" name="city" id="city" >

        <label>State:</label>
        <input type="text" name="state" id="state" >

        <!-- <label>Phone:</label>
        <input type="text" name="phone" id="phone"> -->

        <div class="form-row">
            <label for="card-element">Credit or debit card</label>
        <div id="card-element">
          <!-- A Stripe Element will be inserted here. -->
        </div>

        <!-- Used to display form errors. -->
        <div id="card-errors" role="alert"></div>

        <button id="complete-order" type="submit">Complete Order</button>

    </form>
    <script>
    // Create a Stripe client.
    var stripe = Stripe('pk_test_4ONmbYXYxaIsxOLtjJmseNQV004gH0rYRR');

    // Create an instance of Elements.
    var elements = stripe.elements();

    // Custom styling can be passed to options when creating an Element.
    // (Note that this demo uses a wider set of styles than the guide below.)
    var style = {
      base: {
        color: '#32325d',
        fontFamily: '"Helvetica Neue", Helvetica, sans-serif',
        fontSmoothing: 'antialiased',
        fontSize: '16px',
        '::placeholder': {
          color: '#aab7c4'
        }
      },
      invalid: {
        color: '#fa755a',
        iconColor: '#fa755a'
      }
    };

    // Create an instance of the card Element.
    var card = elements.create('card', {style: style});

    // Add an instance of the card Element into the `card-element` <div>.
    card.mount('#card-element');

    // Handle real-time validation errors from the card Element.
    card.addEventListener('change', function(event) {
      var displayError = document.getElementById('card-errors');
      if (event.error) {
        displayError.textContent = event.error.message;
      } else {
        displayError.textContent = '';
      }
    });

    // Handle form submission.
    var form = document.getElementById('payment-form');
    form.addEventListener('submit', function(event) {
      event.preventDefault();

      document.getElementById('complete-order').disable = true;

      var options = {
        name: document.getElementById('name').value,
        address_line1: document.getElementById('address').value,
        address_city: document.getElementById('city').value,
        address_state: document.getElementById('state').value,
        // phone: document.getElementById('phone').value
      }

      stripe.createToken(card,options).then(function(result) {
        if (result.error) {
          // Inform the user if there was an error.
          var errorElement = document.getElementById('card-errors');
          errorElement.textContent = result.error.message;
        } else {
          // Send the token to your server.
          stripeTokenHandler(result.token);
        }
      });
    });

    

    // Submit the form with the token ID.
    function stripeTokenHandler(token) {
      // Insert the token ID into the form so it gets submitted to the server
      var form = document.getElementById('payment-form');
      var hiddenInput = document.createElement('input');
      hiddenInput.setAttribute('type', 'hidden');
      hiddenInput.setAttribute('name', 'stripeToken');
      hiddenInput.setAttribute('value', token.id);
      form.appendChild(hiddenInput);

      // Submit the form
      form.submit();
    }
    </script>
</body>
</html>