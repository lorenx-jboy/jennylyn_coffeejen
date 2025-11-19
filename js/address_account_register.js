document.addEventListener('contextmenu', e => e.preventDefault());
// =================================ADDRESS & ACCOUNT=====================================

// -----------------------------
// Helper: create/find error box
// -----------------------------
function getErrorBox(inputName, inputElem) {
  const id = `${inputName}_error`;
  let box = document.getElementById(id);
  if (box) return box;

  box = document.createElement("div");
  box.id = id;
  box.className = "error-box";
  box.style.cssText = "background:#ffe5e5;border:1px solid #ff6b6b;color:#b30000;padding:6px 10px;margin-top:3px;font-size:12px;border-radius:5px;";
  
  inputElem.insertAdjacentElement("afterend", box);
  return box;
}

function showSingleError(inputName, inputElem, message) {
  const box = getErrorBox(inputName, inputElem);
  box.textContent = message;
  box.style.display = "block";
  inputElem.classList.add("is-invalid");
  inputElem.classList.remove("is-valid");
  inputElem.focus();
}

function clearError(inputName, inputElem) {
  const box = getErrorBox(inputName, inputElem);
  box.textContent = "";
  box.style.display = "none";
  inputElem.classList.remove("is-invalid");
  inputElem.classList.add("is-valid");
}

// -----------------------------
// Address Field Validator
// -----------------------------
function validateAddressField(fieldName, label, allowExtraChars = false) {
  const input = document.getElementsByName(fieldName)[0];
  if (!input) return true;

  const value = input.value; // raw input value

  // 0) Leading space
  if (/^\s/.test(value)) {
    showSingleError(fieldName, input, `❌ ${label}: First character cannot be a space.`);
    return false;
  }

  // 1) Empty or only spaces
  if (!value || /^\s*$/.test(value)) {
    showSingleError(fieldName, input, `❌ ${label}: This field is required.`);
    return false;
  }

  const trimmed = value.trim();

 
  if (trimmed.length > 50) {
    showSingleError(fieldName, input, `❌ ${label}: Too long.`);
    return false;
  }

  // 3) Numbers
  if (/\d/.test(trimmed)) {
    showSingleError(fieldName, input, `❌ ${label}: Numbers are not allowed.`);
    return false;
  }

  // 4) Special characters
  const specialPattern = allowExtraChars ? /[^A-Za-z\s\-\.\(\)]/ : /[^A-Za-z\s]/;
  if (specialPattern.test(trimmed)) {
    showSingleError(fieldName, input, `❌ ${label}: Invalid special characters.`);
    return false;
  }

  // 5) Double/triple spaces
  if (/\s{2,}/.test(value)) {
    showSingleError(fieldName, input, `❌ ${label}: No double/triple spaces allowed.`);
    return false;
  }

  // 6) Three repeated letters (case-insensitive)
  if (/(.)\1\1/i.test(trimmed)) {
    showSingleError(fieldName, input, `❌ ${label}: No triple repeated letters allowed.`);
    return false;
  }

  // 7) Words validation: first letter uppercase, rest lowercase, no ALL CAPS, no consecutive capitals
  const words = trimmed.split(/\s+/);
  for (let w of words) {
    if (!w) continue;

    // ❌ No ALL CAPS word allowed (more than 1 letter)
    if (w.length > 1 && w === w.toUpperCase()) {
      showSingleError(fieldName, input, `❌ ${label}: All caps words are not allowed.`);
      return false;
    }

    // First letter uppercase
    if (w[0] && w[0] !== w[0].toUpperCase()) {
      showSingleError(fieldName, input, `❌ ${label}: First letter of each word must be uppercase.`);
      return false;
    }

    // Rest lowercase
    if (w.slice(1) !== w.slice(1).toLowerCase()) {
      showSingleError(fieldName, input, `❌ ${label}: All letters after the first must be lowercase.`);
      return false;
    }

    // No two consecutive capitals inside the word
    if (/[A-Z]{2,}/.test(w.slice(1))) {
      showSingleError(fieldName, input, `❌ ${label}: No two consecutive capital letters allowed.`);
      return false;
    }
  }

  // ✅ All good
  clearError(fieldName, input);
  return true;
}

// -----------------------------
// Wrapper functions
// -----------------------------
function validateBarangay() { return validateAddressField("barangay", "Barangay", false); }
function validateCity() { return validateAddressField("city", "Municipality/City", false); }
function validateProvince() { return validateAddressField("province", "Province", false); }
function validateCountry() { return validateAddressField("country", "Country", true); }

// -----------------------------
// Auto attach events like original code
// -----------------------------
document.addEventListener("DOMContentLoaded", () => {
  const mappings = [
    {name: "barangay", func: validateBarangay},
    {name: "city", func: validateCity},
    {name: "province", func: validateProvince},
    {name: "country", func: validateCountry},
    {name: "sex", func: validateSex} // if you have a sex validator
  ];

  mappings.forEach(m => {
    const el = document.getElementsByName(m.name)[0];
    if (el) {
      el.addEventListener("input", m.func);
      el.addEventListener("blur", m.func);
      el.addEventListener("change", m.func);
    }
  });

  const form = document.getElementById("registerForm");
  if (form) {
    form.addEventListener("submit", (e) => {
      const checks = mappings.map(m => ({ok: m.func(), name: m.name}));
      const firstFail = checks.find(c => !c.ok);
      if (firstFail) {
        e.preventDefault();
        const el = document.getElementsByName(firstFail.name)[0];
        if (el) setTimeout(() => el.focus(), 10);
      }
    });
  }
});


// STREET validator: numbers allowed, hyphen allowed, letters allowed, but cannot start with lowercase
// function validateStreetField() {
//   const fieldName = "street";
//   const input = document.getElementsByName(fieldName)[0];
//   if (!input) return true;

//   let value = input.value;

//   // ❗ Leading space check
//   if (/^\s/.test(value)) {
//     showStreetError(input, `❌ Street/Purok: Cannot start with a space.`, 'leading_space');
//     return false;
//   }

//   // Replace triple spaces to double
//   value = value.replace(/\s{3,}/g, "  ");

//   // ❗ Empty check
//   if (!value || value.trim() === "") {
//     showStreetError(input, `❌ Street/Purok: This field is required.`, 'empty');
//     return false;
//   }

//   // 1️⃣ Special characters except letters, numbers, space, dot, dash, comma
//   if (/[^A-Za-z0-9\s.,-]/.test(value)) {
//     showStreetError(
//       input,
//       `❌ Street/Purok: Only letters, numbers, space, dot (.), dash (-), and comma (,) are allowed.`,
//       'invalid_char'
//     );
//     return false;
//   }

//   const trimmed = value.trim();

//   // 2️⃣ Cannot start with lowercase letter (numbers okay)
//   if (/^[a-z]/.test(trimmed)) {
//     showStreetError(input, `❌ Street/Purok: Cannot start with lowercase letter.`, 'lowercase_start');
//     return false;
//   }

//   // 3️⃣ Double or triple spaces
//   if (/\s{2,}/.test(value)) {
//     showStreetError(input, `❌ Street/Purok: No double/triple spaces allowed.`, 'double_space');
//     return false;
//   }

//   // 4️⃣ Two consecutive capital letters
//   if (/[A-Z]{2,}/.test(value)) {
//     showStreetError(input, `❌ Street/Purok: No two consecutive capital letters allowed.`, 'consecutive_caps');
//     return false;
//   }

//   // 5️⃣ Three repeated letters
//   if (/(.)\1\1/.test(value)) {
//     showStreetError(input, `❌ Street/Purok: No triple repeated letters allowed.`, 'triple_repeat');
//     return false;
//   }

//   // 6️⃣ Length check
//   const trimmedLen = trimmed.length;
//   if (trimmedLen < 2) {
//     showStreetError(input, `❌ Street/Purok: Must be at least 2 characters.`, 'min_length');
//     return false;
//   }
//   if (trimmedLen > 50) {
//     showStreetError(input, `❌ Street/Purok: Too long.`, 'max_length');
//     return false;
//   }

//   // ✅ All good
//   clearStreetError(input);
//   return true;
// }

// Helper functions with tag support
function getErrorBoxForStreet(inputElem) {
  const id = `street_error_${inputElem.name}`;
  let box = document.getElementById(id);
  if (box) return box;

  box = document.createElement("div");
  box.id = id;
  box.className = "error-box";
  box.style.cssText = `
    background:#ffe5e5;
    border:1px solid #ff6b6b;
    color:#b30000;
    padding:6px 10px;
    margin-top:6px;
    font-size:12px;
    border-radius:5px;
    display:none;
  `;
  inputElem.parentNode.appendChild(box);
  inputElem.setAttribute("aria-describedby", box.id);
  return box;
}

function showStreetError(inputElem, message, tag='') {
  const box = getErrorBoxForStreet(inputElem);
  box.textContent = message;
  box.dataset.tag = tag; // assign tag
  box.style.display = "block";
  inputElem.classList.add("is-invalid");
  inputElem.classList.remove("is-valid");
}

function clearStreetError(inputElem) {
  const box = getErrorBoxForStreet(inputElem);
  box.textContent = '';
  box.dataset.tag = '';
  box.style.display = "none";
  inputElem.classList.remove("is-invalid");
  inputElem.classList.add("is-valid");
}

// ========================================ZIP CODE==================================================
const ZIP_LENGTH = 4;

function getErrorBoxForZip(inputElem) {
  const id = `zip_error_${inputElem.name}`;
  let box = document.getElementById(id);
  if (box) return box;

  box = document.createElement("div");
  box.id = id;
  box.className = "error-box";
  box.style.cssText = `
    background:#ffe5e5;
    border:1px solid #ff6b6b;
    color:#b30000;
    padding:6px 10px;
    margin-top:6px;
    font-size:12px;
    border-radius:5px;
    display:none;
  `;
  inputElem.parentNode.appendChild(box);
  inputElem.setAttribute("aria-describedby", box.id);
  return box;
}

function showZipError(inputElem, message, tag = '') {
  const box = getErrorBoxForZip(inputElem);
  box.textContent = message;
  box.dataset.tag = tag; // assign specific error tag
  box.style.display = "block";
  inputElem.classList.add("is-invalid");
  inputElem.classList.remove("is-valid");
}

function clearZipError(inputElem) {
  const box = getErrorBoxForZip(inputElem);
  box.textContent = "";
  box.dataset.tag = ''; // clear error tag
  box.style.display = "none";
  inputElem.classList.remove("is-invalid");
  inputElem.classList.add("is-valid");
}

function validateZipField(input, checkEmpty = false) { 
  if (!input) return true;

  const value = input.value;

  // ❗ Leading space
  if (/^\s/.test(value)) {
    showZipError(input, `❌ Zip Code: Cannot start with a space.`, 'leading_space');
    return false;
  }

  // ❗ Double space
  if (/\s{2,}/.test(value)) {
    showZipError(input, `❌ Zip Code: No double spaces allowed.`, 'double_space');
    return false;
  }

  // 1️⃣ Empty check
  if (!value.trim()) {
    if (checkEmpty) {
      showZipError(input, `❌ Zip Code: This field is required.`, 'empty');
      return false;
    } else {
      clearZipError(input);
      return true;
    }
  }

  // 2️⃣ Letters check
  if (/[A-Za-z]/.test(value)) {
    showZipError(input, `❌ Zip Code: Letters are not allowed.`, 'letters_not_allowed');
    return false;
  }

  // 3️⃣ Special characters check
  if (/[^0-9\s]/.test(value)) { // allow digits only
    showZipError(input, `❌ Zip Code: Special characters are not allowed.`, 'special_chars_not_allowed');
    return false;
  }

  // 4️⃣ Length check
  if (value.length !== ZIP_LENGTH) {
    showZipError(input, `❌ Zip Code: Must be exactly ${ZIP_LENGTH} digits.`, 'length');
    return false;
  }

  // ✅ All good
  clearZipError(input);
  return true;
}

// Attach validation to input events
document.addEventListener("DOMContentLoaded", () => {
  const zipInput = document.getElementsByName("zip")[0];
  if (zipInput) {
    zipInput.addEventListener("input", () => validateZipField(zipInput));
    zipInput.addEventListener("blur", () => validateZipField(zipInput, true));
  }
});


// Attach validation to input events
document.addEventListener("DOMContentLoaded", () => {
  const zipInput = document.getElementsByName("zip")[0];
  if (zipInput) {
    zipInput.addEventListener("input", () => validateZipField(zipInput));
    zipInput.addEventListener("blur", () => validateZipField(zipInput, true));
  }
});



document.addEventListener("DOMContentLoaded", () => {
  const zip = document.querySelector("input[name='zip']");
  if (!zip) return;

  // LIVE VALIDATION
  zip.addEventListener("input", () => validateZipField(zip));
  zip.addEventListener("blur", () => validateZipField(zip));

  const form = document.getElementById("registerForm");
  if (form) {
    form.addEventListener("submit", (e) => {
      // CHECK EMPTY ON SUBMIT
      if (!validateZipField(zip, true)) {
        e.preventDefault();
        setTimeout(() => zip.focus(), 10);
      }
    });
  }
});



// ====================================\\EMAIL\\====================================================================

// -----------------------------
// EMAIL VALIDATOR + auto @csucc.edu.ph
// -----------------------------
function validateEmailField() {
  const input = document.querySelector("input[name='email']");
  if (!input) return true;

  let value = input.value.trim();

  // Auto append @csucc.edu.ph if no domain
  if (value && !value.includes("@")) {
    value += "@csucc.edu.ph";
    input.value = value; // update input
  }

  // Regex: only allow valid csucc.edu.ph emails
  const emailPattern = /^[a-zA-Z0-9._%+-]+@csucc\.edu\.ph/;
  if (!value) {
    showEmailError(input, "❌ Email: This field is required.");
    return false;
  } else if (!emailPattern.test(value)) {
    showEmailError(input, "❌ Email: Must be a valid @csucc.edu.ph address.");
    return false;
  }

  // Clear error if valid
  clearEmailError(input);
  return true;
}


function showEmailError(inputElem, message) {
  const box = getErrorBoxForEmail(inputElem);
  box.textContent = message;
  box.style.display = "block";
  inputElem.classList.add("is-invalid");
  inputElem.classList.remove("is-valid");
  inputElem.setAttribute("aria-invalid", "true");
}

function clearEmailError(inputElem) {
  const box = getErrorBoxForEmail(inputElem);
  box.textContent = "";
  box.style.display = "none";
  inputElem.classList.remove("is-invalid");
  inputElem.classList.add("is-valid");
  inputElem.removeAttribute("aria-invalid");
}




// attach events
document.addEventListener("DOMContentLoaded", () => {
  const emailInput = document.querySelector("input[name='email']");
  if (!emailInput) return;

  emailInput.addEventListener("input", validateEmailField);
  emailInput.addEventListener("blur", validateEmailField);

  const form = document.getElementById("registerForm");
  if (form) {
    form.addEventListener("submit", (e) => {
      if (!validateEmailField()) {
        e.preventDefault();
        setTimeout(() => emailInput.focus(), 10);
      }
    });
  }
});

// ===================== KEEP FORM INPUTS AFTER REFRESH =====================
document.addEventListener("DOMContentLoaded", function () {
  const form = document.getElementById("registerForm");
  if (!form) return;

  // Corrected field names based on your form HTML
  const fieldsToPersist = [
    "id_number",
    "first_name",
    "middle_name",
    "last_name",
    "extension",
    "sex",
    "birthdate",
    "age",
    "street",
    "barangay",
    "city",
    "province",
    "country",
    "zip",
    "email",
    "username",
    "password",
    "repassword",
    "a1_question",
    "a1_answer",
    "a2_question",
    "a2_answer",
    "a3_question",
    "a3_answer"
  ];

  // Restore saved input values
  fieldsToPersist.forEach((name) => {
    const field = document.getElementsByName(name)[0];
    if (!field) return;

    const savedValue = sessionStorage.getItem(name);
    if (savedValue !== null) {
      field.value = savedValue;
    }
  });

  // Auto-calc age when birthdate is already saved
  const birthInput = document.getElementsByName("birthdate")[0];
  const ageInput = document.getElementsByName("age")[0];
  if (birthInput && ageInput && birthInput.value) {
    const birthDate = new Date(birthInput.value);
    const today = new Date();
    let years = today.getFullYear() - birthDate.getFullYear();
    const m = today.getMonth() - birthDate.getMonth();
    if (m < 0 || (m === 0 && today.getDate() < birthDate.getDate())) years--;
    ageInput.value = years >= 0 ? years : "";
  }

  // Save every input change
  fieldsToPersist.forEach((name) => {
    const field = document.getElementsByName(name)[0];
    if (!field) return;

    const saveValue = () => {
      sessionStorage.setItem(name, field.value);
    };

    field.addEventListener("input", saveValue);
    field.addEventListener("change", saveValue);
  });

  // Clear sessionStorage only when form is successful (no .is-invalid)
  form.addEventListener("submit", () => {
    const invalid = form.querySelector(".is-invalid");
    if (!invalid) {
      fieldsToPersist.forEach((name) => sessionStorage.removeItem(name));
      localStorage.clear();
    }
  });
});


// ========================================USERNAME VALIDATOR==================================================
function validateUsernameField() {
  const fieldName = "username";
  const input = document.getElementsByName(fieldName)[0];
  if (!input) return true;

  const rawValue = input.value;
  const value = rawValue.trim();

  // 0️⃣ Leading space first
  if (/^\s/.test(rawValue)) {
    showSingleError(fieldName, input, `❌ Username: Cannot start with a space.`);
    return false;
  }

  // 1️⃣ Required
  if (!value) {
    showSingleError(fieldName, input, `❌ Username: This field is required.`);
    return false;
  }

  // 2️⃣ Minimum length
  if (value.length < 3) {
    showSingleError(fieldName, input, `❌ Username: Must be at least 3 characters.`);
    return false;
  }

  // 3️⃣ No spaces inside
  if (/\s/.test(value)) {
    showSingleError(fieldName, input, `❌ Username: Spaces are not allowed.`);
    return false;
  }

  // 4️⃣ AJAX check availability
  const box = getErrorBox(fieldName, input);
  box.textContent = "⏳ Checking availability...";
  box.style.display = "block";
  box.style.background = "#fff3cd";
  box.style.border = "1px solid #ffeeba";
  box.style.color = "#856404";
  input.classList.remove("is-valid", "is-invalid");

  fetch("../include/check_username.php", {
    method: "POST",
    headers: { "Content-Type": "application/x-www-form-urlencoded" },
    body: "username=" + encodeURIComponent(value)
  })
  .then(res => res.json())
  .then(data => {
    if (data.exists) {
      showSingleError(fieldName, input, data.message || "❌ Username already exists.");
    } else {
      clearError(fieldName, input);
      const successBox = getErrorBox(fieldName, input);
      successBox.textContent = data.message || "✅ Username is available.";
      successBox.style.display = "block";
      successBox.style.background = "#e8f5e9";
      successBox.style.border = "1px solid #4caf50";
      successBox.style.color = "#256029";
    }
  })
  .catch(() => {
    showSingleError(fieldName, input, "⚠️ Error checking username. Please try again.");
  });

  return true;
}

// 🕒 Auto attach with debounce
document.addEventListener("DOMContentLoaded", () => {
  const usernameInput = document.querySelector("input[name='username']");
  if (!usernameInput) return;

  let timer;
  usernameInput.addEventListener("input", () => {
    clearTimeout(timer);
    timer = setTimeout(() => validateUsernameField(), 500);
  });

  usernameInput.addEventListener("blur", validateUsernameField);

  // Optional: check again on form submit
  const form = document.getElementById("registerForm");
  if (form) {
    form.addEventListener("submit", (e) => {
      if (!validateUsernameField()) {
        e.preventDefault();
        setTimeout(() => usernameInput.focus(), 10);
      }
    });
  }
});
