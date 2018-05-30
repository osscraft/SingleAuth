/**
 * @link http://lions-mark.com/jquery/scrollTo
 * target A selector, element, or number.
 * options A map of additional options to pass to the method. Supported keys:
 *    scrollTarget: A element, string, or number which indicates desired scroll position.
 *    offsetTop: A number that defines additional spacing above scroll target.
 *    duration: A string or number determining how long the animation will run.
 *    easing: A string indicating which easing function to use for the transition.
 * complete A function to call once the animation is complete.
 */
(function ($, document, undefined) {
  $.fn.scrollTo = function( target, options, callback ){
    if(typeof options == 'function' && arguments.length == 2){ callback = options; options = target; }
    var settings = $.extend({
      scrollTarget  : target,
      offsetTop     : 50,
      duration      : 500,
      easing        : 'swing'
    }, options);
    return this.each(function(){
      var scrollPane = $(this);
      var scrollTarget = (typeof settings.scrollTarget == "number") ? settings.scrollTarget : $(settings.scrollTarget);
      var scrollY = (typeof scrollTarget == "number") ? scrollTarget : scrollTarget.offset().top + scrollPane.scrollTop() - parseInt(settings.offsetTop);
      scrollPane.animate({scrollTop : scrollY }, parseInt(settings.duration), settings.easing, function(){
        if (typeof callback == 'function') { callback.call(this); }
      });
    });
  };
})(jQuery, document);
