export const zipcodeValidation = (value) => {
        const trimmed = value.trim();
        let message = "";

        // 1️⃣ Empty input
        if (trimmed === "") {
            message = "⚠️ Zipcode is required.";
        } 
        // 2️⃣ Only digits allowed
        else if (/[^0-9]/.test(trimmed)) {
            message = "⚠️ Zip Code must contain numbers only.";
        } 
        // 3️⃣ Exactly 4 digits
        else if (trimmed.length !== 4) {
            message = "⚠️ Zip Code must be exactly 4 digits.";
        }

        return { valid: message === "", message };
};