// // JS/forgot_password.js
// // Handles show/hide for answer/password fields, disables paste, and password strength UI.
// document.addEventListener('DOMContentLoaded', () => {
//   // Helper: toggles visibility for inputs when clicking .show-hide-btn (uses data-target or nearby input)
//   document.querySelectorAll('.show-hide-btn').forEach(btn => {
//     btn.addEventListener('click', (e) => {
//       const targetName = btn.getAttribute('data-target');
//       let input;
//       if (targetName) {
//         input = document.getElementById(targetName);
//       } else {
//         // If no data-target, try to find an input in the same input-group
//         const parent = btn.closest('.input-group');
//         input = parent ? parent.querySelector('input') : null;
//       }
//       if (!input) return;
//       if (input.type === 'password') {
//         input.type = 'text';
//         btn.textContent = '🙈';
//       } else {
//         input.type = 'password';
//         btn.textContent = '👁️';
//       }
//     });
//   });

//   // Disable paste & right-click into password-style inputs (answers and new password fields)
//   const blockPasteSelectors = ['#a1', '#a2', '#a3', '#newPassword', '#confirmPassword'];
//   blockPasteSelectors.forEach(sel => {
//     const el = document.querySelector(sel);
//     if (!el) return;
//     el.addEventListener('paste', (ev) => {
//       ev.preventDefault();
//       Swal.fire({
//         icon: 'info',
//         title: 'Pasting is disabled for security.',
//         timer: 1400,
//         showConfirmButton: false,
//         toast: true,
//         position: 'top'
//       });
//     });
//     el.addEventListener('contextmenu', (ev) => ev.preventDefault());
//     el.addEventListener('keydown', (ev) => {
//       if ((ev.ctrlKey || ev.metaKey) && ev.key.toLowerCase() === 'v') ev.preventDefault();
//     });
//   });

//   // PASSWORD STRENGTH UI (for reset step)
//   const newPass = document.getElementById('newPassword');
//   const confirmPass = document.getElementById('confirmPassword');
//   const resetBtn = document.getElementById('resetBtn');
//   const chLen = document.getElementById('chLen');
//   const chUpper = document.getElementById('chUpper');
//   const chLower = document.getElementById('chLower');
//   const chNumber = document.getElementById('chNumber');
//   const chSpecial = document.getElementById('chSpecial');
//   const strengthFill = document.getElementById('strengthFill');
//   const strengthText = document.getElementById('strengthText');

//   function hasLength(p) { return p.length >= 12; }
//   function hasUpper(p) { return /[A-Z]/.test(p); }
//   function hasLower(p) { return /[a-z]/.test(p); }
//   function hasNumber(p) { return /[0-9]/.test(p); }
//   function hasSpecial(p) { return /[\W_]/.test(p); }

//   function updateStrength() {
//     if (!newPass) return;
//     const p = newPass.value || '';

//     chLen.textContent = (hasLength(p) ? '✅' : '❌') + ' 12+ characters';
//     chUpper.textContent = (hasUpper(p) ? '✅' : '❌') + ' At least 1 uppercase letter';
//     chLower.textContent = (hasLower(p) ? '✅' : '❌') + ' At least 1 lowercase letter';
//     chNumber.textContent = (hasNumber(p) ? '✅' : '❌') + ' At least 1 number';
//     chSpecial.textContent = (hasSpecial(p) ? '✅' : '❌') + ' At least 1 special character';

//     let score = 0;
//     if (hasLength(p)) score++;
//     if (hasUpper(p)) score++;
//     if (hasLower(p)) score++;
//     if (hasNumber(p)) score++;
//     if (hasSpecial(p)) score++;

//     const percent = (score / 5) * 100;
//     if (strengthFill) strengthFill.style.width = percent + '%';

//     if (score <= 1) strengthFill.style.background = '#ff4d4f';
//     else if (score <= 3) strengthFill.style.background = '#ffa940';
//     else strengthFill.style.background = '#52c41a';

//     if (score <= 1) strengthText.textContent = 'Very weak';
//     else if (score <= 3) strengthText.textContent = 'Moderate';
//     else strengthText.textContent = 'Strong';

//     // enable reset only when all rules satisfied and both fields match
//     const allGood = (score === 5) && (confirmPass && newPass.value === confirmPass.value && newPass.value.length > 0);
//     if (resetBtn) resetBtn.disabled = !allGood;
//   }

//   if (newPass) newPass.addEventListener('input', updateStrength);
//   if (confirmPass) confirmPass.addEventListener('input', updateStrength);

//   document.addEventListener('DOMContentLoaded', () => {
//   const newPass = document.getElementById('newPassword');
//   const confirmPass = document.getElementById('confirmPassword');
//   const toggleNew = document.getElementById('toggleNew');
//   const toggleConfirm = document.getElementById('toggleConfirm');

//   // Show / Hide New Password
//   if (toggleNew && newPass) {
//     toggleNew.addEventListener('click', () => {
//       if (newPass.type === 'password') {
//         newPass.type = 'text';
//         toggleNew.textContent = '🙈'; // change icon
//       } else {
//         newPass.type = 'password';
//         toggleNew.textContent = '👁️';
//       }
//     });
//   }

//   // Show / Hide Confirm Password
//   if (toggleConfirm && confirmPass) {
//     toggleConfirm.addEventListener('click', () => {
//       if (confirmPass.type === 'password') {
//         confirmPass.type = 'text';
//         toggleConfirm.textContent = '🙈';
//       } else {
//         confirmPass.type = 'password';
//         toggleConfirm.textContent = '👁️';
//       }
//     });
//   }
// });


//   // If the page started in reset stage, ensure strength computed
//   if (typeof PHP_STAGE !== 'undefined' && PHP_STAGE === 'reset_password') {
//     updateStrength();
//   }

//   // Small UX: focus first visible input
//   setTimeout(() => {
//     const firstInput = document.querySelector('.card input:not([disabled])');
//     if (firstInput) firstInput.focus();
//   }, 150);
// });



// document.addEventListener('DOMContentLoaded',()=>{
//   if(PHP_DONE){
//     Swal.fire({icon:'success',title:'Password reset successful',text:'Redirecting to login...',timer:3000,showConfirmButton:false})
//       .then(()=>window.location.href='login.php');
//     setTimeout(()=>window.location.href='login.php',3500);
//   }
// });
