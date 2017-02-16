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
  $.MTALeadGenPopup = function (element) { //renamed arg for readability
    //store the passed element as a property of the created instance.
    this.element = (element instanceof $) ? element : $(element);

    //if avaliable, get some information stored in a local variable
    this.leadgen_timer      = element.data('leadgen-timer');
    this.leadgen_trigger    = element.data('leadgen-trigger');
    this.local_storage_key  = element.data('page-name');
    this.local_storage      = JSON.parse(localStorage.getItem(this.local_storage_key));
    this.dismissed_time     = (this.local_storage !== null) ? (typeof this.local_storage.dismissedTime !== 'undefined') ? this.local_storage.dismissedTime : 0 : 0;
  };

  $.MTALeadGenPopup.prototype = {

    init: function () {
      //`this` references the instance object inside of an instace's method,
      //however `this` is set to reference a DOM element inside jQuery event
      //handler functions' scope. So we take advantage of JS's lexical scope
      //and assign the `this` reference to another variable that we can access
      //inside the jQuery handlers

      var that = this;

      switch(this.leadgen_trigger) {

        case 'exit':
          //show the popup when the users mouse leaves the page
          $(document).on('mouseleave', mouseLeavePage);
          break;

        case 'timer':
          //show the popup after a specified amount of time
          setTimeout(function() {
            timeTrigger();
          }, this.leadgen_timer);
          break;

        case 'exit-and-timer':
          //show the popup after a specified amount of time, or when the users mouse leaves the page, whichever come first
          setTimeout(function() {
            timeTrigger();
          }, this.leadgen_timer);

          $(document).on('mouseleave', mouseLeavePage);
          break;
      }

      function mouseLeavePage(e){
        //trigger the popup event
        that.triggerPopup('Lead Generation - Page Exit Popup');

        //trigger an analytics event
        //that.triggerAnalyticsEvent('Lead Generation Popup', 'Page Exit', that.local_storage_key, 1);
      }

      function timeTrigger(){
        that.triggerPopup('Lead Generation - Page Timer Popup: ' + (that.leadgen_timer/1000) + 's');

        //trigger an analytics event
        //that.triggerAnalyticsEvent('Lead Generation Popup', 'Page Timer - ' + that.leadgen_timer, that.local_storage_key, 1);
      }

      $('body').on('click', '.close-mta-leadgenpopup', function(e) {
        e.preventDefault();
        //store the popup item and time it was dismissed in local storage
        var closeItem = $(this).data('leadgenclose');
        var object = {pageName: closeItem, dismissedTime: new Date().getTime()};
        localStorage.setItem(closeItem, JSON.stringify(object));

        //trigger an analytics event
        that.triggerAnalyticsEvent('Lead Generation Popup', 'Lead Generation - Dismiss Popup', that.local_storage_key, 1);

        //reset the last dismissed time
        that.reset();
        //remove the body class that shows the popup
        $('body').removeClass('show-leadgenpopup');
      });
    },
    reset: function () {
      this.local_storage  = JSON.parse(localStorage.getItem(this.local_storage_key));
      this.dismissed_time = (typeof this.local_storage.dismissedTime !== 'undefined') ? this.local_storage.dismissedTime : 0;
    },
    triggerPopup: function (type) {
      var disable_for = 7*60*60*1000; //1 week
      //var disable_for = 10*1000; //10 seconds (here for testing)

      if(((new Date().getTime() - this.dismissed_time) > disable_for) && !$('body').hasClass('show-leadgenpopup')) {

        //if the body doesnt have the show-leadgenpopup trigger an alalytics event
        if(!$('body').hasClass('show-leadgenpopup')) {
          this.triggerAnalyticsEvent('Lead Generation Popup', type, this.local_storage_key, 1);
        }
        //add a class to the body to show the lean generation popu
        $('body').addClass('show-leadgenpopup');
      }
    },
    triggerAnalyticsEvent: function (evCat, evAct, evLab, evVal) {
      try {
        ga('send', 'event', evCat, evAct, evLab, evVal);
      } catch (e) {
        console.log(e);
      }
    }
  };

  $(function() {
    //if the '#mta_leadgenpopup_wrapper' item exists on the page initialize the exitintent
    if ( $( "#mta_leadgenpopup_wrapper" ).length ) {
      var leadgenpopup = new $.MTALeadGenPopup($("#mta_leadgenpopup_wrapper"));
      leadgenpopup.init();
    }
  });

})( jQuery );
