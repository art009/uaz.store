/**
 * Detects mobile type
 *
 * @returns {boolean}
 */
function isMobile() {

	var media = window
		.getComputedStyle(document.querySelector('body'), '::after')
		.getPropertyValue('content')
		.replace(/"/g, "")
		.replace(/'/g, "");

    return (media == 'mobile')
}

/**
 * Init/Destroy sections animation
 */
function initAnimatedSections() {
	var cont = $('main.site-index'),
		instance = $(cont).animateSection('instance');

	if (isMobile()) {
		(typeof instance != 'undefined') && $(cont).animateSection('destroy');
	} else {
		(typeof instance == 'undefined') && $(cont).animateSection();
	}
}

/**
 * Sections widget
 */
(function($) {

	$.widget('main.animateSection', {

		index: 0,
		max: 2,
		animated: false,

		options: {
			animatedEl: null,
			bullets: null,
			isMobile: false
		},

		_create: function() {

			self = this;

			self.element.find('section').wrapAll('<div class="sections-container"><div class="sections-inner"></div></div>');
			self.option('animatedEl', self.element.find('.sections-inner'));
			self.option('bullets', self.element.find('nav ul li a'));

			$(self.option('bullets')).on('click', function () {
				self.to($(this).index('.nav-bullet'));
			});

			$(window).on('DOMMouseScroll mousewheel', self.scroll);

			$(document).on('keydown', function(event){
				if (event.which == '39' || event.which == '40') {
					event.preventDefault();
					self.next();
				} else if(event.which == '37' || event.which == '38') {
					event.preventDefault();
					self.prev();
				}
			});

			self.change();
		},

		_destroy: function ()
		{
			$(window).off('DOMMouseScroll mousewheel', self.scroll);
			$(window).off('scroll', self.scroll);
			$(self.option('bullets')).off('click');
			$(document).off('keydown');

			self.element.find('section').unwrap().unwrap();
		},

		canAnimate: function() {
			return !self.animated;
		},

		change: function () {
			var h = self.index * -100;
			self.animated = true;
			$(self.option('bullets')).removeClass('active');
			$(self.option('bullets')).eq(self.index).addClass('active');
			$(self.option('animatedEl')).stop().animate({marginTop: h +'vh'}, 900, 'easeOutCubic', function () {
				self.animated = false;
			});
		},

		next: function () {
			self.to(this.index + 1);
		},

		prev: function () {
			self.to(this.index - 1);
		},

		to: function (section) {
			if (section >= 0 && section <= this.max) {
				this.index = section;
				this.change();
			}
		},

		scroll: function (event) {
			if (!self.canAnimate()) {
				return false;
			}

			if (event.originalEvent.detail < 0 || event.originalEvent.wheelDelta > 0) {
				self.prev();
			} else {
				self.next();
			}
		}

	});
})(jQuery);

/**
 * Document READY
 */
$(document).ready(function($){

	/**
	 * Bootstrap tooltips
	 */
	$('[data-toggle="tooltip"]').tooltip();

	initAnimatedSections();

	$(window).on('resize', function(){

		initAnimatedSections();

	});

});
