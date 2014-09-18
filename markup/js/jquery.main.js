$(document).ready(function() {
	$(window).scroll(function() {
		var wS = $(window).scrollTop();
		if (wS > 50) {
			$('#header').addClass('fixed');
		} else {
			$('#header').removeClass('fixed');
		}
	});
	$(".item-box").galleryScroll();
	$('.item-box').fadeGallery({
		slideElements: '.description-slide',
		pagerLinks: '.switcher a',
		pauseOnHover: false,
		autoRotation: false,
		switchTime: 4000,
		duration: 650,
		event: 'click'
	});
	$('body').popup({
		"opener": ".send-msg-popup-opener",
		"popup_holder": "#send-msg-popup",
		"popup": ".popup",
		"close_btn": ".close",
		"beforeOpen": function() {
			$('.custom-form .input-holder input, .custom-form .textarea-holder textarea').val('');
			$('.custom-form input.error, .custom-form textarea.error').removeClass('error');
		}
	});
	if('validate' in $.fn) {
		$('.custom-form').validate({
			rules: {
				name: {
					required: true,
					minlength: 3
				},
				address: {
					required: true,
					minlength: 3
				},
				code: {
					required: true,
					minlength: 3,
					maxlength: 3,
					number: true
				},
				phone: {
					required: true,
					minlength: 7,
					maxlength: 7,
					number: true
				},
				text: {
					required: true,
					minlength: 3
				}
			}
		});
	}
	$('.num .minus, .num .plus').on('click',function(e){
		var input = $(this).parent().find('input'),
			val = parseInt(input.val());
		if ($(this).is('.plus')) {
			input.val((++val))
		} else {
			if (val == 1) {
				return false;
			} else {
				input.val((--val) + "")
			}
		}
		e.preventDefault();
	});
	$(window).load(function() {
		if('dotdotdot' in $.fn) {
			$('.order-list .description .name').dotdotdot({
				wrap: 'letter'
			});
		}
	});
});
jQuery.fn.fadeGallery = function(_options) {
	var _options = jQuery.extend({
		slideElements: 'div.slideset > div',
		pagerLinks: '.control-panel li',
		btnNext: 'a.next',
		btnPrev: 'a.prev',
		btnPlayPause: 'a.play-pause',
		pausedClass: 'paused',
		playClass: 'playing',
		activeClass: 'active',
		pauseOnHover: true,
		autoRotation: false,
		autoHeight: false,
		switchTime: 3000,
		duration: 650,
		event: 'click'
	}, _options);

	return this.each(function() {
		var _this = jQuery(this);
		var _slides = jQuery(_options.slideElements, _this);
		var _pagerLinks = jQuery(_options.pagerLinks, _this);
		var _btnPrev = jQuery(_options.btnPrev, _this);
		var _btnNext = jQuery(_options.btnNext, _this);
		var _btnPlayPause = jQuery(_options.btnPlayPause, _this);
		var _pauseOnHover = _options.pauseOnHover;
		var _autoRotation = _options.autoRotation;
		var _activeClass = _options.activeClass;
		var _pausedClass = _options.pausedClass;
		var _playClass = _options.playClass;
		var _autoHeight = _options.autoHeight;
		var _duration = _options.duration;
		var _switchTime = _options.switchTime;
		var _controlEvent = _options.event;

		var _hover = false;
		var _prevIndex = 0;
		var _currentIndex = 0;
		var _slideCount = _slides.length;
		var _timer;
		if (!_slideCount) return;
		_slides.hide().eq(_currentIndex).show();
		if (_autoRotation) _this.removeClass(_pausedClass).addClass(_playClass);
		else _this.removeClass(_playClass).addClass(_pausedClass);

		if (_btnPrev.length) {
			_btnPrev.bind(_controlEvent, function(e) {
				prevSlide();
				e.preventDefault();
			});
		}
		if (_btnNext.length) {
			_btnNext.bind(_controlEvent, function(e) {
				nextSlide();
				e.preventDefault();
			});
		}
		if (_pagerLinks.length) {
			_pagerLinks.each(function(_ind) {
				jQuery(this).bind(_controlEvent, function(e) {
					if (_currentIndex != _ind) {
						_prevIndex = _currentIndex;
						_currentIndex = _ind;
						switchSlide();
					}
					e.preventDefault();
				});
			});
		}

		if (_btnPlayPause.length) {
			_btnPlayPause.bind(_controlEvent, function(e) {
				if (_this.hasClass(_pausedClass)) {
					_this.removeClass(_pausedClass).addClass(_playClass);
					_autoRotation = true;
					autoSlide();
				} else {
					if (_timer) clearRequestTimeout(_timer);
					_this.removeClass(_playClass).addClass(_pausedClass);
				}
				e.preventDefault();
			});
		}

		function prevSlide() {
			_prevIndex = _currentIndex;
			if (_currentIndex > 0) _currentIndex--;
			else _currentIndex = _slideCount - 1;
			switchSlide();
		}

		function nextSlide() {
			_prevIndex = _currentIndex;
			if (_currentIndex < _slideCount - 1) _currentIndex++;
			else _currentIndex = 0;
			switchSlide();
		}

		function refreshStatus() {
			if (_pagerLinks.length) _pagerLinks.removeClass(_activeClass).eq(_currentIndex).addClass(_activeClass);
			_slides.eq(_prevIndex).removeClass(_activeClass);
			_slides.eq(_currentIndex).addClass(_activeClass);
		}

		function switchSlide() {
			_slides.stop(true, true);
			_slides.eq(_prevIndex).fadeOut(_duration);
			_slides.eq(_currentIndex).fadeIn(_duration);
			refreshStatus();
			autoSlide();
		}

		function autoSlide() {
			if (!_autoRotation || _hover) return;
			if (_timer) clearRequestTimeout(_timer);
			_timer = requestTimeout(nextSlide, _switchTime + _duration);
		}
		if (_pauseOnHover) {
			_this.hover(function() {
				_hover = true;
				if (_timer) clearRequestTimeout(_timer);
			}, function() {
				_hover = false;
				autoSlide();
			});
		}
		refreshStatus();
		autoSlide();
	});
}
/*
 * Drop in replace functions for setTimeout() & setInterval() that
 * make use of requestAnimationFrame() for performance where available
 * http://www.joelambert.co.uk
 *
 * Copyright 2011, Joe Lambert.
 * Free to use under the MIT license.
 * http://www.opensource.org/licenses/mit-license.php
 */

// requestAnimationFrame() shim by Paul Irish
// http://paulirish.com/2011/requestanimationframe-for-smart-animating/
window.requestAnimFrame = (function() {
	return window.requestAnimationFrame ||
		window.webkitRequestAnimationFrame ||
		window.mozRequestAnimationFrame ||
		window.oRequestAnimationFrame ||
		window.msRequestAnimationFrame ||
		function( /* function */ callback, /* DOMElement */ element) {
			window.setTimeout(callback, 1000 / 60);
		};
})();
/**
 * Behaves the same as setTimeout except uses requestAnimationFrame() where possible for better performance
 * @param {function} fn The callback function
 * @param {int} delay The delay in milliseconds
 */

window.requestTimeout = function(fn, delay) {
	if (!window.requestAnimationFrame &&
		!window.webkitRequestAnimationFrame &&
		!window.mozRequestAnimationFrame &&
		!window.oRequestAnimationFrame &&
		!window.msRequestAnimationFrame)
		return window.setTimeout(fn, delay);

	var start = new Date().getTime(),
		handle = new Object();

	function loop() {
		var current = new Date().getTime(),
			delta = current - start;

		delta >= delay ? fn.call() : handle.value = requestAnimFrame(loop);
	};

	handle.value = requestAnimFrame(loop);
	return handle;
};

/**
 * Behaves the same as clearInterval except uses cancelRequestAnimationFrame() where possible for better performance
 * @param {int|object} fn The callback function
 */
window.clearRequestTimeout = function(handle) {
	window.cancelAnimationFrame ? window.cancelAnimationFrame(handle.value) :
		window.webkitCancelRequestAnimationFrame ? window.webkitCancelRequestAnimationFrame(handle.value) :
		window.mozCancelRequestAnimationFrame ? window.mozCancelRequestAnimationFrame(handle.value) :
		window.oCancelRequestAnimationFrame ? window.oCancelRequestAnimationFrame(handle.value) :
		window.msCancelRequestAnimationFrame ? msCancelRequestAnimationFrame(handle.value) :
		clearTimeout(handle);
};
jQuery.fn.galleryScroll = function(_options) {
	// defaults options	
	var _options = jQuery.extend({
		btPrev: 'a.prev',
		btNext: 'a.next',
		holderList: 'div.gallery-holder',
		scrollElParent: 'ul.slide-list',
		scrollEl: 'li.slide',
		slideNum: '.switcher',
		duration: 600,
		step: false,
		circleSlide: true,
		disableClass: 'disable',
		funcOnclick: null,
		autoSlide: false,
		innerMargin: 0,
		stepWidth: false
	}, _options);

	return this.each(function() {
		var _this = jQuery(this);

		var _holderBlock = jQuery(_options.holderList, _this);
		var _gWidth = _holderBlock.width();
		var _animatedBlock = jQuery(_options.scrollElParent, _holderBlock);
		var _liWidth = jQuery(_options.scrollEl, _animatedBlock).outerWidth(true);
		var _liSum = jQuery(_options.scrollEl, _animatedBlock).length * _liWidth;
		var _margin = -_options.innerMargin;
		var f = 0;
		var _step = 0;
		var _autoSlide = _options.autoSlide;
		var _timerSlide = null;
		if (!_options.step) _step = _gWidth;
		else _step = _options.step * _liWidth;
		if (_options.stepWidth) _step = _options.stepWidth;

		if (!_options.circleSlide) {
			if (_options.innerMargin == _margin)
				jQuery(_options.btPrev, _this).addClass('prev-' + _options.disableClass);
		}
		if (_options.slideNum && !_options.step) {
			var _lastSection = 0;
			var _sectionWidth = 0;
			while (_sectionWidth < _liSum) {
				_sectionWidth = _sectionWidth + _gWidth;
				if (_sectionWidth > _liSum) {
					_lastSection = _sectionWidth - _liSum;
				}
			}
		}
		if (_autoSlide) {
			_timerSlide = setTimeout(function() {
				autoSlide(_autoSlide);
			}, _autoSlide);
			_animatedBlock.hover(function() {
				clearTimeout(_timerSlide);
			}, function() {
				_timerSlide = setTimeout(function() {
					autoSlide(_autoSlide)
				}, _autoSlide);
			});
		}

		// click button 'Next'
		jQuery(_options.btNext, _this).bind('click', function() {
			jQuery(_options.btPrev, _this).removeClass('prev-' + _options.disableClass);
			if (!_options.circleSlide) {
				if (_margin + _step > _liSum - _gWidth - _options.innerMargin) {
					if (_margin != _liSum - _gWidth - _options.innerMargin) {
						_margin = _liSum - _gWidth + _options.innerMargin;
						jQuery(_options.btNext, _this).addClass('next-' + _options.disableClass);
						_f2 = 0;
					}
				} else {
					_margin = _margin + _step;
					if (_margin == _liSum - _gWidth - _options.innerMargin) {
						jQuery(_options.btNext, _this).addClass('next-' + _options.disableClass);
						_f2 = 0;
					}
				}
			} else {
				if (_margin + _step > _liSum - _gWidth + _options.innerMargin) {
					if (_margin != _liSum - _gWidth + _options.innerMargin) {
						_margin = _liSum - _gWidth + _options.innerMargin;
					} else {
						_f2 = 1;
						_margin = -_options.innerMargin;
					}
				} else {
					_margin = _margin + _step;
					_f2 = 0;
				}
			}

			_animatedBlock.animate({
				marginLeft: -_margin + "px"
			}, {
				queue: false,
				duration: _options.duration
			});

			if (_timerSlide) {
				clearTimeout(_timerSlide);
				_timerSlide = setTimeout(function() {
					autoSlide(_options.autoSlide)
				}, _options.autoSlide);
			}

			if (_options.slideNum && !_options.step) jQuery.fn.galleryScroll.numListActive(_margin, jQuery(_options.slideNum, _this), _gWidth, _lastSection);
			if (jQuery.isFunction(_options.funcOnclick)) {
				_options.funcOnclick.apply(_this);
			}
			return false;
		});
		// click button 'Prev'
		var _f2 = 1;
		jQuery(_options.btPrev, _this).bind('click', function() {
			jQuery(_options.btNext, _this).removeClass('next-' + _options.disableClass);
			if (_margin - _step >= -_step - _options.innerMargin && _margin - _step <= -_options.innerMargin) {
				if (_f2 != 1) {
					_margin = -_options.innerMargin;
					_f2 = 1;
				} else {
					if (_options.circleSlide) {
						_margin = _liSum - _gWidth + _options.innerMargin;
						f = 1;
						_f2 = 0;
					} else {
						_margin = -_options.innerMargin
					}
				}
			} else if (_margin - _step < -_step + _options.innerMargin) {
				_margin = _margin - _step;
				f = 0;
			} else {
				_margin = _margin - _step;
				f = 0;
			};

			if (!_options.circleSlide && _margin == _options.innerMargin) {
				jQuery(this).addClass('prev-' + _options.disableClass);
				_f2 = 0;
			}

			if (!_options.circleSlide && _margin == -_options.innerMargin) jQuery(this).addClass('prev-' + _options.disableClass);
			_animatedBlock.animate({
				marginLeft: -_margin + "px"
			}, {
				queue: false,
				duration: _options.duration
			});

			if (_options.slideNum && !_options.step) jQuery.fn.galleryScroll.numListActive(_margin, jQuery(_options.slideNum, _this), _gWidth, _lastSection);

			if (_timerSlide) {
				clearTimeout(_timerSlide);
				_timerSlide = setTimeout(function() {
					autoSlide(_options.autoSlide)
				}, _options.autoSlide);
			}

			if (jQuery.isFunction(_options.funcOnclick)) {
				_options.funcOnclick.apply(_this);
			}
			return false;
		});

		if (_liSum <= _gWidth) {
			jQuery(_options.btPrev, _this).addClass('prev-' + _options.disableClass).unbind('click');
			jQuery(_options.btNext, _this).addClass('next-' + _options.disableClass).unbind('click');
		}
		// auto slide
		function autoSlide(autoSlideDuration) {
			//if (_options.circleSlide) {
			jQuery(_options.btNext, _this).trigger('click');
			//}
		};
		// Number list
		jQuery.fn.galleryScroll.numListCreate = function(_elNumList, _liSumWidth, _width, _section) {
			var _numListElC = '';
			var _num = 1;
			var _difference = _liSumWidth + _section;
			while (_difference > 0) {
				_numListElC += '<li><a href="">' + _num + '</a></li>';
				_num++;
				_difference = _difference - _width;
			}
			jQuery(_elNumList).html('<ul>' + _numListElC + '</ul>');
		};
		jQuery.fn.galleryScroll.numListActive = function(_marginEl, _slideNum, _width, _section) {
			if (_slideNum) {
				jQuery('a', _slideNum).removeClass('active');
				var _activeRange = _width - _section - 1;
				var _n = 0;
				if (_marginEl != 0) {
					while (_marginEl > _activeRange) {
						_activeRange = (_n * _width) - _section - 1 + _options.innerMargin;
						_n++;
					}
				}
				var _a = (_activeRange + _section + 1 + _options.innerMargin) / _width - 1;
				jQuery('a', _slideNum).eq(_a).addClass('active');
			}
		};
		if (_options.slideNum && !_options.step) {
			jQuery.fn.galleryScroll.numListCreate(jQuery(_options.slideNum, _this), _liSum, _gWidth, _lastSection);
			jQuery.fn.galleryScroll.numListActive(_margin, jQuery(_options.slideNum, _this), _gWidth, _lastSection);
			numClick();
		};

		function numClick() {
			jQuery(_options.slideNum, _this).find('a').click(function() {
				jQuery(_options.btPrev, _this).removeClass('prev-' + _options.disableClass);
				jQuery(_options.btNext, _this).removeClass('next-' + _options.disableClass);

				var _indexNum = jQuery(_options.slideNum, _this).find('a').index(jQuery(this));
				_margin = (_step * _indexNum) - _options.innerMargin;
				f = 0;
				_f2 = 0;
				if (_indexNum == 0) _f2 = 1;
				if (_margin + _step > _liSum) {
					_margin = _margin - (_margin - _liSum) - _step + _options.innerMargin;
					if (!_options.circleSlide) jQuery(_options.btNext, _this).addClass('next-' + _options.disableClass);
				}
				_animatedBlock.animate({
					marginLeft: -_margin + "px"
				}, {
					queue: false,
					duration: _options.duration
				});

				if (!_options.circleSlide && _margin == 0) jQuery(_options.btPrev, _this).addClass('prev-' + _options.disableClass);
				jQuery.fn.galleryScroll.numListActive(_margin, jQuery(_options.slideNum, _this), _gWidth, _lastSection);

				if (_timerSlide) {
					clearTimeout(_timerSlide);
					_timerSlide = setTimeout(function() {
						autoSlide(_options.autoSlide)
					}, _options.autoSlide);
				}
				return false;
			});
		};
		jQuery(window).resize(function() {
			_gWidth = _holderBlock.width();
			_liWidth = jQuery(_options.scrollEl, _animatedBlock).outerWidth(true);
			_liSum = jQuery(_options.scrollEl, _animatedBlock).length * _liWidth;
			if (!_options.step) _step = _gWidth;
			else _step = _options.step * _liWidth;
			if (_options.slideNum && !_options.step) {
				var _lastSection = 0;
				var _sectionWidth = 0;
				while (_sectionWidth < _liSum) {
					_sectionWidth = _sectionWidth + _gWidth;
					if (_sectionWidth > _liSum) {
						_lastSection = _sectionWidth - _liSum;
					}
				};
				jQuery.fn.galleryScroll.numListCreate(jQuery(_options.slideNum, _this), _liSum, _gWidth, _lastSection);
				jQuery.fn.galleryScroll.numListActive(_margin, jQuery(_options.slideNum, _this), _gWidth, _lastSection);
				numClick();
			};
			//if (_margin == _options.innerMargin) jQuery(this).addClass(_options.disableClass);
			if (_liSum - _gWidth < _margin - _options.innerMargin) {
				if (!_options.circleSlide) jQuery(_options.btNext, _this).addClass('next-' + _options.disableClass);
				_animatedBlock.animate({
					marginLeft: -(_liSum - _gWidth + _options.innerMargin)
				}, {
					queue: false,
					duration: _options.duration
				});
			};
		});
	});
}

$.fn.popup = function(o) {
	var o = $.extend({
		"opener": ".call-back a",
		"popup_holder": "#call-popup",
		"popup": ".popup",
		"close_btn": ".close",
		"close": function() {},
		"beforeOpen": function() {}
	}, o);
	return this.each(function() {
		var container = $(this),
			opener = $(o.opener, container),
			popup_holder = $(o.popup_holder, container),
			popup = $(o.popup, popup_holder),
			close = $(o.close_btn, popup),
			bg = $('.bg', popup_holder);
		popup.css('margin', 0);
		opener.click(function(e) {
			o.beforeOpen.apply(this, [popup_holder]);
			popup_holder.fadeIn(400);
			alignPopup();
			bgResize();
			e.preventDefault();
		});

		function alignPopup() {
			if ((($(window).height() / 2) - (popup.outerHeight() / 2)) + $(window).scrollTop() < 0) {
				popup.css({
					'top': 0,
					'left': (($(window).width() - popup.outerWidth()) / 2) + $(window).scrollLeft()
				});
				return false;
			}
			popup.css({
				'top': (($(window).height() - popup.outerHeight()) / 2) + $(window).scrollTop(),
				'left': (($(window).width() - popup.outerWidth()) / 2) + $(window).scrollLeft()
			});
		}

		function bgResize() {
			var _w = $(window).width(),
				_h = $(document).height();
			bg.css({
				"height": _h,
				"width": _w + $(window).scrollLeft()
			});
		}
		$(window).resize(function() {
			if (popup_holder.is(":visible")) {
				bgResize();
				alignPopup();
			}
		});
		if (popup_holder.is(":visible")) {
			bgResize();
			alignPopup();
		}
		close.add(bg).click(function(e) {
			var closeEl = this;
			popup_holder.fadeOut(400, function() {
				o.close.apply(closeEl, [popup_holder]);
			});
			e.preventDefault();
		});
		$('body').keydown(function(e) {
			if (e.keyCode == '27') {
				popup_holder.fadeOut(400);
			}
		})
	});
}