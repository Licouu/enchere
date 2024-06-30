const form = document.getElementById('a');
const username = document.getElementById('username');
const password = document.getElementById('password');

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



const validateInputs = () => {
    const usernameValue = username.value.trim();
    const passwordValue = password.value.trim();

  
    if(passwordValue === '') {
        setError(password, 'Password is required');
    } else {
        setSuccess(password);
    }

   
    if(usernameValue === '') {
        setError(username, 'username is required');
    } else {
        setSuccess(username);
    }
   


    
};
