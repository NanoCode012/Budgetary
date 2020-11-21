// LOGIN Show and Hide
const singUp = document.getElementById('sign-up'),
    singIn = document.getElementById('sign-in'),
    LoginIn = document.getElementById('login-in'),
    LoginUp = document.getElementById('login-up'),
    forgetUp = document.getElementById('forget-up'),
    forgetIn = document.getElementById('forget-in'),
    forgetOut = document.getElementById('forget-out');

singUp.addEventListener('click', ()=>{
    // remove class if exist
    LoginIn.classList.remove('block')
    LoginUp.classList.remove('none')
    forgetUp.classList.remove('none')

    // add class
    LoginIn.classList.add('none')
    LoginUp.classList.add('block')
    forgetUp.classList.add('none')
})

singIn.addEventListener('click', ()=>{
    // remove class if exist
    LoginIn.classList.remove('none')
    LoginUp.classList.remove('block')
    forgetUp.classList.remove('block')

    // add class
    LoginIn.classList.add('block')
    LoginUp.classList.add('none')
    forgetUp.classList.add('none')
})

forgetIn.addEventListener('click', ()=>{
    // remove class if exist
    LoginIn.classList.remove('block')
    LoginUp.classList.remove('none')
    forgetUp.classList.remove('none')

    // add class
    LoginIn.classList.add('none')
    LoginUp.classList.add('none')
    forgetUp.classList.add('block')
})

forgetOut.addEventListener('click', ()=>{
    // remove class if exist
    LoginIn.classList.remove('none')
    LoginUp.classList.remove('none')
    forgetUp.classList.remove('block')

    // add class
    LoginIn.classList.add('block')
    LoginUp.classList.add('none')
    forgetUp.classList.add('none')
})

