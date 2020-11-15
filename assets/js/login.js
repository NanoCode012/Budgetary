// LOGIN Show and Hide
const singUp = document.getElementById('sign-up'),
    singIn = document.getElementById('sign-in'),
    LoginIn = document.getElementById('login-in'),
    LoginUp = document.getElementById('login-up');

singUp.addEventListener('click', ()=>{
    // remove class if exist
    LoginIn.classList.remove('block')
    LoginUp.classList.remove('none')

    // add class
    LoginIn.classList.add('none')
    LoginUp.classList.add('block')
})

singIn.addEventListener('click', ()=>{
    // remove class if exist
    LoginIn.classList.remove('none')
    LoginUp.classList.remove('block')

    // add class
    LoginIn.classList.add('block')
    LoginUp.classList.add('none')
})