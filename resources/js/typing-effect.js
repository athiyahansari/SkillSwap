const words = ["Calculus...", "Python...", "Biology...", "Organic Chemistry...", "Machine Learning..."];
let wordIndex = 0;
let charIndex = 0;
let isDeleting = false;
const typingInput = document.getElementById('typing-input');

function type() {
    if (!typingInput) return;

    const currentWord = words[wordIndex];
    if (isDeleting) {
        typingInput.placeholder = "Search for " + currentWord.substring(0, charIndex - 1);
        charIndex--;
    } else {
        typingInput.placeholder = "Search for " + currentWord.substring(0, charIndex + 1);
        charIndex++;
    }

    if (!isDeleting && charIndex === currentWord.length) {
        isDeleting = true;
        setTimeout(type, 2000);
    } else if (isDeleting && charIndex === 0) {
        isDeleting = false;
        wordIndex = (wordIndex + 1) % words.length;
        setTimeout(type, 500);
    } else {
        setTimeout(type, isDeleting ? 50 : 100);
    }
}

document.addEventListener('DOMContentLoaded', type);
