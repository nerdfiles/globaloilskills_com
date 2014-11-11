(function($, F7, D7) {
  var $button, testEmail;
  testEmail = function(email, handleData) {
    $.ajax({
      url: "//local.globaloilskills.com/api/make/user/?email=" + email,
      success: function(data) {
        handleData(data);
      }
    });
  };
  $button = $('.wpcf7-submit');
  return $button.on('click.checkEmail', function(e) {
    var $emailEntry, email;
    e.preventDefault();
    $emailEntry = $button.closest('form').find('input[name="your-email"]');
    email = $emailEntry.val();
    return testEmail(email, function(data) {
      return console.log(data);
    });
  });
})(jQuery, Framework7, Dom7);

//# sourceMappingURL=scripts.js.map
