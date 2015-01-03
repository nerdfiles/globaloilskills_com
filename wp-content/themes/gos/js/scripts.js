(function(angular,doc,HTML,$){var $email,$wpcf7,app,applicationScaffolding,body_job_posting,c,clicker,disabler,entryTitle,h,i,input,j,searchInput,someInputs$,toggleClass,uPosts,wp_job_posting_list_item,wp_user_application_create,wp_user_email_scaffolding,__,_i,_j,_len,_len1;__=function(obj){return console.log(obj)};input=function(){var iteritems;iteritems=["input"];return iteritems[0]};entryTitle=HTML.query(".entry-title");entryTitle.each(function(el,i,all){var containerElement,entryTitleAnchor,entryTitleAnchorClone,entryTitleAnchorText,parentElement;entryTitleAnchor=el.query("a");entryTitleAnchorText=el.query("a").textContent;entryTitleAnchorClone=entryTitleAnchor.cloneNode();entryTitleAnchorClone.setAttribute("class","review-job");entryTitleAnchorClone.textContent="Review Job";parentElement=el.each("parentElement");containerElement=parentElement.each("parentElement");return containerElement.query(".entry-content").add(entryTitleAnchorClone)});searchInput=HTML.query("#s");if(searchInput&&HTML.body.getAttribute("class").indexOf("single")===-1){searchInput.setAttribute("placeholder","Search Job Postings")}$wpcf7=$(".wpcf7").detach();if($wpcf7.length===1){$wpcf7.closest(".post-content").attach($wpcf7)}disabler=function(e){return e.preventDefault()};c=void 0;toggleClass=function(){var className,classes,el,existingIndex,i,_i,_len;el=this;className="enabled";if(el.classList){el.classList.toggle(className)}else{classes=el.className.split(" ");existingIndex=-1;for(_i=0,_len=classes.length;_i<_len;_i++){i=classes[_i];i--;if(classes[i]===className){existingIndex=i}}if(existingIndex>=0){classes.splice(existingIndex,1)}else{classes.push(className)}}if(classes){el.className=classes.join(" ")}};clicker=function(){var elem;elem=this;c=elem.getAttribute("class");if(c.indexOf("enabled")===-1){return elem.setAttribute("class","enabled")}else{return elem.removeAttribute("class","enabled")}};$email=HTML.query("#search-2");if($email.length){$email.setAttribute("data-check-email","")}someInputs$=doc.querySelector(""+input()+"[type=submit]");someInputs$.setAttribute("data-input-roster","");uPosts=HTML.query(".widget_ultimate_posts");uPosts.each(function(el,i,all){return console.log(el)});for(_i=0,_len=uPosts.length;_i<_len;_i++){i=uPosts[_i];h=i.getElementsByTagName("h4");for(_j=0,_len1=h.length;_j<_len1;_j++){j=h[_j];j.parentNode.parentNode.addEventListener("mouseover",toggleClass);j.parentNode.parentNode.addEventListener("mouseout",toggleClass)}}wp_user_email_scaffolding=function(){var form,generatedEmail,generatedHandle,generatedPermalink,generatedSubject;h=HTML;form=h.query(".wpcf7");if(form){generatedHandle=h.query("#generated-handle");generatedSubject=h.query("#generated-subject");generatedEmail=h.query("#generated-email");generatedPermalink=h.query("#generated-permalink");return $.ajax({url:"http://"+window.location.hostname+"/api/user/user_metadata/"}).done(function(data){var emailNameAttribute,permalink_url,post_id;if(generatedEmail.length===1){emailNameAttribute=generatedEmail.getAttribute("name");if(emailNameAttribute){generatedEmail.setAttribute("value",data.user_email);generatedHandle.setAttribute("value",data.display_name);generatedSubject.setAttribute("value",""+data.display_name+" has applied!");post_id=$(".page-content > .job_posting").prop("id");permalink_url=post_id.replace("post-","http://"+window.location.hostname+"/?p=");generatedPermalink.setAttribute("value",permalink_url);$(".wpcf7").on("submit",function(){wp_user_application_create()})}}})}};wp_user_application_create=function(){var fd,form,post_content;h=HTML;form=h.query(".wpcf7");fd=new FormData;fd.append("file",$(h.query('input[name="file-966"]')).prop("files")[0]);fd.append("post_title",h.query("h1.post-title").textContent);post_content=[];post_content.push(h.query("#apply-form--qualifications").value);post_content.push(h.query("#apply-form--questions").value);post_content.push(h.query(".wpcf7-list-item.active label").textContent);fd.append("post_content",post_content.join("\n\n\r\r"));return $.ajax({type:"POST",url:"http://"+window.location.hostname+"/api/application/create_application",cache:false,contentType:false,processData:false,data:fd}).done(function(data){return console.log(data)})};body_job_posting=HTML.query("body.single-job_posting");if(body_job_posting){wp_user_email_scaffolding()}wp_job_posting_list_item=function(){var form,wpcf7ListItem;h=HTML;form=h.query(".wpcf7");wpcf7ListItem=form.query(".wpcf7-list-item input");return $.each(wpcf7ListItem,function(){var $listItem;$listItem=$(this);return $listItem.bind("click",function(e){$listItem.closest(".wpcf7-form-control").find(".wpcf7-list-item").removeClass("active");return $listItem.closest(".wpcf7-list-item").addClass("active")})})};wp_job_posting_list_item();angular.module("GOSS.services",[]).service("pageService",["$http",function($http){var serviceInterface;serviceInterface={};serviceInterface.testAjax=function(handleData){$http.get("//"+window.location.hostname+"/api/get_page_index/?post_type=post").success(function(data){handleData(data)}).error(function(data){return console.log(data)})};serviceInterface.getRecruiters=function(){};serviceInterface.getManagers=function(){};serviceInterface.getEmployees=function(){};serviceInterface.getAdministrators=function(){return console.log($http)};serviceInterface.getPostings=function(){};return serviceInterface}]);angular.module("GOSS.services").service("commentsService",["$http",function($http){var serviceInterface;serviceInterface={};serviceInterface.testEmail=function(email,resumeFile,handleData){$http.get("//"+window.location.hostname+"/api/make/user/?email="+email+"&fileToUpload="+resumeFile).success(function(data){handleData(data)}).error(function(data){return console.log(data)})};return serviceInterface}]);angular.module("GOSS.directives",[]).directive("checkEmail",["commentsService",function(commentsService){return{restrict:"A",scope:{yourEmail:"@"},link:function(scope,element,attrs){var email,emailElement$,resumeElement$,resumeFile;__(scope);emailElement$=angular.element(document.querySelector("input[name='your-email']"));resumeElement$=angular.element(document.querySelector("#resume"));email=emailElement$.val();resumeFile=resumeElement$.val();return commentsService.testEmail(email,resumeFile,function(data){return console.log("Creating User: "+data)})}}}]);angular.module("GOSS.directives").directive("pageLoader",["$http","pageService",function($http,pageService){return{restrict:"A",scope:{placeholder:"@"},link:function(scope,element,attrs){return pageService.testAjax(function(resp){element.on("hover",function(e){})})}}}]);angular.module("GOSS.directives").directive("inputRoster",["pageService",function(pageService){return{restrict:"A",scope:{someInputs:"@"},link:function(scope,element,attrs){pageService.testAjax(function(resp){return __(resp)});element.on("focus",function(e){return element.setAttribute("value",e)})}}}]);angular.module("GOSS.controllers",[]).controller("CoreCtrl",function($scope){$scope.dataData=[]});applicationScaffolding=["ngRoute","ngSanitize","ngAnimate","GOSS.services","GOSS.directives","GOSS.controllers"];app=angular.module("GOSS",applicationScaffolding);app.config(function($routeProvider,$locationProvider,$logProvider){$logProvider.debugEnabled(true);return $routeProvider.when("/",{templateUrl:"partials/index",controller:"CoreCtrl"}).when("/login",{templateUrl:"login",controller:"LoginCtrl"}).otherwise("/")});return angular.bootstrap(doc.body,["GOSS"])})(angular,document,HTML,jQuery);