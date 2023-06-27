const limit = 6;
var offset = 0;

const modal = document.getElementById('editForm-modal'), modalContent = document.querySelector('.modal-content');

const content_div = document.getElementById('requests');
const nextBtn = document.getElementById('next'), prevBtn = document.getElementById('prev');
const showRequests = () => showElements('GET', `../api/requests/user/${userID}?limit=${limit}&offset=${offset}`, formatRequests, content_div);


showRequests();

nextBtn.addEventListener('click', function(e) {
    offset += limit;
    nextBtnFunc(e.target, prevBtn, content_div, showRequests);
})

prevBtn.addEventListener('click', function(e) {
    offset -= limit;
    prevBtnFunc(offset, e.target, nextBtn, content_div, showRequests);
})


// Functions

function formatRequests(result) {
    if(Object.keys(result).length == 0) {
        return;
    }

    result.forEach(request => {
        const date_db = request.scheduled_date.split(/[- :]/);
        const date = new Date(Date.UTC(date_db[0], date_db[1]-1, date_db[2], date_db[3], date_db[4], date_db[5]));

        const format =
            `<div id="request${request.request_id}" class="p-5 m-3" style="background-color: white;">
                <div>
                    <h5><b>Request to:</b> ServerviceUsername</h5>
                    <h6 class="font-weight-bold">${request.title} <span class="font-weight-normal"> | ${date.toDateString()} (${date.toLocaleTimeString()})</span></h6>
                    <p>${request.description == null ? '' : request.description}</p>
                    
                    <div class="d-flex align-items-center mt-4">
                        <p class="mr-auto"><b style="color: var(--palette-blue);">Status: </b class="text-upper">
                            ${request.status > 0 ?
                                'ACCEPTED' : request.status < 0 ? 'REJECTED' : 'NOT EVALUATED'}
                        </p>

                        ${request.status != 0 ? '' : `<button type="button" data-id="${request.request_id}" class="btn btn-dark mx-3" onclick="editRequest(this.dataset.id)">Edit</button>`}
                        <button type="button" data-id="${request.request_id}" class="btn btn-danger" onclick="deleteRequest(this.dataset.id)">Delete</button>
                    </div>
                </div>
            </div>`;
        
        content_div.insertAdjacentHTML('beforeend', format);
    })
}


function deleteRequest(elementID) {
    const request = new XMLHttpRequest();
    request.open('DELETE', `../api/requests/${elementID}`);

    request.onreadystatechange = function() {
        if (this.readyState === 4) {
            if (this.status === 204)
                document.getElementById(`request${elementID}`).remove();
            else
                alert('Something went wrong. Try again in a few minutes.');
        }
    }

    if(confirm('Are you sure you want to delete this post?')) 
        request.send();
}

async function editRequest(elementID) {
    const currRequest = await getRequest(elementID);
    const datetime_db = currRequest.scheduled_date.split(/[- :]/);
    const datetime = new Date(Date.UTC(datetime_db[0], datetime_db[1]-1, datetime_db[2], datetime_db[3], datetime_db[4], datetime_db[5]));

    const date = datetime.toISOString().slice(0, 10), time = datetime.toISOString().slice(11, 16);

    const modalBody =
        `<div class="modal-header">
            <h5 class="modal-title" id="editForm-modal-label">Edit request</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <label for="title">Title:</label>
            <input id="title" type="text" value="${currRequest.title}" class="w-75">

            <div class="d-flex my-3" style="gap: 2em;">
                <div>
                    <label for="date">Date:</label>
                    <input type="date" id="date" value="${date}">
                </div>
                
                <div>
                    <label for="time">Time:</label>
                    <input type="time" id="time" value="${time}">
                </div>
            </div>

            <div class="d-flex align-items-start" style="gap: 0.5em;">
                <label for="description">Description:</label>
                <textarea id="description" rows="5" cols="35">${currRequest.description == null ? '' : currRequest.description}</textarea>
            </div>
            
            <br>
            <button id="send-edit" type="button" class="btn btn-dark float-right">Submit</button>
        </div>`;
    
    modalContent.innerHTML = modalBody;
    document.getElementById('send-edit').addEventListener('click', function() {submitEdit(elementID, [currRequest.title, date + ' ' + time, currRequest.description])});

    new bootstrap.Modal(modal).show();
}


// Aux. functions

function getRequest(requestID) {
    return new Promise(function(resolve, reject) {
        const request = new XMLHttpRequest();
        request.open('GET', `../api/requests/${requestID}`);

        request.onload = function () { resolve(JSON.parse(this.response)); }
        request.send();
    });
}

function submitEdit(elementID, elements) {
    const request = new XMLHttpRequest();
    request.open('PATCH', `../api/requests/${elementID}`);

    request.onreadystatechange = function() {
        if (this.readyState === 4) {
            if (this.status === 200)
                location.reload();
            else
                alert('Something went wrong. Try again in a few minutes.');
        } 
    }

    const inputKeys = ['title', 'scheduled_date', 'description'];
    const input = [document.getElementById('title').value.trim(), 
                document.getElementById('date').value + ' ' + document.getElementById('time').value + ':00',
                document.getElementById('description').value.trim()];

    var formInput = {};
    
    for(var i = 0; i < elements.length; i++) {
        if(elements[i] != input[i])
            formInput[inputKeys[i]] = input[i];
    }

    request.setRequestHeader('Content-Type', 'application/json');
    request.send(JSON.stringify(formInput));
}