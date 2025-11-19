export const usernameValidation = (value) => {
        const fieldname = "Username";
        const trimmed = value.trim();
        let message = "";

        if (trimmed === "") {
            message = `⚠️ ${fieldname} is required.`;
        } else if (/\s/.test(trimmed)) {
            message = `⚠️ ${fieldname} cannot contain spaces.`;
        } else {
            const firstChar = trimmed.charAt(0);

            if (/\d/.test(firstChar)) {
                message = `⚠️ ${fieldname} cannot start with a number.`;
            } else if (/[^a-zA-Z]/.test(firstChar)) {
                message = `⚠️ ${fieldname} cannot start with a special character.`;
            } else if (/[^a-zA-Z0-9._-]/.test(trimmed)) {
                message = `⚠️ ${fieldname} can only include letters, numbers, dots, dashes, or underscores.`;
            } else if (/(.)\1\1/i.test(trimmed)) {
                message = `⚠️ ${fieldname} cannot contain three identical letters in a row.`;
            } else if (trimmed.length < 5) {
                message = `⚠️ ${fieldname} must be at least 5 characters.`;
            } else if ((trimmed.match(/[A-Z]/g) || []).length > 1) {
                message = `⚠️ ${fieldname} cannot contain multiple uppercase letters.`;
            } else if (!/^[A-Z]/.test(trimmed)) {
                message = `⚠️ ${fieldname} must start with a capital letter.`;
            }
        }

        return { valid: message === "", message };
};