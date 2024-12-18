let token = localStorage.getItem("token");
let CircleId = localStorage.getItem("circleId");
const membersList = document.getElementById("membersList");
const requestsList = document.getElementById("requestsList");

function getMembersOfCircle() {
   membersList.innerHTML = "<h2>Members :</h2>";
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
        let para1 = document.createElement('p');
        para1.innerHTML = 'there is no members in the circle';
        membersList.appendChild(para1);
        para1.style.color = 'red';
      }
    })
    .then((data) => {
      data.forEach((member) => {
        const memberItem = document.createElement("li");
        memberItem.innerHTML += `
          <span>${member.name}: ${member.brief}</span>
          <button onclick="removeMember(${member.id})" class="remove"><i class="fa-regular fa-circle-xmark"></i></button>
        `;
        membersList.appendChild(memberItem);
      });
    });
}

function removeMember(memberid) {
  let userConfirmed = confirm("Are you sure you want to proceed?");
  if (userConfirmed) {
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
  else {
    alert("Member not removed");
  }
}
function getRequests() {
  requestsList.innerHTML="<h2>Requests :</h2>"
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
        let para = document.createElement('p');
        para.innerHTML += "there are no pending requests.";
        para.style.color = "red";
        requestsList.appendChild(para)
      }
    })
    .then((data) => {
      // requestsList.innerHTML = "";
      data.forEach((request) => {
        const requestItem = document.createElement("li");
        requestItem.innerHTML += `
          <span>${request.username}: ${request.brief}</span>
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
      membersList.innerHTML = "";
      requestsList.innerHTML = "";
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
  let userConfirmed2 = confirm("Are you sure you want to proceed?");
  if (userConfirmed2) {
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
        alert("Internal server error", response.body);
      }
    }).then(data => {
      console.log(data);
      window.location = 'webSiteHomePage.html';
    })
  }
  else {
    alert("Circle not deleted");
  }
}

document.addEventListener("DOMContentLoaded", () => {
  getMembersOfCircle();
  getRequests();
});
