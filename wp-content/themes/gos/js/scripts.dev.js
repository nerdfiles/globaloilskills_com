!function(angular,doc){var $email,abbrevText,app,applicationScaffolding,clicker,disabler,input,j,k,someInputs$,uPosts,uPostsAnchors,uPostsDivs,z,__,__abbrev_text,_i,_j,_k,_len,_len1,_len2,_stored_text;for(input=function(){var iteritems;return iteritems=["input"],iteritems[0]},disabler=function(e){return e.preventDefault()},clicker=function(){var c,elem,t;return elem=this,c=elem.getAttribute("class"),t=void 0,-1===c.indexOf("enabled")?(elem.removeAttribute("class","disabled"),elem.setAttribute("class","enabled")):(elem.removeAttribute("class","enabled"),elem.setAttribute("class","disabled"))},abbrevText=function(obj){var d,i,o,_i,_len,_o;for(o=obj.split(" "),_o=[],i=_i=0,_len=o.length;_len>_i;i=++_i)d=o[i],100>i&&_o.push(d);return _o.join(" ")},$email=doc.querySelector("#search-2"),$email.setAttribute("data-check-email",""),someInputs$=doc.querySelector(""+input()+"[type=submit]"),someInputs$.setAttribute("data-input-roster",""),uPosts=document.getElementsByClassName("widget_ultimate_posts"),_i=0,_len=uPosts.length;_len>_i;_i++){for(j=uPosts[_i],uPostsAnchors=j.getElementsByTagName("a"),_j=0,_len1=uPostsAnchors.length;_len1>_j;_j++)k=uPostsAnchors[_j],k.addEventListener("click",disabler);for(uPostsDivs=j.getElementsByTagName("div"),_k=0,_len2=uPosts.length;_len2>_k;_k++)z=uPosts[_k],_stored_text=z.textContent,__abbrev_text=abbrevText(_stored_text),console.log(z)}return __=function(obj){return console.log(obj)},angular.module("GOSS.services",[]).service("pageService",["$http",function($http){var serviceInterface;return serviceInterface={},serviceInterface.testAjax=function(handleData){$http.get("//local.globaloilstaffing.services/api/get_page_index/?post_type=post").success(function(data){handleData(data)}).error(function(data){return console.log(data)})},serviceInterface.getRecruiters=function(){},serviceInterface.getManagers=function(){},serviceInterface.getEmployees=function(){},serviceInterface.getAdministrators=function(){return console.log($http)},serviceInterface.getPostings=function(){},serviceInterface}]),angular.module("GOSS.services").service("commentsService",["$http",function($http){var serviceInterface;return serviceInterface={},serviceInterface.testEmail=function(email,resumeFile,handleData){$http.get("//"+window.location.hostname+"/api/make/user/?email="+email+"&fileToUpload="+resumeFile).success(function(data){handleData(data)}).error(function(data){return console.log(data)})},serviceInterface}]),angular.module("GOSS.directives",[]).directive("checkEmail",["commentsService",function(commentsService){return{restrict:"A",scope:{yourEmail:"@"},link:function(scope){var email,emailElement$,resumeElement$,resumeFile;return __(scope),emailElement$=angular.element(document.querySelector("input[name='your-email']")),resumeElement$=angular.element(document.querySelector("#resume")),email=emailElement$.val(),resumeFile=resumeElement$.val(),commentsService.testEmail(email,resumeFile,function(data){return console.log("Creating User: "+data)})}}}]),angular.module("GOSS.directives").directive("pageLoader",["$http","pageService",function($http,pageService){return{restrict:"A",scope:{placeholder:"@"},link:function(scope,element){return pageService.testAjax(function(){element.on("hover",function(){})})}}}]),angular.module("GOSS.directives").directive("inputRoster",["pageService",function(pageService){return{restrict:"A",scope:{someInputs:"@"},link:function(scope,element){pageService.testAjax(function(resp){return __(resp)}),element.on("focus",function(e){return element.setAttribute("value",e)})}}}]),angular.module("GOSS.controllers",[]).controller("CoreCtrl",function($scope){$scope.dataData=[]}),applicationScaffolding=["ngRoute","ngSanitize","ngAnimate","GOSS.services","GOSS.directives","GOSS.controllers"],app=angular.module("GOSS",applicationScaffolding),app.config(function($routeProvider,$locationProvider,$logProvider){return $logProvider.debugEnabled(!0),$routeProvider.when("/",{templateUrl:"partials/index",controller:"CoreCtrl"}).when("/login",{templateUrl:"login",controller:"LoginCtrl"}).otherwise("/")}),angular.bootstrap(doc.body,["GOSS"])}(angular,document);