document.getElementById('service-form').addEventListener('submit', (e) => {
    e.preventDefault();

    const request = new XMLHttpRequest();
    request.open('POST', `../api/services`);

    request.onreadystatechange = function() {
        if (this.readyState === 4) {
            if (this.status === 201)
                location.reload();
            else if(this.status === 400){
                const errors = JSON.parse(this.response).result;

                ['title', 'avg_price', 'description', 'photos'].forEach(element => errorOutput(element, errors[element]));
            } else
                alert('Something went wrong. Try again in a few minutes.');
        }
    }

    // Get input
    var formInput= new FormData();
    formInput.append('servicer_id', userID);
    formInput.append('title', document.getElementById('title').value.trim());
    formInput.append('avg_price', document.getElementById('avg_price').value.trim());

    const description = document.getElementById('description').value.trim();
    const photosInput = document.querySelector("input[type='file']");

    if(description != '')
        formInput.append('description', description);

    for(var i = 0; i < photosInput.files.length; i++)
        formInput.append('photos[]', photosInput.files[i]);

    request.send(formInput);
})