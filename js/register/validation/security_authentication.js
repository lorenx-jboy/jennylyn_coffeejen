
const security_answer_validator = (value) => {
    const label = "Security Answer"; 
    const trimmedValue = value.trim();
    let errorMessage = "";
    
    // An answer is usually required
    if (trimmedValue === "") {
        errorMessage = `⚠️ ${label} is required.`; 
    }
    // Rule 1: No numbers
    else if (/\d/.test(trimmedValue)) {
        errorMessage = `⚠️ ${label} cannot contain numbers.`;
    }
    // Rule 2: No special characters (Allows only letters and spaces)
    else if (/[^a-zA-Z\s]/.test(trimmedValue)) {
        errorMessage = `⚠️ ${label} cannot contain special characters.`;
    }
    // Rule 3: No double spaces
    else if (/( {2,})/.test(trimmedValue)) {
        errorMessage = `⚠️ ${label} cannot contain double spaces.`;
    }
    // Rule 4: Must not be all uppercase
    else if (trimmedValue === trimmedValue.toUpperCase() && trimmedValue !== "") {
        errorMessage = `⚠️ ${label} cannot be all uppercase.`;
    }
    // Rule 5: Must not be all lowercase
    else if (trimmedValue === trimmedValue.toLowerCase() && trimmedValue !== "") {
        errorMessage = `⚠️ ${label} cannot be all lowercase.`;
    }

    return { valid: errorMessage === "", message: errorMessage };
};

// Validation rules
export const security_answer_validators = {
    a1_answer: security_answer_validator,
    a2_answer: security_answer_validator,
    a3_answer: security_answer_validator,
};