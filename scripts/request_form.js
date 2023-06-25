const userID = 1; //TODO get user id

document.querySelector('form[method="POST"]').addEventListener('submit', (e) => {
    e.preventDefault();

    const request = new XMLHttpRequest();
    request.open('POST', `../api/requests`);

    request.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 201)
                location.reload();
            else if(this.status === 400){
                const errors = JSON.parse(this.response).result;

                ['title', 'scheduled_date', 'description'].forEach(element => errorOutput(element, errors[element]));
            } else
                alert('Something went wrong. Try again in a few minutes.');
        } 
    }

    var formInput= new FormData();
    formInput.append('requester_id', userID);
    formInput.append('servicer_id', document.querySelector('input[name="servicer_id"]').value);

    formInput.append('title', document.getElementById('title').value.trim());
    formInput.append('date', document.getElementById('date').value + ' ' + document.getElementById('time').value + ':00');
    
    const description = document.getElementById('description').value.trim();
    
    if(description != '')
        formInput.append('description', description);

    request.send();
})