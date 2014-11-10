utils = require('utils')
casper = require('casper').create({
  verbose: true,
  logLevel: 'debug',
  pageSettings: {
    loadImages:  false,
    loadPlugins: false,
    userAgent: 'Mozilla/5.0 (Macintosh; Intel Mac OS X 10_7_5) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.94 Safari/537.4'
  }
})

# print out all the messages in the headless browser context
casper.on('remote.message', (msg) ->
  @echo('remote message caught: ' + msg)
)

# print out all the messages in the headless browser context
casper.on("page.error", (msg, trace) ->
  @echo("Page Error: " + msg, "ERROR")
)

#casper.on('resource.requested', (requestData, request) ->
  #utils.dump request
  #if request.url.indexOf('admin-ajax') != -1
    #utils.dump request
    #utils.dump requestData
#)

#casper.on('resource.received', (response) ->
  #if response.url.indexOf('admin-ajax') != -1
    #utils.dump response
#)

casper.on("resource.requested", (msg, trace) ->
  if msg.url.indexOf('admin-ajax') != -1
    utils.dump msg
    utils.dump trace
    #@echo("Page Requested: " + msg)
)

server = 'http://localhost?p=1'
sep = '//////////////////////////////'

casper.start server, () ->
  @echo 'Starting server:'
  @echo server

SMN = []
getStripeMarketName = () ->
  SMN = document.querySelectorAll('[name="stripe_market_name"]')
  return Array.prototype.map.call(SMN, (e) ->
    return e.getAttribute 'name'
  )

SME = []
getStripeMarketEmail = () ->
  SME = document.querySelectorAll('[name="stripe_market_email"]')
  return Array.prototype.map.call(SME, (e) ->
    return e.getAttribute 'name'
  )

SMC = []
getStripeMarketComment = () ->
  SMC = document.querySelectorAll('[id="stripe_market_comment"]')
  return Array.prototype.map.call(SMC, (e) ->
    return e.getAttribute 'id'
  )

SMCN = []
getStripeMarketCardNumber = () ->
  SMCN = document.querySelectorAll('[id="card-number"]')
  return Array.prototype.map.call(SMCN, (e) ->
    return e.getAttribute 'id'
  )

SMCVCN = []
getStripeMarketCvcNumber = () ->
  SMCVCN = document.querySelectorAll('[id="card-cvc"]')
  return Array.prototype.map.call(SMCVCN, (e) ->
    return e.getAttribute 'id'
  )

SMEM = []
getStripeMarketExpiry = () ->
  SMEM = document.querySelectorAll('[id="card-expiry"]')
  return Array.prototype.map.call(SMEM, (e) ->
    e.selectedIndex = 4
    return e.getAttribute 'id'
  )

SMEY = []
getStripeMarketExpiryYear = () ->
  SMEY = document.querySelectorAll('[class="card-expiry-year"]')
  return Array.prototype.map.call(SMEY, (e) ->
    e.selectedIndex = 1
    return e.getAttribute 'class'
  )

casper.waitFor () ->
  @evaluate () ->
    $btn = document.querySelector '#stripe-market-modal-button'
, (d) ->
  @thenClick '#stripe-market-modal-button'

casper.wait 500

casper.waitFor () ->
  return @withFrame 2, () ->
    @log 'Found frame 2'
, (d) ->
  @withFrame 2, () ->
    TBFrame = @page.switchToChildFrame(2)
    SMN = @evaluate getStripeMarketName
    @sendKeys '[name="'+SMN+'"]', 'Blob Test'
    SME = @evaluate getStripeMarketEmail
    @sendKeys '[name="'+SME+'"]', 'hello@nerdfiles.net'
    SMC = @evaluate getStripeMarketComment
    @sendKeys '[name="'+SMC+'"]', 'Bitcoin Integration'
    SMCN = @evaluate getStripeMarketCardNumber
    @sendKeys '[id="'+SMCN+'"]', '4242424242424242'
    SMCVCN = @evaluate getStripeMarketCvcNumber
    @sendKeys '[id="'+SMCVCN+'"]', '123'
    SMEM = @evaluate getStripeMarketExpiry
    SMEY = @evaluate getStripeMarketExpiryYear
    SMB = @evaluate () ->
      DOMClick = ( element ) ->
        # create a mouse click event
        event = document.createEvent( 'MouseEvents' )
        event.initMouseEvent( 'click', true, true, window, 1, 0, 0 )
        # send click to element
        element.dispatchEvent( event )
      $btn = document.querySelector '.stripe-submit-button'
      DOMClick $btn
    #@thenClick SMB
      #$form = document.querySelectorAll '#stripe-market-payment-form'
      #$form.submit()

casper.then () ->
  @echo('Capture screen of ' + @getCurrentUrl())
  @captureSelector('TB_iframeContent.png', 'body')

res = undefined
casper.waitForResource((resource) ->
  res = resource
  #return /bar/.test(resource.url)
  return resource.url.indexOf("admin-ajax") != -1
, () ->
  #resData = casper.evaluate((url) ->
    ## synchronous GET request
    #return __utils__.sendAJAX(url, "POST")
  #, res.url)
  #@echo resData
  #utils.dump resData
  #utils.dump res
  return
, () ->
  return
, 20000)

#casper.waitFor () ->
  #@evaluate () ->
    #$modalWindow = document.querySelector ''

casper.run(() ->
  @echo('Finished!')
  @echo(@getCurrentUrl())
  #@debugPage()
  @exit()
)
