document.addEventListener("DOMContentLoaded", () => {
    const form = document.getElementById("sign-in-form");
    const nameInput = document.getElementById("my-name");
    const emailInput = document.getElementById("my-email");
    const passwordInput = document.getElementById("my-pass");
    const educationInput = document.getElementById("college");
    const briefInput = document.getElementById("brief");
  
    async function handleSignUp(event) {
      event.preventDefault(); // Prevent default form submission
  
      // Collect form data
      const formData = {
        name: nameInput.value.trim(),
        email: emailInput.value.trim(),
        password: passwordInput.value.trim(),
        education: educationInput.value.trim(),
        brief: briefInput.value.trim(),
        date: new Date().toISOString().split("T")[0], // Format as YYYY-MM-DD
      };
  
      // Validate form inputs
      if (!validateForm(formData)) {
        return;
      }
  
      // Call the signup API
      try {
        const response = await fetch("http://localhost/Recommend/backend/users", {
          method: "POST",
          headers: {
            "Content-Type": "application/json",
          },
          body: JSON.stringify(formData),
        });
  
        if (response.status === 201) {
          const responseData = await response.json();
          alert(responseData.message || "User registered successfully");
          form.reset(); // Clear the form
  
          // Redirect to login.html
          window.location.href = "login.html";
        } else if (response.status === 500) {
          const errorData = await response.json();
          alert(errorData.message || "Email already existed");
        } else {
          alert("Unexpected error occurred. Please try again later.");
        }
      } catch (error) {
        console.error("Error during signup:", error);
        alert(
          "Failed to connect to the server. Please check your network and try again."
        );
      }
    }
  
    function validateForm(data) {
      let isValid = true;
      let nameError = document.getElementById("nameError");
      let emailError = document.getElementById("emailError");
      let passwordError = document.getElementById("passwordError");
      let briefError = document.getElementById("briefError");
      let eduError = document.getElementById("eduError");
      emailError.innerHTML = "";
      nameError.innerHTML = "";
      passwordError.innerHTML = "";
      briefError.innerHTML = "";
      eduError.innerHTML = "";
      // Validate name
      if (data.name.length == 0) {
          nameError.innerHTML = "*Required";
          isValid = false;
      } else if (data.name.length < 4) {
         nameError.innerHTML = "*Name must be between 4 and 15 characters.";
         isValid = false;
      } 
      else if (data.name.length > 15) {
        nameError.innerHTML = "*Name must be between 4 and 15 characters.";
        isValid = false;
      } else {
        nameError.innerHTML = "";
      }
  
      // Validate email
      const emailRegex = /^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/;
      if (data.email.length == 0) {
        emailError.innerHTML = "*Required";
        isValid = false;
      } else if (!emailRegex.test(data.email)) {
        
          emailError.innerHTML = "*Invalid email format.";
          isValid = false;
      } else {
        emailError.innerHTML = "";
      }
  
      // Validate password
      if (data.password.length == 0) {
          passwordError.innerHTML = "*Required";
          isValid=false
      } else if (data.password.length < 6) {
        passwordError.innerHTML ="*Password must be between 6 and 12 characters.";
        isValid = false;
      } else if (data.password.length > 12) {
        passwordError.innerHTML ="*Password must be between 6 and 12 characters.";
        isValid = false;
        }
      else {
          passwordError.innerHTML = "";
        }
      // Validate education
      if (!data.education) {
        eduError.innerHTML = "*Education field is required.";
        isValid = false;
      }
  
      // Validate brief
      if (!data.brief) {
        briefError.innerHTML = "*Brief field is required.";
        isValid = false;
      }
  
      return isValid;
    }
  
    // Attach the event listener to the form
    form.addEventListener("submit", handleSignUp);
  });