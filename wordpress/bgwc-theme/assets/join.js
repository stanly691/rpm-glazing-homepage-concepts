/**
 * Join form polish (Gravity Forms form 1):
 * - "Your plan" summary with a Change link on steps 2 and 3
 * - instant, friendly validation before a step is sent to the server
 * - loading state on the step buttons
 * - smooth scroll between steps, focus on the first field (desktop)
 * - checks a field when the person leaves it (only if they typed something)
 * - single date of birth field with DD/MM/YYYY mask, email typo suggestions
 */
(function ($) {
	var FORM_ID = 1;
	var BACK_KEY = 'bgwc-back-to-plan';
	var PLAN_KEY = 'bgwc-plan';
	var lastPage = null;
	var finePointer = window.matchMedia && window.matchMedia('(pointer: fine)').matches;
	var reduceMotion = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

	function store(key, value) {
		try {
			if (value === null) {
				sessionStorage.removeItem(key);
			} else {
				sessionStorage.setItem(key, value);
			}
		} catch (e) {}
	}

	function read(key) {
		try {
			return sessionStorage.getItem(key);
		} catch (e) {
			return null;
		}
	}

	function isShown($el) {
		return $el.closest('.gfield').css('display') !== 'none' && $el.closest('.gform_page').css('display') !== 'none';
	}

	/* ---------- Plan summary ---------- */

	function chosenPlan($form) {
		var $join = $form.find('input[name="input_2"]:checked');
		if ($join.length && /GP referral/.test($join.val())) {
			return { name: 'GP referral', price: 'Free' };
		}
		var $plan = $form.find('input[name="input_3"]:checked, input[name="input_4"]:checked').filter(function () {
			return $(this).closest('.gfield').css('display') !== 'none';
		}).first();
		if (!$plan.length) {
			return null;
		}
		var $label = $form.find('label[for="' + $plan.attr('id') + '"]');
		var name = $plan.val().split('|')[0];
		var $concession = $form.find('input[name="input_29"]:checked');
		if (/concession/.test(name) && $concession.length) {
			name += ' (' + $concession.val() + ')';
		}
		return { name: name, price: $.trim($label.find('.bgwc-price').text()) };
	}

	function remember($form) {
		var plan = chosenPlan($form);
		store(PLAN_KEY, plan ? JSON.stringify(plan) : null);
	}

	function summaryBar(page) {
		var plan = null;
		try {
			plan = JSON.parse(read(PLAN_KEY) || 'null');
		} catch (e) {}
		if (!plan) {
			return;
		}
		var $bar = $(
			'<div class="bgwc-summary" role="status">' +
				'<span class="bgwc-summary__label">Your plan</span>' +
				'<span class="bgwc-summary__name"></span>' +
				'<span class="bgwc-summary__price"></span>' +
				'<a href="#" class="bgwc-summary__change" role="button">Change</a>' +
			'</div>'
		);
		$bar.find('.bgwc-summary__name').text(plan.name);
		$bar.find('.bgwc-summary__price').text(plan.price);
		$bar.find('.bgwc-summary__change').on('click', function (event) {
			event.preventDefault();
			store(BACK_KEY, '1');
			$('#gform_page_' + FORM_ID + '_' + page + ' .gform_previous_button').trigger('click');
		});
		$('#gform_page_' + FORM_ID + '_' + page + ' .gform_page_fields').before($bar);
	}

	/* ---------- Instant validation ---------- */

	var EMAIL = /^[^\s@]+@[^\s@]+\.[^\s@]{2,}$/;

	function setError($field, message) {
		$field.addClass('bgwc-invalid');
		var $msg = $field.find('.bgwc-error');
		if (!$msg.length) {
			$msg = $('<div class="bgwc-error" role="alert"></div>').appendTo($field);
		}
		$msg.text(message);
	}

	function clearError($field) {
		$field.removeClass('bgwc-invalid').find('.bgwc-error').remove();
	}

	function checkField($field) {
		if ($field.css('display') === 'none' || !$field.hasClass('gfield_contains_required')) {
			return true;
		}
		var $radios = $field.find('input[type=radio]');
		if ($radios.length) {
			return $radios.filter(':checked').length ? true : 'Please choose one to continue.';
		}
		var $consent = $field.find('input[type=checkbox]');
		if ($field.hasClass('gfield--type-consent')) {
			return $consent.is(':checked') ? true : 'Please tick to confirm.';
		}
		var $inputs = $field.find('input[type=text], input[type=email], input[type=tel], input[type=number], textarea').filter(function () {
			return $(this).closest('.gform-grid-col, .ginput_container').css('display') !== 'none' && this.type !== 'hidden';
		});
		var empty = $inputs.filter(function () {
			return !$.trim(this.value);
		});
		if (empty.length) {
			return $field.hasClass('gfield--type-date') ? 'Please enter the date of birth.' : 'Please fill this in.';
		}
		if ($field.hasClass('gfield--type-date') && !validDate($inputs.first().val())) {
			return 'Please enter the date of birth as DD/MM/YYYY.';
		}
		var $email = $field.find('input[type=email]');
		if ($email.length && !EMAIL.test($.trim($email.val()))) {
			return 'Please enter a valid email address.';
		}
		var $tel = $field.find('input[type=tel]');
		if ($tel.length && $tel.val().replace(/\D/g, '').length < 10) {
			return 'Please enter a valid phone number.';
		}
		return true;
	}

	function validatePage($page) {
		var $first = null;
		$page.find('.gfield').each(function () {
			var $field = $(this);
			var result = checkField($field);
			if (result === true) {
				clearError($field);
			} else {
				setError($field, result);
				$first = $first || $field;
			}
		});
		if ($first) {
			scrollTo($first, -24);
			var $focus = $first.find('input:not([type=hidden]), textarea').first();
			setTimeout(function () {
				$focus.trigger('focus', { preventScroll: true });
			}, reduceMotion ? 0 : 350);
			return false;
		}
		return true;
	}

	function validDate(value) {
		var m = /^(\d{2})\/(\d{2})\/(\d{4})$/.exec($.trim(value));
		if (!m) {
			return false;
		}
		var d = new Date(+m[3], +m[2] - 1, +m[1]);
		var year = new Date().getFullYear();
		return d.getDate() === +m[1] && d.getMonth() === +m[2] - 1 && +m[3] > year - 110 && d <= new Date();
	}

	/* ---------- Helpers ---------- */

	function scrollTo($el, offset) {
		if (!$el.length) {
			return;
		}
		var top = $el[0].getBoundingClientRect().top + window.pageYOffset + (offset || 0);
		window.scrollTo({ top: Math.max(top, 0), behavior: reduceMotion ? 'auto' : 'smooth' });
	}

	function nudgeFooter($page) {
		if (finePointer) {
			return;
		}
		var $footer = $page.find('.gform_page_footer, .gform_footer').first();
		if (!$footer.length) {
			return;
		}
		$footer.removeClass('bgwc-nudge');
		void $footer[0].offsetWidth;
		$footer.addClass('bgwc-nudge');
	}

	var TYPO_DOMAINS = {
		'gmial.com': 'gmail.com', 'gamil.com': 'gmail.com', 'gmai.com': 'gmail.com', 'gmail.co': 'gmail.com', 'gmail.co.uk': 'gmail.com', 'gnail.com': 'gmail.com',
		'hotmial.com': 'hotmail.com', 'hotmal.com': 'hotmail.com', 'hotmail.co': 'hotmail.co.uk', 'hotmai.co.uk': 'hotmail.co.uk',
		'outlok.com': 'outlook.com', 'outloo.com': 'outlook.com', 'outlook.co': 'outlook.com',
		'yahooo.com': 'yahoo.com', 'yaho.com': 'yahoo.com', 'yahoo.co': 'yahoo.co.uk',
		'icloud.co': 'icloud.com', 'iclod.com': 'icloud.com', 'icoud.com': 'icloud.com', 'icloud.co.uk': 'icloud.com'
	};

	function suggestEmail($input) {
		var $field = $input.closest('.gfield');
		$field.find('.bgwc-suggest').remove();
		var value = $.trim($input.val()).toLowerCase();
		var at = value.lastIndexOf('@');
		if (at < 1) {
			return;
		}
		var fix = TYPO_DOMAINS[value.slice(at + 1)];
		if (!fix) {
			return;
		}
		var suggestion = value.slice(0, at + 1) + fix;
		var $hint = $('<div class="bgwc-suggest">Did you mean <a href="#"></a>?</div>');
		$hint.find('a').text(suggestion).on('click', function (event) {
			event.preventDefault();
			$input.val(suggestion).trigger('change');
			$hint.remove();
			clearError($field);
		});
		$input.closest('.ginput_container').after($hint);
	}

	/* ---------- Wiring ---------- */

	function enhance($form, page) {
		var $page = $('#gform_page_' + FORM_ID + '_' + page);

		// Date of birth: one typed field, number keypad, slashes added automatically.
		var $dob = $form.find('#input_' + FORM_ID + '_10');
		$dob.attr({ inputmode: 'numeric', maxlength: 10 });
		$dob.off('input.bgwc').on('input.bgwc', function (event) {
			var deleting = event.originalEvent && /^delete/.test(event.originalEvent.inputType || '');
			var digits = this.value.replace(/\D/g, '').slice(0, 8);
			var out = digits.slice(0, 2);
			if (digits.length > 2 || (digits.length === 2 && !deleting)) {
				out += '/' + digits.slice(2, 4);
			}
			if (digits.length > 4 || (digits.length === 4 && !deleting)) {
				out += '/' + digits.slice(4);
			}
			this.value = out;
		});

		// Check a field when the person leaves it, but never flag an untouched empty field.
		$form.off('focusout.bgwcb').on('focusout.bgwcb', 'input[type=text], input[type=email], input[type=tel]', function () {
			if (!$.trim(this.value)) {
				return;
			}
			var $field = $(this).closest('.gfield');
			var result = checkField($field);
			if (result === true) {
				clearError($field);
			} else if (!/fill this in|full date/.test(result)) {
				setError($field, result);
			}
		});

		// Clear an error as soon as the field is fixed.
		$form.off('input.bgwcv change.bgwcv').on('input.bgwcv change.bgwcv', '.bgwc-invalid :input', function () {
			var $field = $(this).closest('.gfield');
			if (checkField($field) === true) {
				clearError($field);
			}
		});

		$form.off('blur.bgwce').on('blur.bgwce', 'input[type=email]', function () {
			suggestEmail($(this));
		});

		if (page === 1) {
			$form.off('change.bgwc').on('change.bgwc', 'input[name="input_2"], input[name="input_3"], input[name="input_4"], input[name="input_29"]', function () {
				remember($form);
				if (this.name === 'input_3' || this.name === 'input_4' || this.name === 'input_29') {
					nudgeFooter($page);
				}
			});
			remember($form);
		} else {
			summaryBar(page);
		}

		// Arriving on a new step: bring it into view, focus the first field on desktop.
		if (lastPage !== null && lastPage !== page) {
			scrollTo($form.closest('.entry'), -16);
			if (finePointer) {
				var $first = $page.find('.gfield:visible').first().find('input[type=text], input[type=email], input[type=tel], textarea').first();
				setTimeout(function () {
					$first.trigger('focus', { preventScroll: true });
				}, reduceMotion ? 0 : 400);
			}
		}
		lastPage = page;
	}

	// Validate before Gravity Forms sends the step; show a loading state when it goes.
	document.addEventListener('click', function (event) {
		var button = event.target.closest && event.target.closest('#gform_' + FORM_ID + ' .gform_next_button, #gform_' + FORM_ID + ' .gform_button');
		if (!button) {
			return;
		}
		var $page = $(button).closest('.gform_page');
		if (!validatePage($page)) {
			event.preventDefault();
			event.stopImmediatePropagation();
			return;
		}
		$(button).addClass('bgwc-loading').attr('aria-busy', 'true');
	}, true);

	$(document).on('gform_post_render', function (event, formId, page) {
		if (parseInt(formId, 10) !== FORM_ID) {
			return;
		}
		page = parseInt(page, 10) || 1;
		var $form = $('#gform_' + FORM_ID);
		$form.find('.bgwc-summary').remove();

		// Came from "Change" on step 3: keep stepping back to step 1.
		if (read(BACK_KEY) && page > 1) {
			$('#gform_page_' + FORM_ID + '_' + page + ' .gform_previous_button').trigger('click');
			return;
		}
		store(BACK_KEY, null);
		enhance($form, page);
	});
})(jQuery);
