(function( $ ) {
  'use strict';

  /**
   * All of the code for your admin-facing JavaScript source
   * should reside in this file.
   *
   * Note: It has been assumed you will write jQuery code here, so the
   * $ function reference has been prepared for usage within the scope
   * of this function.
   *
   * This enables you to define handlers, for when the DOM is ready:
   *
   * $(function() {
   *
   * });
   *
   * When the window is loaded:
   *
   * $( window ).load(function() {
   *
   * });
   *
   * ...and/or other possibilities.
   *
   * Ideally, it is not considered best practise to attach more than a
   * single DOM-ready or window-load handler for a particular page.
   * Although scripts in the WordPress core, Plugins and Themes may be
   * practising this, we should strive to set a better example in our own work.
   */

  $.MTALeadGenPopupAdminSection = function (element) { //renamed arg for readability
    //store the passed element as a property of the created instance.
    this.element = (element instanceof $) ? element : $(element);

    //attach an array of possible layout selection classes to the object
    this.layout_selector = document.getElementById("mta_leadgenpopup_layout");
    this.layout_selector_values = [];
    for (var i = 0; i < this.layout_selector.length; i++) {
      if(this.layout_selector.options[i].value !== '') {
        this.layout_selector_values.push(this.layout_selector.options[i].value);
      }
    }

    //attach an array of possible trigger selection classes to the object
    this.trigger_selector = document.getElementById("mta_leadgenpopup_trigger");
    this.trigger_selector_values = [];
    for (var j = 0; j < this.trigger_selector.length; j++) {
      if(this.trigger_selector.options[j].value !== '') {
        this.trigger_selector_values.push(this.trigger_selector.options[j].value);
      }
    }
    //attach an array of possible enable customer selection classes to the object
    this.enable_custom = document.getElementById("mta_leadgenpopup_enable_custom");
    this.enable_custom_values = [];
    for (var k = 0; k < this.enable_custom.length; k++) {
      if(this.enable_custom.options[k].value !== '') {
        this.enable_custom_values.push(this.enable_custom.options[k].value);
      }
    }
  };

  $.MTALeadGenPopupAdminSection.prototype = {

    init: function () {
      //`this` references the instance object inside of an instace's method,
      //however `this` is set to reference a DOM element inside jQuery event
      //handler functions' scope. So we take advantage of JS's lexical scope
      //and assign the `this` reference to another variable that we can access
      //inside the jQuery handlers
      var that = this;

      this.layout_selector.addEventListener("change", function() {
        //loop through the layout_selector_values and remove them from the dom wrapper element before adding the new item
        for (var i = 0; i < that.layout_selector_values.length; i++) {
          $('.mta-leadgenpopup-meta-wrapper').removeClass(that.layout_selector_values[i]);
        }
        //add the new layout class value
        $('.mta-leadgenpopup-meta-wrapper').addClass(this.value);
      });

      this.trigger_selector.addEventListener("change", function() {
        //loop through the layout_selector_values and remove them from the dom wrapper element before adding the new item
        for (var j = 0; j < that.trigger_selector_values.length; j++) {
          $('.mta-leadgenpopup-meta-wrapper').removeClass(that.trigger_selector_values[j]);
        }
        //add the new trigger class value
        $('.mta-leadgenpopup-meta-wrapper').addClass(this.value);
      });

      this.enable_custom.addEventListener("change", function() {
        //loop through the layout_selector_values and remove them from the dom wrapper element before adding the new item
        for (var k = 0; k < that.enable_custom_values.length; k++) {
          $('.mta-leadgenpopup-meta-wrapper').removeClass(that.enable_custom_values[k]);
        }
        //add the new trigger class value
        $('.mta-leadgenpopup-meta-wrapper').addClass(this.value);
      });
    }
  };

  $(function() {
    //if the '#mta_leadgenpopup_wrapper' item exists on the page initialize the exitintent
    if ( $( "#mta_leadgenpopup_section" ).length ) {
      var leadgenpopup = new $.MTALeadGenPopupAdminSection($("#mta_leadgenpopup_section"));
      leadgenpopup.init();
    }
  });

})( jQuery );
