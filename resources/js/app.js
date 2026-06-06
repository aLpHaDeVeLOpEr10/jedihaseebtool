import './bootstrap';
import Alpine from 'alpinejs';

window.Alpine = Alpine;
Alpine.start();

// Utility Functions
window.JST = {
    // Copy to clipboard
    async copyToClipboard(text, btn) {
        try {
            await navigator.clipboard.writeText(text);
            if (btn) {
                const original = btn.innerHTML;
                btn.innerHTML = '<svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/></svg> Copied!';
                btn.classList.add('copy-success');
                setTimeout(() => {
                    btn.innerHTML = original;
                    btn.classList.remove('copy-success');
                }, 2000);
            }
        } catch (err) {
            console.error('Failed to copy:', err);
        }
    },

    // Format number with commas
    formatNumber(num) {
        return new Intl.NumberFormat().format(num);
    },

    // Format currency
    formatCurrency(num, currency = 'USD') {
        return new Intl.NumberFormat('en-US', {
            style: 'currency',
            currency: currency
        }).format(num);
    },

    // Show notification
    notify(message, type = 'success') {
        const container = document.getElementById('notifications') || (() => {
            const el = document.createElement('div');
            el.id = 'notifications';
            el.className = 'fixed top-4 right-4 z-50 flex flex-col gap-2';
            document.body.appendChild(el);
            return el;
        })();

        const types = {
            success: 'alert-success',
            error: 'alert-error',
            warning: 'alert-warning',
            info: 'alert-info',
        };

        const toast = document.createElement('div');
        toast.className = `alert ${types[type] || types.success} shadow-lg animate-fade-in max-w-sm`;
        toast.textContent = message;
        container.appendChild(toast);

        setTimeout(() => {
            toast.style.opacity = '0';
            toast.style.transition = 'opacity 0.3s';
            setTimeout(() => toast.remove(), 300);
        }, 3000);
    },

    // Debounce
    debounce(fn, delay = 300) {
        let timer;
        return (...args) => {
            clearTimeout(timer);
            timer = setTimeout(() => fn(...args), delay);
        };
    },

    // Show/hide loading
    setLoading(btn, loading) {
        if (loading) {
            btn.dataset.original = btn.innerHTML;
            btn.innerHTML = '<span class="spinner"></span> Processing...';
            btn.disabled = true;
        } else {
            btn.innerHTML = btn.dataset.original;
            btn.disabled = false;
        }
    }
};

// Tool calculator functions
window.ToolCalculators = {
    percentage(value, percent) {
        return (value * percent) / 100;
    },

    bmi(weight, height, unit = 'metric') {
        if (unit === 'metric') {
            const heightM = height / 100;
            return weight / (heightM * heightM);
        } else {
            return (703 * weight) / (height * height);
        }
    },

    bmiCategory(bmi) {
        if (bmi < 18.5) return { label: 'Underweight', color: 'text-blue-600' };
        if (bmi < 25) return { label: 'Normal weight', color: 'text-green-600' };
        if (bmi < 30) return { label: 'Overweight', color: 'text-yellow-600' };
        return { label: 'Obese', color: 'text-red-600' };
    },

    loan(principal, annualRate, months) {
        if (annualRate === 0) return principal / months;
        const r = annualRate / 100 / 12;
        return principal * r * Math.pow(1 + r, months) / (Math.pow(1 + r, months) - 1);
    },

    tipAmount(bill, tipPercent) {
        return (bill * tipPercent) / 100;
    },

    dateDiff(date1, date2) {
        const d1 = new Date(date1);
        const d2 = new Date(date2);
        const diffMs = Math.abs(d2 - d1);
        const days = Math.floor(diffMs / (1000 * 60 * 60 * 24));
        const weeks = Math.floor(days / 7);
        const months = Math.floor(days / 30.44);
        const years = Math.floor(days / 365.25);
        return { days, weeks, months, years };
    }
};

// Search functionality
const searchInput = document.getElementById('site-search');
if (searchInput) {
    searchInput.addEventListener('input', window.JST.debounce(async (e) => {
        const q = e.target.value.trim();
        if (q.length < 2) return;
        // Redirect to search page
        window.location.href = `/search?q=${encodeURIComponent(q)}`;
    }, 600));
}

// Flash message auto-dismiss
document.querySelectorAll('[data-auto-dismiss]').forEach(el => {
    setTimeout(() => {
        el.style.opacity = '0';
        el.style.transition = 'opacity 0.5s';
        setTimeout(() => el.remove(), 500);
    }, parseInt(el.dataset.autoDismiss) || 5000);
});

// Confirm delete dialogs
document.querySelectorAll('[data-confirm]').forEach(el => {
    el.addEventListener('click', (e) => {
        if (!confirm(el.dataset.confirm || 'Are you sure?')) {
            e.preventDefault();
        }
    });
});
