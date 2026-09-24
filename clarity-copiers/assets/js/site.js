/* Clarity Copiers — tiny, dependency-free enhancements (loaded deferred). */
(function () {
	'use strict';
	var d = document;

	// Mobile menu + sub-menu toggles.
	var toggle = d.querySelector('.nav-toggle'), nav = d.getElementById('primary-nav');
	if (toggle && nav) {
		toggle.addEventListener('click', function () {
			var open = nav.classList.toggle('open');
			toggle.setAttribute('aria-expanded', open ? 'true' : 'false');
			toggle.setAttribute('aria-label', open ? 'Close menu' : 'Open menu');
		});
		nav.querySelectorAll('.menu-item-has-children > a').forEach(function (a) {
			var b = d.createElement('button');
			b.type = 'button'; b.className = 'sub-toggle'; b.setAttribute('aria-expanded', 'false');
			b.setAttribute('aria-label', 'Show ' + a.textContent.trim() + ' menu');
			a.after(b);
			b.addEventListener('click', function () {
				var li = b.parentElement, open = li.classList.toggle('sub-open');
				b.setAttribute('aria-expanded', open ? 'true' : 'false');
			});
		});
	}

	// Sticky header after the hero starts scrolling away (desktop floats; mobile uses CSS sticky).
	var header = d.querySelector('.site-header');
	if (header) {
		var onScroll = function () { header.classList.toggle('is-stuck', window.scrollY > 160); };
		window.addEventListener('scroll', onScroll, { passive: true });
		onScroll();
	}

	// Scroll reveal for content blocks (skipped for reduced motion).
	var reduce = window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
	if (!reduce && 'IntersectionObserver' in window) {
		var targets = d.querySelectorAll('.sec-head, .cgrid > *, .grid4 > *, .grid3 > *, .split-grid > *, .stat, .step, .team-group, .model-grid > *, .model-detail > *, .contact-grid > *, .cta-inner > *, .team .wrap > *, .head-row, .section-head');
		if (targets.length) {
			d.documentElement.classList.add('js-reveal');
			var io = new IntersectionObserver(function (entries) {
				entries.forEach(function (en) {
					if (en.isIntersecting) { en.target.classList.add('is-in'); io.unobserve(en.target); }
				});
			}, { rootMargin: '0px 0px -8% 0px' });
			targets.forEach(function (el, i) {
				el.setAttribute('data-reveal', '');
				var sib = el.parentElement ? Array.prototype.indexOf.call(el.parentElement.children, el) : 0;
				el.style.transitionDelay = Math.min(sib, 4) * 70 + 'ms';
				io.observe(el);
			});
		}
	}

	// Respect reduced-motion: don't autoplay the hero video.
	var v = d.querySelector('.hero-video');
	if (v && window.matchMedia && window.matchMedia('(prefers-reduced-motion: reduce)').matches) {
		v.removeAttribute('autoplay'); v.pause();
	}

	// YouTube facade: load the (privacy-enhanced) player only on click.
	d.addEventListener('click', function (e) {
		var btn = e.target.closest && e.target.closest('.video-facade');
		if (!btn) return;
		var id = btn.getAttribute('data-yt');
		if (!/^[\w-]{6,20}$/.test(id)) return;
		var f = d.createElement('iframe');
		f.src = 'https://www.youtube-nocookie.com/embed/' + id + '?autoplay=1&rel=0';
		f.title = btn.getAttribute('aria-label') || 'Video';
		f.allow = 'accelerometer; autoplay; encrypted-media; gyroscope; picture-in-picture';
		f.allowFullscreen = true;
		btn.replaceWith(f);
	});

	// Lightweight gallery lightbox using <dialog>.
	var grids = d.querySelectorAll('[data-lightbox]');
	if (grids.length && 'HTMLDialogElement' in window) {
		var dlg = d.createElement('dialog'); dlg.className = 'lightbox';
		dlg.innerHTML = '<button type="button" class="lb-close" aria-label="Close">&times;</button><button type="button" class="lb-prev" aria-label="Previous">&#8249;</button><img alt=""><button type="button" class="lb-next" aria-label="Next">&#8250;</button>';
		d.body.appendChild(dlg);
		var img = dlg.querySelector('img'), items = [], idx = 0;
		function show(i) { idx = (i + items.length) % items.length; img.src = items[idx].href; img.alt = (items[idx].querySelector('img') || {}).alt || ''; }
		grids.forEach(function (g) {
			var links = Array.prototype.slice.call(g.querySelectorAll('a'));
			links.forEach(function (a, i) {
				a.addEventListener('click', function (e) { e.preventDefault(); items = links; show(i); dlg.showModal(); });
			});
		});
		dlg.querySelector('.lb-close').onclick = function () { dlg.close(); };
		dlg.querySelector('.lb-prev').onclick = function () { show(idx - 1); };
		dlg.querySelector('.lb-next').onclick = function () { show(idx + 1); };
		dlg.addEventListener('click', function (e) { if (e.target === dlg) dlg.close(); });
		dlg.addEventListener('keydown', function (e) { if (e.key === 'ArrowLeft') show(idx - 1); if (e.key === 'ArrowRight') show(idx + 1); });
	}
})();
