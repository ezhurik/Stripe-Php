<!DOCTYPE html>
<html>
<head>
	<title>Stripe</title>
	<script src="https://js.stripe.com/v3/"></script>
	<style media="screen">

	#card-errors {
		height: 20px;
		padding: 4px 0;
		color: #fa755a;
	}

	.form-row {
		width: 70%;
		float: left;
	}

	.token {
		color: #32325d;
		font-family: 'Source Code Pro', monospace;
		font-weight: 500;
	}

	.wrapper {
		width: 670px;
		margin: 0 auto;
		height: 100%;
	}

	#stripe-token-handler {
		position: absolute;
		top: 0;
		left: 25%;
		right: 25%;
		padding: 20px 30px;
		border-radius: 0 0 4px 4px;
		box-sizing: border-box;
		box-shadow: 0 50px 100px rgba(50, 50, 93, 0.1),
		0 15px 35px rgba(50, 50, 93, 0.15),
		0 5px 15px rgba(0, 0, 0, 0.1);
		-webkit-transition: all 500ms ease-in-out;
		transition: all 500ms ease-in-out;
		transform: translateY(0);
		opacity: 1;
		background-color: white;
	}

	#stripe-token-handler.is-hidden {
		opacity: 0;
		transform: translateY(-80px);
	}

	.StripeElement {
		background-color: white;
		height: 40px;
		padding: 10px 12px;
		border-radius: 4px;
		border: 1px solid transparent;
		box-shadow: 0 1px 3px 0 #e6ebf1;
		-webkit-transition: box-shadow 150ms ease;
		transition: box-shadow 150ms ease;
	}

	.StripeElement--focus {
		box-shadow: 0 1px 3px 0 #cfd7df;
	}

	.StripeElement--invalid {
		border-color: #fa755a;
	}

	.StripeElement--webkit-autofill {
		background-color: #fefde5 !important;
	}
</style>

<style type="text/css">
.StripeElement {
	background-color: white;
	height: 40px;
	padding: 10px 12px;
	border-radius: 4px;
	border: 1px solid transparent;
	box-shadow: 0 1px 3px 0 #e6ebf1;
	-webkit-transition: box-shadow 150ms ease;
	transition: box-shadow 150ms ease;
}

.StripeElement--focus {
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

<div id="stripe-token-handler" class="is-hidden">Success! Got token: <span class="token"></span></div>
<div class="row">
	<div class="col-md-12">
		<div class="form-group">
			<label for="card-element" style="color: #fff;" >
				Credit or debit card
			</label>
			<div id="card-element" class="StripeElement StripeElement--empty StripeElement--invalid"><div class="__PrivateStripeElement" style="margin: 0px !important; padding: 0px !important; border: none !important; display: block !important; background: transparent !important; position: relative !important; opacity: 1 !important;"><iframe frameborder="0" allowtransparency="true" scrolling="no" name="__privateStripeFrame3" allowpaymentrequest="true" src="https://js.stripe.com/v3/elements-inner-card-a2a4069ff8634daf23dd43e823ae4390.html#style[base][color]=%2332325d&amp;style[base][lineHeight]=18px&amp;style[base][fontFamily]=%22Helvetica+Neue%22%2C+Helvetica%2C+sans-serif&amp;style[base][fontSmoothing]=antialiased&amp;style[base][fontSize]=16px&amp;style[base][::placeholder][color]=%23aab7c4&amp;style[invalid][color]=%23fa755a&amp;style[invalid][iconColor]=%23fa755a&amp;componentName=card&amp;wait=false&amp;rtl=false&amp;features[noop]=true&amp;origin=https%3A%2F%2Fstripe.com&amp;referrer=https%3A%2F%2Fstripe.com%2Fdocs%2Fstripe-js%2Felements%2Fquickstart&amp;controllerId=__privateStripeController0" title="Secure payment input frame" style="border: none !important; margin: 0px !important; padding: 0px !important; width: 1px !important; min-width: 100% !important; overflow: hidden !important; display: block !important; height: 18px;"></iframe><input class="__PrivateStripeElement-input" aria-hidden="true" style="border: none !important; display: block !important; position: absolute !important; height: 1px !important; top: 0px !important; left: 0px !important; padding: 0px !important; margin: 0px !important; width: 100% !important; opacity: 0 !important; background: transparent !important;"></div></div>

			<!-- Used to display form errors -->
			<div id="card-errors" role="alert"></div>
		</div>
		<input type="hidden" name="isValid" id="isValid">
	</div>
</div>

</body>
</html>

<script type="text/javascript">
  // Create a Stripe client
  var stripe = Stripe('pk***********');

  // Create an instance of Elements
  var elements = stripe.elements();

  // Custom styling can be passed to options when creating an Element.
  // (Note that this demo uses a wider set of styles than the guide below.)
  var style = {
  	base: {
  		color: '#32325d',
  		lineHeight: '18px',
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

  // Create an instance of the card Element
  var card = elements.create('card', {hidePostalCode: true,style: style});

  // Add an instance of the card Element into the `card-element` <div>
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

  // Handle form submission
  var form = document.getElementById('dealerSignUpForm'); // id of form
  form.addEventListener('submit', function(event) {
  	event.preventDefault();

  	stripe.createToken(card).then(function(result) {
  		if (result.error) {
  			alert("error");
        // Inform the user if there was an error
        var errorElement = document.getElementById('card-errors');
        errorElement.textContent = result.error.message;
    } else {
        // Send the token to your server
        stripeTokenHandler(result.token);
    }
});
  });

  var successElement = document.getElementById('stripe-token-handler');
  document.querySelector('.wrapper').addEventListener('click', function() {
  	successElement.className = 'is-hidden';
  });

  
  function stripeTokenHandler(token) {
  	successElement.className = '';
  	successElement.querySelector('.token').textContent = token.id;

  	if(token.id!="")
  	{
  		// after the form validation is done, set the value of isValid  field  as yes or anything according your choice
      // here i am setting as  'yes' 
  		if($('#isValid').val() == "yes")
  		{
  			var radioValue = $("input[name='plan']:checked").val();
  			var price=100;

  			var data = $('#dealerForm').serializeArray();
  			data.push({token: token.id, amount: price});
  			$('#isValid').val("");
  			$.ajax({
  				type: 'POST',
  				url: BASE_URL + "stripe/checkout",
  				data: $('#dealerSignUpForm').serialize() + "&token="+ token.id +"&amount="+price,
  				success: function (data) {
  					data = JSON.parse(data);
  					if(data.code==0)
  					{	
  						console.log("success");

  					} 
  					else if(data.code==1)
  					{
  						console.log("error");
  					}
  				}                
  			});

  		}
  	}
  }
</script>