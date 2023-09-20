(function($) {
  /**
   * For each element with class .count-to, this function looks for the following data attributes:
   * data-count, data-prefix, data-suffix
   * If the element is visible in the viewport, the function will use the data-count attribute to
   * count up to the number, appending the prefix/suffix and adding commas where necessary
   */

  // @link https://stackoverflow.com/questions/49877610/modify-countup-jquery-functions-to-include-commas
  function addCommas(nStr) {
    return nStr.toString().replace(/(\d)(?=(\d\d\d)+(?!\d))/g, "$1,");
  }

  // Only run if IntersectionObserver supported
  if(!!window.IntersectionObserver){
    // @link https://css-tricks.com/a-few-functional-uses-for-intersection-observer-to-know-when-an-element-is-in-view/

  let countUpObserver = new IntersectionObserver(
    (entries, countUpObserver) => {
    entries.forEach(entry => {
      if(entry.isIntersecting){
        countUp(entry.target);
       // countUpObserver.unobserve(entry.target); // Comment in to turn off observing (animate only once)
      }
      });
    }, {rootMargin: "0px 0px -20px 0px"}); // Wait until a slither of the number is visible
    document.querySelectorAll('.count-to').forEach(number => countUpObserver.observe(number));
  }

  function countUp(number) {
      var $this = $(number),
          countTo = $this.attr('data-count'),
          prefix = '', // Defaults
          suffix = '', // Defaults
          countDuration = 3000; // Defaults

      $this.text('0'); // Reset count to 0

      if (number.hasAttribute('data-prefix')) prefix = '<span class="small">'+$this.attr('data-prefix')+'</span>';
      if (number.hasAttribute('data-suffix')) suffix = '<span class="small">'+$this.attr('data-suffix')+'</span>';
      if (number.hasAttribute('data-duration')) countDuration = Number($this.attr('data-duration'));

      $this.removeClass('count-to'); // Prevents event firing in future.

      $({ countNum: $this.text() }).animate(
        {
          countNum: countTo
        },
        {
          duration: countDuration,
          easing: 'linear',
          step: function () {
            $this.html(prefix + addCommas(Math.floor(this.countNum)) + suffix);
          },
          complete: function () {
            $this.html(prefix + addCommas(this.countNum) + suffix);
          }
        });

  };

})( jQuery );
