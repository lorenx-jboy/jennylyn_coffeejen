
const url = {
    checkEmail: '/api/check_email.php',
    checkUsername: '/api/check_username.php'
}

export async function checkEmailExists(email) {
    try {
        const res = await fetch(url.checkEmail, {
            method: 'POST',
            body: new URLSearchParams({ email })
        });
        const data = await res.json();
        return data;
    } catch (err) {
        console.error('Error checking email:', err);
        return false;
    }

}

export async function checkUsernameExists(username) {
    try {
        const res = await fetch(url.checkUsername, {
            method: 'POST',
            body: new URLSearchParams({ username })
        });
        const data = await res.json();
        return data;
    } catch (err) {
        console.error('Error checking username:', err);
        return false;
    }
}