!function(angular,doc){var $email,CommentsService,app,applicationScaffolding,input,someInputs$,__;return input=function(){var iteritems;return iteritems=["input"],iteritems[0]},$email=doc.querySelector("#search-2"),$email.setAttribute("data-check-email",""),someInputs$=doc.querySelector(""+input()+"[type=submit]"),someInputs$.setAttribute("data-input-roster",""),console.log(someInputs$),__=function(obj){return console.log(obj)},angular.module("GOSS.services",[]).service("commentsService",["$http",CommentsService]),CommentsService=function($http){var serviceInterface;return serviceInterface={},serviceInterface.testEmail=function(email,resumeFile,handleData){$http.get({url:"//"+window.location.hostname+"/api/make/user/?email="+email+"&fileToUpload="+resumeFile,success:function(data){handleData(data)}})},serviceInterface},angular.module("GOSS.directives",[]).directive("checkEmail",["commentsService",function(commentsService){return{restrict:"A",scope:{yourEmail:"@"},link:function(scope){var email,emailElement$,resumeElement$,resumeFile;return __(scope),emailElement$=angular.element(document.querySelector("input[name='your-email']")),resumeElement$=angular.element(document.querySelector("#resume")),email=emailElement$.val(),resumeFile=resumeElement$.val(),commentsService.testEmail(email,resumeFile,function(data){return console.log("Creating User: "+data)})}}}]),angular.module("GOSS.services",[]).service("pageService",["$http",function($http){var serviceInterface;return serviceInterface={},serviceInterface.testAjax=function(handleData){$http.get("//local.globaloilstaffing.services/api/get_page_index/?post_type=post").success(function(data){handleData(data)}).error(function(data){return console.log(data)})},serviceInterface}]),angular.module("GOSS.directives",[]).directive("inputRoster",["pageService",function(pageService){return{restrict:"A",scope:{someInputs:"@"},link:function(scope,element){pageService.testAjax(function(resp){return __(resp)}),element.on("focus",function(e){return element.setAttribute("value",e)})}}}]),angular.module("GOSS.controllers",[]).controller("CoreCtrl",function($scope){$scope.dataData=[]}),applicationScaffolding=["ngRoute","ngSanitize","ngAnimate","GOSS.directives","GOSS.services","GOSS.controllers"],app=angular.module("GOSS",applicationScaffolding),app.config(function($routeProvider,$locationProvider,$logProvider){return $logProvider.debugEnabled(!0),$routeProvider.when("/",{templateUrl:"partials/main",controller:"CoreCtrl"}).otherwise("/")}),angular.bootstrap(doc.body,["GOSS"])}(angular,document);