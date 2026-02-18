/**
 * Auth Page Interactions
 * Handles modals, "Remember Me" persistence, and saved credentials.
 */

function openModal() {
    const modal = document.getElementById('loginModal');
    if (modal) {
        checkSavedCredentials();
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function openRegModal() {
    const modal = document.getElementById('regModal');
    if (modal) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const modal = document.getElementById(modalId);
    if (modal) {
        modal.classList.add('hidden');
        document.body.style.overflow = 'auto';
    }
}

// Close modal on background click
window.onclick = function (event) {
    const loginModal = document.getElementById('loginModal');
    const regModal = document.getElementById('regModal');
    if (event.target == loginModal) closeModal('loginModal');
    if (event.target == regModal) closeModal('regModal');
};

function checkSavedCredentials() {
    const savedEmail = localStorage.getItem('skillswap_saved_email');
    const emailInput = document.getElementById('emailInput');
    const rememberMe = document.getElementById('rememberMe');
    const dismissed = localStorage.getItem('skillswap_prompt_dismissed');

    if (savedEmail && emailInput && emailInput.value === '') {
        if (!dismissed) {
            if (confirm(`Do you want to use your saved email: ${savedEmail}?`)) {
                emailInput.value = savedEmail;
                if (rememberMe) rememberMe.checked = true;
            } else {
                localStorage.setItem('skillswap_prompt_dismissed', 'true');
            }
        } else {
            emailInput.value = savedEmail;
            if (rememberMe) rememberMe.checked = true;
        }
    }
}

document.addEventListener('DOMContentLoaded', () => {
    const loginForm = document.getElementById('loginForm');
    if (loginForm) {
        loginForm.onsubmit = function () {
            const emailInput = document.getElementById('emailInput');
            const rememberMe = document.getElementById('rememberMe');

            if (emailInput && rememberMe) {
                const email = emailInput.value;
                const remember = rememberMe.checked;

                if (remember) {
                    localStorage.setItem('skillswap_saved_email', email);
                } else {
                    localStorage.removeItem('skillswap_saved_email');
                    localStorage.removeItem('skillswap_prompt_dismissed');
                }
            }
        };
    }
});
