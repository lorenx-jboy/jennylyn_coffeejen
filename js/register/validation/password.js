// export const passwordValidation = (password) => {
//     let strength = 0;
//     let message = "";

//     // Strength scoring rules
//     if (password.length >= 6) strength++;
//     if (password.length >= 10) strength++;
//     if (/[A-Z]/.test(password)) strength++;
//     if (/\d/.test(password)) strength++;
//     if (/[^A-Za-z0-9]/.test(password)) strength++;

//     // Strength scale
//     const strengths = ["Very Weak", "Weak", "Moderate", "Strong", "Very Strong"];
//     const strengthLabel = strengths[strength - 1] || "";

//     // Validation messages
//     if (password === "") {
//         message = "⚠️ Password is required.";
//     } else if (password.length < 6) {
//         message = "⚠️ Password must be at least 6 characters.";
//     }

//     return {
//         valid: message === "",
//         message,
//         strength,
//         strengthLabel
//     };
// };

export const passwordValidation = (password) => {
    let strength = 0;
    let message = "";

    // Strength scoring rules
    if (password.length >= 8) strength++;  // Password length must be at least 8
    if (/[A-Z]/.test(password)) strength++; // Contains at least 1 uppercase letter
    if (/\d/.test(password)) strength++;   // Contains at least 1 number
    if (/[^A-Za-z0-9]/.test(password)) strength++; // Contains at least 1 special character

    // Strength scale
    const strengths = ["Very Weak", "Weak", "Moderate", "Strong", "Very Strong"];
    const strengthLabel = strengths[strength - 1] || "";

    // Validation messages
    if (password === "") {
        message = "⚠️ Password is required.";
    } else if (password.length < 8) {
        message = "⚠️ Password must be at least 8 characters.";
    } else if (!/[A-Z]/.test(password)) {
        message = "⚠️ Password must contain at least 1 uppercase letter.";
    } else if (!/\d/.test(password)) {
        message = "⚠️ Password must contain at least 1 number.";
    } else if (!/[^A-Za-z0-9]/.test(password)) {
        message = "⚠️ Password must contain at least 1 special character.";
    }

    return {
        valid: message === "",
        message,
        strength,
        strengthLabel
    };
};


export const confirmPasswordValidation = (value) => {
    const confirmValue = value.trim();
    const passwordInput = document.querySelector("input[name='password']");
    let message = "";

    // Check if the primary password field exists and has a value
    if (!passwordInput || !passwordInput.value) {
        return { valid: false, message: "⚠️ Enter a password first." };
    }

    // 1️⃣ Required check
    if (confirmValue.length === 0) {
        message = "⚠️ Please re-enter your password.";
    }
    // 2️⃣ Match Check
    else if (confirmValue !== passwordInput.value) {
        message = "⚠️ Passwords do not match.";
    }

    return { valid: message === "", message };
};

