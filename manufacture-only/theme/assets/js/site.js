(function () {
  const toggle = document.querySelector('.site-header__toggle');
  const nav = document.querySelector('.site-nav');
  if (!toggle || !nav) return;

  const menuLinks = Array.from(nav.querySelectorAll('a'));
  const branches = Array.from(nav.querySelectorAll('.menu-item-has-children'));

  function setBranch(item, open, returnFocus) {
    const button = item.querySelector(':scope > .submenu-toggle');
    if (!button) return;
    if (open) branches.filter(other => other !== item).forEach(other => setBranch(other, false, false));
    item.classList.toggle('submenu-open', open);
    button.setAttribute('aria-expanded', String(open));
    if (returnFocus) button.focus();
  }

  function closeBranches() { branches.forEach(item => setBranch(item, false, false)); }

  branches.forEach(function (item) {
    const button = item.querySelector(':scope > .submenu-toggle');
    if (!button) return;
    button.addEventListener('click', function () {
      setBranch(item, button.getAttribute('aria-expanded') !== 'true', false);
    });
    item.addEventListener('mouseenter', function () {
      if (window.innerWidth > 1050) setBranch(item, true, false);
    });
    item.addEventListener('mouseleave', function () {
      if (window.innerWidth > 1050 && !item.contains(document.activeElement)) setBranch(item, false, false);
    });
    item.addEventListener('focusout', function () {
      window.setTimeout(function () {
        if (!item.contains(document.activeElement)) setBranch(item, false, false);
      }, 0);
    });
  });

  nav.addEventListener('keydown', function (event) {
    if (event.key !== 'Escape') return;
    const item = branches.find(branch => branch.classList.contains('submenu-open') && branch.contains(document.activeElement)) || branches.find(branch => branch.classList.contains('submenu-open'));
    if (item) {
      event.preventDefault();
      event.stopPropagation();
      setBranch(item, false, true);
    }
  });

  function closeMenu(returnFocus) {
    closeBranches();
    toggle.setAttribute('aria-expanded', 'false');
    toggle.setAttribute('aria-label', 'Open navigation');
    nav.classList.remove('is-open');
    document.body.classList.remove('menu-open');
    if (returnFocus) toggle.focus();
  }

  toggle.addEventListener('click', function () {
    const open = toggle.getAttribute('aria-expanded') === 'true';
    if (open) {
      closeMenu(false);
      return;
    }
    toggle.setAttribute('aria-expanded', 'true');
    toggle.setAttribute('aria-label', 'Close navigation');
    nav.classList.add('is-open');
    document.body.classList.add('menu-open');
    if (menuLinks[0]) menuLinks[0].focus();
  });

  document.addEventListener('keydown', function (event) {
    if (event.key === 'Escape' && toggle.getAttribute('aria-expanded') === 'true') {
      closeMenu(true);
    }
  });

  document.addEventListener('click', function (event) {
    if (!nav.contains(event.target)) closeBranches();
    if (toggle.getAttribute('aria-expanded') === 'true' && !nav.contains(event.target) && !toggle.contains(event.target)) {
      closeMenu(false);
    }
  });

  window.addEventListener('resize', function () {
    closeBranches();
    if (window.innerWidth > 1050 && toggle.getAttribute('aria-expanded') === 'true') {
      closeMenu(false);
    }
  });
})();

(function () {
  document.querySelectorAll('.rpm-form').forEach(function (form) {
    const button = form.querySelector('[type="submit"]');
    const status = form.querySelector('.rpm-form__status');
    if (!button || !status || !window.fetch) return;
    status.tabIndex = -1;
    form.addEventListener('submit', async function (event) {
      event.preventDefault();
      if (!form.reportValidity() || button.disabled) return;
      const data = new FormData(form);
      data.set('rpm_async', '1');
      const label = button.textContent;
      button.disabled = true;
      button.textContent = 'Sending…';
      form.setAttribute('aria-busy', 'true');
      const controller = new AbortController();
      const timeout = window.setTimeout(function () { controller.abort(); }, 30000);
      try {
        // WordPress's hidden input named "action" shadows the form.action property.
        const response = await fetch(form.getAttribute('action'), { method: 'POST', body: data, credentials: 'same-origin', signal: controller.signal });
        if (!response.ok) throw new Error('Unable to send');
        const result = await response.json();
        if (!result || typeof result.message !== 'string') throw new Error('Unexpected response');
        status.textContent = result.message;
        if (result.status === 'sent') form.reset();
      } catch (error) {
        status.textContent = 'We could not confirm delivery. Your entries are still here; please call or email RPM before sending again.';
      } finally {
        window.clearTimeout(timeout);
        button.disabled = false;
        button.textContent = label;
        form.removeAttribute('aria-busy');
        status.focus({ preventScroll: true });
        status.scrollIntoView({ block: 'nearest', behavior: 'auto' });
      }
    });
  });
})();
