// Error displaying functions for form fields

// Display error message for a single field
function displayError(field, error) {
    const errorElement = document.getElementById(`${field}_error`);
    if (errorElement) {
        errorElement.textContent = error;
    }
    console.log(field + ': ' + error);
}

// Reset errors for fields
function resetErrors(fields) {
    fields.forEach(field => {
        displayError(field, '');
    });
}

// display error alert above submit button
function displayGenericError(form, error) {
    let errorElement = form.querySelector('.alert-danger');

    if (!errorElement) {
        errorElement = document.createElement('div');
        errorElement.classList.add('alert', 'alert-danger');
        form.insertBefore(errorElement, form.querySelector('button[type="submit"]'));
    }
    errorElement.textContent = error;
}

// remove error alert
function resetGenericError(form) {
    const errorElement = form.querySelector('.alert-danger');
    if (errorElement) {
        errorElement.remove();
    }
}

function genericErrorHandler(response) {
    if (typeof response.result !== 'string') {
        Object.entries(response.result).forEach(([field, error]) => {
            displayError(field, error);
        });
        
        resetGenericError(form);
        if (typeof fields !== 'undefined') {
            fields.forEach(field => {
                if (!response.result[field]) {
                    displayError(field, '');
                }
            });
        }
        else {
            console.log(response.result);
        }
    }
    else {
        if (typeof fields !== 'undefined') {
            resetErrors(fields);
        }
        displayGenericError(form, response.result);
    }
}