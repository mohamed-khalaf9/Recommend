

function frame1Loaded() {
    try {
        // Accessing content inside frame1
        var frame1 = window.frames['col1'];
        if (frame1) {
            var requestsDiv = frame1.document.querySelector('.requsts');
            if (requestsDiv) {
                fetchRequests(requestsDiv); // Assuming fetchRequests is defined
            } else {
                console.error("Requests div not found in frame1");
            }
        }
    } catch (error) {
        console.error("Error accessing frame1:", error);
    }
}

function frame2Loaded() {
    try {
        // Accessing content inside frame2
        var frame2 = window.frames['col2'];
        if (frame2) {
            var circlesDiv = frame2.document.querySelector('.mycircles');
            if (circlesDiv) {
                fetchCircles(circlesDiv); // Assuming fetchCircles is defined
            } else {
                console.error("Circles div not found in frame2");
            }
        }
    } catch (error) {
        console.error("Error accessing frame2:", error);
    }
}
// Function to handle fetching and displaying join requests in frame 1


// Function to get the correct icon based on the request status
function fetchRequests(requestsDiv) {
    const token = localStorage.getItem("token");

    if (!requestsDiv) {
        console.error("Element with class .requests not found!");
        return;
    }

    // Clear previous content
    requestsDiv.innerHTML = "";

    if (!token) {
        const errorMessage = document.createElement("p");
        errorMessage.textContent = "You are not logged in. Please log in to see join requests.";
        errorMessage.style.color = "red";
        requestsDiv.appendChild(errorMessage);
        return;
    }

    fetch("http://localhost/Recommend/backend/requests", {
        method: "GET",
        headers: { Authorization: `Bearer ${token}` },
    })
        .then((response) =>
            response.json().then((data) => ({ status: response.status, body: data })))
        .then(({ status, body }) => {
            if (status >= 200 && status < 300) {
                if (body.length === 0) {
                    const noRequestsMessage = document.createElement("p");
                    noRequestsMessage.textContent = "You have no pending join requests.";
                    noRequestsMessage.style.color = "gray";
                    requestsDiv.appendChild(noRequestsMessage);
                } else {
                    body.forEach((request) => {
                    
                        let text = `
                        <div class="request-item" style="border: 1px solid #ccc; padding: 10px; margin-bottom: 10px; border-radius: 5px;">
                            <div class="circle ${request.status}" style="display: inline-block; margin-right: 10px; text-align: center;">
                                <h5 style="margin: 0; color: #333;">${request.circleName}</h5>
                                <i class="fa ${getIconClass(request.status)}" style="font-size: 24px;"></i>
                            </div>
                            <p style="margin: 5px 0; color: #555;">${request.circleDesc}</p>
                        </div>
                    `;
                    
                    // Create a temporary div element to parse the HTML string
                    let tempDiv = document.createElement('div');
                    tempDiv.innerHTML = text;
                    
                    // Append the first child (the newly created element) to the requestsDiv
                    requestsDiv.appendChild(tempDiv.firstElementChild);
                    
                    });
                }
            } else {
                const errorMessage = document.createElement("p");
                errorMessage.textContent =
                    body.message || "An error occurred while fetching requests.";
                errorMessage.style.color = "red";
                requestsDiv.appendChild(errorMessage);
            }
        })
        .catch((error) => {
            const errorMessage = document.createElement("p");
            errorMessage.textContent = `Error: ${error.message}`;
            errorMessage.style.color = "red";
            requestsDiv.appendChild(errorMessage);
        });
}

// Helper function to get the appropriate icon class based on the status
function getIconClass(status) {
    switch (status) {
        case "Approved":
            return "fa-circle-check";
        case "Rejected":
            return "fa-circle-xmark";
        case "Pending":
            return "fa-spinner fa-spin";
        default:
            return "fa-circle-question";
    }
}







// Function to handle fetching and displaying circles in frame 2
function fetchCircles(myCirclesDiv) {
    const token = localStorage.getItem("token");

    if (!myCirclesDiv) {
        console.error("Element with the specified class not found!");
        return;
    }

    // Clear any existing content before adding new data or error message
    myCirclesDiv.innerHTML = '';

    if (!token) {
        const errorMessage = document.createElement("p");
        errorMessage.textContent = "You are not logged in. Please log in to see your circles.";
        errorMessage.style.color = "red";
        myCirclesDiv.appendChild(errorMessage);
        return;
    }

    fetch("http://localhost/Recommend/backend/circles", {
        method: "GET",
        headers: { Authorization: `Bearer ${token}` },
    })
        .then((response) => {
            if (response.status === 404) {
                // Handle 404 without parsing JSON
                return { status: 404, body: null };
            }
            if (!response.ok) {
                // Throw an error for any other non-200 response
                throw new Error(`HTTP error! Status: ${response.status}`);
            }
            // Parse JSON for 200 responses
            return response.json().then((data) => ({ status: response.status, body: data }));
        })
        .then(({ status, body }) => {
            if (status === 404 || (status === 200 && body.length === 0)) {
                const noCirclesMessage = document.createElement("p");
                noCirclesMessage.textContent = "You haven't joined any circles at the moment.";
                noCirclesMessage.style.color = "gray";
                myCirclesDiv.appendChild(noCirclesMessage);
            } else if (status === 200) {
                body.forEach((circle) => {
                    const circleContainer = document.createElement("div");
                    circleContainer.classList.add("circle-container");

                    // Create styled circle button
                    const circleButton = document.createElement("button");
                    circleButton.textContent = circle.name;
                    circleButton.title = circle.desc;
                    circleButton.classList.add("circle-button");

                    // Add crown icon if user is admin
                    if (circle.role && circle.role.toLowerCase() === "admin") {
                        const goldKingIcon = document.createElement("span");
                        goldKingIcon.classList.add("gold-king-icon");
                        goldKingIcon.textContent = "👑";
                        circleButton.appendChild(goldKingIcon);
                    }

                    circleContainer.appendChild(circleButton);

                    // Add description without the word "Description" and apply styles
                    const circleDesc = document.createElement("p");
                    circleDesc.textContent = circle.desc;
                    circleDesc.style.color = "dark";  // Adjust color as needed to match website
                    circleContainer.appendChild(circleDesc);

                    // Add ID with styling
                    if (circle.id) {
                        const circleId = document.createElement("p");
                        circleId.textContent = `ID: ${circle.id}`;
                        circleId.style.color = "dark";  // Adjust color as needed to match website
                        circleContainer.appendChild(circleId);
                    }

                    // Add click event to store circle id and role in localStorage
                    circleButton.addEventListener("click", function () {
                        if (localStorage.getItem("circleId")) {
                            localStorage.removeItem("circleId");
                        }
                        localStorage.setItem("circleId", circle.id);
                        if (localStorage.getItem("role")) {
                            localStorage.removeItem("role");
                        }
                        localStorage.setItem("role", circle.role);

                        window.open('circleHomePage.html')
                    });

                    myCirclesDiv.appendChild(circleContainer);
                });
            }
        })
        .catch((error) => {
            const errorMessage = document.createElement("p");
            errorMessage.textContent = `Error: ${error.message}`;
            errorMessage.style.color = "red";
            myCirclesDiv.appendChild(errorMessage);
        });
}



async function createCircle() {
    // Collect the form data
    const name = document.getElementById('name-inp').value;
    const desc = document.getElementById('desc-inp').value;

    // Get the current date (curdate)
    const currentDate = new Date();
    const date = currentDate.toISOString().split('T')[0];  // Format as yyyy-mm-dd

    // Create the payload
    const payload = {
        name: name,
        desc: desc,
        date: date  // Current date automatically added
    };

    // Define the request options
    const token = localStorage.getItem('token');  // Assuming token is stored in localStorage
    const headers = {
        'Content-Type': 'application/json',
        'Authorization': `Bearer ${token}`
    };

    // Make the POST request to the backend
    try {
        const response = await fetch('http://localhost/Recommend/backend/circles', {
            method: 'POST',
            headers: headers,
            body: JSON.stringify(payload)
        });

        // Handle the response
        const result = await response.json();
        const messageContainer = document.getElementById('response-message');
        if (response.ok) {
            // Success case
            messageContainer.innerHTML = `<p style="color: green;">${result.message}</p>`;
            // Optionally fetch the updated circles list or handle other actions
            this.frame2Loaded();
        } else {
            // Error case
            messageContainer.innerHTML = `<p style="color: red;">${result.message || 'Internal server error'}</p>`;
        }
    } catch (error) {
        console.error('Error:', error);
        const messageContainer = document.getElementById('response-message');
        messageContainer.innerHTML = `<p style="color: red;">An error occurred. Please try again later.</p>`;
    }
}

