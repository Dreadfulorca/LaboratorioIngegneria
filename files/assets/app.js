/*
 * Welcome to your app's main JavaScript file!
 *
 * This file will be included onto the page via the importmap() Twig function,
 * which should already be in your base.html.twig.
 */
import 'bootstrap/dist/css/bootstrap.min.css';

import 'bootstrap'

// === Custom validation: max 2 decimal digits on amount field ===
(function() {
    function hasTooManyDecimals(raw) {
        if (!raw) return false;
        const v = String(raw).trim().replace(/\s+/g, '');
        // Accetta virgola o punto come separatore decimale
        const m = v.match(/^[-+]?\d+(?:[\.,](\d+))?$/);
        if (!m) return false;
        const dec = m[1] || '';
        return dec.length > 2;
    }

    function attachValidation(root) {
        const selector = 'input[name$="[amount]"], input[id$="_amount"], input[name="amount"], input#amount';
        const amountInput = (root || document).querySelector(selector);
        if (!amountInput) return;

        amountInput.addEventListener('blur', function() {
            if (hasTooManyDecimals(amountInput.value)) {
                alert("L'importo può avere al massimo 2 cifre decimali.");
                amountInput.focus();
            }
        });

        const form = amountInput.closest('form');
        if (form && !form.dataset.decimalValidationAttached) {
            form.addEventListener('submit', function(e) {
                if (hasTooManyDecimals(amountInput.value)) {
                    e.preventDefault();
                    alert("L'importo può avere al massimo 2 cifre decimali.");
                    amountInput.focus();
                }
            });
            form.dataset.decimalValidationAttached = '1';
        }
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', function() { attachValidation(document); });
    } else {
        attachValidation(document);
    }
})();
// === End custom validation ===
