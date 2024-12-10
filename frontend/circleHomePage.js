const rec = document.getElementById("mid");
var txt = "";
var object = {
  id: 1,
  desc: "A great resource to get started with AI concepts.",
  link: "https://www.facebook.com",
  likesnum: 125,
};
for (let i = 0; i < 10; i++) {
  txt += `<div class="recoms" id="${object.id}" ><div class="content"><h1>Someone</h1><p class="desc">This recommendation about: ${object.desc}</p><button class="link" ><i class="fa-solid fa-link"></i></button> <button class="like"> <i class="fa-regular fa-thumbs-up"></i></button><p class="counter">${object.likesnum}</p></div></div>`;
  txt += "<br/>";
}
rec.innerHTML = txt;
let btn = document.getElementById("add");
btn.addEventListener("click", () => {
  let crea = document.getElementById("create");
  crea.style.display = "block";
});

let btns = document.getElementsByClassName("like");
let paras = document.getElementsByClassName("counter");
for (let i = 0; i < btns.length; i++) {
  btns[i].addEventListener("click", () => {
    let cnt = paras[i].innerHTML;
    let num = parseInt(cnt);
    num++;
    paras[i].innerHTML = num;
    object.likesnum = num;
    let parent = btns[i].parentNode;
    let id = parent.parentNode.id;
    alert(`this recommendation id = ${id}`);
  });
}

let links = document.getElementsByClassName("link");
for (let i = 0; i < links.length; i++) {
  links[i].addEventListener("click", () => {
    navigator.clipboard
      .writeText(object.link)
      .then(() => {
        alert("Link copied to clipboard: " + object.link);
      })
      .catch((err) => {
        console.error("Failed to copy link: ", err);
        alert("Failed to copy the link. Please try again.");
      });
  });
}
