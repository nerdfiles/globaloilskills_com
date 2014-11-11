(($, F7, D7) ->

  testEmail = (email, resumeFile, handleData) ->
    $.ajax
      url: "//#{window.location.hostname}/api/make/user/?email=#{email}&fileToUpload=#{resumeFile}"
      success: (data) ->
        handleData data
        return
    return

  $button = $ '.wpcf7-submit'

  $button.on 'click.checkEmail', (e) ->
    #e.preventDefault()
    # We're actually checking for the e-mail 
    # address here from Contact Form 7.
    $emailEntry = $button.closest('form').find('input[name="your-email"]')
    $resumeEntry = $button.closest('form').find('#resume')
    email = $emailEntry.val()
    resumeFile = $resumeEntry.val()
    console.log resumeFile
    testEmail email, resumeFile, (data) ->
      console.log "Creating User: #{data}"

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
)(jQuery, Framework7, Dom7)