export const extensionNameValidation = (value) => {
    const fieldLabel = "Extension name";
    const trimmed = value.trim();
    let message = "";

    // List of allowed suffixes
    const validExtensions = [
        "Jr", "Sr", "JR", "SR",
        "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X"
    ];

    // 1) Check for double spaces
    if (/\s{2,}/.test(trimmed)) {
        message = `⚠️ ${fieldLabel} cannot contain double spaces.`;
    }
    else if (trimmed !== "") {

        // Check for invalid characters
        const hasNumber = /\d/.test(trimmed);
        const hasSpecial = /[^A-Za-z0-9\s]/.test(trimmed);

        if (hasNumber && hasSpecial) {
            message = `⚠️ ${fieldLabel} cannot contain numbers and special characters.`;
        } else if (hasNumber) {
            message = `⚠️ ${fieldLabel} cannot contain numbers.`;
        } else if (hasSpecial) {
            message = `⚠️ ${fieldLabel} cannot contain special characters.`;
        } 
        else if (!validExtensions.includes(trimmed)) {
            message = `⚠️ ${fieldLabel} must be a valid suffix (e.g., Jr, Sr, I, II, III, IV).`;
        }
    }

    return { valid: message === "", message };
}