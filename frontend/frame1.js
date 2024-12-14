function LogOut() {
    window.open('index.html', '_blank');
     window.close();
    // localStorage.clear;
    localStorage.removeItem(token);
}
function profile() {
    window.open ('profile.html', '_blank');
    window.close();
}
