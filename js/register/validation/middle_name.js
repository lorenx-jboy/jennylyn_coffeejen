export const middleNameValidation = (value) => {
        const fieldLabel = "Middle name";
        const trimmed = value.trim();
        let message = "";

        // 1) Check for double spaces
        if (/\s{2,}/.test(trimmed)) {
            message = `⚠️ ${fieldLabel} cannot contain double spaces.`;
        } 
        else if (trimmed !== "") {
            // Detect numbers and special characters across the entire input
            const hasNumber = /\d/.test(trimmed);
            const hasSpecial = /[^A-Za-z0-9\s]/.test(trimmed);

            // 2) Combined / specific messages
            if (hasNumber && hasSpecial) {
                message = `⚠️ ${fieldLabel} cannot contain numbers and special characters.`;
            } else if (hasNumber) {
                message = `⚠️ ${fieldLabel} cannot contain numbers.`;
            } else if (hasSpecial) {
                message = `⚠️ ${fieldLabel} cannot contain special characters.`;
            }
        }

        return { valid: message === "", message };
};