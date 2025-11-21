
const urls = {
    checkUserId: "/api/check_user_id.php",
    resetPassword: "/api/reset_password.php",
    authenticateQuestions: "/api/authenticate_questions.php",
}   

async function checkUserId(user_id) {
    try {
        const response = await fetch(urls.checkUserId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify({ user_id })
        });

        const data = await response.json();
        console.table(data);

        // Example result expected: { valid: true/false }
        if (data.valid) {
            setResetState({ validId: true });
        } else {
            setResetState({ validId: false });
        }

        return data;
    } catch (error) {
        console.error('Error:', error);
    }
}





async function submitPasswordReset(form) {
    const data = new FormData(form);
    try {
        const response = await fetch(urls.resetPassword, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const data = await response.json();
        console.table(data);
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
}

async function authenticateQuestions(form) {
    const data = new FormData(form);
    try {
        const response = await fetch(urls.authenticateQuestions, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json'
            },
            body: JSON.stringify(data)
        });

        const data = await response.json();
        console.table(data);
        return data;
    } catch (error) {
        console.error('Error:', error);
    }
}

function getResetState() {
        return JSON.parse(localStorage.getItem(RESET_STATE_KEY)) || {
            validId: false,
            validAuth: false,
            passwordResetting: false
        };
    }

    // Save/update state
    function setResetState(patch) {
        const current = getResetState();
        const updated = { ...current, ...patch };
        localStorage.setItem(RESET_STATE_KEY, JSON.stringify(updated));
    }

    // Clear state entirely (e.g., when process completed)
    function clearResetState() {
        localStorage.removeItem(RESET_STATE_KEY);
    }

export const apis = {
    checkUserId,
    submitPasswordReset,
    authenticateQuestions
}