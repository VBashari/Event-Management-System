function tryLogin(username, password) {
    const auth = {
        username: username,
        password: password
    };

    httpRequest('POST', '/api/sessions', auth, (response) => {
        if (typeof fields !== 'undefined') {
            resetErrors(fields);
        }
        resetGenericError(form);

        document.cookie = `session=${response.result.token}; path=/; expires=${new Date(response.result.expiration * 1000).toUTCString()}`;
        document.location = '/';
    }, genericErrorHandler);
}

function isLoggedIn() {
    return document.cookie.includes('session=');
}

function signOut() {
    document.cookie = 'session=; path=/; expires=Thu, 01 Jan 1970 00:00:00 GMT';
    document.location = '/';
}