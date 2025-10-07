'use strict';

function getInputElement(form, elementNames) {
  if (form && form.elements) {
    for (const name of elementNames) {
      if (form.elements[name]) {
        return form.elements[name];
      }
    }
  }
  for (const id of elementNames) {
    const element = document.getElementById(id);
    if (element) {
      return element;
    }
  }
  return null;
}

function validate(form) {
  const usernameElement = getInputElement(form, ['username', 'email']);
  const passwordElement = getInputElement(form, ['password']);

  const username = (usernameElement && typeof usernameElement.value === 'string'
    ? usernameElement.value
    : '').trim();
  const password = (passwordElement && typeof passwordElement.value === 'string'
    ? passwordElement.value
    : '');

  if (username.length === 0) {
    alert('You must enter a username.');
    return false;
  }

  if (password.length === 0) {
    alert('You must enter a password.');
    return false;
  }

  if (password.length < 6 || password.length > 12) {
    alert('Length of Password must be between 6 to 12 characters');
    return false;
  }

  // Username must start with a letter
  if (!/^[A-Za-z]/.test(username)) {
    alert('Username must start with a letter');
    return false;
  }

  return true;
}
