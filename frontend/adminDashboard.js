let token = localStorage.getItem("token");
let circleId = localStorage.getItem("circleID");
function getMembersOfCircle() {
  let url = `http://localhost/Recommend/backend/members/${circleId}`;
  fetch(url, {
    method: "GET",
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })
    .then((response) => {
      if (response.ok) return response.json();
      else {
        let membersList = document.getElementById("members");
        membersList.innerHTML = "No members joined to this circle.";
        membersList.style.color = "red";
      }
    })
    .then((data) => {
      data.forEach((member) => {
        let text = "";
        text += ` <li>
                   <span>${member.name}</span>
                    <button class="remove" onclick="removeMember(${memberId})">
                      <i class="fa-regular fa-circle-xmark"></i>
                    </button>
                  </li>`;
      });
      let membersList = document.getElementById("members");
      membersList.innerHTML = text;
    })
    .catch((error) => {
      console.log(error);
    });
}

function getPendingRequests() {
  let url = `http://localhost/Recommend/backend/requests/${circleId}`;
  fetch(url, {
    Method: "GET",
    Headers: {
      Authorization: `${token}`,
    },
  })
    .then((response) => {
      if (response.ok) return response.json();
      else {
        let requestsList = document.getElementById("requests");
        requestsList.innerHTML = "there are no pending requests.";
        requestsList.style.color = "red";
      }
    })
    .then((data) => {
      data.forEach((request) => {
        let text = "";
        text += ` <li class="item">
            <span>Request x</span>
            <button class="accept">
              <i class="fa-regular fa-circle-check"></i>
            </button>
            <button class="reject">
              <i class="fa-regular fa-circle-xmark"></i>
            </button>
          </li>`;
      });
      let membersList = document.getElementById("members");
      membersList.innerHTML = text;
    });
}

function removeMember(memberId) {
  fetch(`http://localhost/Recommend/backend/members/${memberId}`, {
    method: "DELETE",
    headers: {
      Authorization: token,
      "Content-Type": "application/json",
    },
    body: JSON.stringify({ circleId }),
  })
    .then((response) => response.json())
    .then((data) => {
      if (response.ok) {
        alert(data.message);
        fetchMembers();
      } else {
        alert("Error: " + data.message);
      }
    })
    .catch((error) => console.error("Error removing member:", error));
}


document.addEventListener("DOMContentLoaded", () => {
  getMembersOfCircle();
});
