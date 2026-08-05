document.documentElement.classList.add("js");

const toggle = document.querySelector("[data-nav-toggle]");
const nav = document.querySelector("[data-nav]");

if (toggle && nav) {
  toggle.addEventListener("click", () => {
    const open = nav.dataset.open !== "true";
    nav.dataset.open = String(open);
    toggle.setAttribute("aria-expanded", String(open));
    toggle.textContent = open ? "Close" : "Menu";
  });

  nav.addEventListener("click", (event) => {
    if (event.target.closest("a")) {
      nav.dataset.open = "false";
      toggle.setAttribute("aria-expanded", "false");
      toggle.textContent = "Menu";
    }
  });
}

document.querySelectorAll("[data-year]").forEach((node) => {
  node.textContent = new Date().getFullYear();
});

document.querySelectorAll("[data-prototype-form]").forEach((form) => {
  form.addEventListener("submit", (event) => {
    event.preventDefault();
    let firstInvalid = null;

    form.querySelectorAll("[required]").forEach((field) => {
      const error = form.querySelector(`[data-error-for="${field.id}"]`);
      const invalid = !field.value.trim();
      field.setAttribute("aria-invalid", String(invalid));
      if (error) error.textContent = invalid ? "Please complete this field." : "";
      if (invalid && !firstInvalid) firstInvalid = field;
    });

    const email = form.querySelector('input[type="email"]');
    if (email && email.value && !email.validity.valid) {
      email.setAttribute("aria-invalid", "true");
      const emailError = form.querySelector(`[data-error-for="${email.id}"]`);
      if (emailError) emailError.textContent = "Enter a valid email address.";
      if (!firstInvalid) firstInvalid = email;
    }

    if (firstInvalid) {
      firstInvalid.focus();
      return;
    }

    const status = form.parentElement.querySelector("[data-form-status]");
    form.hidden = true;
    if (status) {
      status.hidden = false;
      status.focus();
    }
  });
});

