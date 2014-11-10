###
stripe-market

@package   stripe-market
@author    nerdfiles <hello@nerdfiles.net>
@license   GPL-2.0+
@link      http://nerdfiles.net
###

(($) ->
  $ ->
    # var stripe-market-key declared in DOM from localized script

    Stripe.setPublishableKey wpstripekey

    # Stripe Token Creation & Event Handling
    (stripeResponseHandler = (status, response) ->
      if response.error
        $(".stripe-submit-button").prop("disabled", false).css "opacity", "1.0"
        $(".payment-errors").show().html response.error.message
        $(".stripe-submit-button .spinner").fadeOut "slow"
        $(".stripe-submit-button span").removeClass "spinner-gap"
      else
        form$ = $("#stripe-market-payment-form")
        token = response["id"]
        form$.append "<input type='hidden' name='stripeToken' value='" + token + "' />"
        newStripeForm = form$.serialize()
        $.ajax
          type: "post"
          dataType: "json"
          url: ajaxurl
          data: newStripeForm
          success: (response) ->
            $(".stripe-market-details").prepend response
            $(".stripe-submit-button").prop("disabled", false).css "opacity", "1.0"
            $(".stripe-submit-button .spinner").fadeOut "slow"
            $(".stripe-submit-button span").removeClass "spinner-gap"
            resetStripeForm()
            return
      return
    )

    (resetStripeForm = () ->
      $("#stripe-market-payment-form").get(0).reset()
      $("input").removeClass "stripe-valid stripe-invalid"
      return
    )

    $("#stripe-market-payment-form").submit((event) ->
      event.preventDefault()
      $(".stripe-market-notification").hide()
      $(".stripe-submit-button").prop("disabled", true).css "opacity", "0.4"
      $(".stripe-submit-button .spinner").fadeIn "slow"
      $(".stripe-submit-button span").addClass "spinner-gap"
      amount = $(".stripe-market-card-amount").val() * 100 #amount you want to charge in cents
      Stripe.createToken
        name      : $(".stripe-market-name").val()
        number    : $(".card-number").val()
        cvc       : $(".card-cvc").val()
        exp_month : $(".card-expiry-month").val()
        exp_year  : $(".card-expiry-year").val()
      , stripeResponseHandler

      # prevent the form from submitting with the default action
      false
    )


    # Form Validation & Enhancement
    $(".card-number").focusout(() ->
      cardValid = Stripe.validateCardNumber($(this).val())
      cardType = Stripe.cardType($(this).val())

      # Card Number Validation
      if cardValid
        $(this).removeClass("stripe-invalid").addClass "stripe-valid"
      else
        $(this).removeClass("stripe-valid").addClass "stripe-invalid"
      return
    )

    # Card Type Information
    #        if ( cardType && cardValid  ) {
    #            // Display Card Logo
    #        }

    # CVC Validation
    $(".card-cvc").focusout(() ->
      if Stripe.validateCVC($(this).val())
        $(this).removeClass("stripe-invalid").addClass "stripe-valid"
      else
        $(this).removeClass("stripe-valid").addClass "stripe-invalid"
      return
    )

  return
) jQuery

