// // Initialize database from localStorage
// function initDatabase() {
//   if (!localStorage.getItem("users")) {
//     localStorage.setItem("users", JSON.stringify([]))
//   }
// }

// async function hashPassword(password) {
//   const msgUint8 = new TextEncoder().encode(password)
//   const hashBuffer = await crypto.subtle.digest("SHA-256", msgUint8)
//   const hashArray = Array.from(new Uint8Array(hashBuffer))
//   return hashArray.map((b) => b.toString(16).padStart(2, "0")).join("")
// }

// // Check if user is logged in
// function checkLoginStatus() {
//   const loggedInUser = localStorage.getItem("loggedInUser")
//   const registerLink = document.getElementById("register-link")
//   const loginLink = document.getElementById("login-link")
//   const logoutLink = document.getElementById("logout-link")

//   if (loggedInUser && logoutLink) {
//     if (registerLink) registerLink.style.display = "none"
//     if (loginLink) loginLink.style.display = "none"
//     logoutLink.style.display = "block"
//   }
// }

// // Logout functionality
// function logout() {
//   localStorage.removeItem("loggedInUser")
//   window.location.href = "index.html"
// }

// function hasSpecialCharacters(str) {
//   return /[^a-zA-Z\s]/.test(str)
// }

// function hasNumbersFollowedByLetters(str) {
//   return /\d+[a-zA-Z]/.test(str)
// }

// function hasDoubleSpace(str) {
//   return /\s{2,}/.test(str)
// }

// function isAllCapitals(str) {
//   return str === str.toUpperCase() && /[A-Z]/.test(str)
// }

// function hasThreeConsecutiveLetters(str) {
//   for (let i = 0; i < str.length - 2; i++) {
//     const char1 = str[i].toLowerCase()
//     const char2 = str[i + 1].toLowerCase()
//     const char3 = str[i + 2].toLowerCase()

//     if (/[a-z]/.test(char1) && /[a-z]/.test(char2) && /[a-z]/.test(char3)) {
//       if (char1 === char2 && char2 === char3) {
//         return true
//       }
//     }
//   }
//   return false
// }

// function isProperCapitalization(str) {
//   const words = str.split(" ").filter((word) => word.length > 0)
//   for (const word of words) {
//     if (word[0] !== word[0].toUpperCase()) {
//       return false
//     }
//     for (let i = 1; i < word.length; i++) {
//       if (word[i] !== word[i].toLowerCase()) {
//         return false
//       }
//     }
//   }
//   return true
// }

// function validateName(name, fieldName) {
//   if (!name || name.trim() === "") {
//     return `${fieldName} is required`
//   }

//   if (hasSpecialCharacters(name)) {
//     return `Special characters are not allowed in ${fieldName}`
//   }

//   if (hasNumbersFollowedByLetters(name)) {
//     return `Numbers followed by letters are not allowed in ${fieldName}`
//   }

//   if (hasDoubleSpace(name)) {
//     return `Double spaces are not allowed in ${fieldName}`
//   }

//   if (isAllCapitals(name)) {
//     return `All capital letters are not allowed in ${fieldName}`
//   }

//   if (hasThreeConsecutiveLetters(name)) {
//     return `Three consecutive same letters are not allowed in ${fieldName}`
//   }

//   if (!isProperCapitalization(name)) {
//     return `First letter must start with capital in ${fieldName}`
//   }

//   return ""
// }

// function validateIDNumber(idNumber) {
//   if (!idNumber || idNumber.trim() === "") {
//     return "ID Number is required"
//   }

//   const idPattern = /^\d{4}-\d{4}$/
//   if (!idPattern.test(idNumber)) {
//     return "ID Number must be in format xxxx-xxxx"
//   }

//   const users = JSON.parse(localStorage.getItem("users") || "[]")
//   if (users.some((user) => user.idNumber === idNumber)) {
//     return "ID Number already exists in the database"
//   }

//   return ""
// }

// function calculateAge(birthdate) {
//   const today = new Date()
//   const birth = new Date(birthdate)
//   let age = today.getFullYear() - birth.getFullYear()
//   const monthDiff = today.getMonth() - birth.getMonth()

//   if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
//     age--
//   }

//   return age
// }

// function validateAge(age) {
//   if (!age || age === "") {
//     return "Age is required"
//   }

//   const ageNum = Number.parseInt(age)
//   if (ageNum < 18) {
//     return "Only legal age (18 and above) is allowed"
//   }

//   return ""
// }

// function validatePurok(purok) {
//   if (!purok || purok.trim() === "") {
//     return "Purok/Street is required"
//   }

//   if (hasDoubleSpace(purok)) {
//     return "Double spaces are not allowed in Purok/Street"
//   }

//   const hasNumber = /\d/.test(purok)
//   const hasLetter = /[a-zA-Z]/.test(purok)

//   if (!hasNumber || !hasLetter) {
//     return "Purok must be a combination of numbers and letters"
//   }

//   return ""
// }

// function validateAddress(value, fieldName) {
//   if (!value || value.trim() === "") {
//     return `${fieldName} is required`
//   }

//   if (hasDoubleSpace(value)) {
//     return `Double spaces are not allowed in ${fieldName}`
//   }

//   return ""
// }

// function validateZipCode(zipCode) {
//   if (!zipCode || zipCode.trim() === "") {
//     return "Zip Code is required"
//   }

//   if (!/^\d{4}$/.test(zipCode)) {
//     return "Zip Code must be 4 digits"
//   }

//   return ""
// }

// function checkPasswordStrength(password) {
//   if (!password) return ""

//   let strength = 0
//   if (password.length >= 8) strength++
//   if (password.length >= 12) strength++
//   if (/[a-z]/.test(password)) strength++
//   if (/[A-Z]/.test(password)) strength++
//   if (/[0-9]/.test(password)) strength++
//   if (/[^a-zA-Z0-9]/.test(password)) strength++

//   if (strength <= 2) return "weak"
//   if (strength <= 4) return "medium"
//   return "strong"
// }

// function validatePassword(password) {
//   if (!password || password.trim() === "") {
//     return "Password is required"
//   }

//   if (password.length < 6) {
//     return "Password must be at least 6 characters long"
//   }

//   return ""
// }

// function checkUsernameExists(username) {
//   const users = JSON.parse(localStorage.getItem("users") || "[]")
//   return users.some((user) => user.username === username)
// }

// function validateUsername(username) {
//   if (!username || username.trim() === "") {
//     return "Username is required"
//   }

//   if (username.length < 4) {
//     return "Username must be at least 4 characters long"
//   }

//   return ""
// }

// function togglePasswordVisibility(inputId, toggleId) {
//   const input = document.getElementById(inputId);
//   const toggle = document.getElementById(toggleId);
//   if (!input || !toggle) return;
//   const icon = toggle.querySelector('i');

//   toggle.addEventListener("click", () => {
//     if (input.type === "password") {
//       input.type = "text";
//       if (icon) {
//         icon.classList.remove("fa-eye");
//         icon.classList.add("fa-eye-slash");
//       }
//     } else {
//       input.type = "password";
//       if (icon) {
//         icon.classList.remove("fa-eye-slash");
//         icon.classList.add("fa-eye");
//       }
//     }
//   });
// }

// function addCharacterFiltering() {
//   const form = document.getElementById("register-form");
//   if (!form) return;

//   // Helper to update error display in real-time
//   const updateRealTimeError = (inputEl, errorMsg, errorElId) => {
//     const errorEl = document.getElementById(errorElId);
//     if (errorEl) {
//       errorEl.textContent = errorMsg;
//       inputEl.classList.toggle("error", !!errorMsg);
//     }
//   };

//   // First Name - Only letters and spaces, auto-capitalize
//   const firstNameInput = document.getElementById("first-name");
//   if (firstNameInput) {
//     firstNameInput.addEventListener("input", (e) => {
//       let value = e.target.value;
//       // Remove anything that's not a letter or space
//       value = value.replace(/[^a-zA-Z\s]/g, "");
//       // Remove double spaces
//       value = value.replace(/\s{2,}/g, " ");
//       // Auto-capitalize each word (Enforcing Proper Capitalization)
//       value = value
//         .split(" ")
//         .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
//         .join(" ");
//       e.target.value = value;
      
//       // Real-time validation
//       const error = validateName(value, "First Name");
//       updateRealTimeError(firstNameInput, error, "first-name-error");
//     });
//   }

//   // Middle Name - Only letters and spaces, auto-capitalize
//   const middleNameInput = document.getElementById("middle-name");
//   if (middleNameInput) {
//     middleNameInput.addEventListener("input", (e) => {
//       let value = e.target.value;
//       value = value.replace(/[^a-zA-Z\s]/g, "");
//       value = value.replace(/\s{2,}/g, " ");
//       value = value
//         .split(" ")
//         .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
//         .join(" ");
//       e.target.value = value;
      
//       // Real-time validation (optional field)
//       const error = value.trim() ? validateName(value, "Middle Name") : "";
//       updateRealTimeError(middleNameInput, error, "middle-name-error");
//     });
//   }

//   // Family Name - Only letters and spaces, auto-capitalize
//   const familyNameInput = document.getElementById("family-name");
//   if (familyNameInput) {
//     familyNameInput.addEventListener("input", (e) => {
//       let value = e.target.value;
//       value = value.replace(/[^a-zA-Z\s]/g, "");
//       value = value.replace(/\s{2,}/g, " ");
//       value = value
//         .split(" ")
//         .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
//         .join(" ");
//       e.target.value = value;
      
//       // Real-time validation
//       const error = validateName(value, "Family Name");
//       updateRealTimeError(familyNameInput, error, "family-name-error");
//     });
//   }

//   // Extension Other - Only letters and spaces
//   const extensionOtherInput = document.getElementById("extension-other");
//   if (extensionOtherInput) {
//     extensionOtherInput.addEventListener("input", (e) => {
//       let value = e.target.value;
//       value = value.replace(/[^a-zA-Z\s]/g, "");
//       value = value.replace(/\s{2,}/g, " ");
//       value = value
//         .split(" ")
//         .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
//         .join(" ");
//       e.target.value = value;
//     });
//   }

//   // ID Number - Format as xxxx-xxxx
//   const idNumberInput = document.getElementById("id-number");
//   if (idNumberInput) {
//     idNumberInput.addEventListener("input", (e) => {
//       let value = e.target.value.replace(/[^\d]/g, "");
//       if (value.length > 4) {
//         value = value.slice(0, 4) + "-" + value.slice(4, 8);
//       }
//       e.target.value = value;
      
//       // Real-time validation
//       const error = validateIDNumber(value);
//       updateRealTimeError(idNumberInput, error, "id-number-error");
//     });
//   }

//   // Zip Code - Only numbers (4 digits)
//   const zipCodeInput = document.getElementById("zip-code");
//   if (zipCodeInput) {
//     zipCodeInput.addEventListener("input", (e) => {
//       let value = e.target.value.replace(/[^\d]/g, "");
//       if (value.length > 4) {
//         value = value.slice(0, 4);
//       }
//       e.target.value = value;
      
//       // Real-time validation
//       const error = validateZipCode(value);
//       updateRealTimeError(zipCodeInput, error, "zip-code-error");
//     });
//   }

//   // Purok/Street - Numbers and letters combination
//   const purokInput = document.getElementById("purok-street");
//   if (purokInput) {
//     purokInput.addEventListener("input", (e) => {
//       let value = e.target.value;
//       // Allow only alphanumeric and spaces
//       value = value.replace(/[^a-zA-Z0-9\s]/g, "");
//       value = value.replace(/\s{2,}/g, " ");
//       e.target.value = value;
      
//       // Real-time validation
//       const error = validatePurok(value);
//       updateRealTimeError(purokInput, error, "purok-street-error");
//     });
//   }

//   // Barangay - Letters and spaces
//   const barangayInput = document.getElementById("barangay");
//   if (barangayInput) {
//     barangayInput.addEventListener("input", (e) => {
//       let value = e.target.value;
//       value = value.replace(/[^a-zA-Z\s]/g, "");
//       value = value.replace(/\s{2,}/g, " ");
//       value = value
//         .split(" ")
//         .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
//         .join(" ");
//       e.target.value = value;
      
//       // Real-time validation
//       const error = validateAddress(value, "Barangay");
//       updateRealTimeError(barangayInput, error, "barangay-error");
//     });
//   }

//   // Municipal/City - Letters and spaces
//   const municipalCityInput = document.getElementById("municipal-city");
//   if (municipalCityInput) {
//     municipalCityInput.addEventListener("input", (e) => {
//       let value = e.target.value;
//       value = value.replace(/[^a-zA-Z\s]/g, "");
//       value = value.replace(/\s{2,}/g, " ");
//       value = value
//         .split(" ")
//         .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
//         .join(" ");
//       e.target.value = value;
      
//       // Real-time validation
//       const error = validateAddress(value, "City");
//       updateRealTimeError(municipalCityInput, error, "municipal-city-error");
//     });
//   }

//   // Province - Letters and spaces
//   const provinceInput = document.getElementById("province");
//   if (provinceInput) {
//     provinceInput.addEventListener("input", (e) => {
//       let value = e.target.value;
//       value = value.replace(/[^a-zA-Z\s]/g, "");
//       value = value.replace(/\s{2,}/g, " ");
//       value = value
//         .split(" ")
//         .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
//         .join(" ");
//       e.target.value = value;
      
//       // Real-time validation
//       const error = validateAddress(value, "Province");
//       updateRealTimeError(provinceInput, error, "province-error");
//     });
//   }

//   // Country - Letters and spaces
//   const countryInput = document.getElementById("country");
//   if (countryInput) {
//     countryInput.addEventListener("input", (e) => {
//       let value = e.target.value;
//       value = value.replace(/[^a-zA-Z\s]/g, "");
//       value = value.replace(/\s{2,}/g, " ");
//       value = value
//         .split(" ")
//         .map((word) => word.charAt(0).toUpperCase() + word.slice(1).toLowerCase())
//         .join(" ");
//       e.target.value = value;
      
//       // Real-time validation
//       const error = validateAddress(value, "Country");
//       updateRealTimeError(countryInput, error, "country-error");
//     });
//   }

//   // Email - Real-time validation
//   const emailInput = document.getElementById("email");
//   if (emailInput) {
//     emailInput.addEventListener("input", () => {
//       const email = emailInput.value.trim();
//       let error = "";
      
//       if (!email) {
//         error = "Email Address is required";
//       } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
//         error = "Please enter a valid email address";
//       }
      
//       updateRealTimeError(emailInput, error, "email-error");
//     });
//   }

//   // Username - Real-time validation
//   const usernameInput = document.getElementById("username");
//   if (usernameInput) {
//     usernameInput.addEventListener("input", () => {
//       const usernameError = validateUsername(usernameInput.value);
//       let finalError = usernameError;
      
//       if (!usernameError && checkUsernameExists(usernameInput.value)) {
//         finalError = "Username already exists in the database";
//       }
      
//       updateRealTimeError(usernameInput, finalError, "username-error");
//     });
//   }

//   // Password - Real-time validation with strength feedback
//   const passwordInput = document.getElementById("password");
//   if (passwordInput) {
//     passwordInput.addEventListener("input", () => {
//       const strength = checkPasswordStrength(passwordInput.value);
//       let error = validatePassword(passwordInput.value);
      
//       if (!error) {
//         if (strength === "weak") {
//             error = "Password is weak. Use uppercase, lowercase, numbers, and special characters";
//         }
//       }
      
//       updateRealTimeError(passwordInput, error, "password-error");
//     });
//   }

//   // Confirm Password - Real-time validation
//   const confirmPasswordInput = document.getElementById("confirm-password");
//   if (passwordInput && confirmPasswordInput) {
//     confirmPasswordInput.addEventListener("input", () => {
//       let error = "";
//       if (!confirmPasswordInput.value) {
//         error = "Please re-enter your password";
//       } else if (passwordInput.value !== confirmPasswordInput.value) {
//         error = "Passwords do not match";
//       }
      
//       updateRealTimeError(confirmPasswordInput, error, "confirm-password-error");
//     });
//   }

//   // Security Questions - Real-time validation
//   const secQ1 = document.getElementById("security-q1");
//   if (secQ1) {
//     secQ1.addEventListener("input", () => {
//       const error = secQ1.value.trim() ? "" : "Security Question 1 answer is required";
//       updateRealTimeError(secQ1, error, "security-q1-error");
//     });
//   }

//   const secQ2 = document.getElementById("security-q2");
//   if (secQ2) {
//     secQ2.addEventListener("input", () => {
//       const error = secQ2.value.trim() ? "" : "Security Question 2 answer is required";
//       updateRealTimeError(secQ2, error, "security-q2-error");
//     });
//   }

//   const secQ3 = document.getElementById("security-q3");
//   if (secQ3) {
//     secQ3.addEventListener("input", () => {
//       const error = secQ3.value.trim() ? "" : "Security Question 3 answer is required";
//       updateRealTimeError(secQ3, error, "security-q3-error");
//     });
//   }

//   // Birthdate - Auto-calculate age
//   const birthdateInput = document.getElementById("birthdate");
//   const ageInput = document.getElementById("age");
//   if (birthdateInput && ageInput) {
//     birthdateInput.addEventListener("change", () => {
//       let ageError = "";
//       if (birthdateInput.value) {
//         const age = calculateAge(birthdateInput.value);
//         ageInput.value = age;
//         ageError = validateAge(age);
//       } else {
//         ageInput.value = "";
//         ageError = "Birthdate is required";
//       }
//       updateRealTimeError(birthdateInput, ageError, "birthdate-error");
//     });
//   }
  
//   // Sex (Dropdown) - Validation on change
//   const sexSelect = document.getElementById("sex");
//   if (sexSelect) {
//     sexSelect.addEventListener("change", () => {
//       const error = sexSelect.value ? "" : "Sex is required";
//       updateRealTimeError(sexSelect, error, "sex-error");
//     });
//   }
  
//   // Extension Dropdown - Conditional visibility for "Other" field
//   const extensionSelect = document.getElementById("extension");
//   const extensionOtherGroup = document.getElementById("extension-other-group");
//   if (extensionSelect && extensionOtherGroup) {
//       extensionSelect.addEventListener('change', () => {
//           if (extensionSelect.value === 'Other') {
//               extensionOtherGroup.style.display = 'block';
//           } else {
//               extensionOtherGroup.style.display = 'none';
//               // Clear error and value if hidden
//               const extensionOtherInput = document.getElementById("extension-other");
//               const errorEl = document.getElementById("extension-other-error");
//               if (extensionOtherInput) {
//                   extensionOtherInput.value = '';
//                   extensionOtherInput.classList.remove("error");
//               }
//               if (errorEl) {
//                   errorEl.textContent = '';
//               }
//           }
//       });
//       // Initial check on load
//       if (extensionSelect.value !== 'Other') {
//           extensionOtherGroup.style.display = 'none';
//       }
//   }
// }

// // Multi-step form navigation and step-aware validation
// function initMultiStepForm() {
//   const form = document.getElementById("register-form");
//   if (!form) return;

//   const stepSelector = document.querySelectorAll(".form-step").length ? ".form-step" : (document.querySelectorAll(".step").length ? ".step" : null);
//   if (!stepSelector) return; // No multi-step containers found

//   const steps = Array.from(document.querySelectorAll(stepSelector));
//   let current = steps.findIndex(s => s.classList.contains("active"));
//   if (current === -1) current = 0;

//   const prevBtn = form.querySelector('#prev-btn');
//   const nextBtn = form.querySelector('#next-btn');
//   const submitBtn = form.querySelector('#submit-btn');
//   const progressSteps = Array.from(document.querySelectorAll('.progress-indicator .progress-step'));

//   const showStep = (idx) => {
//     // Toggle step visibility
//     steps.forEach((s, i) => s.classList.toggle('active', i === idx));

//     // Toggle navigation button visibility
//     if (prevBtn) prevBtn.style.display = idx === 0 ? 'none' : 'inline-flex';
//     if (nextBtn) nextBtn.style.display = idx === steps.length - 1 ? 'none' : 'inline-flex';
//     if (submitBtn) submitBtn.style.display = idx === steps.length - 1 ? 'block' : 'none';

//     // Update progress indicator (mark current and previous as active/completed)
//     if (progressSteps.length) {
//       progressSteps.forEach((ps, i) => ps.classList.toggle('active', i <= idx));
//     }
//   };
//   showStep(current);

//   const updateRealTimeError = (inputEl, errorMsg) => {
//     if (!inputEl) return;
//     const errorEl = document.getElementById(`${inputEl.id}-error`);
//     if (errorEl) {
//       errorEl.textContent = errorMsg || "";
//       inputEl.classList.toggle("error", !!errorMsg);
//     }
//   };

//   const validateField = (el) => {
//     if (!el || !el.id) return "";
//     const id = el.id;
//     switch (id) {
//       case "first-name": return validateName(el.value, "First Name");
//       case "middle-name": return el.value.trim() ? validateName(el.value, "Middle Name") : "";
//       case "family-name": return validateName(el.value, "Family Name");
//       case "extension-other": {
//         const extensionSelect = document.getElementById("extension");
//         if (extensionSelect && extensionSelect.value === "Other") {
//           return el.value.trim() ? validateName(el.value, "Extension") : "Please enter extension name";
//         }
//         return "";
//       }
//       case "id-number": return validateIDNumber(el.value);
//       case "sex": return el.value ? "" : "Sex is required";
//       case "birthdate": {
//         const ageInput = document.getElementById("age");
//         if (el.value && ageInput) {
//           const age = calculateAge(el.value);
//           ageInput.value = age;
//           return validateAge(age);
//         }
//         return el.value ? "" : "Birthdate is required";
//       }
//       case "age": return validateAge(el.value);
//       case "purok-street": return validatePurok(el.value);
//       case "barangay": return validateAddress(el.value, "Barangay");
//       case "municipal-city": return validateAddress(el.value, "City");
//       case "province": return validateAddress(el.value, "Province");
//       case "country": return validateAddress(el.value, "Country");
//       case "zip-code": return validateZipCode(el.value);
//       case "email": {
//         const email = el.value.trim();
//         if (!email) return "Email Address is required";
//         if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) return "Please enter a valid email address";
//         return "";
//       }
//       case "username": {
//         let err = validateUsername(el.value);
//         if (!err && checkUsernameExists(el.value)) err = "Username already exists in the database";
//         return err;
//       }
//       case "password": {
//         let err = validatePassword(el.value);
//         if (!err) {
//           const s = checkPasswordStrength(el.value);
//           if (s === "weak") err = "Password strength is Weak. Please use a stronger password with at least 8 characters, uppercase, lowercase, numbers, and special characters";
//           else if (s === "medium") err = "Password strength is Medium. Consider using a stronger password with more character variety";
//         }
//         return err;
//       }
//       case "confirm-password": {
//         const pwd = document.getElementById("password");
//         if (!el.value) return "Please re-enter your password";
//         if (pwd && pwd.value !== el.value) return "Passwords do not match";
//         return "";
//       }
//       case "security-q1": return el.value.trim() ? "" : "Security Question 1 answer is required";
//       case "security-q2": return el.value.trim() ? "" : "Security Question 2 answer is required";
//       case "security-q3": return el.value.trim() ? "" : "Security Question 3 answer is required";
//       default: return "";
//     }
//   };

//   const validateCurrentStep = () => {
//     let hasError = false;
//     const inputs = steps[current].querySelectorAll("input, select, textarea");
//     inputs.forEach((el) => {
//       const msg = validateField(el);
//       if (msg) hasError = true;
//       updateRealTimeError(el, msg);
//     });
//     if (hasError) {
//       const firstErr = steps[current].querySelector(".error");
//       if (firstErr) firstErr.scrollIntoView({ behavior: "smooth", block: "center" });
//     }
//     return !hasError;
//   };

//   const validateEntireForm = () => {
//     let hasError = false;
//     steps.forEach((step) => {
//       const inputs = step.querySelectorAll("input, select, textarea");
//       inputs.forEach((el) => {
//         const msg = validateField(el);
//         if (msg) hasError = true;
//         updateRealTimeError(el, msg);
//       });
//     });
//     if (hasError) {
//       const firstErr = form.querySelector(".error");
//       if (firstErr) firstErr.scrollIntoView({ behavior: "smooth", block: "center" });
//     }
//     return !hasError;
//   };

//   const nextButtons = form.querySelectorAll('[data-action="next"], .btn-next, .next-btn');
//   nextButtons.forEach(btn => {
//     btn.addEventListener("click", (e) => {
//       e.preventDefault();
//       if (validateCurrentStep()) {
//         if (current < steps.length - 1) {
//           current += 1;
//           showStep(current);
//         }
//       }
//     });
//   });

//   const prevButtons = form.querySelectorAll('[data-action="prev"], .btn-prev, .prev-btn');
//   prevButtons.forEach(btn => {
//     btn.addEventListener("click", (e) => {
//       e.preventDefault();
//       if (current > 0) {
//         current -= 1;
//         showStep(current);
//       }
//     });
//   });

//   // Accept All: validate whole form and submit if valid
//   const acceptAllButtons = form.querySelectorAll('[data-action="accept-all"], .btn-accept-all, .accept-all');
//   acceptAllButtons.forEach(btn => {
//     btn.addEventListener("click", (e) => {
//       e.preventDefault();
//       if (validateEntireForm()) {
//         if (typeof form.requestSubmit === 'function') {
//           form.requestSubmit();
//         } else {
//           form.submit();
//         }
//       }
//     });
//   });
// }

// // Backend mode: let server handle submit; keep client-side validation & UX
// const BACKEND_MODE = true

// // Registration form handling
// document.addEventListener("DOMContentLoaded", () => {
//   const form = document.getElementById("register-form")
//   if (!form) return
//   const idNumberInput = document.getElementById("id-number")
//   const firstNameInput = document.getElementById("first-name")
//   const middleNameInput = document.getElementById("middle-name")
//   const familyNameInput = document.getElementById("family-name")
//   const extensionSelect = document.getElementById("extension")
//   const extensionOtherInput = document.getElementById("extension-other")
//   const sexSelect = document.getElementById("sex")
//   const birthdateInput = document.getElementById("birthdate")
//   const ageInput = document.getElementById("age")
//   const purokStreetInput = document.getElementById("purok-street")
//   const barangayInput = document.getElementById("barangay")
//   const municipalCityInput = document.getElementById("municipal-city")
//   const provinceInput = document.getElementById("province")
//   const countryInput = document.getElementById("country")
//   const zipCodeInput = document.getElementById("zip-code")
//   const emailInput = document.getElementById("email")
//   const usernameInput = document.getElementById("username")
//   const passwordInput = document.getElementById("password")
//   const confirmPasswordInput = document.getElementById("confirm-password")
//   const secQ1Input = document.getElementById("security-q1")
//   const secQ2Input = document.getElementById("security-q2")
//   const secQ3Input = document.getElementById("security-q3")

//   togglePasswordVisibility("password", "toggle-password")
//   togglePasswordVisibility("confirm-password", "toggle-confirm-password")

//   addCharacterFiltering();
//   initMultiStepForm();

//   form.addEventListener("submit", async (e) => {
//     if (!BACKEND_MODE) {
//       e.preventDefault()
//     }

//     // A map to store the input element and the error message
//     const validationMap = new Map();
//     let hasError = false; // Flag to check if any error was found
    
//     // Helper to add an error and update the DOM element immediately
//     const addError = (inputEl, errorMsg, errorElId) => {
//         if (!inputEl) return; // Skip if the field is not in the current DOM (multi-step safe)
//         if (errorMsg) {
//             validationMap.set(inputEl, { msg: errorMsg, elId: errorElId });
//             hasError = true;
//         }
//     };
    
//     // Clear all existing errors first
//     document.querySelectorAll(".error-message").forEach(el => el.textContent = "");
//     document.querySelectorAll("input, select").forEach(el => el.classList.remove("error"));

//     // --- Validation Checks (Reflecting all errors in the map) ---
    
//     // Check if input elements exist before validating (Crucial for "Next" button fix)
    
//     // Validate ID Number
//     addError(idNumberInput, validateIDNumber(idNumberInput.value), "id-number-error");

//     // Validate First Name
//     addError(firstNameInput, validateName(firstNameInput.value, "First Name"), "first-name-error");

//     // Validate Middle Name (optional but must be valid if provided)
//     if (middleNameInput && middleNameInput.value.trim()) {
//       addError(middleNameInput, validateName(middleNameInput.value, "Middle Name"), "middle-name-error");
//     }

//     // Validate Family Name
//     addError(familyNameInput, validateName(familyNameInput.value, "Family Name"), "family-name-error");

//     // Validate Extension
//     if (extensionSelect && extensionSelect.value === "Other") {
//       const extensionOtherVal = extensionOtherInput ? extensionOtherInput.value.trim() : "";
//       let extensionError = extensionOtherVal ? validateName(extensionOtherVal, "Extension") : "Please enter extension name";
//       addError(extensionOtherInput || extensionSelect, extensionError, "extension-other-error");
//     }

//     // Validate Sex
//     const sexError = sexSelect.value ? "" : "Sex is required";
//     addError(sexSelect, sexError, "sex-error");

//     // Validate Birthdate
//     let birthdateError = birthdateInput.value ? "" : "Birthdate is required";
//     addError(birthdateInput, birthdateError, "birthdate-error");

//     // Validate Age
//     addError(ageInput, validateAge(ageInput.value), "age-error");

//     // Validate Address fields
//     addError(purokStreetInput, validatePurok(purokStreetInput.value), "purok-street-error");
//     addError(barangayInput, validateAddress(barangayInput.value, "Barangay"), "barangay-error");
//     addError(municipalCityInput, validateAddress(municipalCityInput.value, "City"), "municipal-city-error");
//     addError(provinceInput, validateAddress(provinceInput.value, "Province"), "province-error");
//     addError(countryInput, validateAddress(countryInput.value, "Country"), "country-error");
//     addError(zipCodeInput, validateZipCode(zipCodeInput.value), "zip-code-error");

//     // Validate Email
//     const email = emailInput.value.trim()
//     let emailError = "";
//     if (!email) {
//       emailError = "Email Address is required";
//     } else if (!/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email)) {
//       emailError = "Please enter a valid email address";
//     }
//     addError(emailInput, emailError, "email-error");

//     // Validate Username
//     let usernameFinalError = validateUsername(usernameInput.value);
//     if (!usernameFinalError && checkUsernameExists(usernameInput.value)) {
//       usernameFinalError = "Username already exists in the database";
//     }
//     addError(usernameInput, usernameFinalError, "username-error");

//     // Validate Password
//     let passwordFinalError = validatePassword(passwordInput.value);
//     if (!passwordFinalError) {
//       const passwordStrength = checkPasswordStrength(passwordInput.value)
//       if (passwordStrength === "weak") {
//         passwordFinalError = "Password strength is Weak. Please use a stronger password with at least 8 characters, uppercase, lowercase, numbers, and special characters";
//       }
//     }
//     addError(passwordInput, passwordFinalError, "password-error");

//     // Validate Confirm Password
//     let confirmPasswordError = "";
//     if (!confirmPasswordInput.value) {
//       confirmPasswordError = "Please re-enter your password";
//     } else if (passwordInput.value !== confirmPasswordInput.value) {
//       confirmPasswordError = "Passwords do not match";
//     }
//     addError(confirmPasswordInput, confirmPasswordError, "confirm-password-error");

//     // Validate Security Questions
//     addError(secQ1Input, secQ1Input.value.trim() ? "" : "Security Question 1 answer is required", "security-q1-error");
//     addError(secQ2Input, secQ2Input.value.trim() ? "" : "Security Question 2 answer is required", "security-q2-error");
//     addError(secQ3Input, secQ3Input.value.trim() ? "" : "Security Question 3 answer is required", "security-q3-error");
    
//     // --- Error Display and Prevention of Submission ---

//     if (hasError) {
//       // Iterate through the map to apply error styles and messages
//       let firstErrorEl = null;
//       for (const [inputEl, errorInfo] of validationMap.entries()) {
//           const errorEl = document.getElementById(errorInfo.elId);
//           if (errorEl) {
//               errorEl.textContent = errorInfo.msg;
//               inputEl.classList.add("error");
//               if (!firstErrorEl) {
//                   // Find the first input with an error to scroll to
//                   firstErrorEl = inputEl;
//               }
//           }
//       }
      
//       // Scroll to the first error element/input
//       if (firstErrorEl) {
//         firstErrorEl.scrollIntoView({ behavior: "smooth", block: "center" });
//       }

//       if (BACKEND_MODE) {
//         e.preventDefault()
//       }
//       return
//     }

//     // If no errors and not in BACKEND_MODE, proceed with local registration
//     if (!BACKEND_MODE) {
//       const users = JSON.parse(localStorage.getItem("users") || "[]")
//       const hashedPassword = await hashPassword(passwordInput.value)
//       const hashedSecQ1 = await hashPassword(secQ1Input.value.toLowerCase())
//       const hashedSecQ2 = await hashPassword(secQ2Input.value.toLowerCase())
//       const hashedSecQ3 = await hashPassword(secQ3Input.value.toLowerCase())
//       const newUser = {
//         idNumber: idNumberInput.value,
//         firstName: firstNameInput.value,
//         middleName: middleNameInput ? middleNameInput.value : '',
//         familyName: familyNameInput.value,
//         extension: extensionSelect.value === "Other" ? extensionOtherInput.value : extensionSelect.value,
//         sex: sexSelect.value,
//         birthdate: birthdateInput.value,
//         age: ageInput.value,
//         email: email,
//         address: {
//           purokStreet: purokStreetInput.value,
//           barangay: barangayInput.value,
//           municipalCity: municipalCityInput.value,
//           province: provinceInput.value,
//           country: countryInput.value,
//           zipCode: zipCodeInput.value,
//         },
//         username: usernameInput.value,
//         password: hashedPassword,
//         securityQuestions: {
//           q1: hashedSecQ1,
//           q2: hashedSecQ2,
//           q3: hashedSecQ3,
//         },
//       }
//       users.push(newUser)
//       localStorage.setItem("users", JSON.stringify(users))
//       window.location.href = "login.html?success=Registration successful! You can now log in."
//     }
//   })
// })

// // All previous showAlert calls and function definition are removed.

// window.addEventListener("load", () => {
//   history.pushState(null, "", location.href)
//   window.addEventListener("popstate", () => {
//     history.pushState(null, "", location.href)
//   })
//   const params = new URLSearchParams(window.location.search)
//   const msg = params.get('success') || params.get('error')
//   const el = document.getElementById('server-message')
//   if (msg && el) {
//     el.textContent = decodeURIComponent(msg)
//     el.style.color = params.get('error') ? '#d32f2f' : '#388e3c'
//   }
// })

// document.addEventListener("DOMContentLoaded", () => {
//   initDatabase()
//   checkLoginStatus()

//   const logoutLink = document.getElementById("logout-link")
//   if (logoutLink) {
//     logoutLink.addEventListener("click", (e) => {
//       e.preventDefault()
//       logout()
//     })
//   }
// })

// document.addEventListener("contextmenu", (e) => e.preventDefault())

// document.addEventListener('keydown', (e) => {
//   const key = (e.key || '').toUpperCase()
//   const isCtrl = e.ctrlKey || e.metaKey
//   const isShift = e.shiftKey

//   if (key === 'F12') {
//     e.preventDefault()
//     e.stopPropagation()
//     return false
//   }
//   if (isCtrl && isShift && (key === 'I' || key === 'J' || key === 'C')) {
//     e.preventDefault()
//     e.stopPropagation()
//     return false
//   }
//   if (isCtrl && key === 'U') {
//     e.preventDefault()
//     e.stopPropagation()
//     return false
//   }
//   if (isCtrl && (key === 'S' || key === 'P')) {
//     e.preventDefault()
//     e.stopPropagation()
//     return false
//   }
// })

// document.addEventListener('selectstart', (e) => {
//   const tag = (e.target && e.target.tagName ? e.target.tagName.toLowerCase() : '')
//   if (tag !== 'input' && tag !== 'textarea') {
//     e.preventDefault()
//   }
// })

// ;['copy', 'cut', 'paste'].forEach((evt) => {
//   document.addEventListener(evt, (e) => {
//     e.preventDefault()
//   })
// })

// document.addEventListener('dragstart', (e) => e.preventDefault())