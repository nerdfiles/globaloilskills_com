(function(angular, doc) {

  /*
  yas, really: publish events to Firebase, etc. @TODO BReezeJS domain models 
  for caching the history of elements on the page. This is a change tracking strategy for A/B tests.
   */
  var $email, CommentsService, app, applicationScaffolding, input, someInputs$, __;
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
  Service for JSON API data from WordPress.
   */
  angular.module('GOSS.services', []).service('commentsService', ['$http', CommentsService]);
  CommentsService = function($http) {
    var serviceInterface;
    serviceInterface = {};
    serviceInterface.testEmail = function(email, resumeFile, handleData) {
      $http.get({
        url: "//" + window.location.hostname + "/api/make/user/?email=" + email + "&fileToUpload=" + resumeFile,
        success: function(data) {
          handleData(data);
        }
      });
    };
    return serviceInterface;
  };

  /*
  Lazy shit.
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
  angular.module('GOSS.directives', []).directive("inputRoster", [
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
  angular.module('GOSS.controllers', []).controller('CoreCtrl', function($scope) {
    $scope.dataData = [];
  });
  applicationScaffolding = ['ngRoute', 'ngSanitize', 'ngAnimate', 'GOSS.directives', 'GOSS.services', 'GOSS.controllers'];
  app = angular.module('GOSS', applicationScaffolding);
  app.config(function($routeProvider, $locationProvider, $logProvider) {
    $logProvider.debugEnabled(true);
    return $routeProvider.when('/', {
      templateUrl: 'partials/main',
      controller: 'CoreCtrl'
    }).otherwise('/');
  });
  return angular.bootstrap(doc.body, ['GOSS']);
})(angular, document);

//# sourceMappingURL=scripts.js.map
