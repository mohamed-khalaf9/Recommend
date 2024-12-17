// let members = document.getElementById("members");
// let text = "<h2>Members:</h2>";
// for (let i = 0; i < 8; i++) {
//   text += `<div class="item${i}">
//             <span class="span${i}">member ${i+1}</span>
//             <button class="remove"><i class="fa-regular fa-circle-xmark"></i></button>
//           </div>`;
// }
// members.innerHTML = text;

// let requests = document.getElementById("requests");
// let text2 = `<h2>Requests:</h2>`;
// for (let i = 0; i < 8; i++) {
//   text2 += `<div class="item">
//             <span>Request x</span>
//             <button class="accept"><i class="fa-regular fa-circle-check"></i></button>
//             <button class="reject"><i class="fa-regular fa-circle-xmark"></i></button>
//           </div>`;
// }
// requests.innerHTML = text2;

// const remove = document.querySelectorAll('.remove');

// remove.forEach(element => {
//   element.addEventListener('click', () => {
//     let parent = element.parentNode
//     parent.remove()
//   })
// });
let token = localStorage.getItem('token');
let circleId = localStorage.getItem("circleID");
function getMembersOfCircle() {
  let url = `http://localhost/Recommend/backend/members/${circleId}`;
  fetch(url, {
    method: 'GET',
    headers: {
      Authorization: `Bearer ${token}`
    }
  }).then(response => {
    if (response.ok) return response.json()
    else {
      let membersList = document.getElementById('members')
      membersList.innerHTML = 'No members joined to this circle.';
      membersList.style.color='red'
    }
  }).then(data => {
    data.forEach(member => {
      let text = "";
        text += ` <li>
                   <span>${member.name}</span>
                    <button class="remove">
                      <i class="fa-regular fa-circle-xmark"></i>
                    </button>
                  </li>`;
    })
    let membersList = document.getElementById("members");
    membersList.innerHTML = text;
  }).catch(error=>{
    console.log(error);
  })
}

function getPendingRequests() {
  let url = `http://localhost/Recommend/backend/requests/${circleId}`;
  fetch(url, {
    Method: 'GET',
    Headers:
    {
      Authorization: `${token}`,
    }
  }).then(response => {
    if (response.ok) return response.json();
    else {
      let requestsList = document.getElementById("requests");
      requestsList.innerHTML = "there are no pending requests.";
      requestsList.style.color = "red";
    }
  }).then(data => {
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
  })
}
document.addEventListener('DOMContentLoaded', () => {
  getMembersOfCircle()
})