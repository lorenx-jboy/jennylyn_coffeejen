// export const firstNameValidation = (value) => {
//     value = value.trim();
//     const fieldLabel = "First name";
//     let message = "";

//     if (!value) {
//         message = `⚠️ ${fieldLabel} is required.`;
//     } else if (/\s{2,}/.test(value)) {
//         message = `⚠️ ${fieldLabel} cannot contain double spaces.`;
//     } else {
//         const words = value.split(/\s+/);
//         const wordLabel = (i) => ["First", "Second", "Third"][i] || `${i + 1}th`;

//         for (let i = 0; i < words.length; i++) {
//             const word = words[i];
//             if (!word) continue;

//             const hasNumber = /\d/.test(word);
//             const hasSpecial = /[^a-zA-Z.]/.test(word);  // except dot
//             const hasTripleLetter = /(.)\1\1/i.test(word);
//             const uppercaseCount = (word.match(/[A-Z]/g) || []).length;

//             const prefix = words.length > 1 ? `⚠️ ${wordLabel(i)} name` : `⚠️ ${fieldLabel}`;

//             if (/^\d+$/.test(word)) { message = `${prefix} cannot be numbers only.`; break; }
//             if (/^\d/.test(word)) { message = `${prefix} cannot start with a number.`; break; }
//             if (/^[^a-zA-Z]/.test(word)) { message = `${prefix} cannot start with a special character.`; break; }
//             if (/^[^a-zA-Z]+$/.test(word)) { message = `${prefix} cannot be special characters only.`; break; }
//             if (hasNumber && hasSpecial) { message = `${prefix} cannot contain both numbers and special characters.`; break; }
//             if (hasNumber) { message = `${prefix} cannot contain numbers.`; break; }
//             if (hasSpecial) { message = `${prefix} cannot contain special characters`; break; }
//             if (hasTripleLetter) { message = `${prefix} cannot contain three identical letters.`; break; }
//             if (uppercaseCount === word.length) { message = `${prefix} cannot contain all uppercase letters.`; break; }
//             if (!/^[A-Z]/.test(word)) { message = `${prefix} must start with a capital letter.`; break; }
//         }
//     }

//     // SHOW THE ERROR UNDER FIELD
//     // const errorElement = field.parentElement.querySelector(".error-message");
//     // errorElement.textContent = message;

//     return { valid: message === "", message };
// };



export const firstNameValidation = (value) => {
    value = value;
    const fieldLabel = "First name";
    let message = "";

    if (!value) {
        message = `⚠️ ${fieldLabel} is required.`;
    } else if (/\s{2,}/.test(value)) {
        message = `⚠️ ${fieldLabel} cannot contain double spaces.`;
    } else {
        const words = value.split(/\s+/);
        const wordLabel = (i) => ["First", "Second", "Third"][i] || `${i + 1}th`;

        for (let i = 0; i < words.length; i++) {
            const word = words[i];
            if (!word) continue;

            const hasNumber = /\d/.test(word);
            const hasTripleLetter = /(.)\1\1/i.test(word);
            const uppercaseCount = (word.match(/[A-Z]/g) || []).length;
            const hasSpecialChar = /[^a-zA-Z0-9]/.test(word); // Only non-alphanumeric counts as special

            const prefix = words.length > 1 ? `⚠️ ${wordLabel(i)} name` : `⚠️ ${fieldLabel}`;

            // Check both numbers and special characters first
            if (hasNumber && hasSpecialChar) {
                message = `${prefix} cannot contain both numbers and special characters.`;
                break;
            }

            // Numbers only
            if (hasNumber && !hasSpecialChar) {
                message = `${prefix} cannot contain numbers.`;
                break;
            }

            // Special characters only
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

