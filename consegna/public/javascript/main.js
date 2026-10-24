'use strict';
// Miglioramento progressivo: senza JS menu e moduli restano disponibili.
const toggle = document.getElementById('menu-toggle');
const menu = document.getElementById('menu');
const mobile = window.matchMedia('(max-width: 800px)');
function resetMenu() {
    if (!toggle || !menu) return;
    const focusInMenu = menu.contains(document.activeElement);
    toggle.hidden = !mobile.matches;
    menu.classList.toggle('collapsed', mobile.matches);
    toggle.setAttribute('aria-expanded', String(!mobile.matches));
    if (mobile.matches && focusInMenu) toggle.focus();
}
if (toggle && menu) {
    resetMenu();
    mobile.addEventListener('change', resetMenu);
    toggle.addEventListener('click', () => {
        const expanded = toggle.getAttribute('aria-expanded') === 'true';
        toggle.setAttribute('aria-expanded', String(!expanded));
        menu.classList.toggle('collapsed', expanded);
    });
    menu.addEventListener('keydown', event => {
        if (event.key === 'Escape' && mobile.matches) {
            menu.classList.add('collapsed');
            toggle.setAttribute('aria-expanded', 'false');
            toggle.focus();
        }
    });
}
const registration = document.getElementById('register-form');
if (registration) {
    const password = document.getElementById('password');
    const confirmation = document.getElementById('confirm');
    function validatePasswords() {
        confirmation.setCustomValidity(confirmation.value && confirmation.value !== password.value ? 'Le due password non coincidono.' : '');
        password.setCustomValidity(new TextEncoder().encode(password.value).length > 72 ? 'La password supera il limite di 72 byte.' : '');
    }
    password.addEventListener('input', validatePasswords);
    confirmation.addEventListener('input', validatePasswords);
}
const errors = document.getElementById('errors');
if (errors) errors.focus();
