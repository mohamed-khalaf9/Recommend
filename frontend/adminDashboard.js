let token = localStorage.getItem("token");
let CircleId = localStorage.getItem("circleId");
const membersList = document.getElementById("members");
const requestsList = document.getElementById("requests");

function getMembersOfCircle() {
  fetch(`http://localhost/Recommend/backend/members/${CircleId}`, {
    method: "GET",
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })
    .then((response) => {
      if (response.ok) return response.json();
      else {
        console.log(response.status);
        membersList.innerHTML = "there is no members in the circle"
        membersList.style.color = 'red';
      }
    })
    .then((data) => {
      membersList.innerHTML = "";
      data.forEach((member) => {
        const memberItem = document.createElement("li");
        memberItem.innerHTML = `
          <span>${member.name}</span>
          <button onclick="removeMember(${member.id})" class="remove"><i class="fa-regular fa-circle-xmark"></i></button>
        `;
        membersList.appendChild(memberItem);
      });
    });
}
function removeMember(memberid) {
  let url = `http://localhost/Recommend/backend/members/${memberid}`;
  fetch(url, {
    method: "DELETE",
    headers: {
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify({ circleId: `${CircleId}` }),
  })
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        alert("Internal server error");
        console.log(response.status);
      }
    })
    .then((data) => {
      console.log(data);
      getMembersOfCircle()
    })
    .catch((error) => {
      console.error("Fetch error:", error);
    });
}
function getRequests() {
  let url = `http://localhost/Recommend/backend/requests/${CircleId}`;
  fetch(url, {
    method: "GET",
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })
    .then((response) => {
      if (response.ok) {
        return response.json();
      } else {
        requestsList.innerHTML = "there are no pending requests.";
        requestsList.style.color = "red";
      }
    })
    .then((data) => {
      requestsList.innerHTML = "";
      data.forEach((request) => {
        const requestItem = document.createElement("li");
        requestItem.innerHTML = `
          <span>${request.username}</span>
            <button class="accept" onclick="approveRequest(${request.requestId})"><i class="fa-regular fa-circle-check"></i></button>
            <button class="reject" onclick="rejectRequest(${request.requestId})"><i class="fa-regular fa-circle-xmark"></i></button>
        `;
        requestsList.appendChild(requestItem);
      });
    });
}

function approveRequest(requestId) {
  let url = `http://localhost/Recommend/backend/requests/${requestId}`;
  fetch(url, {
    method: "Put",
    headers: {
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify({ status: "approved" }),
  })
    .then((response) => {
      if (response.ok) {
        alert("request accepted and member added successfully to the circle");
        return response.json();
      } else {
        alert("Internal server error");
      }
    })
    .then((data) => {
      console.log(data);
      getRequests();
      getMembersOfCircle();
    });
}

function rejectRequest(requestId) {
  let url = `http://localhost/Recommend/backend/requests/${requestId}`;
  fetch(url, {
    method: "Put",
    headers: {
      Authorization: `Bearer ${token}`,
    },
    body: JSON.stringify({ status: "rejected" }),
  })
    .then((response) => {
      if (response.ok) {
        alert("request rejected successfully");
        return response.json();
      } else {
        alert("Internal server error");
      }
    })
    .then((data) => {
      console.log(data);
      getRequests();
    });
}

function deleteCircle() {
  let url = `http://localhost/Recommend/backend/circles/${CircleId}`;
  fetch(url, {
    method: 'DELETE',
    headers: {
      Authorization: `Bearer ${token}`,
    },
  }).then(response => {
    if (response.ok) {
      alert("circle deleted successfully");
    }
    else {
      alert("Internal server error",response.body);
    }
  }).then(data => {
    console.log(data);
    window.location = 'webSiteHomePage.html';
  })
}

document.addEventListener("DOMContentLoaded", () => {
  getMembersOfCircle();
  getRequests();
});
