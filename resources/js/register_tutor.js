/**
 * Tutor Registration Flow JavaScript
 * Handles multi-phase transitions, file uploads, and availability grid.
 */

function goToStep(step) {
    try {
        const phases = document.querySelectorAll('.phase-container');
        const phaseCount = document.getElementById('phase-count');
        const phaseLabel = document.getElementById('phase-label');
        const progressBar = document.getElementById('progress-bar');
        const mainTitle = document.getElementById('main-title');
        const mainSubtitle = document.getElementById('main-subtitle');

        // Card elements
        const card1Title = document.getElementById('card-1-title');
        const card1Text = document.getElementById('card-1-text');
        const card2Title = document.getElementById('card-2-title');
        const card2Text = document.getElementById('card-2-text');

        if (!phaseCount || !phaseLabel || !progressBar || !mainTitle || !mainSubtitle) {
            console.error('Missing required progress elements');
            return;
        }

        if (step === 2) {
            // Validation for Step 1
            const nameEl = document.getElementById('field-name');
            const emailEl = document.getElementById('field-email');
            const passEl = document.getElementById('tutor-pass');

            if (!nameEl || !emailEl || !passEl) return;

            const name = nameEl.value;
            const email = emailEl.value;
            const pass = passEl.value;

            if (!name || !email || !pass) {
                alert('Please fill in all required fields (Name, Email, Password) in Step 1.');
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
        }

        // Hide all phases
        phases.forEach(p => p.classList.add('hidden'));

        // Show current phase
        const currentPhase = document.getElementById(`phase-${step}`);
        if (currentPhase) currentPhase.classList.remove('hidden');

        // Update Progress UI
        phaseCount.textContent = `Step ${step} of 4`;
        progressBar.style.width = `${(step / 4) * 100}%`;

        // Update Headings and Cards based on Step
        switch (step) {
            case 1:
                mainTitle.innerHTML = 'Share your expertise.<br>Shape the future.';
                mainSubtitle.textContent = 'Join our community of elite tutors and start earning on your own schedule. Fill out the application below to get started.';
                phaseLabel.textContent = 'Personal Information & Expertise';
                if (card1Title) card1Title.textContent = 'Academic Standards';
                if (card1Text) card1Text.textContent = 'We maintain high standards. Ensure your ID matches your subject expertise.';
                if (card2Title) card2Title.textContent = 'Weekly Payouts';
                if (card2Text) card2Text.textContent = 'Direct deposit every Friday for all completed sessions from the previous week.';
                break;
            case 2:
                mainTitle.innerHTML = 'Academic<br>Qualifications.';
                mainSubtitle.textContent = 'We value excellence. Tell us about your educational background and upload your supporting documents for verification.';
                phaseLabel.textContent = 'Academic Qualifications';
                if (card1Title) card1Title.textContent = 'Transcript Guidelines';
                if (card1Text) card1Text.textContent = 'Official or unofficial transcripts are accepted. They must clearly show your degree status.';
                if (card2Title) card2Title.textContent = 'Fast Verification';
                if (card2Text) card2Text.textContent = 'Our team typically reviews and verifies credentials within 24-48 business hours.';
                break;
            case 3:
                mainTitle.innerHTML = 'Set your availability.<br>Tutor on your terms.';
                mainSubtitle.textContent = 'Define when you\'re available to teach. You can change these hours at any time after your profile is live.';
                phaseLabel.textContent = 'Availability & Schedule';
                if (card1Title) card1Title.textContent = 'Flexible Hours';
                if (card1Text) card1Text.textContent = 'There are no minimum hours required. Each session is 1 hour long. You can work as much or as little as you want.';
                if (card2Title) card2Title.textContent = 'Instant Bookings';
                if (card2Text) card2Text.textContent = 'When students book a slot you\'ve marked as available, it\'s instantly confirmed.';
                break;
            case 4:
                mainTitle.innerHTML = 'Final Review.<br>Set your payout.';
                mainSubtitle.textContent = 'You\'re almost there! Review your application details and select how you\'d like to receive your earnings.';
                phaseLabel.textContent = 'Payment & Review';
                if (card1Title) card1Title.textContent = 'Review Period';
                if (card1Text) card1Text.textContent = 'Our team typically reviews applications within 24-48 business hours.';
                if (card2Title) card2Title.textContent = 'Onboarding Support';
                if (card2Text) card2Text.textContent = 'You can always email us at support@skillswap.com for any questions or concerns.';

                // Populate Summary
                populateSummary();
                break;
        }

        window.scrollTo({ top: 0, behavior: 'smooth' });
    } catch (e) {
        console.error('Tutor Registration Error:', e);
    }
}

function triggerUpload(id) {
    document.getElementById(id).click();
}

function handleFile(input, statusId) {
    const status = document.getElementById(statusId);
    if (input.files.length > 0) {
        const file = input.files[0];
        const maxSize = input.id === 'university-id' ? 5 : 10; // MB

        if (file.size > maxSize * 1024 * 1024) {
            alert(`File is too large. Max size is ${maxSize}MB.`);
            input.value = '';
            status.classList.add('hidden');
            return;
        }

        status.textContent = `Selected: ${file.name}`;
        status.classList.remove('hidden');
    }
}

function toggleSlot(el) {
    el.classList.toggle('slot-active');
    el.classList.toggle('slot-inactive');
    updateAvailability();
}

function updateAvailability() {
    const grid = document.querySelector('.grid-cols-8');
    const slots = grid.querySelectorAll('div[onclick="toggleSlot(this)"]');
    let data = [];

    slots.forEach((slot, index) => {
        if (slot.classList.contains('slot-active')) {
            data.push(index); // Simple index based storage
        }
    });

    document.getElementById('availability-hidden').value = JSON.stringify(data);
}

function populateSummary() {
    const name = document.getElementById('field-name').value || 'Jane Doe';
    const rate = document.querySelector('input[name="rate"]').value || '45.00';
    const subjectSelect = document.querySelector('select[name="subject"]');
    const subject = subjectSelect.options[subjectSelect.selectedIndex].text || 'Mathematics';

    document.getElementById('summary-name').textContent = name;
    document.getElementById('summary-rate').textContent = `$${rate} / hour`;
    document.getElementById('summary-subject').textContent = subject;
}
