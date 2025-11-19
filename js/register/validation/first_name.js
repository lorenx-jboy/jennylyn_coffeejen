
export const firstNameValidation = (value) => {
        const fieldLabel = "First name";
        const trimmed = value.trim();
        let message = "";

        if (!trimmed) {
            message = `⚠️ ${fieldLabel} is required.`;
            return { valid: false, message };
        }

        if (/\s{2,}/.test(trimmed)) {
            message = `⚠️ ${fieldLabel} cannot contain double spaces.`;
            return { valid: false, message };
        }

        const words = trimmed.split(/\s+/);

        const wordLabel = (i) => ["First", "Second", "Third"][i] || `${i + 1}th`;

        for (let i = 0; i < words.length; i++) {
            const word = words[i];
            if (!word) continue;

            const hasNumber = /\d/.test(word);
            const hasSpecial = /[^a-zA-Z]/.test(word);
            const hasTripleLetter = /(.)\1\1/i.test(word);
            const uppercaseCount = (word.match(/[A-Z]/g) || []).length;

            const prefix = words.length > 1 ? `⚠️ ${wordLabel(i)} name` : `⚠️ ${fieldLabel}`;

            if (/^\d+$/.test(word)) { message = `${prefix} cannot be numbers only.`; break; }
            if (/^\d/.test(word)) { message = `${prefix} cannot start with a number.`; break; }
            if (/^[^a-zA-Z]/.test(word)) { message = `${prefix} cannot start with a special character.`; break; }
            if (/^[^a-zA-Z]+$/.test(word)) { message = `${prefix} cannot be special characters only.`; break; }
            if (hasNumber && hasSpecial) { message = `${prefix} cannot contain both numbers and special characters.`; break; }
            if (hasNumber) { message = `${prefix} cannot contain numbers.`; break; }
            if (hasSpecial) { message = `${prefix} cannot contain special characters.`; break; }
            if (hasTripleLetter) { message = `${prefix} cannot contain three identical letters.`; break; }
            if (uppercaseCount > 1) { message = `${prefix} cannot contain multiple uppercase letters.`; break; }
            if (!/^[A-Z]/.test(word)) { message = `${prefix} must start with a capital letter.`; break; }
        }

        return { valid: message === "", message };
};