
    /* ==========================
   PASSWORD RESET MODULE
   ========================== */

const urls = {
    checkUserId: "/api/check_user_id.php",
    authenticateQuestions: "/api/authenticate_questions.php",
    resetPassword: "/api/reset_password.php",
};

const RESET_STORAGE = "passwordResetState";
const FORM_STORAGE = "passwordResetForm";
const form = document.getElementById("password-reset-form");
const userIdInput = document.getElementById("user_id");
const submitBtn = form.querySelector('button[type="submit"]');
const usernameDisplay = document.querySelector("span[name='resetPass']");

form.setAttribute("novalidate", true);

if (form && getState().validId) {
    form.dispatchEvent(new Event("reset"));
    userIdInput.focus();
}

// submitBtn.textContent = (state.validId && state.validAuth) ? "Reset Password" : "Verify User ID";


/* ──────────────────────────────
    TOGGLE PASSWORD VALIDATION
────────────────────────────── */




const passwordInput = document.getElementById("newPassword");
const passwordConfirmInput = document.getElementById("confirmPassword");
const passwordStrengthEl = document.getElementById("password-strength");

// Password strength scoring (upgraded rules)
const passwordStrength = p => [
  p.length >= 8,
  /[A-Z]/.test(p),
  /\d/.test(p),
  /[^A-Za-z0-9]/.test(p)
].filter(Boolean).length;

// Strength labels
const passwordLabel = s => ["Very Weak","Weak","Moderate","Strong","Very Strong"][s-1] || "";

// Validate password with stricter rules
const validatePassword = p => {
  if (!p) return "⚠️ Password is required.";
  if (p.length < 8) return "⚠️ Password must be at least 8 characters.";
  if (!/[A-Z]/.test(p)) return "⚠️ Must contain at least 1 uppercase letter.";
  if (!/\d/.test(p)) return "⚠️ Must contain at least 1 number.";
  if (!/[^A-Za-z0-9]/.test(p)) return "⚠️ Must contain at least 1 special character.";
  return "";
};

// Confirm password
const confirmPassword = (p, c) => !c ? "⚠️ Please re-enter." : p !== c ? "⚠️ Passwords do not match." : "";

// Update UI
const update = () => {
  const p = passwordInput.value;
  const c = passwordConfirmInput.value;

  const strength = passwordStrength(p);
  const strengthLabel = passwordLabel(strength);

  const error = validatePassword(p);
  const confirmError = confirmPassword(p, c);

  
  // Map strength to colors
  const colorMap = {
    "Very Weak": "redorange",
    "Weak": "orange",
    "Moderate": "goldenrod",
    "Strong": "green",
    "Very Strong": "yellowgreen"
  };

  // Display strength or error
  passwordStrengthEl.textContent = error || confirmError || strengthLabel;
  passwordStrengthEl.style.color = error || confirmError ? "red" : colorMap[strengthLabel] || "black";

  // Toggle input classes
  if (error) {
    passwordInput.classList.add("is-invalid");
    passwordInput.classList.remove("is-valid");
  } else if (strengthLabel === "Very Strong") {
    passwordInput.classList.add("is-valid");
    passwordInput.classList.remove("is-invalid");
  } else {
    passwordInput.classList.remove("is-valid", "is-invalid");
  }

  // Confirm password invalid class
  passwordConfirmInput.classList.toggle("is-invalid", !!confirmError);
};

// Event listeners
passwordInput.addEventListener("input", update);
passwordConfirmInput.addEventListener("input", update);

/* ────────────────────────────
    FORCE DISABLE FIELDS FUNCTION
────────────────────────────── */

function disableAuthQuestions(disable = true) {
    const selects = document.querySelectorAll('.authQuestion');
    const inputs = document.querySelectorAll('.authAnswer');

    selects.forEach(select => select.disabled = disable);
    inputs.forEach(input => input.disabled = disable);
}

/* ──────────────────────────────
    TOGGLE FIELD
────────────────────────────── */
toggleField();
function toggleField() {
    const state = getState();

    const password = document.getElementById('newPassword');
    const confirmPasswordEl = document.getElementById('confirmPassword');

    password.disabled = !state.validAuth;
    confirmPasswordEl.disabled = password.disabled;

    state.validId && userIdInput.classList.toggle('active', state.validId);
    if (state.validAuth) {
        password.focus();
    }
}

/* ─────────────────────────────
   STATE MANAGEMENT
────────────────────────────── */

function getState() {
    return JSON.parse(localStorage.getItem(RESET_STORAGE)) || {
        validId: false,
        validAuth: false,
        complete: false,
        username: ""
    };
}

function setState(patch) {
    const updated = { ...getState(), ...patch };
    localStorage.setItem(RESET_STORAGE, JSON.stringify(updated));
    updateUI();
}

function clearState() {
    localStorage.removeItem(RESET_STORAGE);
}


/* ─────────────────────────────
   API UTIL
────────────────────────────── */

async function post(url, payload) {
    try {
        const response = await fetch(url, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(payload),
        });
        return await response.json();
    } catch (err) {
        console.error("API ERROR:", err);
        return { success: false, message: "Server error" };
    }
}


/* ─────────────────────────────
   API CALLS
────────────────────────────── */

async function apiCheckUserId() {
    const result = await post(urls.checkUserId, { user_id: userIdInput.value });
    const s = result.success;
    
    toggleField();

    if (s) {
        setState({ validId: true, username: result.username });
        userIdInput.parentElement.querySelector('.invalid-feedback').textContent = result.message;
        userIdInput.classList.remove('is-invalid');
    } else {
        setState({ validId: false, validAuth: false, complete: false });
        userIdInput.classList.add('is-invalid');
        userIdInput.parentElement.querySelector('.invalid-feedback').textContent = result.message;
        userIdInput.focus();
    }
    // console.warn('API RESULT check user id:', result);
    return result;
}

async function apiAuthenticate(form) {
    const data = Object.fromEntries(new FormData(form));
    const result = await post(urls.authenticateQuestions, data);
    if (result.success) setState({ validAuth: true });
    
    toggleField();

    
    const selects = document.querySelectorAll('.authQuestion');
    const inputs = document.querySelectorAll('.authAnswer');

    selects.forEach(s => {
        s.classList.toggle('is-invalid', result.success === false);
        const msgEl = s.parentElement.querySelector('.invalid-feedback');
        if (msgEl) msgEl.textContent = result.message;
        s.disabled = result.success;
    });

    console.warn('API RESULT authenticate questions:', result);
    return result;

}

async function apiResetPassword(form) {
    const data = Object.fromEntries(new FormData(form));
    const result = await post(urls.resetPassword, data);

    if (result.success) {
        clearState();
        clearFormData();
        window.location.href = "login.php";
    }
    passwordInput.focus();

    // console.warn('API RESULT reset password:', result);
    return result;
}


/* ─────────────────────────────
   FORM DATA SAVE/RESTORE
────────────────────────────── */

function saveForm() {
    const data = Object.fromEntries(new FormData(form));
    localStorage.setItem(FORM_STORAGE, JSON.stringify(data));
}

function loadForm() {
    const saved = localStorage.getItem(FORM_STORAGE);
    if (!saved) return;

    const data = JSON.parse(saved);
    Object.entries(data).forEach(([key, value]) => {
        const field = form.querySelector(`[name="${key}"]`);
        if (field) field.value = value;
    });
}

function clearFormData() {
    localStorage.removeItem(FORM_STORAGE);
}


/* ─────────────────────────────
   UI FLOW LOGIC
────────────────────────────── */

function updateUI() {
    const state = getState();
    let stageTitle;

    if (state.validId && !state.validAuth) {
        form.dataset.submit = "verifyAnswers";
        stageTitle = "Verify Answers";

    } else if (state.validId && state.validAuth) {
        form.dataset.submit = "resetPassword";
        stageTitle = "Reset Password";
        update();
    } else {
        form.dataset.submit = "verifyUserID";
        stageTitle = "Verify ID";

    }

    const selects = document.querySelectorAll('.authQuestion');
    const inputs = document.querySelectorAll('.authAnswer');

    selects.forEach(s => s.disabled = !state.validId);
    inputs.forEach(i => i.disabled = !state.validId);

    // Display username if found
    if (state.username) usernameDisplay.textContent = state.username;

    console.log(`📌 Stage: ${form.dataset.submit}`);
    submitBtn.textContent = stageTitle;
    // document.querySelector('span[data-type="reset-stage"]').textContent = stageTitle;
    // console.table(state);
}


/* ─────────────────────────────
   INPUT EVENTS
────────────────────────────── */

userIdInput.addEventListener("input", async () => {
    // Format as ####-####
    let num = userIdInput.value.replace(/\D/g, "").substring(0, 8);
    userIdInput.value = num.length > 4 ? `${num.slice(0,4)}-${num.slice(4)}` : num;

    const result = await apiCheckUserId();
    if (!result.success) console.warn("❌ ID Not Found");

    submitBtn.disabled = !result.success;

    saveForm();
});


/* ─────────────────────────────
   FORM SUBMISSION FLOW
────────────────────────────── */

form.addEventListener("submit", async (e) => {
    e.preventDefault();

    const stage = form.dataset.submit;

    if (stage === "verifyUserID") {
        await apiCheckUserId();
    } 
    else if (stage === "verifyAnswers") {
        await apiAuthenticate(form);
    } 
    else if (stage === "resetPassword") {
        await apiResetPassword(form);
        return;
    }

    saveForm();
    updateUI();
});


/* ─────────────────────────────
   INIT
────────────────────────────── */

loadForm();
updateUI();

console.log("%c Password Reset JS Loaded", "color: orange; font-weight: bold;");

// toggle password visibility
document.querySelectorAll(".toggle-password-btn").forEach(btn => {
    btn.addEventListener("click", () => {
        // Find the closest input associated with this toggle button
        const input = btn.closest(".input-group").querySelector("input");
        const icon = btn.querySelector("i");

        if (input.type === "password") {
            input.type = "text";
            icon.classList.replace("bi-eye", "bi-eye-slash");
        } else {
            input.type = "password";
            icon.classList.replace("bi-eye-slash", "bi-eye");
        }
    });
});
