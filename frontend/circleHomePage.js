const rec = document.getElementById("mid");
var txt = "";
for (let i = 0; i < 6; i++){
    txt +='<div class="recoms"><div class="content"><h1>Someone</h1> <p class="desc">This recommendation about: Lorem ipsum dolor, sit amet consecteturadipisicing elit. Aliquam voluptatibus quidem ipsam hicperspiciatis ullam nam quod reprehenderit earum. Possimus aliasatque a, iusto molestias voluptatum suscipit corporis sed quaerat?</p><span class="share"><button class="link"><i class="fa-solid fa-link"></i></button></span><hr /><div class="likes"><button class="like"><i class="fa-regular fa-thumbs-up"></i></button><p class="counter">12</p></div></div></div>';
    txt+='<br/>'
}
rec.innerHTML=txt;

let btn = document.getElementById('add');
btn.addEventListener('click', () => {
    let crea = document.getElementById('create');
    crea.style.display = 'block';
})