document.addEventListener('click', event => {
  const button = event.target.closest('[data-password-toggle]');
  if (!button) {
    return;
  }

  const input = document.getElementById(button.dataset.target);
  if (!input) {
    return;
  }

  const hiddenIcon = button.querySelector('[data-password-icon="hidden"]');
  const visibleIcon = button.querySelector('[data-password-icon="visible"]');
  const isHidden = input.type === 'password';

  input.type = isHidden ? 'text' : 'password';
  hiddenIcon?.classList.toggle('hidden', isHidden);
  visibleIcon?.classList.toggle('hidden', !isHidden);
  button.setAttribute('aria-label', isHidden ? 'Hide password' : 'Show password');
});
