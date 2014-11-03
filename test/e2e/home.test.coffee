fs        = require('fs')
utils     = require('utils')
#cache    = require('./cache')
#mimetype = require('mimetype')

#process = require 'process'
#filename = seed = undefined

#process.argv.forEach((arg, index) ->
  #if index == 2
    #filename = arg
  #if index == 3
    #seed = arg
#)

scraper = () ->
  casper = require('casper').create {
    verbose        : true
    logLevel       : 'debug'
    waitTimeout    : 10000
    stepTimeout    : 10000
    retryTimeout   : 150
    #clientScripts : ["jquery.min.js"]
    viewportSize:
      width  : 1176
      height : 806
    pageSettings:
      loadImages         : false
      loadPlugins        : false
      userAgent          : 'Mozilla/5.0 (Windows NT 6.1; WOW64; rv: 22.0) Gecko/20100101 Firefox/22.0'
      webSecurityEnabled : false
      ignoreSslErrors    : true
    onWaitTimeout: () ->
      @echo('wait timeout')
      @clear()
      @page.stop()
    onStepTimeout: (timeout, step) ->
      @echo('step timeout')
      @clear()
      @page.stop()
  }

  _str = undefined
  #casper.saveJSON = (w) ->
    #fs.write("/Users/nerdfiles/Projects/protopex/harvester/build/judgments.json", w.toString(), 'w')

  #casper.on('navigation.requested', (url, navigationType, navigationLocked, isMainFrame) ->
    #utils.dump(arguments)
    #if /Wide/.test(url)
      #request.abort()
  #)

  # print out all the messages in the headless browser context
  casper.on('remote.message', (msg) ->
    @echo('remote message caught: ' + msg)
  )

  # print out all the messages in the headless browser context
  casper.on('page.error', (msg, trace) ->
     this.echo('Error: ' + msg, 'ERROR')
     for i in trace
       step = trace[i]
       @echo('   ' + step.file + ' (line ' + step.line + ')', 'ERROR')
  )

  casper.on('page.resource.requested', (requestData, request) ->
    if requestData.url.indexOf('advertising') != -1
      request.abort()
    if requestData.url.indexOf('bing') != -1
      request.abort()
    if requestData.url.indexOf('turn') != -1
      request.abort()
    if requestData.url.indexOf('doubleclick') != -1
      request.abort()
    if requestData.url.indexOf('ru4') != -1
      request.abort()
    if requestData.url.indexOf('openx') != -1
      request.abort()
    if requestData.url.indexOf('adnxs') != -1
      request.abort()
    if requestData.url.indexOf('rubiconproject') != -1
      request.abort()
    if requestData.url.indexOf('googlesyndication') != -1
      request.abort()
    if requestData.url.indexOf('ad') != -1
      request.abort()
    if requestData.url.indexOf('facebook') != -1
      request.abort()
    if requestData.url.indexOf('googleads') != -1
      request.abort()
  )

  #server = 'http://local.globaloilskills.com/?s=EUR'
  server = 'http://local.globaloilskills.com/employees/carter-selfsame/'
  #server = 'http://local.globaloilskills.com/connect'
  report = undefined

  ###
  Init
  ###
  casper.start server, ->
  casper.thenEvaluate(() ->
    console.log("Page Title: " + document.title)
    console.log("Found frame: " + document.querySelector('body') )
  )

  ###
  Wait for pnlSearch form
  ###
  casper.waitFor ->
    @evaluate ((sel) ->
      document.querySelector sel
    ), 'body'
  , ->
    @captureSelector 'stripe-connect-test.png', 'div'

  casper.nameComposite = () ->
    @log '/////////////////////////'
    nameComposite = @
    @log '/////////////////////////'
    return nameComposite

  ####
  #Wait for Input Field for Name
  ####
  #casper.waitFor ->
    #@evaluate ((sel) ->
      #sep = ' '
      #firstName = ''
      #lastName = ''
      #nameComposite = (lastName + sep + firstName)
      #searchName = document.querySelector(sel)
      #blank = ''
      ##$txtName.value = blank
      ##$txtName.value = nameComposite

      #true
    #), 'input'
  #, ->
    #sep = ' '
    #blank = ''
    #firstName = 'omega'
    #lastName = 'alpha'
    #nameComposite = (firstName + sep + lastName)
    ##@sendKeys 'input[name=""]', blank
    ##@sendKeys 'input[name=""]', nameComposite
    #@captureSelector 'internomic-calc.png', 'input'

  ####
  #Select Type
  ####
  #casper.waitFor ->
    #@evaluate ((sel) ->
      #document
        #.querySelector(sel)
        #.selectedIndex = 7
      #true
    #), "select[name='2']"

  #casper.then ->
    #@captureSelector ".png", "select[name='']"

  ###
  Submit
  ###
  casper.then ->
    @evaluate = () ->
      document.querySelector 'body'
      true
    return


  casper.then ->
    @echo '=== WE CLICKED ==='
    #@emit('page.loaded')
    return

  ####
  #Results
  ####
  #casper.waitFor (->
    #@evaluate ((sel) ->
      #results = document.querySelector sel
    #), '#stripe-market-modal-button'
  #), ->
    #@thenEvaluate () ->
      #input = document.querySelector '#stripe-market-modal-button'
      #this.thenClick input
    #@wait 3000
    #@captureSelector 'post.png', '#stripe-market-modal-button'
    #@capture 'stripe-payouts-test.png'

  ###
  Store discoveries
  ###
  casper.run ->
    fs.write('/Users/nerdfiles/Projects/globaloilskills_com/test/home.html', casper.debugHTML(), 'w')
    @echo('=== Finished! ===')
    @debugHTML()
    #@debugPage()
    @exit()

scraper()
