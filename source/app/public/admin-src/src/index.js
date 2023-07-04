(function () {
    window.addEventListener('DOMContentLoaded', (event) => {
        (function () {
            // Stop duplicate clicks
            const nodes = document.querySelectorAll('input[data-submit-once="1"]');
            nodes.forEach((node) => {
                node.addEventListener('click', (event) => {
                    const el = event.target;

                    const form = el.closest('form');
                    if (form === null || ! form.checkValidity()) {
                        return;
                    }

                    if (el.dataset.disabled && el.dataset.disabled === 1) {
                        event.preventDefault();
                        event.stopPropagation();
                        return false;
                    }

                    el.dataset.disabled = 1;
                });
            });
        })();
    });
})();
