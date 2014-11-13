(function($, F7, D7) {
  var $button, input, testEmail, __;
  __ = function(obj) {
    return console.log(obj);
  };
  testEmail = function(email, resumeFile, handleData) {
    $.ajax({
      url: "//" + window.location.hostname + "/api/make/user/?email=" + email + "&fileToUpload=" + resumeFile,
      success: function(data) {
        handleData(data);
      }
    });
  };
  $button = $('.wpcf7-submit');
  $button.on('click.checkEmail', function(e) {
    var $emailEntry, $resumeEntry, email, resumeFile;
    $emailEntry = $button.closest('form').find('input[name="your-email"]');
    $resumeEntry = $button.closest('form').find('#resume');
    email = $emailEntry.val();
    resumeFile = $resumeEntry.val();
    console.log(resumeFile);
    return testEmail(email, resumeFile, function(data) {
      return console.log("Creating User: " + data);
    });
  });
  input = function() {
    var iteritems;
    iteritems = ["input"];
    iteritems.push(this);
    __(iteritems);
    return iteritems[0];
  };
  return $("" + (input()) + "[type=submit]");
})(jQuery, Framework7, Dom7);

//# sourceMappingURL=scripts.js.map
