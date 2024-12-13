document.addEventListener("DOMContentLoaded", () => {
    const form = document.querySelector("form");
    const emailInput = document.querySelector(".emaill");
    const passwordInput = document.querySelector(".pass");
    const submitButton = document.querySelector(".btn");
    localStorage.clear();

    // Create a message element dynamically
    const messageDiv = document.createElement("div");
    messageDiv.style.color = "red";
    messageDiv.style.marginTop = "10px";
    submitButton.parentNode.insertBefore(messageDiv, submitButton.nextSibling);

    async function handleLogin(event) {
        event.preventDefault(); // Prevent default form submission

        // Collect form data
        const formData = {
            email: emailInput.value.trim(),
            password: passwordInput.value.trim(),
        };

        // Validate form inputs
        if (!validateForm(formData)) {
            return;
        }

        // Call the login API
        try {
            const response = await fetch("http://localhost/Recommend/backend/users", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                },
                body: JSON.stringify(formData),
            });

            const responseData = await response.json();

            if (response.ok) {
                // Store the token in localStorage
                if (responseData.token) {
                    localStorage.setItem("token", responseData.token);
                }

                messageDiv.style.color = "green";
                messageDiv.textContent = responseData.message || "Login successful!";
             
                setTimeout(() => {
                    window.location.href = "webSiteHomePage.html"; // Redirect to the home page
                }, 2000);
            } else {
                messageDiv.style.color = "red";
                messageDiv.textContent = responseData.message || "Invalid login credentials.";
            }
        } catch (error) {
            console.error("Error during login:", error);
            messageDiv.style.color = "red";
            messageDiv.textContent = "Failed to connect to the server. Please try again.";
        }
    }

    function validateForm(data) {
        let isValid = true;

        // Validate email
        const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
        if (!emailRegex.test(data.email)) {
            messageDiv.textContent = "Invalid email format.";
            isValid = false;
        }

        // Validate password
        if (data.password.length < 6 || data.password.length > 12) {
            messageDiv.textContent = "Password must be between 6 and 12 characters.";
            isValid = false;
        }

        return isValid;
    }

    // Attach the event listener to the form
    form.addEventListener("submit", handleLogin);
});
