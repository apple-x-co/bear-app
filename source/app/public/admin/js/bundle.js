function initDCL () {
  document.addEventListener('DOMContentLoaded', () => {
    setupSubmitOnce();
  });
}

function setupSubmitOnce () {
  const nodes = document.querySelectorAll('input[data-submit-once="1"]');
  nodes.forEach((node) => {
    node.addEventListener('click', (ev) => {
      const el = ev.currentTarget;

      const form = el.closest('form');
      if (form === null || ! form.checkValidity()) {
        return;
      }

      if (el.dataset.submited && el.dataset.submited === 'true') {
        ev.preventDefault();
        ev.stopPropagation();

        return false;
      }

      el.dataset.submited = 'true';
    });
  });
}

initDCL();
