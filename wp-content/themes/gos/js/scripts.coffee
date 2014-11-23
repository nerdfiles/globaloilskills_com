((angular, doc, HTML) ->

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
  entryTitle = HTML.query('.entry-title')
  entryTitle.each((el, i, all) ->
    entryTitleAnchor = el.query('a')
    entryTitleAnchorText = el.query('a').textContent
    entryTitleAnchorClone = entryTitleAnchor.cloneNode()
    #entryTitleAnchorClone.textContent = entryTitleAnchorText
    entryTitleAnchorClone.setAttribute 'class', 'review-job'
    entryTitleAnchorClone.textContent = 'Review Job'
    #entryTitleAnchorClone.textContent 'Full Details'
    parentElement = el.each('parentElement')
    containerElement = parentElement.each('parentElement')
    containerElement.query('.entry-content').add entryTitleAnchorClone
    #entryTitleAnchor.setAttribute 'href', '#'
    #console.log el
    #el.addEventListener 'click', () ->
      #console.log el.parentNode.parentNode
  )

  searchInput = HTML.query('#s')
  searchInput.setAttribute 'placeholder', 'Search Job Postings'

  ###
  Utility Functions
  ###
  disabler = (e) ->
    e.preventDefault()

  clicker = () ->
    elem = @
    c = elem.getAttribute 'class'
    t = undefined
    if c.indexOf('enabled') == -1
      # isn't present
      elem.removeAttribute 'class', 'disabled'
      elem.setAttribute 'class', 'enabled'
    else
      elem.removeAttribute 'class', 'enabled'
      elem.setAttribute 'class', 'disabled'

  abbrevText = (obj) ->
    o = obj.split ' '
    _o = []
    for d, i in o
      if i < 100
        _o.push d
    return _o.join(' ')

  $email = doc.querySelector('#search-2')
  $email.setAttribute 'data-check-email', ''

  someInputs$ = doc.querySelector "#{input()}[type=submit]"
  someInputs$.setAttribute 'data-input-roster', ''

  uPosts = HTML.query('.widget_ultimate_posts')
  uPosts.each((el, i, all) ->
    console.log el
  )

  #for j in uPosts
    #uPostsAnchors = j.getElementsByTagName('a')
    #for k in uPostsAnchors
      #k.addEventListener 'click', disabler
    #uPostsDivs = j.getElementsByTagName('div')
    #for z in uPosts
      #_stored_text = z.textContent
      #__abbrev_text = abbrevText _stored_text
      #console.log z
      ##z.textContent = __abbrev_text

  ###
  for i in uPosts
    h = i.getElementsByTagName 'h4'
    for j in h
      #j.setAttribute 'ng-mouseover', 'clicker()'
      #j.parentNode.parentNode.addClass 'enabled'
      j.parentNode.parentNode.addEventListener 'mouseover', clicker
      j.parentNode.parentNode.addEventListener 'mouseout', clicker
  ###


  __ = (obj) ->
    console.log obj

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

  ###
     / _____)                 (_)                 
    ( (____  _____  ____ _   _ _  ____ _____  ___ 
     \____ \| ___ |/ ___) | | | |/ ___) ___ |/___)
     _____) ) ____| |    \ V /| ( (___| ____|___ |
    (______/|_____)_|     \_/ |_|\____)_____|___/ 
  ###
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
      serviceInterface.getRecruiters = () ->
      serviceInterface.getManagers = () ->
      serviceInterface.getEmployees = () ->
      serviceInterface.getAdministrators = () ->
        console.log $http
      serviceInterface.getPostings = () ->
      serviceInterface
  ]

  ###
  Service for JSON API data from WordPress.
  ###
  angular.module('GOSS.services').service 'commentsService', [
    '$http'
    ($http) ->
      serviceInterface = {}
      serviceInterface.testEmail = (email, resumeFile, handleData) ->
        $http.get("//#{window.location.hostname}/api/make/user/?email=#{email}&fileToUpload=#{resumeFile}")
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


  ###
    (______)(_)                    _  (_)                 
     _     _ _  ____ _____  ____ _| |_ _ _   _ _____  ___ 
    | |   | | |/ ___) ___ |/ ___|_   _) | | | | ___ |/___)
    | |__/ /| | |   | ____( (___  | |_| |\ V /| ____|___ |
    |_____/ |_|_|   |_____)\____)  \__)_| \_/ |_____|___/ 
  ###

  angular.module('GOSS.directives', []).directive("checkEmail", [
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
  ])

  ###
  Page.Loader Directive
  ###
  angular.module('GOSS.directives').directive("pageLoader", [
    '$http'
    'pageService'
    ($http, pageService) ->
      return {
        restrict: "A"
        scope:
          placeholder: '@'
        link: (scope, element, attrs) ->
          pageService.testAjax (resp) ->
            element.on 'hover', (e) ->
              return
            return
      }
  ])

  ###
  Form.InputRoster
  ###
  angular.module('GOSS.directives').directive("inputRoster", [
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

  ###
    (_______)            _             | | |
     _       ___  ____ _| |_  ____ ___ | | | _____  ____ ___
    | |     / _ \|  _ (_   _)/ ___) _ \| | || ___ |/ ___)___)
    | |____| |_| | | | || |_| |  | |_| | | || ____| |  |___ |
     \______)___/|_| |_| \__)_|   \___/ \_)_)_____)_|  (___/

  @depends define
  ###

  angular.module('GOSS.controllers', []).controller('CoreCtrl', ($scope) ->
    $scope.dataData = []
    #$scope.clicker = () ->
      #console.log $scope
    #$scope.submitResume = () ->
      #testAjax (dataContext) ->
        #return dataContext
    return
  )

  ###
  | |     (_)  _
  | |____  _ _| |_
  | |  _ \| (_   _)
  | | | | | | | |_
  |_|_| |_|_|  \__)
  ###

  applicationScaffolding = [
    'ngRoute'
    'ngSanitize'
    'ngAnimate'
    'GOSS.services'
    'GOSS.directives'
    'GOSS.controllers'
  ]
  # Initialize application space.
  app = angular.module('GOSS', applicationScaffolding)

  ###
  (_______)            / __|_)
   _       ___  ____ _| |__ _  ____
  | |     / _ \|  _ (_   __) |/ _  |
  | |____| |_| | | | || |  | ( (_| |
   \______)___/|_| |_||_|  |_|\___ |
                             (_____|
  ###
  app.config(($routeProvider, $locationProvider, $logProvider) ->
    $logProvider.debugEnabled(true)
    $routeProvider
      .when('/', {
        templateUrl: 'partials/index',
        controller: 'CoreCtrl'
      })
      .when('/login', {
        templateUrl: 'login',
        controller: 'LoginCtrl'
      })
      .otherwise('/')
  )

  ###
  (______)(_______|_______)   / _____) |   (_)  _
   _     _ _     _ _  _  _   ( (____ | |__  _ _| |_ 
  | |   | | |   | | ||_|| |   \____ \|  _ \| (_   _)
  | |__/ /| |___| | |   | |   _____) ) | | | | | |_ 
  |_____/  \_____/|_|   |_|  (______/|_| |_|_|  \__)
  ###
  angular.bootstrap doc.body, ['GOSS']

)(angular, document, HTML)
