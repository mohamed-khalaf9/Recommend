function joinCircle() {
    const circleIdInput = document.querySelector(".join-inp");
    const circleId = circleIdInput.value.trim();

    if (!circleId || isNaN(circleId)) {
        alert("Please enter a valid Circle ID.");
        return;
    }

    const url = `http://localhost/Recommend/backend/requests/${circleId}`;
    const token = localStorage.getItem('token');

    fetch(url, {
        method: "POST",
        headers: {
            'Content-Type': 'application/json',
            'Authorization': `Bearer ${token}`
        },
        body: null
    })
        .then((response) => {
            if (response.ok) {
                return response.json();
            } else {
                return response.json().then((error) => {
                    throw new Error(error.message || "Something went wrong.");
                });
            }
        })
        .then((data) => {
            alert(data.message || "Join circle request created successfully.");
        })
        .catch((error) => {
            alert(error.message || "An error occurred. Please try again.");
        });
}
