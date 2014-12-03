(function(angular, doc, HTML) {
  var $email, abbrevText, app, applicationScaffolding, clicker, disabler, entryTitle, input, searchInput, someInputs$, uPosts, wp_user_email_scaffolding, __;
  __ = function(obj) {
    return console.log(obj);
  };

  /*
  yas, really: publish events to Firebase, etc. @TODO BReezeJS domain models 
  for caching the history of elements on the page. This is a change tracking strategy for A/B tests.
   */
  input = function() {
    var iteritems;
    iteritems = ["input"];
    return iteritems[0];
  };

  /*
   *
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
   */
  entryTitle = HTML.query('.entry-title');
  entryTitle.each(function(el, i, all) {
    var containerElement, entryTitleAnchor, entryTitleAnchorClone, entryTitleAnchorText, parentElement;
    entryTitleAnchor = el.query('a');
    entryTitleAnchorText = el.query('a').textContent;
    entryTitleAnchorClone = entryTitleAnchor.cloneNode();
    entryTitleAnchorClone.setAttribute('class', 'review-job');
    entryTitleAnchorClone.textContent = 'Review Job';
    parentElement = el.each('parentElement');
    containerElement = parentElement.each('parentElement');
    return containerElement.query('.entry-content').add(entryTitleAnchorClone);
  });
  searchInput = HTML.query('#s');
  if (searchInput.length) {
    searchInput.setAttribute('placeholder', 'Search Job Postings');
  }

  /*
  Utility Functions
   */
  disabler = function(e) {
    return e.preventDefault();
  };
  clicker = function() {
    var c, elem, t;
    elem = this;
    c = elem.getAttribute('class');
    t = void 0;
    if (c.indexOf('enabled') === -1) {
      elem.removeAttribute('class', 'disabled');
      return elem.setAttribute('class', 'enabled');
    } else {
      elem.removeAttribute('class', 'enabled');
      return elem.setAttribute('class', 'disabled');
    }
  };
  abbrevText = function(obj) {
    var d, i, o, _i, _len, _o;
    o = obj.split(' ');
    _o = [];
    for (i = _i = 0, _len = o.length; _i < _len; i = ++_i) {
      d = o[i];
      if (i < 100) {
        _o.push(d);
      }
    }
    return _o.join(' ');
  };
  $email = HTML.query('#search-2');
  if ($email.length) {
    $email.setAttribute('data-check-email', '');
  }
  someInputs$ = doc.querySelector("" + (input()) + "[type=submit]");
  someInputs$.setAttribute('data-input-roster', '');
  uPosts = HTML.query('.widget_ultimate_posts');
  uPosts.each(function(el, i, all) {
    return console.log(el);
  });

  /*
  for i in uPosts
    h = i.getElementsByTagName 'h4'
    for j in h
       *j.setAttribute 'ng-mouseover', 'clicker()'
       *j.parentNode.parentNode.addClass 'enabled'
      j.parentNode.parentNode.addEventListener 'mouseover', clicker
      j.parentNode.parentNode.addEventListener 'mouseout', clicker
   */

  /*
  wp-user--email-scaffolding
   */
  wp_user_email_scaffolding = function() {
    var generatedEmail, generatedHandle, generatedSubject, h;
    h = HTML;
    generatedHandle = h.query('input[name="id:generated-handle"]');
    generatedSubject = h.query('input[name="id:generated-subject"]');
    generatedEmail = h.query('input[name="id:generated-email"]');
    return __(generatedEmail);
  };
  wp_user_email_scaffolding();

  /*
     / _____)                 (_)                 
    ( (____  _____  ____ _   _ _  ____ _____  ___ 
     \____ \| ___ |/ ___) | | | |/ ___) ___ |/___)
     _____) ) ____| |    \ V /| ( (___| ____|___ |
    (______/|_____)_|     \_/ |_|\____)_____|___/
   */
  angular.module('GOSS.services', []).service('pageService', [
    '$http', function($http) {
      var serviceInterface;
      serviceInterface = {};
      serviceInterface.testAjax = function(handleData) {
        $http.get("//" + window.location.hostname + "/api/get_page_index/?post_type=post").success(function(data) {
          handleData(data);
        }).error(function(data) {
          return console.log(data);
        });
      };
      serviceInterface.getRecruiters = function() {};
      serviceInterface.getManagers = function() {};
      serviceInterface.getEmployees = function() {};
      serviceInterface.getAdministrators = function() {
        return console.log($http);
      };
      serviceInterface.getPostings = function() {};
      return serviceInterface;
    }
  ]);

  /*
  Service for JSON API data from WordPress.
   */
  angular.module('GOSS.services').service('commentsService', [
    '$http', function($http) {
      var serviceInterface;
      serviceInterface = {};
      serviceInterface.testEmail = function(email, resumeFile, handleData) {
        $http.get("//" + window.location.hostname + "/api/make/user/?email=" + email + "&fileToUpload=" + resumeFile).success(function(data) {
          handleData(data);
        }).error(function(data) {
          return console.log(data);
        });
      };
      return serviceInterface;
    }
  ]);

  /*
    (______)(_)                    _  (_)                 
     _     _ _  ____ _____  ____ _| |_ _ _   _ _____  ___ 
    | |   | | |/ ___) ___ |/ ___|_   _) | | | | ___ |/___)
    | |__/ /| | |   | ____( (___  | |_| |\ V /| ____|___ |
    |_____/ |_|_|   |_____)\____)  \__)_| \_/ |_____|___/
   */
  angular.module('GOSS.directives', []).directive("checkEmail", [
    'commentsService', function(commentsService) {
      return {
        restrict: "A",
        scope: {
          yourEmail: '@'
        },
        link: function(scope, element, attrs) {
          var email, emailElement$, resumeElement$, resumeFile;
          __(scope);
          emailElement$ = angular.element(document.querySelector("input[name='your-email']"));
          resumeElement$ = angular.element(document.querySelector('#resume'));
          email = emailElement$.val();
          resumeFile = resumeElement$.val();
          return commentsService.testEmail(email, resumeFile, function(data) {
            return console.log("Creating User: " + data);
          });
        }
      };
    }
  ]);

  /*
  Page.Loader Directive
   */
  angular.module('GOSS.directives').directive("pageLoader", [
    '$http', 'pageService', function($http, pageService) {
      return {
        restrict: "A",
        scope: {
          placeholder: '@'
        },
        link: function(scope, element, attrs) {
          return pageService.testAjax(function(resp) {
            element.on('hover', function(e) {});
          });
        }
      };
    }
  ]);

  /*
  Form.InputRoster
   */
  angular.module('GOSS.directives').directive("inputRoster", [
    'pageService', function(pageService) {
      return {
        restrict: "A",
        scope: {
          someInputs: '@'
        },
        link: function(scope, element, attrs) {
          pageService.testAjax(function(resp) {
            return __(resp);
          });
          element.on('focus', function(e) {
            return element.setAttribute('value', e);
          });
        }
      };
    }
  ]);

  /*
    (_______)            _             | | |
     _       ___  ____ _| |_  ____ ___ | | | _____  ____ ___
    | |     / _ \|  _ (_   _)/ ___) _ \| | || ___ |/ ___)___)
    | |____| |_| | | | || |_| |  | |_| | | || ____| |  |___ |
     \______)___/|_| |_| \__)_|   \___/ \_)_)_____)_|  (___/
  
  @depends define
   */
  angular.module('GOSS.controllers', []).controller('CoreCtrl', function($scope) {
    $scope.dataData = [];
  });

  /*
  | |     (_)  _
  | |____  _ _| |_
  | |  _ \| (_   _)
  | | | | | | | |_
  |_|_| |_|_|  \__)
   */
  applicationScaffolding = ['ngRoute', 'ngSanitize', 'ngAnimate', 'GOSS.services', 'GOSS.directives', 'GOSS.controllers'];
  app = angular.module('GOSS', applicationScaffolding);

  /*
  (_______)            / __|_)
   _       ___  ____ _| |__ _  ____
  | |     / _ \|  _ (_   __) |/ _  |
  | |____| |_| | | | || |  | ( (_| |
   \______)___/|_| |_||_|  |_|\___ |
                             (_____|
   */
  app.config(function($routeProvider, $locationProvider, $logProvider) {
    $logProvider.debugEnabled(true);
    return $routeProvider.when('/', {
      templateUrl: 'partials/index',
      controller: 'CoreCtrl'
    }).when('/login', {
      templateUrl: 'login',
      controller: 'LoginCtrl'
    }).otherwise('/');
  });

  /*
  (______)(_______|_______)   / _____) |   (_)  _
   _     _ _     _ _  _  _   ( (____ | |__  _ _| |_ 
  | |   | | |   | | ||_|| |   \____ \|  _ \| (_   _)
  | |__/ /| |___| | |   | |   _____) ) | | | | | |_ 
  |_____/  \_____/|_|   |_|  (______/|_| |_|_|  \__)
   */
  return angular.bootstrap(doc.body, ['GOSS']);
})(angular, document, HTML);

//# sourceMappingURL=scripts.js.map
