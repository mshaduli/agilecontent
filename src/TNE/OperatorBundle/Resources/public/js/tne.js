var TNE = TNE || {};

TNE.core = (function (doc, $, undefined) {

    // Private variables
    var barLinks = $('#panel0').find('ul.inline a'),
        headerDrop = $('#headerDrop'),
        ww = $(window).width(),
        that = this,
        navPrevious = $('body');

    // Initialise on page load
    init = function () {
      nav();
      responseInit();
      $('.carousel').carousel('pause'); // stop any carousels from playing
    },

    nav = function() {

      // mob nav https://github.com/tessa-lt/dropdowns
      $(".nav li a").each(function() {
        if ($(this).next().length > 0) {
          $(this).addClass("parent");
        };
      });
      $(".toggleMenu").click(function(e) {
        e.preventDefault();
        $(this).toggleClass("active");
        $("#mobileNav").toggle();
      });

      adjustMenu();

      $(window).bind('resize orientationchange', function() {
        ww = $(window).width();
        adjustMenu();
      });

    },
    
    that.desktopNav = function() {

      // this needs further implementation or suggest leave as
      // var nlform = new NLForm( document.getElementById( 'nl-form' ) ); 
      // natural language form

      barLinks.on('click', function(){  // slide down the panel when required

        barLinks.not(this).removeClass('on');
        
        if ($(this).hasClass('on')) { // :active state
          $(this).removeClass('on');
        } else {
          $(this).addClass('on');
          headerDrop.find('div.navContent').not(this).css('position','absolute').hide();
        }

        if ( ($(this).hasClass('toggleDesktop')) || ($(this).hasClass('toggleDesktopShare')) ) {
          var ind = 'navContent' + $(this).parent('li').index();
          console.log($(this).parent()[0])
          console.log(ind)
          
          if ( !$(this).parents('ul').hasClass('nav') ) {
            ind = ind + 'top';
          }

            if ($(this)[0]!=navPrevious[0]) {
              // Do this if new link
              headerDrop.find('#'+ind+'').css('position','relative').fadeIn('slow');
            } else {
              // Do this if current link
              headerDrop.find('#'+ind+'').css('position','relative').slideToggle();
            }
            navPrevious = $(this);
        }

      });

    },

    adjustMenu = function() {
      console.log(ww)
      if (ww <= 1024) {
        $('#desktopNav,#headerDrop').hide();
        $(".toggleMenu").css("display", "inline-block");
        if (!$(".toggleMenu").hasClass("active")) {
          $("#mobileNav").hide();
        } else {
          $("#mobileNav").show();
        }
        $(".nav li").unbind('mouseenter mouseleave');
        $(".nav li a.parent").unbind('click').bind('click', function(e) {
          // must be attached to anchor element to prevent bubbling
          e.preventDefault();
          $(this).parent("li").toggleClass("hover");
        });
      } 
      else if (ww > 1024) {
        $('#desktopNav,#headerDrop').show();
        $(".toggleMenu, .toggle").css("display", "none");
        $("#mobileNav").hide();
        barLinks.unbind('click');
        that.desktopNav();
      }
    },

    responseInit = function (){
      Response.create({
          prop: "width"  // "width" "device-width" "height" "device-height" or "device-pixel-ratio"
        , prefix: "min-width- r src"  // the prefix(es) for your data attributes (aliases are optional)
        , breakpoints: [1281,1025,961,641,481,320,0] // min breakpoints (defaults for width/device-width)
        , lazy: true // optional param - data attr contents lazyload rather than whole page at once
      });
    }    


    // Public variables, these are exposed so that selected methods can be called externally
    return {
        run: function () {
            init();
        }
    };

})(document, jQuery);

$(function () {
    TNE.core.run();
});