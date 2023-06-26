const userID = 8;

document.getElementById('post-form').addEventListener('submit', (e) => {
    e.preventDefault();

    const request = new XMLHttpRequest();
    request.open('POST', `../api/posts`);

    request.onreadystatechange = function() {
        if (this.readyState === 4) {
            if (this.status === 201)
                location.reload();
            else if(this.status === 400){
                const errors = JSON.parse(this.response).result;

                ['title', 'photos'].forEach(element => errorOutput(element, errors[element]));
            } else
                alert('Something went wrong. Try again in a few minutes.');
        }
    }

    // Get input
    var formInput= new FormData();
    formInput.append('servicer_id', userID);
    formInput.append('title', document.getElementById('title').value.trim());

    const photosInput = document.querySelector("input[type='file']");

    for(var i = 0; i < photosInput.files.length; i++)
        formInput.append('photos[]', photosInput.files[i]);

    request.send(formInput);
})