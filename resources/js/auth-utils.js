/**
 * Auth Utilities
 * Centralized logic for password toggling and strength validation.
 */

/**
 * Toggles visibility of a password input field.
 * @param {string} id - The ID of the input field.
 */
function togglePassword(id) {
    const input = document.getElementById(id);
    if (input) {
        input.type = input.type === 'password' ? 'text' : 'password';
    }
}

/**
 * Initializes real-time password strength validation for a field.
 * @param {object} config - Configuration object.
 * @param {string} config.inputId - ID of the password input.
 * @param {string} config.activeClass - Tailwind color class for met requirements (e.g., 'orange-500').
 * @param {object} config.requirements - Mapping of requirement IDs (e.g., { length: 'req-length', upper: 'req-upper', ... }).
 */
function initPasswordValidation({ inputId, activeClass, requirements }) {
    const passInput = document.getElementById(inputId);
    if (!passInput) return;

    passInput.addEventListener('input', () => {
        const pass = passInput.value;

        if (requirements.length) updateChecklist(requirements.length, pass.length >= 8);
        if (requirements.upper) updateChecklist(requirements.upper, /[A-Z]/.test(pass));
        if (requirements.lower) updateChecklist(requirements.lower, /[a-z]/.test(pass));
        if (requirements.number) updateChecklist(requirements.number, /[0-9]/.test(pass));
    });

    function updateChecklist(id, isMet) {
        const item = document.getElementById(id);
        if (!item) return;
        const dot = item.querySelector('div');
        const icon = item.querySelector('svg');
        const text = item.querySelector('span');

        if (isMet) {
            dot.classList.remove('border-slate-200');
            dot.classList.add(`border-${activeClass}`, `bg-${activeClass}`);
            icon.classList.remove('opacity-0');
            icon.classList.add('opacity-100');
            text.classList.remove('text-slate-400');
            text.classList.add(`text-${activeClass}`);
        } else {
            dot.classList.add('border-slate-200');
            dot.classList.remove(`border-${activeClass}`, `bg-${activeClass}`);
            icon.classList.add('opacity-0');
            icon.classList.remove('opacity-100');
            text.classList.add('text-slate-400');
            text.classList.remove(`text-${activeClass}`);
        }
    }
}
