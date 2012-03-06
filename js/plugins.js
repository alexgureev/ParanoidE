
// usage: log('inside coolFunc', this, arguments);
// paulirish.com/2009/log-a-lightweight-wrapper-for-consolelog/
window.log = function(){
  log.history = log.history || [];   // store logs to an array for reference
  log.history.push(arguments);
  if(this.console) {
    arguments.callee = arguments.callee.caller;
    var newarr = [].slice.call(arguments);
    (typeof console.log === 'object' ? log.apply.call(console.log, console, newarr) : console.log.apply(console, newarr));
  }
};

// make it safe to use console.log always
(function(b){function c(){}for(var d="assert,count,debug,dir,dirxml,error,exception,group,groupCollapsed,groupEnd,info,log,timeStamp,profile,profileEnd,time,timeEnd,trace,warn".split(","),a;a=d.pop();){b[a]=b[a]||c}})((function(){try
{console.log();return window.console;}catch(err){return window.console={};}})());

!function( $ ){

	  "use strict"

	 /* DROPDOWN CLASS DEFINITION
	  * ========================= */

	  var toggle = '[data-toggle="dropdown"]'
	    , Dropdown = function ( element ) {
	        var $el = $(element).on('click.dropdown.data-api', this.toggle)
	        $('html').on('click.dropdown.data-api', function () {
	          $el.parent().removeClass('open')
	        })
	      }

	  Dropdown.prototype = {

	    constructor: Dropdown

	  , toggle: function ( e ) {
	      var $this = $(this)
	        , selector = $this.attr('data-target')
	        , $parent
	        , isActive

	      if (!selector) {
	        selector = $this.attr('href')
	        selector = selector && selector.replace(/.*(?=#[^\s]*$)/, '') //strip for ie7
	      }

	      $parent = $(selector)
	      $parent.length || ($parent = $this.parent())

	      isActive = $parent.hasClass('open')

	      clearMenus()
	      !isActive && $parent.toggleClass('open')

	      return false
	    }

	  }

	  function clearMenus() {
	    $(toggle).parent().removeClass('open')
	  }


	  /* DROPDOWN PLUGIN DEFINITION
	   * ========================== */

	  $.fn.dropdown = function ( option ) {
	    return this.each(function () {
	      var $this = $(this)
	        , data = $this.data('dropdown')
	      if (!data) $this.data('dropdown', (data = new Dropdown(this)))
	      if (typeof option == 'string') data[option].call($this)
	    })
	  }

	  $.fn.dropdown.Constructor = Dropdown


	  /* APPLY TO STANDARD DROPDOWN ELEMENTS
	   * =================================== */

	  $(function () {
	    $('html').on('click.dropdown.data-api', clearMenus)
	    $('body').on('click.dropdown.data-api', toggle, Dropdown.prototype.toggle)
	  })

	}( window.jQuery )

!function( $ ){

  "use strict"

 /* CSS TRANSITION SUPPORT (https://gist.github.com/373874)
  * ======================================================= */

  var transitionEnd

  $(document).ready(function () {

    $.support.transition = (function () {
      var thisBody = document.body || document.documentElement
        , thisStyle = thisBody.style
        , support = thisStyle.transition !== undefined || thisStyle.WebkitTransition !== undefined || thisStyle.MozTransition !== undefined || thisStyle.MsTransition !== undefined || thisStyle.OTransition !== undefined
      return support
    })()

    // set CSS transition event type
    if ( $.support.transition ) {
      transitionEnd = "TransitionEnd"
      if ( $.browser.webkit ) {
      	transitionEnd = "webkitTransitionEnd"
      } else if ( $.browser.mozilla ) {
      	transitionEnd = "transitionend"
      } else if ( $.browser.opera ) {
      	transitionEnd = "oTransitionEnd"
      }
    }

  })


 /* MODAL PUBLIC CLASS DEFINITION
  * ============================= */

  var Modal = function ( content, options ) {
    this.settings = $.extend({}, $.fn.modal.defaults, options)
    this.$element = $(content)
      .delegate('.close', 'click.modal', $.proxy(this.hide, this))

    if ( this.settings.show ) {
      this.show()
    }

    return this
  }

  Modal.prototype = {

      toggle: function () {
        return this[!this.isShown ? 'show' : 'hide']()
      }

    , show: function () {
        var that = this
        this.isShown = true
        this.$element.trigger('show')

        escape.call(this)
        backdrop.call(this, function () {
          var transition = $.support.transition && that.$element.hasClass('fade')

          that.$element
            .appendTo(document.body)
            .show()

          if (transition) {
            that.$element[0].offsetWidth // force reflow
          }

          that.$element.addClass('in')

          transition ?
            that.$element.one(transitionEnd, function () { that.$element.trigger('shown') }) :
            that.$element.trigger('shown')

        })

        return this
      }

    , hide: function (e) {
        e && e.preventDefault()

        if ( !this.isShown ) {
          return this
        }

        var that = this
        this.isShown = false

        escape.call(this)

        this.$element
          .trigger('hide')
          .removeClass('in')

        $.support.transition && this.$element.hasClass('fade') ?
          hideWithTransition.call(this) :
          hideModal.call(this)

        return this
      }

  }


 /* MODAL PRIVATE METHODS
  * ===================== */

  function hideWithTransition() {
    // firefox drops transitionEnd events :{o
    var that = this
      , timeout = setTimeout(function () {
          that.$element.unbind(transitionEnd)
          hideModal.call(that)
        }, 500)

    this.$element.one(transitionEnd, function () {
      clearTimeout(timeout)
      hideModal.call(that)
    })
  }

  function hideModal (that) {
    this.$element
      .hide()
      .trigger('hidden')

    backdrop.call(this)
  }

  function backdrop ( callback ) {
    var that = this
      , animate = this.$element.hasClass('fade') ? 'fade' : ''
    if ( this.isShown && this.settings.backdrop ) {
      var doAnimate = $.support.transition && animate

      this.$backdrop = $('<div class="modal-backdrop ' + animate + '" />')
        .appendTo(document.body)

      if ( this.settings.backdrop != 'static' ) {
        this.$backdrop.click($.proxy(this.hide, this))
      }

      if ( doAnimate ) {
        this.$backdrop[0].offsetWidth // force reflow
      }

      this.$backdrop.addClass('in')

      doAnimate ?
        this.$backdrop.one(transitionEnd, callback) :
        callback()

    } else if ( !this.isShown && this.$backdrop ) {
      this.$backdrop.removeClass('in')

      $.support.transition && this.$element.hasClass('fade')?
        this.$backdrop.one(transitionEnd, $.proxy(removeBackdrop, this)) :
        removeBackdrop.call(this)

    } else if ( callback ) {
       callback()
    }
  }

  function removeBackdrop() {
    this.$backdrop.remove()
    this.$backdrop = null
  }

  function escape() {
    var that = this
    if ( this.isShown && this.settings.keyboard ) {
      $(document).bind('keyup.modal', function ( e ) {
        if ( e.which == 27 ) {
          that.hide()
        }
      })
    } else if ( !this.isShown ) {
      $(document).unbind('keyup.modal')
    }
  }


 /* MODAL PLUGIN DEFINITION
  * ======================= */

  $.fn.modal = function ( options ) {
    var modal = this.data('modal')

    if (!modal) {

      if (typeof options == 'string') {
        options = {
          show: /show|toggle/.test(options)
        }
      }

      return this.each(function () {
        $(this).data('modal', new Modal(this, options))
      })
    }

    if ( options === true ) {
      return modal
    }

    if ( typeof options == 'string' ) {
      modal[options]()
    } else if ( modal ) {
      modal.toggle()
    }

    return this
  }

  $.fn.modal.Modal = Modal

  $.fn.modal.defaults = {
    backdrop: false
  , keyboard: false
  , show: false
  }


 /* MODAL DATA- IMPLEMENTATION
  * ========================== */

  $(document).ready(function () {
    $('body').delegate('[data-controls-modal]', 'click', function (e) {
      e.preventDefault()
      var $this = $(this).data('show', true)
      $('#' + $this.attr('data-controls-modal')).modal( $this.data() )
    })
  })

}( window.jQuery || window.ender );


!function( $ ){

  "use strict"

  function activate ( element, container ) {
    container
      .find('> .active')
      .removeClass('active')
      .find('> .dropdown-menu > .active')
      .removeClass('active')

    element.addClass('active')

    if ( element.parent('.dropdown-menu') ) {
      element.closest('li.dropdown').addClass('active')
    }
  }

  function tab( e ) {
    var $this = $(this)
      , $ul = $this.closest('ul:not(.dropdown-menu)')
      , href = $this.attr('href')
      , previous
      , $href

    if ( /^#\w+/.test(href) ) {
      e.preventDefault()

      if ( $this.parent('li').hasClass('active') ) {
        return
      }

      previous = $ul.find('.active a').last()[0]
      $href = $(href)

      activate($this.parent('li'), $ul)
      activate($href, $href.parent())

      $this.trigger({
        type: 'change'
      , relatedTarget: previous
      })
    }
  }


 /* TABS/PILLS PLUGIN DEFINITION
  * ============================ */

  $.fn.tabs = $.fn.pills = function ( selector ) {
    return this.each(function () {
      $(this).delegate(selector || '.tabs li > a, .pills > li > a', 'click', tab)
    })
  }

  $(document).ready(function () {
    $('body').tabs('ul[data-tabs] li > a, ul[data-pills] > li > a')
  })

}( window.jQuery || window.ender );

!function( $ ){

	  "use strict"

	  /* CSS TRANSITION SUPPORT (https://gist.github.com/373874)
	   * ======================================================= */

	   var transitionEnd

	   $(document).ready(function () {

	     $.support.transition = (function () {
	       var thisBody = document.body || document.documentElement
	         , thisStyle = thisBody.style
	         , support = thisStyle.transition !== undefined || thisStyle.WebkitTransition !== undefined || thisStyle.MozTransition !== undefined || thisStyle.MsTransition !== undefined || thisStyle.OTransition !== undefined
	       return support
	     })()

	     // set CSS transition event type
	     if ( $.support.transition ) {
	       transitionEnd = "TransitionEnd"
	       if ( $.browser.webkit ) {
	        transitionEnd = "webkitTransitionEnd"
	       } else if ( $.browser.mozilla ) {
	        transitionEnd = "transitionend"
	       } else if ( $.browser.opera ) {
	        transitionEnd = "oTransitionEnd"
	       }
	     }

	   })

	 /* ALERT CLASS DEFINITION
	  * ====================== */

	  var Alert = function ( content, options ) {
	    if (options == 'close') return this.close.call(content)
	    this.settings = $.extend({}, $.fn.alert.defaults, options)
	    this.$element = $(content)
	      .delegate(this.settings.selector, 'click', this.close)
	  }

	  Alert.prototype = {

	    close: function (e) {
	      var $element = $(this)
	        , className = 'alert-message'

	      $element = $element.hasClass(className) ? $element : $element.parent()

	      e && e.preventDefault()
	      $element.removeClass('in')

	      function removeElement () {
	        $element.remove()
	      }

	      $.support.transition && $element.hasClass('fade') ?
	        $element.bind(transitionEnd, removeElement) :
	        removeElement()
	    }

	  }


	 /* ALERT PLUGIN DEFINITION
	  * ======================= */

	  $.fn.alert = function ( options ) {

	    if ( options === true ) {
	      return this.data('alert')
	    }

	    return this.each(function () {
	      var $this = $(this)
	        , data

	      if ( typeof options == 'string' ) {

	        data = $this.data('alert')

	        if (typeof data == 'object') {
	          return data[options].call( $this )
	        }

	      }

	      $(this).data('alert', new Alert( this, options ))

	    })
	  }

	  $.fn.alert.defaults = {
	    selector: '.close'
	  }

	  $(document).ready(function () {
	    new Alert($('body'), {
	      selector: '.alert-message[data-alert] .close'
	    })
	  })

	}( window.jQuery || window.ender );
	
	!function( $ ){

		  "use strict"

		  function setState(el, state) {
		    var d = 'disabled'
		      , $el = $(el)
		      , data = $el.data()

		    state = state + 'Text'
		    data.resetText || $el.data('resetText', $el.html())

		    $el.html( data[state] || $.fn.button.defaults[state] )

		    setTimeout(function () {
		      state == 'loadingText' ?
		        $el.addClass(d).attr(d, d) :
		        $el.removeClass(d).removeAttr(d)
		    }, 0)
		  }

		  function toggle(el) {
		    $(el).toggleClass('active')
		  }

		  $.fn.button = function(options) {
		    return this.each(function () {
		      if (options == 'toggle') {
		        return toggle(this)
		      }
		      options && setState(this, options)
		    })
		  }

		  $.fn.button.defaults = {
		    loadingText: 'loading...'
		  }

		  $(function () {
		    $('body').delegate('.btn[data-toggle]', 'click', function () {
		      $(this).button('toggle')
		    })
		  })

		}( window.jQuery || window.ender );
		
		!function( $ ){

			  "use strict"

			 /* BUTTON PUBLIC CLASS DEFINITION
			  * ============================== */

			  var Button = function ( element, options ) {
			    this.$element = $(element)
			    this.options = $.extend({}, $.fn.button.defaults, options)
			  }

			  Button.prototype = {

			      constructor: Button

			    , setState: function ( state ) {
			        var d = 'disabled'
			          , $el = this.$element
			          , data = $el.data()
			          , val = $el.is('input') ? 'val' : 'html'

			        state = state + 'Text'
			        data.resetText || $el.data('resetText', $el[val]())

			        $el[val](data[state] || this.options[state])

			        // push to event loop to allow forms to submit
			        setTimeout(function () {
			          state == 'loadingText' ?
			            $el.addClass(d).attr(d, d) :
			            $el.removeClass(d).removeAttr(d)
			        }, 0)
			      }

			    , toggle: function () {
			        var $parent = this.$element.parent('[data-toggle="buttons-radio"]')

			        $parent && $parent
			          .find('.active')
			          .removeClass('active')

			        this.$element.toggleClass('active')
			      }

			  }


			 /* BUTTON PLUGIN DEFINITION
			  * ======================== */

			  $.fn.button = function ( option ) {
			    return this.each(function () {
			      var $this = $(this)
			        , data = $this.data('button')
			        , options = typeof option == 'object' && option
			      if (!data) $this.data('button', (data = new Button(this, options)))
			      if (option == 'toggle') data.toggle()
			      else if (option) data.setState(option)
			    })
			  }

			  $.fn.button.defaults = {
			    loadingText: 'loading...'
			  }

			  $.fn.button.Constructor = Button


			 /* BUTTON DATA-API
			  * =============== */

			  $(function () {
			    $('body').on('click.button.data-api', '[data-toggle^=button]', function ( e ) {
			      $(e.target).button('toggle')
			    })
			  })

			}( window.jQuery )	
