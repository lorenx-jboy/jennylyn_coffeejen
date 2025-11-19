document.addEventListener('contextmenu', e => e.preventDefault());
// ==========================PERSONAL INFORAMATION=========================================
// register.js - per-field validation functions + focus on error (single prioritized error)

// -----------------------------
// Helper: create/find error box
// -----------------------------
function getErrorBox(inputName, inputElem) {
  const id = `${inputName}_error`;
  let box = document.getElementById(id);
  if (box) return box;

  // If not found, create a temporary error-box (so script still works)
  box = document.createElement("div");
  box.id = id;
  box.className = "error-box";
  box.style.cssText = "background:#ffe5e5;border:1px solid #ff6b6b;color:#b30000;padding:6px 10px;margin-top:3px;font-size:12px;border-radius:5px;display:none;";
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
// Priority validators (single error only)
// -----------------------------

// NAME priority: numbers -> special chars -> first-letter uppercase -> multiple spaces -> three repeats -> min/max length -> word case
function validateNameField(fieldName, label, required = true) {
  const input = document.getElementsByName(fieldName)[0];
  if (!input) return true;

  let value = input.value;

  // 0) Leading space check
  if (/^\s/.test(value)) {
    showSingleError(fieldName, input, `❌ ${label}: Cannot start with a space.`);
    return false;
  }

  const trimmed = value.trim();

  // 1) Empty / required
  if (!trimmed) {
    if (required) {
      showSingleError(fieldName, input, `❌ ${label}: This field is required.`);
      return false;
    } else {
      clearError(fieldName, input);
      return true;
    }
  }

  // 2) Double/triple spaces
  if (/\s{2,}/.test(value)) {
    showSingleError(fieldName, input, `❌ ${label}: No double spaces allowed.`);
    return false;
  }

  // 3) Three repeated letters (case-insensitive)
  if (/(.)\1\1/i.test(value)) {
    showSingleError(fieldName, input, `❌ ${label}: No triple repeated letters are allowed.`);
    return false;
  }

  // 4) Max length
  if (trimmed.length > 50) {
    showSingleError(fieldName, input, `❌ ${label}: Must be at most 50 characters.`);
    return false;
  }

  // 5) Check each word individually
  const words = trimmed.split(/\s+/);
  const positions = ['First', 'Second', 'Third', 'Fourth', 'Fifth']; // label sa word positions

  for (let i = 0; i < words.length; i++) {
    const w = words[i];
    const posLabel = positions[i] || `Word ${i + 1}`; // fallback kung sobra sa 5 words
    if (!w) continue;

    // Numbers check
    if (/\d/.test(w)) {
      showSingleError(fieldName, input, `❌ ${posLabel} name: Numbers are not allowed.`);
      return false;
    }

    // Special characters check
    if (/[^A-Za-z]/.test(w)) {
      showSingleError(fieldName, input, `❌ ${posLabel} name: Special characters are not allowed.`);
      return false;
    }

    // First letter uppercase, rest lowercase
    const first = w[0];
    const rest = w.slice(1);
    if (first !== first.toUpperCase() || rest !== rest.toLowerCase()) {
      showSingleError(fieldName, input, `❌ ${posLabel} name: Must start uppercase, rest lowercase.`);
      return false;
    }

    // ALL CAPS word check
    if (w.length > 1 && w === w.toUpperCase()) {
      showSingleError(fieldName, input, `❌ ${posLabel} name: All caps words are not allowed.`);
      return false;
    }
  }

  // ✅ All good
  clearError(fieldName, input);
  return true;
}



// EXTENSION validator (Jr, Jr., I, II, III, ...)
function validateExtensionField() {
  const fieldName = "extension";
  const input = document.getElementsByName(fieldName)[0];
  if (!input) return true;

  const raw = input.value;

  // ❗ Check if starts with space
  if (/^\s/.test(raw)) {
    showSingleError(fieldName, input, `❌ Extension cannot start with a space.`);
    return false;
  }

  const trimmed = raw.trim();
  if (trimmed === "") {
    clearError(fieldName, input);
    return true; // optional field
  }

  const clean = trimmed.replace(/\./g, "").toUpperCase();

  // Accept Jr or Roman numerals
  const romanRegex = /^(JR|SR|I|II|III|IV|V|VI|VII|VIII|IX|X|XI|XII|XIII|XIV|XV)$/;

  if (!romanRegex.test(clean)) {
    showSingleError(fieldName, input, `❌ Extension must be 'Jr', 'Sr', or a Roman numeral (I, II, III, ...).`);
    return false;
  }

  // Normalize display
  if (clean === "JR") {
    input.value = trimmed.includes(".") ? "Jr." : "Jr";
  } else if (clean === "SR") {
    input.value = trimmed.includes(".") ? "Sr." : "Sr";
  } else {
    input.value = clean; // roman uppercase
  }

  clearError(fieldName, input);
  return true;
}

// =============================SEX==================================================

function validateSex() {
  const inputName = "sex";
  const select = document.getElementsByName(inputName)[0];
  if (!select) return true;

  // Create a "touched" flag to avoid showing error on load
  if (!select.dataset.touched) {
    select.addEventListener("change", () => {
      select.dataset.touched = "true";
      validateSex(); // recheck once touched
    });
    return true;
  }

  if (!select.value || select.value.trim() === "") {
    showSingleError(inputName, select, "❌ Sex: Please select your sex.");
    return false;
  }

  clearError(inputName, select);
  return true;
}


// BIRTHDATE & AGE validator (auto-calc, min 18)
function validateBirthdateField() {
  const fieldName = "birthdate";
  const input = document.getElementsByName(fieldName)[0];
  const ageInput = document.getElementsByName("age")[0];

  if (!input) return true;
  const raw = input.value;
  if (!raw) {
    showSingleError(fieldName, input, `❌ Birthdate: Please select your birthdate.`);
    if (ageInput) ageInput.value = "";
    return false;
  }

  const bday = new Date(raw);
  const today = new Date();
  let years = today.getFullYear() - bday.getFullYear();
  const m = today.getMonth() - bday.getMonth();
  if (m < 0 || (m === 0 && today.getDate() < bday.getDate())) years--;

  if (ageInput) ageInput.value = (years >= 0 ? years : "");

  if (years < 18) {
    showSingleError(fieldName, input, `❌ Birthdate: You must be at least 18 years old.`);
    if (ageInput) ageInput.value = "";
    return false;
  }

  clearError(fieldName, input);
  return true;
}


// ========================== COUNTER CLICKABLE==========================
document.addEventListener("DOMContentLoaded", () => {
  // Make AGE readonly and non-clickable
  const ageInput = document.getElementsByName("age")[0];
  if (ageInput) {
    ageInput.readOnly = true;
    ageInput.style.pointerEvents = "none";
  }

  // Make ID NUMBER readonly and non-clickable
  const idInput = document.getElementsByName("id_number")[0];
  if (idInput) {
    idInput.readOnly = true;
    idInput.style.pointerEvents = "none";
  }
});


// -----------------------------
// Attach events & form submit
// -----------------------------
document.addEventListener("DOMContentLoaded", () => {
  // attach ids if missing (keeps compatibility)
  const mapping = {
    first_name: "firstName",
    middle_name: "middleName",
    last_name: "lastName",
    extension: "extension",
    street: "street",
    birthdate: "birthdate",
    age: "age"
  };
  for (const name in mapping) {
    const el = document.querySelector(`input[name="${name}"]`);
    if (el && !el.id) el.id = mapping[name];
  }

  // Real-time + blur per-field
  const fn = document.getElementsByName("first_name")[0];
  const mn = document.getElementsByName("middle_name")[0];
  const ln = document.getElementsByName("last_name")[0];
  const ext = document.getElementsByName("extension")[0];
  const st = document.getElementsByName("street")[0];
  const bd = document.getElementsByName("birthdate")[0];

  if (fn) {
    fn.addEventListener("input", () => validateNameField("first_name", "First Name", true));
    fn.addEventListener("blur", () => validateNameField("first_name", "First Name", true));
  }
  if (mn) {
    mn.addEventListener("input", () => validateNameField("middle_name", "Miageddle Name", false));
    mn.addEventListener("blur", () => validateNameField("middle_name", "Middle Name", false));
  }
  if (ln) {
    ln.addEventListener("input", () => validateNameField("last_name", "Last Name", true));
    ln.addEventListener("blur", () => validateNameField("last_name", "Last Name", true));
  }
  if (ext) {
    ext.addEventListener("input", () => validateExtensionField());
    ext.addEventListener("blur", () => validateExtensionField());
  }
  if (st) {
    st.addEventListener("input", () => validateStreetField());
    st.addEventListener("blur", () => validateStreetField());
  }
  if (bd) {
    bd.addEventListener("change", () => validateBirthdateField());
    bd.addEventListener("blur", () => validateBirthdateField());
    bd.addEventListener("input", () => validateBirthdateField());
  }

  // Form submit: validate all, focus first invalid
  const form = document.getElementById("registerForm");
  if (form) {
    form.addEventListener("submit", (e) => {
      const checks = [
        { ok: validateNameField("first_name", "First Name", true), name: "first_name" },
        { ok: validateNameField("middle_name", "Middle Name", false), name: "middle_name" },
        { ok: validateNameField("last_name", "Last Name", true), name: "last_name" },
        { ok: validateExtensionField(), name: "extension" },
        { ok: validateStreetField(), name: "street" },
        { ok: validateBirthdateField(), name: "birthdate" }
      ];

      const firstFailed = checks.find(c => !c.ok);
      if (firstFailed) {
        e.preventDefault();
        // focus already done by validator, but ensure focus
        const el = document.getElementsByName(firstFailed.name)[0];
        if (el) setTimeout(() => el.focus(), 10);
        return false;
      }
      // otherwise allow submit
    });
  }
});


// === Sex Validation ===
function validateSex() {
  const select = document.getElementById('sex');
  const inputName = "sex";

  // If user never interacted yet, don't show error
  if (!select.dataset.touched) {
    select.addEventListener("change", () => {
      select.dataset.touched = "true";
      validateSex(); // Revalidate when user changes
    });
    return true;
  }

  if (select.value.trim() === "") {
    showSingleError(inputName, select, "❌ Sex: Please select your sex.");
    return false;
  }

  clearError(inputName, select);
  return true;
}

// === Attach "touched" when user clicks ===
document.getElementById('sex').addEventListener("focus", function () {
  this.dataset.touched = "true";
});


// -----------------------------
// Attach events for sex like other fields
// -----------------------------
document.addEventListener("DOMContentLoaded", () => {
  const sexSelect = document.getElementsByName("sex")[0];
  if (sexSelect) {
    sexSelect.addEventListener("change", () => validateSex());
    sexSelect.addEventListener("blur", () => validateSex());
  }

  // Add to form submit validation
  const form = document.getElementById("registerForm");
  if (form) {
    form.addEventListener("submit", (e) => {
      const ok = validateSex();
      if (!ok) {
        e.preventDefault();
        setTimeout(() => sexSelect.focus(), 10);
      }
    });
  }
});


// =======================================
document.addEventListener("DOMContentLoaded", () => {
  const form = document.getElementById("registerForm");
  if (!form) return;

  // -------------------------
  // PERSONAL INFO PERSISTENCE
  // -------------------------
  const personalFields = ["first_name","middle_name","last_name","extension","street","birthdate","age"];

  // Restore saved values on page load
  personalFields.forEach(f => {
    const input = document.getElementsByName(f)[0];
    if (!input) return;

    const saved = sessionStorage.getItem(f);
    if (saved) input.value = saved;
  });

  // Recalculate age from birthdate after restoring
  const birthdateInput = document.getElementsByName("birthdate")[0];
  const ageInput = document.getElementsByName("age")[0];
  if (birthdateInput && ageInput && birthdateInput.value) {
    validateBirthdateField(); // auto-fills age
    sessionStorage.setItem("age", ageInput.value); // persist calculated age
  }

  // Save values in real-time
  personalFields.forEach(f => {
    const input = document.getElementsByName(f)[0];
    if (!input) return;
    input.addEventListener("input", () => {
      sessionStorage.setItem(f, input.value);
      if (f === "birthdate") validateBirthdateField(); // recalc age on birthdate change
    });
    input.addEventListener("change", () => {
      sessionStorage.setItem(f, input.value);
      if (f === "birthdate") validateBirthdateField(); // recalc age on birthdate change
    });
  });

  // -------------------------
  // FORM SUBMIT VALIDATION
  // -------------------------
  if (form) {
    form.addEventListener("submit", (e) => {
      const personalValid = personalFields.every(f => {
        const input = document.getElementsByName(f)[0];
        if (!input) return true;
        // trigger existing validators
        if (f === "first_name") return validateNameField("first_name","First Name",true);
        if (f === "middle_name") return validateNameField("middle_name","Middle Name",false);
        if (f === "last_name") return validateNameField("last_name","Last Name",true);
        if (f === "extension") return validateExtensionField();
        if (f === "street") return validateStreetField();
        if (f === "birthdate" || f === "age") return validateBirthdateField(); // age handled by birthdate
        return true;
      });

      if (!personalValid) {
        e.preventDefault();
        const firstInvalid = form.querySelector(".is-invalid");
        if (firstInvalid) firstInvalid.focus();
      } else {
        // Clear stored data on successful submit
        personalFields.forEach(f => sessionStorage.removeItem(f));
      }
    });
  }
});
