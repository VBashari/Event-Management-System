const userID = 8; //TODO get logged in user's id
const limit = 6;
var offset = 0;

const modal = document.getElementById('editForm-modal'), modalContent = document.querySelector('.modal-content');

const content_div = document.getElementById('requests');
const nextBtn = document.getElementById('next'), prevBtn = document.getElementById('prev');
const showRequests = () => showElements('GET', `../api/requests/user/${userID}/incoming?limit=${limit}&offset=${offset}`, formatRequests, content_div);


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
                    <h5 class="font-weight-bold">${request.title}</h5>
                    <h6 class="font-italic">${date.toDateString()} (${date.toLocaleTimeString()})</h6>

                    <p>${request.description == null ? '' : request.description}</p>
                    
                    ${request.status == 1 ? '' :
                        `<div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-success mx-2" data-id="${request.request_id}" onclick="evaluateRequest(this.dataset.id, true)">Accept</button>
                            <button type="button" class="btn btn-danger" data-id="${request.request_id}" onclick="evaluateRequest(this.dataset.id, false)">Deny</button>
                        </div>`}
                </div>
            </div>`;
        
        content_div.insertAdjacentHTML('beforeend', format);
    })
}

function evaluateRequest(elementID, isAccepted) {
    const request = new XMLHttpRequest();
    request.open('PATCH', `../api/requests/${elementID}`);

    request.onreadystatechange = function() {
        if (this.readyState === 4) {
            if (this.status === 200)
                document.getElementById(`request${elementID}`).remove();
            else
                alert('Something went wrong. Try again in a few minutes.');
        }
    }

    if(confirm(`Are you sure you want to ${isAccepted ? 'accept' : 'reject'} this request?`))
        request.send(`status=${isAccepted ? 1 : -1}`);
}