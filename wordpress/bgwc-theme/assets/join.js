/**
 * Join form polish: shows the chosen plan at the top of steps 2 and 3,
 * with a "Change" link back to step 1.
 */
(function ($) {
	var FORM_ID = 1;
	var BACK_KEY = 'bgwc-back-to-plan';

	function chosenPlan($form) {
		var $join = $form.find('input[name="input_2"]:checked');
		var $plan = $form.find('input[name="input_3"]:checked, input[name="input_4"]:checked').filter(function () {
			return $(this).closest('.gfield').css('display') !== 'none';
		}).first();

		if ($join.length && /GP referral/.test($join.val())) {
			return { name: $join.val(), price: 'Free' };
		}
		if (!$plan.length) {
			return null;
		}
		var $label = $form.find('label[for="' + $plan.attr('id') + '"]');
		return {
			name: $plan.val().split('|')[0],
			price: $.trim($label.find('.bgwc-price').text())
		};
	}

	function remember($form) {
		var plan = chosenPlan($form);
		try {
			sessionStorage.setItem('bgwc-plan', plan ? JSON.stringify(plan) : '');
		} catch (e) {}
	}

	function stored() {
		try {
			return JSON.parse(sessionStorage.getItem('bgwc-plan') || 'null');
		} catch (e) {
			return null;
		}
	}

	function render(formId, page) {
		if (formId !== FORM_ID) {
			return;
		}
		var $form = $('#gform_' + FORM_ID);
		$form.find('.bgwc-summary').remove();

		// Came from "Change" on step 3: keep stepping back to step 1.
		try {
			if (sessionStorage.getItem(BACK_KEY) && page > 1) {
				$('#gform_page_' + FORM_ID + '_' + page + ' .gform_previous_button').trigger('click');
				return;
			}
			sessionStorage.removeItem(BACK_KEY);
		} catch (e) {}

		if (page === 1) {
			$form.off('change.bgwc').on('change.bgwc', 'input[name="input_2"], input[name="input_3"], input[name="input_4"]', function () {
				remember($form);
			});
			remember($form);
			return;
		}

		var plan = stored();
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
			try {
				sessionStorage.setItem(BACK_KEY, '1');
			} catch (e) {}
			$('#gform_page_' + FORM_ID + '_' + page + ' .gform_previous_button').trigger('click');
		});
		$('#gform_page_' + FORM_ID + '_' + page + ' .gform_page_fields').before($bar);
	}

	$(document).on('gform_post_render', function (event, formId, page) {
		render(parseInt(formId, 10), parseInt(page, 10) || 1);
	});
})(jQuery);
