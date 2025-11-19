
//   // ==================SHOW PASSWORD AND RE-ENTER_PASSWORD================
// // ================== TOGGLE PASSWORD / RE-ENTER PASSWORD =================
// function togglePassword(fieldId, iconElement) {
//   const input = document.getElementById(fieldId);

//   if (input.type === "password") {
//     input.type = "text";             // show password
//     iconElement.textContent = "🙈";  // change emoji while visible
//   } else {
//     input.type = "password";         // hide password
//     iconElement.textContent = "👁️"; // back to eye
//   }
// }

//  // ================== TOGGLE PASSWORD / SECURITY ANSWERS =================
// function togglePassword(fieldId, iconElement) {
//   const input = document.getElementById(fieldId);

//   if (input.type === "password") {
//     input.type = "text";             // show answer
//     iconElement.textContent = "🙈";  // change emoji while visible
//   } else {
//     input.type = "password";         // hide answer
//     iconElement.textContent = "👁️"; // back to eye
//   }
// }





// // Toggle password visibility
// function togglePassword(fieldId, iconElem) {
//   const field = document.getElementById(fieldId);
//   field.type = field.type === "password" ? "text" : "password";
//   iconElem.textContent = field.type === "password" ? "👁️" : "🙈";
// }

// // Password Strength Checker
// function checkPasswordStrength() {
//   const password = document.getElementById("password").value;
//   const strengthText = document.getElementById("strengthText");
//   const strengthBar = document.getElementById("strengthBar");

//   let strength = 0;

//   if (password.length >= 6) strength++;
//   if (password.length >= 10) strength++;
//   if (/[A-Z]/.test(password)) strength++;
//   if (/[0-9]/.test(password)) strength++;
//   if (/[^A-Za-z0-9]/.test(password)) strength++;

//   const strengths = ["Very Weak", "Weak", "Moderate", "Strong", "Very Strong"];
//   const colors = ["red", "orange", "yellow", "blue", "green"];
//   const width = ["20%", "40%", "60%", "80%", "100%"];

//   strengthText.textContent = strengths[strength - 1] || "";
//   strengthBar.style.width = width[strength - 1] || "0%";
//   strengthBar.style.background = colors[strength - 1] || "transparent";
// }

// // Re-enter Password Check
// function checkMatchPassword() {
//   const pass = document.getElementById("password").value;
//   const repass = document.getElementById("repassword").value;
//   const matchText = document.getElementById("matchText");

//   if (!repass) {
//     matchText.textContent = "";
//     return;
//   }

//   if (pass === repass) {
//     matchText.textContent = "✅ Passwords match";
//     matchText.style.color = "green";
//   } else {
//     matchText.textContent = "❌ Passwords do not match";
//     matchText.style.color = "red";
//   }
// }

// // =================================REGISTERED FUNCTION POPUP===================================

// document.addEventListener("DOMContentLoaded", () => {
//   const form = document.getElementById("registerForm");
//   if (!form) return;

//   const requiredFields = [
//     "first_name", "last_name", "sex", "birthdate", "age",
//     "street", "barangay", "city", "province", "country",
//     "zip", "email", "username", "password", "repassword",
//     "a1_question", "a1_answer",
//     "a2_question", "a2_answer",
//     "a3_question", "a3_answer"
//   ];

//   // ✅ Auto-remove red highlight once user enters valid input
//   requiredFields.forEach(name => {
//     const field = form.elements[name];
//     if (!field) return;

//     const removeInvalid = () => {
//       if (field.value.trim() !== "") {
//         field.classList.remove("is-invalid");
//         field.classList.add("is-valid");
//       }
//     };

//     // For text/password inputs
//     field.addEventListener("input", removeInvalid);

//     // For dropdown/select & date
//     field.addEventListener("change", removeInvalid);
//   });

//   // ✅ On submit
//   form.addEventListener("submit", async (e) => {
//     e.preventDefault();

//     let hasEmpty = false;

//     // ❌ Check required fields if empty
//     requiredFields.forEach(name => {
//       const field = form.elements[name];
//       if (!field) return;

//       if (field.value.trim() === "") {
//         hasEmpty = true;
//         field.classList.add("is-invalid");
//         field.classList.remove("is-valid");
//       } else {
//         field.classList.remove("is-invalid");
//         field.classList.add("is-valid");
//       }
//     });

//     if (hasEmpty) {
//       echo("❌ Please fill out all required fields.");
//       return;
//     }

//     // 🔐 Password match check
//     const password = form.elements["password"].value.trim();
//     const repassword = form.elements["repassword"].value.trim();

//     if (password !== repassword) {
//       alert("❌ Passwords do not match!");
//       form.elements["password"].classList.add("is-invalid");
//       form.elements["repassword"].classList.add("is-invalid");
//       form.elements["password"].focus();
//       return;
//     }

//     // ✅ If all good, submit via fetch
//     const formData = new FormData(form);

//     try {
//       const res = await fetch("", { method: "POST", body: formData });
//       const data = await res.json();

//       if (data.success) {
//         alert(`✅ Registration Successful!\nYour ID: ${data.id_number}`);
//         form.reset();

//         // Reset green highlights after reset
//         requiredFields.forEach(name => {
//           const field = form.elements[name];
//           if (field) field.classList.remove("is-valid", "is-invalid");
//         });

//         // Set ID for next registration
//         document.getElementById("id_number").value = data.id_number;

//         // Redirect after successful registration
//         window.location.href = "login.php";

//       } else {
//         alert(`❌ Registration Failed!\n${data.message}`);
//       }

//     } catch (err) {
//       console.error(err);
//       alert("❌ An unexpected error occurred.");
//     }
//   });
// });
