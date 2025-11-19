
export const birthdateValidation = (value) => {
        const dateValue = value.trim();
        const label = "Birthday";
        let message = "";
        let calculatedAge = null; // New return value

        // Helper function (define this outside the validator for cleaner code)
        const calculateAge = (birthDate) => {
            const today = new Date();
            let age = today.getFullYear() - birthDate.getFullYear();
            const monthDiff = today.getMonth() - birthDate.getMonth();
            if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
                age--;
            } 
            return age;
        };

        // 1. Required check
        if (dateValue === "") {
            message = `⚠️ ${label} is required.`;
        } 
        else {
            const birthDate = new Date(dateValue);
            const today = new Date();

            // 2. Date validity checks
            if (isNaN(birthDate) || birthDate > today) {
                message = `⚠️ Invalid date or future date selected.`;
            } else {
                const age = calculateAge(birthDate);
                calculatedAge = age; // Store the calculated age

                // 3. Year range check
                const year = birthDate.getFullYear();
                if (year < 1900 || year > 9999) {
                    message = "⚠️ Please enter a valid 4-digit year (1900-9999).";
                    calculatedAge = null; // Clear age if invalid
                }
                // 4. Age range checks
                else if (age < 18) {
                    message = "⚠️ You must be at least 18 years old.";
                } 
                else if (age > 85) {
                    message = "⚠️ Age must not exceed 85 years old.";
                }
                else if (age > 120 || age < 0) {
                    message = "⚠️ Invalid age calculated.";
                }
            }
        }

        // Return the age along with the validation status
        return { valid: message === "", message, age: calculatedAge };
};