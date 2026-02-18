/**
 * Learner Registration Flow JavaScript
 * Handles multi-step transitions, password toggling, and tag management.
 */

function goToStep(step) {
    const s1 = document.getElementById('step-1');
    const s2 = document.getElementById('step-2');
    const mainTitle = document.getElementById('main-title');
    const mainSubtitle = document.getElementById('main-subtitle');
    const stepCount = document.getElementById('step-count');
    const stepLabel = document.getElementById('step-label');
    const progressBar = document.getElementById('progress-bar');

    if (!s1 || !s2 || !mainTitle || !mainSubtitle || !stepCount || !stepLabel || !progressBar) return;

    if (step === 2) {
        // Validation for Step 1
        const name = document.getElementById('field-name').value;
        const email = document.getElementById('field-email').value;
        const pass = document.getElementById('field-pass').value;

        if (!name || !email || !pass) {
            alert('Please fill in all required fields in Step 1.');
            return;
        }

        // Password strength validation
        if (pass.length < 8) {
            alert('Password must be at least 8 characters long.');
            return;
        }
        if (!/[A-Z]/.test(pass)) {
            alert('Password must contain at least one uppercase letter.');
            return;
        }
        if (!/[a-z]/.test(pass)) {
            alert('Password must contain at least one lowercase letter.');
            return;
        }
        if (!/[0-9]/.test(pass)) {
            alert('Password must contain at least one number.');
            return;
        }

        s1.classList.add('hidden');
        s2.classList.remove('hidden');
        mainTitle.textContent = 'Learning Preferences';
        mainSubtitle.textContent = 'Help us personalize your learning experience.';
        stepCount.textContent = 'Step 2 of 2';
        stepLabel.textContent = 'Account Details';
        progressBar.classList.remove('w-1/2');
        progressBar.classList.add('w-full');
    } else {
        s1.classList.remove('hidden');
        s2.classList.add('hidden');
        mainTitle.textContent = 'Create your account';
        mainSubtitle.textContent = 'Join the student community and start swapping skills.';
        stepCount.textContent = 'Step 1 of 2';
        stepLabel.textContent = 'Account Details';
        progressBar.classList.add('w-1/2');
        progressBar.classList.remove('w-full');
    }
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

// Tag Input Management
document.addEventListener('DOMContentLoaded', () => {
    const tagInput = document.getElementById('tag-input');
    const tagContainer = document.getElementById('tag-container');
    const hiddenInput = document.getElementById('subjects-hidden');

    if (!tagInput || !tagContainer || !hiddenInput) return;

    let tags = hiddenInput.value ? hiddenInput.value.split(',') : ['Mathematics', 'Physics'];

    function renderTags() {
        // Clear but keep input
        tagContainer.querySelectorAll('span').forEach(tag => tag.remove());
        tags.forEach(tag => {
            const tagEl = document.createElement('span');
            tagEl.className = 'bg-indigo-100 text-indigo-600 px-3 py-1 rounded-lg text-sm font-bold flex items-center gap-2 animate-in zoom-in-75 duration-200';
            tagEl.innerHTML = `${tag} <button type="button" class="hover:text-indigo-800 transition">×</button>`;
            tagEl.querySelector('button').onclick = () => {
                tags = tags.filter(t => t !== tag);
                renderTags();
            };
            tagContainer.insertBefore(tagEl, tagInput);
        });
        hiddenInput.value = tags.join(',');
    }

    tagInput.onkeydown = (e) => {
        if (e.key === 'Enter') {
            e.preventDefault();
            const val = tagInput.value.trim();
            if (val && !tags.includes(val)) {
                tags.push(val);
                tagInput.value = '';
                renderTags();
            }
        }
        if (e.key === 'Backspace' && !tagInput.value && tags.length > 0) {
            tags.pop();
            renderTags();
        }
    };

    // Initial render
    renderTags();

    // Initial render
    renderTags();
});
