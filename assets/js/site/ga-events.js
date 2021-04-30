(function ($) {
  if (typeof gtag == 'function') {
    // mailto function
    $('a[href^="mailto:"]').click(function(){
      gtag('event', 'contact', { 'event_category' : 'Email Enquiry', 'event_action' : 'Mailto Click', 'event_label' : $(this).attr('href')});
      return true;
      });
    // tel: function
    $('a[href^="tel:"]').click(function(){
      gtag('event', 'contact', { 'event_category' : 'Telephone Enquiry', 'event_action' : 'Tel Link Click', 'event_label' : $(this).attr('href')});
      return true;
      });
    // pdf function
    $('a[href$=".pdf"]').click(function(){
      gtag('event', 'contact', { 'event_category' : 'PDF Download', 'event_action' : 'Download', 'event_label' : $(this).attr('href')});
      return true;
      });
    //external link click
    $("a:not([href*='" + document.domain + "'],[href*='mailto'],[href*='tel'])").click(function(event) {
      if($(this).attr('href')!="#"){
        gtag('event', 'contact', { 'event_category': 'External Link', 'event_action': 'Link Click', 'event_label': $(this).attr('href') });
        return true;
      }
      });
  }
})( jQuery );
