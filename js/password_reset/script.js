const urls = {
    checkUserId: "/api/check_user_id.php",
    resetPassword: "/api/reset_password.php",
    authenticateQuestions: "/api/authenticate_questions.php",
};


// Converts FormData ➜ JSON object
function formDataToJSON(formData) {
    const obj = {};
    formData.forEach((value, key) => obj[key] = value);
    return obj;
}


/* ===== API CALLS ===== */


export async function checkUserId(user_id) {
    try {
        const response = await fetch(urls.checkUserId, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify({ user_id })
        });

        const data = await response.json();
        console.table(data);

        // expected: { success:true/false, username:"John" }
        if (data.success) {
            setResetState({ validId: true, username: data.username });
        } else {
            setResetState({ validId: false });
        }

        return data;
    } catch (error) {
        console.error("API ERROR:", error);
    }
}



export async function authenticateQuestions(form) {
    const dataToSend = formDataToJSON(new FormData(form));

    try {
        const response = await fetch(urls.authenticateQuestions, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(dataToSend)
        });

        const data = await response.json();
        console.table(data);

        if (data.success) {
            setResetState({ validAuth: true });
        }

        return data;

    } catch (error) {
        console.error("API ERROR:", error);
    }
}



export async function submitPasswordReset(form) {
    const dataToSend = formDataToJSON(new FormData(form));

    try {
        const response = await fetch(urls.resetPassword, {
            method: "POST",
            headers: { "Content-Type": "application/json" },
            body: JSON.stringify(dataToSend)
        });

        const data = await response.json();
        console.table(data);

        if (data.success) {
            clearResetState();
        }

        return data;
    } catch (error) {
        console.error("API ERROR:", error);
    }
}



/* ===== RESET STATE HANDLING ===== */

const RESET_STATE_KEY = "passwordResetState";

export function getResetState() {
    return JSON.parse(localStorage.getItem(RESET_STATE_KEY)) || {
        validId: false,
        validAuth: false,
        passwordResetting: false,
        username: null
    };
}

export function setResetState(patch) {
    const updated = { ...getResetState(), ...patch };
    localStorage.setItem(RESET_STATE_KEY, JSON.stringify(updated));
    updateStepUI(); // <-- auto update UI when state changes
}

export function clearResetState() {
    localStorage.removeItem(RESET_STATE_KEY);
}


/* ===== AUTO-FLOW UI UPDATE ===== */

function updateStepUI() {
    const state = getResetState();
    const form = document.getElementById("password-reset-form");

    if (!form) return;

    if (state.validId && !state.validAuth) {
        form.dataset.submit = "verifyAnswers";
    }
    else if (state.validId && state.validAuth) {
        form.dataset.submit = "resetPassword";
    }
    else {
        form.dataset.submit = "verifyUserID";
    }

    console.log("%c Updated Stage → " + form.dataset.submit, "color:lime;font-weight:bold;");
}


/* ===== FORM FLOW HANDLER ===== */

document.addEventListener("submit", async (e) => {
    if (!e.target.matches("#password-reset-form")) return;
    
    e.preventDefault();
    const form = e.target;
    const stage = form.dataset.submit;
    const state = getResetState();

    console.log("🧾 CURRENT STAGE:", stage);

    if (stage === "verifyUserID") {
        const result = await checkUserId(form.user_id.value);
        if (result.success) updateStepUI();
        return;
    }

    if (stage === "verifyAnswers") {
        const result = await authenticateQuestions(form);
        if (result.success) updateStepUI();
        return;
    }

    if (stage === "resetPassword") {
        const result = await submitPasswordReset(form);
        if (result.success) {
            alert("Password reset successful!");
            clearFormData();
            clearResetState();
            location.reload();
        }
    }
});
