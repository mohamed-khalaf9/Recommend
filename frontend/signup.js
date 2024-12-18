var nameError=document.getElementById("name-err");
      //allow characters only
function justChar(evt){
    var charCode=evt.keyCode;
    if((charCode>=97&&charCode<=122)||(charCode>=65&&charCode<=90)||charCode===32){
        nameError.innerHTML=""
        return true
    }
    else{
        nameError.innerHTML='*this field accepts only characters'
        return false
        }
    }

document.addEventListener("DOMContentLoaded",()=>{
    //getting input objects
    const frm=document.getElementById("form");
    const nm=document.getElementById("my-name");
    const email=document.getElementById("my-email");
    const password=document.getElementById("my-pass");
    const education=document.getElementById("my-edu");
    const brief=document.getElementById("brief");
    //getting spans for erroe handling
     nameError=document.getElementById("name-err");
    var emailError=document.getElementById("email-err");
    var passError=document.getElementById("pass-err");
    var eduError=document.getElementById("edu-err");
    var briefError=document.getElementById("brf-err");
 
    async function handleSignUp(event) {
        event.preventDefault();//prevent default form submission
     
//validate form inputs
if(!validateFormData()){
    return;
 }
//collect form data
var formData={
    name:nm.value,
    email:email.value,
    password:password.value,
    education:education.value,
    brief:brief.value,
    date:new Date().toISOString().split('T')[0]
 }

 //call the signUp api
 try{
    const response = await  fetch("http://localhost/Recommend/backend/users",{
                                   method:"post",
                                   headers:{"Content-Type":"application/json"},
                                   body:JSON.stringify(formData),
                                   }
                                );

    if(response.status===201){
        var responseData=await response.json();
        alert(responseData.message +"  and you will be in {login-page} now😊")
        form.reset();//clear the form
        window.location.href="login.html";
     }
     else if(response.status===500){ 
        var errorData=await response.json();
        alert(errorData.message+"😢");
     }
     else{
        alert("unexpected error occured.Please try again later.😞");
     }
 }
 catch(error){
    console.error("Error during sign-up",error);
    alert("Failed to connect to the server😞.Please check your network & try again.");
        }     
    }
    
function validateFormData(){
    var valid=true
if(nm.value===""||nm.value==null){
    nameError.innerHTML="*Required"
    valid=false;
}
else if(nm.value.length<4){
    nameError.innerHTML="name must be more than 3 char and less than 15 char "
    valid=false;
}   
else if(nm.value.length>15)
    {
        nameError.innerHTML="name must be more than 3 char and less than 15 char "
        valid=false;
    }
else{
    nameError.innerHTML=""
}

var trueMail=/^[A-Za-z0-9._%+-]+@[A-Za-z0-9.-]+\.[A-Za-z]{2,}$/
if(email.value===""||email.value==null){
    emailError.innerHTML="*Required"
    valid=false;
}
else if(!email.value.match(trueMail)){
    emailError.innerHTML="*InValid E-mail Format"
    valid=false;
}
else
    emailError.innerHTML=""

    if(password.value===""||password.value==null){
        passError.innerHTML="*Required"
        valid=false;
    }
    else if(password.value.length<6){
        passError.innerHTML="*password must be more than 5 characters and than 13 characters"
        valid=false;
    }
    else if(password.value.length>12){
         passError.innerHTML="*password must be more than 5 characters and less than 13 characters"
         valid=false;
        }
        else
        passError.innerHTML=""

        if(education.value===""||education.value==null){
            eduError.innerHTML="*Required"
            valid=false;
        }
        else
        eduError.innerHTML=""

            if(brief.value===""||brief.value==null){
                briefError.innerHTML="*Required"
                valid=false;
            }
            else
                  briefError.innerHTML=""

                  return valid;
}

        //Attach the event listener to the form 
        form.addEventListener("submit",handleSignUp);

    });

