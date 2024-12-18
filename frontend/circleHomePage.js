let role = localStorage.getItem("role");
let token = localStorage.getItem("token");
let circleId = localStorage.getItem("circleId");

function circleInfoAndRole() {
  let url = `http://localhost/Recommend/backend/circles/${circleId}`;
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
        throw new Error(`there is an error with status: ${response.status}`);
      }
    })
    .then((data) => {
      let info = document.getElementById("circleInfo");
      info.innerHTML = data.name;
      let admin = document.getElementById("left");
      let leave = document.getElementById("leave");
      if (role != "Admin") {
        admin.style.display = "none";
      } else {
        leave.style.display = "none";
      }
    })
    .catch((error) => {
      console.error("Error fetching circle info:", error);
    });
}

function validateLink(link) {
  try {
    new URL(link);
    return true;
  } catch (e) {
    return false;
  }
}

function addRecommendation() {
  let add = document.getElementById("add");
  let create = document.getElementById("create");
  add.addEventListener("click", () => {
    create.style.display = "block";
  });

  let close = document.getElementById("cl");
  close.addEventListener("click", () => {
    create.style.display = "none";
  });

  document.getElementById("share2").addEventListener("click", () => {
    let Title = document.getElementById("title").value;
    let Brief = document.getElementById("brief").value;
    let Link = document.getElementById("link").value;
    let year = new Date().getFullYear();
    let month = new Date().getMonth() + 1;
    let day = new Date().getDate();
    let status = document.getElementById("inputStatus");

    if (!validateLink(Link) || !Brief || !Title) {
      status.innerHTML = "There is something wrong with the inputs";
      status.style.color = "red";
    } else {
      let recommendation = {
        title: Title,
        brief: Brief,
        link: Link,
        date: `${year}-${month}-${day}`,
      };

      let url = `http://localhost/Recommend/backend/recommendations/${circleId}`;

      fetch(url, {
        method: "POST",
        headers: {
          "Content-Type": "application/json",
          Authorization: `Bearer ${token}`,
        },
        body: JSON.stringify(recommendation),
      })
        .then((response) => {
          if (response.ok) {
            return response.json();
          } else {
            return response.json().then((data) => {
              throw new Error(`Error: ${response.status}, ${data.message}`);
            });
          }
        })
        .then((data) => {
          console.log("Success:", data);
          create.style.display = "none";
          getRecommendations(); // Refresh the recommendations
        })
        .catch((error) => {
          console.error("Error creating recommendation:", error);
          status.innerHTML = "Error creating recommendation.";
          status.style.color = "red";
        });
    }
  });
}

function getRecommendations() {
  let url = `http://localhost/Recommend/backend/recommendations/${circleId}`;
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
        let recoms = document.getElementById("mid");
        recoms.innerHTML = `There are no available recommendations right now.`;
        recoms.style.backgroundColor = "white";
        recoms.style.color = "red";
      }
    })
    .then((data) => {
      let text = "";
      data.forEach((recommendation) => {
        let likes = recommendation.numberOfLikes;
        if (likes == null) {
          likes = 0;
        }
        text += `
        <div class="recoms" id="recoms-${recommendation.id}" >
        <div class="content" >
            <h2> ${recommendation.username}  </h2> <br>
            <h3 class="title">
            ${recommendation.title}
            </h3>
            <p class="desc">${recommendation.desc} </p>
        </div>
        <div class="actions">
            <button class="like" data-id="${recommendation.id}">
              <i class="fa-regular fa-thumbs-up" ></i>
            </button>
            <p class="counter" id="counter-${recommendation.id}" >${likes}</p>
           <button class="link" data-link="${recommendation.link}" 
        onclick="copyLink('${recommendation.link}')">
        <i class="fa-regular fa-copy"></i>
       </button>

          </div>
        </div>`;
      });
      let mid = document.getElementById("mid");
      mid.innerHTML = text;
      addLikeListeners();
      addCopyLinkListeners();
    })
    .catch((error) => {
      console.error("Error fetching recommendations:", error);
    });
}
function addLikeListeners() {
  let recommendations = document.querySelectorAll(".recoms");
  recommendations.forEach((recommendation) => {
    recommendation.addEventListener("click", function (event) {
      if (event.target && event.target.closest(".like")) {
        let button = event.target.closest(".like");
        let recommendationId = button.getAttribute("data-id");
        incrementLike(recommendationId);
      }
    });
  });
}

function incrementLike(recommendationId) {
  let counter = document.getElementById(`counter-${recommendationId}`);
  let url = `http://localhost/Recommend/backend/likes/${recommendationId}`;
  fetch(url, {
    method: "POST",
    headers: {
      Authorization: `Bearer ${token}`,
    },
  })
    .then((response) => {
      if (response.status === 201) {
        return response.json();
      } else {
        return response.json().then((data) => {
          throw new Error(`Error: ${response.status}, ${data.message}`);
        });
      }
    })
    .then((data) => {
      console.log("Like count updated successfully:", data);
      getRecommendations();
    })
    .catch((error) => {
      console.error("Error updating like count:", error);
      alert("you are already liked this recommendation");
    });
}

function addCopyLinkListeners() {
  document.getElementById("mid").addEventListener("click", function (event) {
    if (event.target && event.target.closest(".link")) {
      let button = event.target.closest(".link");
      let link = button.getAttribute("data-link");
      copyToClipboard(link);
    }
  });
}

function copyToClipboard(link) {
  navigator.clipboard
    .writeText(link)
    .then(() => {
      alert("Link copied to clipboard!");
    })
    .catch((err) => {
      console.error("Could not copy link: ", err);
    });
}

function leaveCircle() {
  let confirmation = confirm('You are about to leave');
  if (confirmation) {
    let url = `http://localhost/Recommend/backend/members/${circleId}`;
    fetch(url, {
      method: 'DELETE',
      headers: {
        Authorization: `Bearer ${token}`
      }
    }).then(response => {
      if (response.ok) {
        return response.json()
      }
      else {
        alert("circle deleted successfully");
      }
    }).then(data => {
      console.log(data);
      window.location = 'webSiteHomePage.html'
    })
  }
  else {
    alert('You are stil in the Circle');
  }
}

document.addEventListener("DOMContentLoaded", () => {
  circleInfoAndRole();
  addRecommendation();
  getRecommendations();
  addLikeListeners();
});
