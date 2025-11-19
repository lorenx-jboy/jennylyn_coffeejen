import { addressValidators } from "./address.js";
import { firstNameValidation } from "./first_name.js";
import { middleNameValidation } from "./middle_name.js";
import { lastNameValidation } from "./last_name.js";
import { extensionNameValidation } from "./extension_name.js";
import { birthdateValidation } from "./birthdate.js";
import { emailValidation } from "./email.js";
import { usernameValidation } from "./username.js";
import { passwordValidation, confirmPasswordValidation } from "./password.js";
// address -- country, province, city, barangay
import { purokValidation } from "./purok.js";
import { zipcodeValidation } from "./zipcode.js";

import { security_answer_validators } from "./security_authentication.js";

export const validations = {
    required: (value) => {
        const valid = value.trim() !== "";
        return {
            valid,
            message: valid ? "" : "This field is required."
        };
    },
    first_name: firstNameValidation,
    middle_name: middleNameValidation,
    last_name: lastNameValidation,
    extension_name: extensionNameValidation,
    birthdate: birthdateValidation,
    email: emailValidation,
    username: usernameValidation,
    password: passwordValidation,
    repassword: confirmPasswordValidation,
    // address -- country, province, city, barangay
    purok: purokValidation,
    zipcode: zipcodeValidation,

    ...security_answer_validators,
    ...addressValidators,
};