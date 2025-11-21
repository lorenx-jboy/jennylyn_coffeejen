import { validations } from "./validation/validators.js";
import { checkEmailExists, checkUsernameExists } from "./api.js";

function updatePasswordStrength(strength) {
    const bar = document.querySelector(".password-strength-bar .strength-fill");

    const percent = (strength / 4) * 100; // 0–4 → 0–100%

    bar.style.width = percent + "%";

    if (strength <= 1) bar.style.background = "#dc3545";      // red
    else if (strength === 2) bar.style.background = "#fd7e14"; // orange
    else if (strength === 3) bar.style.background = "#ffc107"; // yellow
    else bar.style.background = "#28a745";                    // green
}

export function bindValidation(form){
    form.querySelectorAll('[data-validate]').forEach(field => {
        const errorEl = field.parentElement.querySelector('.error-message');
        field.addEventListener('input', async () => {
            const rules = field.dataset.validate.split('|');
            let result = { valid: true, message: '' };

            for(const rule of rules){
                if (validations[rule]) {
                    if (validations[rule].length > 1) {
                        result = validations[rule](field.value, rule);
                    } else {
                        result = validations[rule](field.value);
                    }
                    
                    if (rule === "password") {
                        updatePasswordStrength(result.strength);
                    }

                    if (!result.valid) break;
                }
            }

            // Async checks
            if (result.valid && rules.includes("email")) {
                const exists = await checkEmailExists(field.value); // implement this
                console.log("check email",exists);
                if (exists.success) {
                    result = { valid: false, message: "Email already exists." };
                }
            }

            if (result.valid && rules.includes("username")) {
                const exists = await checkUsernameExists(field.value); // implement this
                console.log("check username",exists);
                if (exists.success) {
                    result = { valid: false, message: "Username already exists." };
                }
            }

            if (result.valid && rules.includes('birthdate')) {
                const ageInput = document.querySelector("input[name='age']"); // Get the age input element
                
                if (result.valid && result.age !== null) {
                    ageInput.value = result.age;
                    ageInput.classList.remove("is-invalid");
                } else {
                    // Clear the age field if validation fails
                    ageInput.value = "";
                    ageInput.classList.add("is-invalid");
                }
            }

            updateUI(field, errorEl, result);
        })
    });

}

function updateUI(field, errorEl, result) {
    if (!result.valid) {
        field.classList.add("is-invalid");
        field.classList.remove("is-valid");
        errorEl.textContent = result.message;
    } else {
        field.classList.remove("is-invalid");
        field.classList.add("is-valid");
        errorEl.textContent = "";
    }
}