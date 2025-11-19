export const lastNameValidation = (value) => {
            const fieldLabel = "Last name";
            const trimmed = value.trim();
            let message = "";
    
            // Allow empty last name (optional)
            if (trimmed === "") {
                return { valid: true, message: "" };
            }
    
            // Check for double spaces
            if (/\s{2,}/.test(trimmed)) {
                message = `⚠️ ${fieldLabel} cannot contain double spaces.`;
            } else {
                const words = trimmed.split(/\s+/);
    
                for (let i = 0; i < words.length; i++) {
                    const word = words[i];
                    if (!word) continue;
    
                    const hasNumber = /\d/.test(word);
                    const hasSpecial = /[^a-zA-Z]/.test(word);
                    const hasBoth = hasNumber && hasSpecial;
                    const hasTripleLetter = /(.)\1\1/i.test(word);
                    const uppercaseCount = (word.match(/[A-Z]/g) || []).length;
    
                    if (/^\d+$/.test(word)) {
                        message = `⚠️ ${fieldLabel} cannot contain numbers only.`;
                        break;
                    }
    
                    if (/^[^a-zA-Z]+$/.test(word)) {
                        message = `⚠️ ${fieldLabel} cannot contain special characters only.`;
                        break;
                    }
    
                    if (/^\d/.test(word)) {
                        message = `⚠️ ${fieldLabel} cannot start with a number.`;
                        break;
                    }
    
                    if (/^[^a-zA-Z]/.test(word)) {
                        message = `⚠️ ${fieldLabel} cannot start with a special character.`;
                        break;
                    }
    
                    if (hasBoth) {
                        message = `⚠️ ${fieldLabel} cannot contain both numbers and special characters.`;
                        break;
                    }
    
                    if (hasNumber) {
                        message = `⚠️ ${fieldLabel} cannot contain numbers.`;
                        break;
                    }
    
                    if (hasSpecial) {
                        message = `⚠️ ${fieldLabel} cannot contain special characters.`;
                        break;
                    }
    
                    if (hasTripleLetter) {
                        message = `⚠️ ${fieldLabel} cannot contain three identical letters in a row.`;
                        break;
                    }
    
                    if (uppercaseCount > 1) {
                        message = `⚠️ ${fieldLabel} cannot contain multiple uppercase letters.`;
                        break;
                    }
    
                    if (!/^[A-Z]/.test(word)) {
                        message = `⚠️ ${fieldLabel} must start with a capital letter.`;
                        break;
                    }
                }
            }
    
            return { valid: message === "", message };
};