document.addEventListener("DOMContentLoaded", function() {
    // Get the token from localStorage (assuming it's stored there after login)
    const token = localStorage.getItem("token");

    // Check if the token exists
    if (!token) {
        alert("You are not logged in. Please log in to view your profile.");
        window.location.href = "login.html"; // Redirect to login page if no token
        return;
    }

    // Fetch the user profile data
    fetch("http://localhost/Recommend/backend/users", {
        method: "GET",
        headers: {
            "Authorization": `Bearer ${token}`
        }
    })
    .then(response => {
        if (!response.ok) {
            throw new Error("Failed to fetch profile data");
        }
        return response.json();
    })
    .then(data => {
        // Check if the response body is correct
        if (data && data.id) {
            // Populate the table with the profile data
            document.querySelector(".table table").innerHTML = `
                <tr>
                    <td>Name:</td>
                    <td>${data.name}</td>
                </tr>
                <tr>
                    <td>Email:</td>
                    <td>${data.email}</td>
                </tr>
                <tr>
                    <td>Education:</td>
                    <td>${data.education}</td>
                </tr>
                <tr>
                    <td>Brief:</td>
                    <td>${data.brief}</td>
                </tr>
            `;
        } else {
            alert("Failed to load profile data.");
        }
    })
    .catch(error => {
        console.error("Error fetching profile info:", error);
        alert("There was an error loading your profile. Please try again later.");
    });
});
