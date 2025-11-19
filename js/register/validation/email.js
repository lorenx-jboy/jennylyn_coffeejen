export const emailValidation = (value) => {
        value = value.trim();
        const pattern = /^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/;

        let message = "";

        const hasAt = /@/.test(value);
        const hasDotAfterAt = /@[^@]*\./.test(value);
        const hasDoubleDots = /\.\./.test(value);
        const hasSpaces = /\s/.test(value);
        const hasComma = /,/.test(value);
        const [localPart, domainPart] = value.split("@");

        if (/^\d+$/.test(value)) {
            message = "⚠️ Email username cannot be numbers only.";
        } else if (!hasAt) {
            message = "⚠️ Missing '@' symbol.";
        } else if (!domainPart) {
            message = "⚠️ Missing domain.";
        } else if (!hasDotAfterAt) {
            message = "⚠️ Missing dot in domain.";
        } else if (hasDoubleDots) {
            message = "⚠️ No consecutive dots.";
        } else if (hasSpaces) {
            message = "⚠️ No spaces allowed.";
        } else if (hasComma) {
            message = "⚠️ Commas not allowed.";
        } else if (/^\d+$/.test(localPart)) {
            message = "⚠️ Email username cannot be numbers only.";
        } else if (/[^a-zA-Z0-9._-]/.test(localPart)) {
            message = "⚠️ Invalid character found.";
        } else if (!pattern.test(value)) {
            message = "⚠️ Invalid email format ( you@example.com ).";
        }

        return { valid: message === "", message };
    };
