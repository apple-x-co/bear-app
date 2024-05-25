document.addEventListener('DOMContentLoaded', () => {
    (() => {
        // Stop duplicate clicks
        const nodes = document.querySelectorAll('input[data-submit-once="1"]');
        nodes.forEach((node) => {
            node.addEventListener('click', (ev) => {
                const el = ev.currentTarget;

                const form = el.closest('form');
                if (form === null || ! form.checkValidity()) {
                    return;
                }

                if (el.dataset.disabled && el.dataset.disabled === '1') {
                    ev.preventDefault();
                    ev.stopPropagation();

                    return false;
                }

                el.dataset.disabled = '1';
            });
        });
    })();
});
