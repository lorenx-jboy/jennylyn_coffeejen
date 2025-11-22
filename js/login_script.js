document.addEventListener("DOMContentLoaded", () => {

    // Fetch lock status every time the login page loads
    checkLockStatus();

    async function checkLockStatus() {
        try {
            const res = await fetch("/php/login_submit.php", {
                method: "GET",
                headers: { "X-Requested-With": "XMLHttpRequest" }
            })
            const data = await res.json();
            const remaining = parseInt(data.remaining, 10);

            if (remaining > 0) {
                startLockoutCountdown(remaining);
            }
        } catch (err) {
            console.error("Error fetching lock status:", err);
        }
    }

    function startLockoutCountdown(remaining) {
        const loginBtn = document.getElementById('login-button');
        const registerLinkHeader = document.getElementById('register-link-header');
        const serverMessage = document.querySelector('.server-message');
        const countdownEl = document.getElementById('countdown');

        loginBtn.disabled = true;
        registerLinkHeader.classList.add('disabled');

        serverMessage.style.display = 'block';

        countdownEl.textContent = remaining;

        let timer = remaining;

        const interval = setInterval(() => {
            timer--;
            countdownEl.textContent = timer;

            if (timer <= 0) {
                clearInterval(interval);
                loginBtn.disabled = false;
                registerLinkHeader.classList.remove('disabled');
                serverMessage.style.display = 'none';
            }
        }, 1000);
    }

    // Password toggle logic (unchanged)
    const toggleBtn = document.querySelector('.toggle-password-btn');
    if (toggleBtn) {
        const passwordInput = document.getElementById('login-password');
        toggleBtn.addEventListener('click', () => {
            const type = passwordInput.type === 'password' ? 'text' : 'password';
            passwordInput.type = type;
            toggleBtn.innerHTML = type === 'password'
               ? '<i class="fas fa-eye"></i>'
               : '<i class="fas fa-eye-slash"></i>';
        });
    }

    // Form validation (unchanged)
    const form = document.getElementById('login-form');
    form.addEventListener('submit', function(event) {
        if (!form.checkValidity()) {
            event.preventDefault();
            event.stopPropagation();
            form.querySelectorAll('input').forEach(input => {
                input.classList.toggle('is-invalid', !input.checkValidity());
            });
        }
    });
});
