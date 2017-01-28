$(document).ready(function($){
	//variables
	var delta = 0,
        scrollThreshold = 1,
        animating = false;
    
    //DOM elements
    var sections = $('main.site-index section'),
        bullets = $('main.site-index nav ul li a');

	
	//check the media query and bind corresponding events
	var MQ = deviceType(),
		bindToggle = false;
	
	bindEvents(MQ, true);
	
	$(window).on('resize', function(){
		MQ = deviceType();
		bindEvents(MQ, bindToggle);
		if( MQ == 'mobile' ) bindToggle = true;
		if( MQ == 'desktop' ) bindToggle = false;
	});

    function bindEvents(MQ, bool) {

    	if( MQ == 'desktop' && bool) {
    		//bind the animation to the window scroll event, arrows click and keyboard
			initHijacking();
			$(window).on('DOMMouseScroll mousewheel', scrollHijacking);
            bullets.on('click', toSection);
    		$(document).on('keydown', function(event){
				if (event.which=='40') {
					event.preventDefault();
					nextSection();
				} else if( event.which=='38') {
					event.preventDefault();
					prevSection();
				}
			});
			//set navigation arrows visibility
			checkNavigation();
		} else if( MQ == 'mobile' ) {
			//reset and unbind
			resetSectionStyle();
			$(window).off('DOMMouseScroll mousewheel', scrollHijacking);
			$(window).off('scroll', scrollAnimation);
            bullets.off('click', toSection);
    		$(document).off('keydown');
		}
    }

	function scrollAnimation(){
		//normal scroll - use requestAnimationFrame (if defined) to optimize performance
		(!window.requestAnimationFrame) ? animateSection() : window.requestAnimationFrame(animateSection);
	}

	function animateSection() {
		var scrollTop = $(window).scrollTop(),
			windowHeight = $(window).height(),
			windowWidth = $(window).width();

		sections.each(function(){
			var actualBlock = $(this),
				offset = scrollTop - actualBlock.offset().top;

			//according to animation type and window scroll, define animation parameters
			var animationValues = setSectionAnimation(offset, windowHeight);

			transformSection(actualBlock.children('div'), animationValues[0], animationValues[1], animationValues[2], animationValues[3], animationValues[4]);
			( offset >= 0 && offset < windowHeight ) ? actualBlock.addClass('visible') : actualBlock.removeClass('visible');
		});

		checkNavigation();
	}

	function transformSection(element, translateY, scaleValue, rotateXValue, opacityValue, boxShadow) {
		//transform sections - normal scroll
		element.velocity({
			translateY: translateY+'vh',
			scale: scaleValue,
			rotateX: rotateXValue,
			opacity: opacityValue,
			boxShadowBlur: boxShadow+'px',
			translateZ: 0,
		}, 0);
	}

	function initHijacking() {
		// initialize section style - scrollhijacking
		var visibleSection = sections.filter('.visible'),
			topSection = visibleSection.prevAll('section'),
			bottomSection = visibleSection.nextAll('section'),
			animationParams = selectAnimation(),
			animationVisible = animationParams[0],
			animationTop = animationParams[1],
			animationBottom = animationParams[2];

		visibleSection.children('div').velocity(animationVisible, 1, function(){
			visibleSection.css('opacity', 1);
	    	topSection.css('opacity', 1);
	    	bottomSection.css('opacity', 1);
		});
        topSection.children('div').velocity(animationTop, 0);
        bottomSection.children('div').velocity(animationBottom, 0);
	}

	function scrollHijacking (event) {
		// on mouse scroll - check if animate section
        if (event.originalEvent.detail < 0 || event.originalEvent.wheelDelta > 0) {
            delta--;
            ( Math.abs(delta) >= scrollThreshold) && prevSection();
        } else {
            delta++;
            (delta >= scrollThreshold) && nextSection();
        }
        return false;
    }

    function prevSection(event) {
    	//go to previous section
    	typeof event !== 'undefined' && event.preventDefault();
        changeSection(sections.filter('.visible').index('section') - 1);
    }

    function nextSection(event) {
    	typeof event !== 'undefined' && event.preventDefault();
        changeSection(sections.filter('.visible').index('section') + 1);
    }

    function toSection(event) {
    	typeof event !== 'undefined' && event.preventDefault();
        changeSection($(this).index('.nav-bullet'));
    }

    /**
     * Переход к указанному экрану
     *
     * @param index
     */
    function changeSection(index) {
        var section = sections.filter('.visible'),
            sectionIndex = $(section).index('section'),
            slaveSection = sections.eq(index),
            animationParams = selectAnimation(),
            next = (index > sectionIndex);

        if (!animating && sectionIndex != index && ((next && !section.is(':last-of-type')) || (!next && !section.is(':first-child')))) {
            animating = true;
            section
                .removeClass('visible')
                .children('div')
                .velocity(next ? animationParams[1] : animationParams[2], animationParams[3], animationParams[4]);

            slaveSection
                .addClass('visible')
                .children('div').velocity(animationParams[0], animationParams[3], animationParams[4], function(){
                    animating = false;
                });
        }
        resetScroll();
    }

    function resetScroll() {
        delta = 0;
        checkNavigation();
    }

    function checkNavigation() {
	    bullets.removeClass('active');
	    bullets.eq(sections.filter('.visible').index('section')).addClass('active');
	}

	function resetSectionStyle() {
		//on mobile - remove style applied with jQuery
		sections.children('div').each(function(){
			$(this).attr('style', '');
		});
	}


	function selectAnimation() {
		return ['translateNone', 'translateUp', 'translateDown', 800, 'easeInCubic'];
	}

	function setSectionAnimation(sectionOffset, windowHeight) {
		// select section animation - normal scroll
		var scale = 1,
			translateY = 100,
			rotateX = '0deg',
			opacity = 1,
			boxShadowBlur = 0;

		if( sectionOffset >= -windowHeight && sectionOffset <= 0 ) {
			// section entering the viewport
			translateY = (-sectionOffset)*100/windowHeight;

		} else if( sectionOffset > 0 && sectionOffset <= windowHeight ) {
			//section leaving the viewport - still has the '.visible' class
			translateY = 0;
		} else if( sectionOffset < -windowHeight ) {
			//section not yet visible
			translateY = 100;
		} else {
			//section not visible anymore
			translateY = 0;
		}

		return [translateY, scale, rotateX, opacity, boxShadowBlur];
	}
});

/* Custom effects registration - feature available in the Velocity UI pack */
//none
$.Velocity
    .RegisterEffect("translateUp", {
    	defaultDuration: 1,
        calls: [ 
            [ { translateY: '-100%'}, 1]
        ]
    });
$.Velocity
    .RegisterEffect("translateDown", {
    	defaultDuration: 1,
        calls: [ 
            [ { translateY: '100%'}, 1]
        ]
    });
$.Velocity
    .RegisterEffect("translateNone", {
    	defaultDuration: 1,
        calls: [ 
            [ { translateY: '0', opacity: '1', scale: '1', rotateX: '0', boxShadowBlur: '0'}, 1]
        ]
    });

