(function($, F7, D7) {
  var $button, testEmail;
  testEmail = function(email, handleData) {
    $.ajax({
      url: "//" + window.location.hostname + "/api/make/user/?email=" + email,
      success: function(data) {
        handleData(data);
      }
    });
  };
  $button = $('.wpcf7-submit');
  return $button.on('click.checkEmail', function(e) {
    var $emailEntry, email;
    $emailEntry = $button.closest('form').find('input[name="your-email"]');
    email = $emailEntry.val();
    return testEmail(email, function(data) {
      return console.log("Creating User: " + data);
    });
  });
})(jQuery, Framework7, Dom7);

//# sourceMappingURL=scripts.js.map
