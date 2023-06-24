function httpRequest(method, url, data, successHandler, failureHandler) {
    const xhr = new XMLHttpRequest();
    xhr.open(method, url, true);
    xhr.setRequestHeader('Content-Type', 'application/json');
    xhr.send(JSON.stringify(data));

    xhr.onreadystatechange = function() {
        if (xhr.readyState === 4) {
            if (xhr.status < 200 || xhr.status >= 300) {                
                failureHandler(JSON.parse(xhr.responseText));
            }
            else {
                successHandler(JSON.parse(xhr.responseText));
            }
        }
    }
}