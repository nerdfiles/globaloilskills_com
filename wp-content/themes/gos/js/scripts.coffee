((angular, doc, HTML, $) ->

  __ = (obj) ->
    console.log obj

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
  if (searchInput.length)
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

  #abbrevText = (obj) ->
    #o = obj.split ' '
    #_o = []
    #for d, i in o
      #if i < 100
        #_o.push d
    #return _o.join(' ')

  $email = HTML.query('#search-2')
  if ($email.length)
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

  ###
  wp-user--email-scaffolding
  ###
  wp_user_email_scaffolding = () ->
    h = HTML
    form = h.query( '.wpcf7' )
    if (form)
      generatedHandle = h.query('#generated-handle')
      generatedSubject = h.query('#generated-subject')
      generatedEmail = h.query('#generated-email')
      generatedPermalink = h.query('#generated-permalink')

      $.ajax({
        url: "http://#{window.location.hostname}/api/user/user_metadata/"
      }).done (data) ->
        console.log data
        generatedEmail.setAttribute 'value', data.user_email
        generatedHandle.setAttribute 'value', data.display_name
        generatedSubject.setAttribute 'value', "#{data.display_name} has applied!"
        generatedPermalink.setAttribute 'value', data.permalink
        $('.wpcf7').on 'submit', () ->
          console.log data
          wp_user_application_create()
        return

  wp_user_application_create = () ->
    h = HTML
    form = h.query( '.wpcf7' )
    fd = new FormData()
    fd.append('file', $(h.query('input[name="file-966"]')).prop('files')[0])
    fd.append('post_title', h.query('h1.post-title').textContent)
    fd.append('post_content', "<div>" + h.query('#apply-form--qualifications').value + "</div><div>" + h.query('#apply-form--questions').value + "</div><div>" + h.query('.wpcf7-list-item.active label').textContent + "</div>")
    $.ajax({
      type: 'POST'
      url: "http://#{window.location.hostname}/api/application/create_application"
      cache: false
      contentType: false
      processData: false
      data: fd
    }).done (data) ->
      console.log data

  wp_user_email_scaffolding()

  wp_job_posting_list_item = () ->
    h = HTML
    form = h.query( '.wpcf7' )
    wpcf7ListItem = form.query( '.wpcf7-list-item input' )
    $.each wpcf7ListItem, () ->
      $listItem = $(this)
      $listItem.bind 'click', (e) ->
        $listItem.closest('.wpcf7-form-control').find('.wpcf7-list-item').removeClass 'active'
        $listItem.closest('.wpcf7-list-item').addClass 'active'

  wp_job_posting_list_item()

  #// API
  #// http://local.globaloilskills.com/api/get_page_index/?post_type=employee
  #// http://local.globaloilskills.com/api/get_page_index/?post_type=recruiter
  #// http://local.globaloilskills.com/api/get_page_index/?post_type=post
  
  # Use the above to print arbitrary WordPress init-based JS code with nonces, 
  # etc. like for Stripe Connect OAuth filtered by additional CGI parameters 
  # like the nonces from template code. By default they only print JSON, but 
  # with REST-ful sugar they print HTML that has to pass through AngularJS's
  # $httpIntercept, albeit HTML with a messy of JSON data glued to its back.
  # AngularJS's http://www.webdeveasy.com/interceptors-in-angularjs-and-useful-examples/ 
  # can clean it away with an abstraction wrapper that tests a sequence of chars 
  # for being HTML, likely looking for tags. Mark the semantic chars, then split
  # the whole string by the first tag found. Sometimes it will be a <script> tag,
  # but it could very well be a <div> in case of a simple partial template 
  # condition. Either way, test for tags when calling the API for data. Either way, 
  # it's one URL that servers ``(<element>)?({})?`` with a nonce binding template 
  # to anticipated async Request (who will have the nonce token as CGI param).
  #
  # Essentially http://codex.wordpress.org/Function_Reference/wp_nonce_url wrapper 
  # code at add_action('init', ...) for AJAX calls through WP JSON API. The JS 
  # code will add RequireJS modules will wrap AngularJS directive code AND vanilla 
  # JS such that my route in AngularJS and service calls reuse the same Cool URL but 
  # request UI resources they need as URL Attributes which pick out named partials 
  # and named data, binding RequireJS dependency chain to WordPress API.
  
  # Fine. Example:
  
  # http://local.globaloilskills.com/api/get_page_index/?post_type=employee&component[site-footer]&component[site-header] serves:
  #
  #     <script id="site-footer" nonce="...">/* Code in here, however, sanitizes and nonces the $http GETs using ngSanitize. */</script>{}
  
  # define 

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
        #$http.get("//local.globaloilstaffing.services/api/get_page_index/?post_type=post")
        #$http.get("http://lcamtuf.coredump.cx/squirrel/")
        $http.get("//#{window.location.hostname}/api/get_page_index/?post_type=post")
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

)(angular, document, HTML, jQuery)
