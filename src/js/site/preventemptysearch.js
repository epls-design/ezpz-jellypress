(function($) {

  $('#searchform').submit(function(e) {
    var s = $( this ).find("#s");
          if (!s.val()) {
      e.preventDefault();
      alert("Please enter a search term");
      $('#s').focus();
    }
  });

})( jQuery );
