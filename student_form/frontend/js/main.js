/**
 * Student Grade Management System — main.js
 * Handles: alert auto-dismiss, form client-side validation, loading state
 */

document.addEventListener('DOMContentLoaded', () => {

    const prefersReducedMotion = window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    /* ── Auto-dismiss success alert ─────────────────────────── */
    const successAlert = document.querySelector('.alert--success');
    if (successAlert) {
        setTimeout(() => {
            if (prefersReducedMotion) {
                successAlert.remove();
            } else {
                successAlert.style.transition = 'opacity 0.4s ease, transform 0.4s ease';
                successAlert.style.opacity    = '0';
                successAlert.style.transform  = 'translateY(-4px)';
                setTimeout(() => successAlert.remove(), 420);
            }
        }, 4000);
    }

    /* ── Form: validate + loading state ─────────────────────── */
    const form      = document.querySelector('.js-student-form');
    const submitBtn = form?.querySelector('.btn--primary');

    if (!form || !submitBtn) return;

    form.addEventListener('submit', (e) => {
        const fname  = form.querySelector('#fname');
        const lname  = form.querySelector('#lname');
        const studid = form.querySelector('#studid');
        const grade  = form.querySelector('#grade');

        // Client-side validation (mirrors server-side rules)
        const gradeVal = Number(grade.value);
        const gradeOk  = grade.value.trim() !== ''
                      && Number.isFinite(gradeVal)
                      && gradeVal >= 0
                      && gradeVal <= 100;

        const isValid =
            fname.value.trim()  !== '' &&
            lname.value.trim()  !== '' &&
            studid.value.trim() !== '' &&
            gradeOk;

        if (!isValid) {
            // Let browser/server handle — do NOT lock button
            return;
        }

        // All valid — show loading state
        submitBtn.textContent = 'Saving…';
        submitBtn.disabled    = true;
    });

});
