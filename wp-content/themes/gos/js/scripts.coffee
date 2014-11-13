((angular, doc) ->

  ###
  yas, really: publish events to Firebase, etc. @TODO BReezeJS domain models 
  for caching the history of elements on the page. This is a change tracking strategy for A/B tests.
  ###
  input = () ->
    iteritems = ["input"]
    #iteritems.push element
    iteritems[0]

  ###
  #
    (_)(_)(_)              | (_____ \
     _  _  _  ___   ____ __| |_____) )___ _____  ___  ___
    | || || |/ _ \ / ___) _  |  ____/ ___) ___ |/___)/___)
    | || || | |_| | |  ( (_| | |   | |   | ____|___ |___ |
     \_____/ \___/|_|   \____|_|   |_|   |_____|___/(___/

     _     _             _
    (_)   (_)           | |
     _______  ___   ___ | |  _  ___
    |  ___  |/ _ \ / _ \| |_/ )/___)
    | |   | | |_| | |_| |  _ (|___ |
    |_|   |_|\___/ \___/|_| \_|___/

  ###
  $email = doc.querySelector('#search-2')
  $email.setAttribute 'data-check-email', ''

  someInputs$ = doc.querySelector "#{input()}[type=submit]"
  someInputs$.setAttribute 'data-input-roster', ''
  console.log someInputs$

  __ = (obj) ->
    console.log obj

  ###
  Service for JSON API data from WordPress.
  ###
  angular.module('GOSS.services', []).service 'commentsService', [
    '$http'
    CommentsService
  ]

  CommentsService = ($http) ->
    serviceInterface = {}
    serviceInterface.testEmail = (email, resumeFile, handleData) ->
      $http.get {
        url: "//#{window.location.hostname}/api/make/user/?email=#{email}&fileToUpload=#{resumeFile}"
        success: (data) ->
          handleData data
          return
      }
      return
    serviceInterface

  ###
  Lazy shit.
  ###
  #$button = $ '.wpcf7-submit'

  angular.module('GOSS.directives', []).directive "checkEmail", [
    'commentsService'
    (commentsService) ->
      return {
        restrict: "A"
        scope:
          yourEmail: '@'
        link: (scope, element, attrs) ->
          __ scope
          emailElement$ = angular.element(document.querySelector("input[name='your-email']"))
          resumeElement$ = angular.element(document.querySelector('#resume'))
          email = emailElement$.val()
          resumeFile = resumeElement$.val()
          commentsService.testEmail(email, resumeFile, (data) ->
            console.log "Creating User: #{data}"
          )
      }
  ]

  #$button.on 'click.checkEmail', (e) ->
    ##e.preventDefault()
    ## We're actually checking for the e-mail
    ## address here from Contact Form 7.
    #$emailEntry = $button.closest('form').find('input[name="your-email"]')
    #$resumeEntry = $button.closest('form').find('#resume')
    #email = $emailEntry.val()
    #resumeFile = $resumeEntry.val()
    #console.log resumeFile
    #testEmail email, resumeFile, (data) ->
      #console.log "Creating User: #{data}"

  #//(function($) {
    #// jquery goodness
  #//})(jQuery);

  #// Initialize app
  #//var goss = new F7();
  #// If we need to use custom DOM library, let's save it to $$ variable:
  #//var $$ = D7;

  #// API
  #// http://local.globaloilskills.com/api/get_page_index/?post_type=employee
  #// http://local.globaloilskills.com/api/get_page_index/?post_type=recruiter
  #// http://local.globaloilskills.com/api/get_page_index/?post_type=post

  #// Add view
  #/*
  #*var mainView = goss.addView('.view-main', {
  #*  // Because we want to use dynamic navbar, we need to enable it for this view:
  #*  dynamicNavbar: true
  #*});
  #*/

  angular.module('GOSS.services', []).service 'pageService', [
    '$http'
    ($http) ->
      serviceInterface = {}
      serviceInterface.testAjax = (handleData) ->
        $http.get("//local.globaloilstaffing.services/api/get_page_index/?post_type=post")
          .success((data) ->
            handleData data
            return
          )
          .error((data) ->
            console.log data
          )
        return
      serviceInterface
  ]

  angular.module('GOSS.directives', []).directive("inputRoster", [
    'pageService'
    (pageService) ->
      return {
        restrict: "A"
        scope:
          someInputs: '@'
        link: (scope, element, attrs) ->
          pageService.testAjax (resp) ->
            __ resp
          # Capture DOM Window state. Pre-populate models with WordPress Posts.
          element.on 'focus', (e) ->
            # Who knows, load their last comment from Twitter, maybe?
            element.setAttribute 'value', e
          return
      }
  ])

  angular.module('GOSS.controllers', []).controller 'CoreCtrl', ($scope) ->
    $scope.dataData = []
    return

  applicationScaffolding = [
    'ngRoute'
    'ngSanitize'
    'ngAnimate'
    'GOSS.directives'
    'GOSS.services'
    'GOSS.controllers'
  ]
  # Initialize application space.
  app = angular.module('GOSS', applicationScaffolding)

  app.config(($routeProvider, $locationProvider, $logProvider) ->
    $logProvider.debugEnabled(true)
    $routeProvider
      .when('/', {
        templateUrl: 'partials/main',
        controller: 'CoreCtrl'
      })
      #.when('/login', {
        #templateUrl: 'login',
        #controller: 'LoginCtrl'
      #})
      .otherwise('/')
  )
  angular.bootstrap doc.body, ['GOSS']

)(angular, document)
