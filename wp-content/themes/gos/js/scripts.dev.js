!function(angular,doc,HTML){var $email,abbrevText,app,applicationScaffolding,clicker,disabler,entryTitle,input,searchInput,someInputs$,uPosts,wp_user_email_scaffolding,__;return __=function(obj){return console.log(obj)},input=function(){var iteritems;return iteritems=["input"],iteritems[0]},entryTitle=HTML.query(".entry-title"),entryTitle.each(function(el){var containerElement,entryTitleAnchor,entryTitleAnchorClone,entryTitleAnchorText,parentElement;return entryTitleAnchor=el.query("a"),entryTitleAnchorText=el.query("a").textContent,entryTitleAnchorClone=entryTitleAnchor.cloneNode(),entryTitleAnchorClone.setAttribute("class","review-job"),entryTitleAnchorClone.textContent="Review Job",parentElement=el.each("parentElement"),containerElement=parentElement.each("parentElement"),containerElement.query(".entry-content").add(entryTitleAnchorClone)}),searchInput=HTML.query("#s"),searchInput.length&&searchInput.setAttribute("placeholder","Search Job Postings"),disabler=function(e){return e.preventDefault()},clicker=function(){var c,elem,t;return elem=this,c=elem.getAttribute("class"),t=void 0,-1===c.indexOf("enabled")?(elem.removeAttribute("class","disabled"),elem.setAttribute("class","enabled")):(elem.removeAttribute("class","enabled"),elem.setAttribute("class","disabled"))},abbrevText=function(obj){var d,i,o,_i,_len,_o;for(o=obj.split(" "),_o=[],i=_i=0,_len=o.length;_len>_i;i=++_i)d=o[i],100>i&&_o.push(d);return _o.join(" ")},$email=HTML.query("#search-2"),$email.length&&$email.setAttribute("data-check-email",""),someInputs$=doc.querySelector(""+input()+"[type=submit]"),someInputs$.setAttribute("data-input-roster",""),uPosts=HTML.query(".widget_ultimate_posts"),uPosts.each(function(el){return console.log(el)}),wp_user_email_scaffolding=function(){var generatedEmail,generatedHandle,generatedSubject,h;return h=HTML,generatedHandle=h.query('input[name="id:generated-handle"]'),generatedSubject=h.query('input[name="id:generated-subject"]'),generatedEmail=h.query('input[name="id:generated-email"]'),__(generatedEmail)},wp_user_email_scaffolding(),angular.module("GOSS.services",[]).service("pageService",["$http",function($http){var serviceInterface;return serviceInterface={},serviceInterface.testAjax=function(handleData){$http.get("//"+window.location.hostname+"/api/get_page_index/?post_type=post").success(function(data){handleData(data)}).error(function(data){return console.log(data)})},serviceInterface.getRecruiters=function(){},serviceInterface.getManagers=function(){},serviceInterface.getEmployees=function(){},serviceInterface.getAdministrators=function(){return console.log($http)},serviceInterface.getPostings=function(){},serviceInterface}]),angular.module("GOSS.services").service("commentsService",["$http",function($http){var serviceInterface;return serviceInterface={},serviceInterface.testEmail=function(email,resumeFile,handleData){$http.get("//"+window.location.hostname+"/api/make/user/?email="+email+"&fileToUpload="+resumeFile).success(function(data){handleData(data)}).error(function(data){return console.log(data)})},serviceInterface}]),angular.module("GOSS.directives",[]).directive("checkEmail",["commentsService",function(commentsService){return{restrict:"A",scope:{yourEmail:"@"},link:function(scope){var email,emailElement$,resumeElement$,resumeFile;return __(scope),emailElement$=angular.element(document.querySelector("input[name='your-email']")),resumeElement$=angular.element(document.querySelector("#resume")),email=emailElement$.val(),resumeFile=resumeElement$.val(),commentsService.testEmail(email,resumeFile,function(data){return console.log("Creating User: "+data)})}}}]),angular.module("GOSS.directives").directive("pageLoader",["$http","pageService",function($http,pageService){return{restrict:"A",scope:{placeholder:"@"},link:function(scope,element){return pageService.testAjax(function(){element.on("hover",function(){})})}}}]),angular.module("GOSS.directives").directive("inputRoster",["pageService",function(pageService){return{restrict:"A",scope:{someInputs:"@"},link:function(scope,element){pageService.testAjax(function(resp){return __(resp)}),element.on("focus",function(e){return element.setAttribute("value",e)})}}}]),angular.module("GOSS.controllers",[]).controller("CoreCtrl",function($scope){$scope.dataData=[]}),applicationScaffolding=["ngRoute","ngSanitize","ngAnimate","GOSS.services","GOSS.directives","GOSS.controllers"],app=angular.module("GOSS",applicationScaffolding),app.config(function($routeProvider,$locationProvider,$logProvider){return $logProvider.debugEnabled(!0),$routeProvider.when("/",{templateUrl:"partials/index",controller:"CoreCtrl"}).when("/login",{templateUrl:"login",controller:"LoginCtrl"}).otherwise("/")}),angular.bootstrap(doc.body,["GOSS"])}(angular,document,HTML);