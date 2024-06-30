console.log('inscription');
const form = document.querySelector('form');
const email = document.querySelector('#email');
const password = document.querySelector('#password');
const password2 = document.querySelector('#password2');
const ligne1 = document.querySelector('#ligne1');
const ligne2 = document.querySelector('#ligne2');
const ame = document.querySelector('#ame');
const age = document.querySelector('#age');
const cgu = document.querySelector('#cgu');

console.log(form);

form.addEventListener('submit', e => {
    e.preventDefault();
    validateInputs();
});

const setError = (element, message) => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.nextElementSibling;
    while (errorDisplay && !errorDisplay.classList.contains("error")) {
      errorDisplay = errorDisplay.nextElementSibling;
    }

    errorDisplay.innerText = message;
    inputControl.classList.add('error');
    inputControl.classList.remove('success')
}

const setSuccess = element => {
    const inputControl = element.parentElement;
    const errorDisplay = inputControl.nextElementSibling;
    while (errorDisplay && !errorDisplay.classList.contains("error")) {
      errorDisplay = errorDisplay.nextElementSibling;
    }
    errorDisplay.innerText = '';
    inputControl.classList.add('success');
    inputControl.classList.remove('error');
};

const isValidEmail = email => {
    const re = /^(([^<>()[\]\\.,;:\s@"]+(\.[^<>()[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
    return re.test(String(email).toLowerCase());
}

const validateInputs = () => {
    const emailValue = email.value.trim();
    const passwordValue = password.value.trim();
    const password2Value = password2.value.trim();
    const ameValue = ame.value.trim();
    const ligne1Value = ligne1.value.trim();
    const ligne2Value = ligne2.value.trim();
    const cguValue = cgu.value;
    const ageValue = age.value;
   
   if(emailValue === '') {
        setError(email, 'Email is required');
    } else if (!isValidEmail(emailValue)) {
        setError(email, 'Provide a valid email address');
    } else {
        setSuccess(email);
    }

    if(passwordValue === '') {
        setError(password, 'Password is required');
    } else if (passwordValue.length < 8 ) {
        setError(password, 'Votre Mot de passe doit avoir 8caractère.')
    } else {
        setSuccess(password);
    }

    if(password2Value === '') {
        setError(password2, 'Please confirm your password');
    } else if (password2Value !== passwordValue) {
        setError(password2, "Passwords doesn't match");
    }else {
        setSuccess(password2);
    }



   


    if(ligne2Value === '' ||  ligne1Value === '' ) {
        setError(ligne2, 'name or surname is required');
    }else {
        setSuccess(ligne2);
    }

    
    if(ameValue === '') {
        setError(ame, 'surname is required');
    }else {
        setSuccess(ame);
    }

    if(!cgu.checked) {
        setError(cgu, 'Please valid cgu');
    }else {
        setSuccess(cgu);
    }

    if(!age.checked) {
        setError(age, 'You must have at least 18 years old');
    } else {
        setSuccess(age);
    }
};
