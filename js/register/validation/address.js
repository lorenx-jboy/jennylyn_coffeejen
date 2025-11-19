export const streetValidation = (value) => {
    const trimmed = value.trim();
    let message = "";

    // 1️⃣ Required
    if (trimmed === "") {
        message = "⚠️ Street/Purok is required.";
    } 
    // 2️⃣ Cannot begin with a space
    else if (/^\s/.test(value)) {
        message = "⚠️ Street/Purok cannot start with a space.";
    }
    // 3️⃣ Special characters check (allowed: letters, numbers, space, dot, dash, comma)
    else if (/[^A-Za-z0-9\s.,-]/.test(value)) {
        message = "⚠️ Only letters, numbers, space, dot (.), dash (-), and comma (,) are allowed.";
    }
    // 4️⃣ Cannot start with lowercase letter
    else if (/^[a-z]/.test(trimmed)) {
        message = "⚠️ Street/Purok cannot start with a lowercase letter.";
    }
    // 5️⃣ No double or triple spaces
    else if (/\s{2,}/.test(value)) {
        message = "⚠️ Double or triple spaces are not allowed.";
    }
    // 6️⃣ No two consecutive capital letters
    else if (/[A-Z]{2,}/.test(value)) {
        message = "⚠️ Two consecutive capital letters are not allowed.";
    }
    // 7️⃣ Triple repeated characters
    else if (/(.)\1\1/.test(value)) {
        message = "⚠️ Triple repeated characters are not allowed.";
    }
    // 8️⃣ Length check
    else if (trimmed.length < 2) {
        message = "⚠️ Must be at least 2 characters long.";
    } 
    else if (trimmed.length > 50) {
        message = "⚠️ Street/Purok is too long (maximum 50 characters).";
    }

    return { valid: message === "", message };
};


export const addressValidation = (value, label = "Address", allowExtraChars = false) => {
    const trimmed = value.trim();
    let message = "";

    // 1️⃣ Required
    if (trimmed === "") {
        message = `⚠️ ${label} is required.`;
    }
    // 2️⃣ Cannot begin with space
    else if (/^\s/.test(value)) {
        message = `⚠️ ${label} cannot start with a space.`;
    }
    // 3️⃣ Max length
    else if (trimmed.length > 50) {
        message = `⚠️ ${label} is too long (max 50 characters).`;
    }
    // 4️⃣ Numbers not allowed
    else if (/\d/.test(trimmed)) {
        message = `⚠️ ${label} cannot contain numbers.`;
    }
    // 5️⃣ Special character rules
    else {
        const specialPattern = allowExtraChars
            ? /[^A-Za-z\s\-\.\(\)]/
            : /[^A-Za-z\s]/;

        if (specialPattern.test(trimmed)) {
            message = `⚠️ ${label} contains invalid special characters.`;
        }
    }
    // 6️⃣ No double/triple spaces
    if (!message && /\s{2,}/.test(value)) {
        message = `⚠️ ${label} cannot contain double or triple spaces.`;
    }
    // 7️⃣ No triple repeating characters
    else if (!message && /(.)\1\1/i.test(trimmed)) {
        message = `⚠️ ${label} cannot contain three repeating letters.`;
    }
    // 8️⃣ Validate each word formatting
    else if (!message) {
        const words = trimmed.split(/\s+/);

        for (const w of words) {
            if (!w) continue;

            // ❌ All-caps word more than 1 character
            if (w.length > 1 && w === w.toUpperCase()) {
                message = `⚠️ ${label} cannot contain fully uppercase words.`;
                break;
            }

            // First letter must be uppercase
            if (w[0] !== w[0].toUpperCase()) {
                message = `⚠️ ${label} must have each word start with an uppercase letter.`;
                break;
            }

            // Remaining letters must be lowercase
            if (w.slice(1) !== w.slice(1).toLowerCase()) {
                message = `⚠️ ${label} must use proper capitalization (only first letter uppercase).`;
                break;
            }

            // Inside-word capital restriction
            if (/[A-Z]{2,}/.test(w.slice(1))) {
                message = `⚠️ ${label} cannot have two consecutive uppercase letters.`;
                break;
            }
        }
    }

    return { valid: message === "", message };
};


export const addressValidators = {
    country: addressValidation,
    province: addressValidation,
    city: addressValidation,
    barangay: addressValidation,
}