
export const saveFormState = (form) => {
    [...form.elements].forEach(el => {
        if (!el.id) return;

        // Don’t save password fields for security
        if (el.type === "password") return;

        localStorage.setItem(el.id, el.value);
    });
};

export const loadFormState = (form) => {
    [...form.elements].forEach(el => {
        if (!el.id) return;

        const stored = localStorage.getItem(el.id);
        if (stored === null) return;

        // Browsers block setting password fields on load
        if (el.type === "password") return;

        el.value = stored;
        el.dispatchEvent(new Event("input"));
        el.dispatchEvent(new Event("change"));
    });
};
