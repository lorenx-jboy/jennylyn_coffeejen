
export const purokValidation = (value) => {
        const trimmed = value.trim();
        let message = "";

        // 1️⃣ Empty input
        if (trimmed === "") {
            message = "⚠️ Purok/Street is required.";
        } 
        // 2️⃣ Cannot start with a dash
        else if (/^-/.test(trimmed)) {
            message = "⚠️ Purok cannot start with a dash (-).";
        } 
        // 3️⃣ Double or multiple spaces
        else if (/\s{2,}/.test(trimmed)) {
            message = "⚠️ Purok/Street cannot contain double spaces.";
        } 
        // 4️⃣ Three identical consecutive letters
        else if (/([a-zA-Z])\1\1/.test(trimmed)) {
            message = "⚠️ Purok/Street cannot contain three identical consecutive letters.";
        } 
        // 5️⃣ Only numbers
        else if (/^\d+$/.test(trimmed)) {
            message = "⚠️ Purok/Street cannot contain only numbers.";
        } 
        // 6️⃣ Special characters (allow only comma, period, dash)
        else if (/[^a-zA-Z0-9\s,.-]/.test(trimmed)) {
            message = "⚠️ Purok/Street can only contain letters, numbers, spaces, commas (,), periods (.), and dashes (-).";
        } 

        return { valid: message === "", message };
};