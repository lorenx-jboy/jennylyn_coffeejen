// export const middleNameValidation = (value) => {
//         const fieldLabel = "Middle name";
//         const trimmed = value.trim();
//         let message = "";

//         // 1) Check for double spaces
//         if (/\s{2,}/.test(trimmed)) {
//             message = `⚠️ ${fieldLabel} cannot contain double spaces.`;
//         } 
//         else if (trimmed !== "") {
//             // Detect numbers and special characters across the entire input
//             const hasNumber = /\d/.test(trimmed);
//             const hasSpecial = /[^A-Za-z0-9\s]/.test(trimmed);

//             // 2) Combined / specific messages
//             if (hasNumber && hasSpecial) {
//                 message = `⚠️ ${fieldLabel} cannot contain numbers and special characters.`;
//             } else if (hasNumber) {
//                 message = `⚠️ ${fieldLabel} cannot contain numbers.`;
//             } else if (hasSpecial) {
//                 message = `⚠️ ${fieldLabel} cannot contain special characters.`;
//             }
//         }

//         return { valid: message === "", message };
// };



export const middleNameValidation = (value) => {
    const fieldLabel = "Middle name";
    const trimmed = value;
    let message = "";

    if (!trimmed) {
        message = `⚠️ ${fieldLabel} is required.`;
    } else if (/\s{2,}/.test(trimmed)) {
        message = `⚠️ ${fieldLabel} cannot contain double spaces.`;
    } else {
        const words = trimmed.split(/\s+/);
        const wordLabel = (i) => ["First", "Second", "Third"][i] || `${i + 1}th`;

        for (let i = 0; i < words.length; i++) {
            const word = words[i];
            if (!word) continue;

            const hasNumber = /\d/.test(word);
            const hasTripleLetter = /(.)\1\1/i.test(word);
            const uppercaseCount = (word.match(/[A-Z]/g) || []).length;
            const hasSpecialChar = /[^a-zA-Z0-9]/.test(word);

            const prefix = words.length > 1 ? `⚠️ ${wordLabel(i)} middle name` : `⚠️ ${fieldLabel}`;

            // Combined / specific messages
            if (hasNumber && hasSpecialChar) {
                message = `${prefix} cannot contain both numbers and special characters.`;
                break;
            }
            if (hasNumber && !hasSpecialChar) {
                message = `${prefix} cannot contain numbers.`;
                break;
            }
            if (hasSpecialChar && !hasNumber) {
                message = `${prefix} cannot contain special characters.`;
                break;
            }

            if (/^\d+$/.test(word)) {
                message = `${prefix} cannot be numbers only.`;
                break;
            }
            if (/^\d/.test(word)) {
                message = `${prefix} cannot start with a number.`;
                break;
            }
            if (/^[^a-zA-Z]/.test(word)) {
                message = `${prefix} cannot start with a non-letter.`;
                break;
            }
            if (hasTripleLetter) {
                message = `${prefix} cannot contain three identical letters.`;
                break;
            }
            if (uppercaseCount === word.length) {
                message = `${prefix} cannot contain all uppercase letters.`;
                break;
            }
            if (!/^[A-Z]/.test(word)) {
                message = `${prefix} must start with a capital letter.`;
                break;
            }
        }
    }

    return { valid: message === "", message };
};
