import './bootstrap';

document.addEventListener('click', async (event) => {
    const button = event.target.closest('[data-copy], [data-copy-text]');

    if (!button) return;

    const source = button.dataset.copy
        ? document.getElementById(button.dataset.copy)?.value
        : button.dataset.copyText;

    if (!source) return;

    try {
        await navigator.clipboard.writeText(source);
        const originalText = button.textContent;
        button.textContent = 'Copied!';
        window.setTimeout(() => { button.textContent = originalText; }, 1500);
    } catch {
        button.focus();
        button.textContent = 'Select and copy';
    }
});
