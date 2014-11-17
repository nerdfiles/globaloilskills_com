(function(angular, doc) {

  /*
  yas, really: publish events to Firebase, etc. @TODO BReezeJS domain models 
  for caching the history of elements on the page. This is a change tracking strategy for A/B tests.
   */
  var $email, app, applicationScaffolding, input, someInputs$, __;
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
  $email = doc.querySelector('#search-2');
  $email.setAttribute('data-check-email', '');
  someInputs$ = doc.querySelector("" + (input()) + "[type=submit]");
  someInputs$.setAttribute('data-input-roster', '');
  console.log(someInputs$);
  __ = function(obj) {
    return console.log(obj);
  };

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
        $http.get("//local.globaloilstaffing.services/api/get_page_index/?post_type=post").success(function(data) {
          handleData(data);
        }).error(function(data) {
          return console.log(data);
        });
      };
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
    console.log($scope);
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
})(angular, document);

//# sourceMappingURL=scripts.js.map
