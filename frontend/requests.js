async function formatRequests(result, isIncoming) {
    if(Object.keys(result).length == 0) {
        requests_div.insertAdjacentHTML('beforeend', '<p>No requests sent</p>');
        document.getElementsByClassName('Prev')[0].disabled = true;
        document.getElementsByClassName('Next')[0].disabled = true;
        return;
    }

    result.forEach(async request => {
        const servicerUsername = await getServicerName(request.servicer_id);

        // Creating div element for current request
        const requestElement = document.createElement('div');
        requestElement.setAttribute('class', 'request');
        
        const format = `
            <b>Request to: <span class="sUname" style="font-weight: normal">${servicerUsername}</span></b>
            <p class="Rtittle" style="font-weight: bold">${request.title}</p>
            <p class="Desc" style="font-size: small;">${request.description ?? 'No description provided'}</p>
        `;

        requestElement.insertAdjacentHTML('beforeend', format);

        // If requests are from the perspective of the requester, then show the status, edit, and delete buttons
        // otherwise, show accept, reject buttons
        if(!isIncoming) {
            requestElement.insertAdjacentHTML('beforeend', `
                <p style="color :lightblue;">Status: <span class="status" style="color:black; font-weight:bold">
                ${request.status === 0 ? 'NOT EVALUATED' : request.status === 1 ? 'ACCEPTED' : 'REJECTED'}
                </span></p>
            `);

            if(request.status === 0)
                requestElement.insertAdjacentElement('beforeend', createEditButton(request.request_id));
            
            requestElement.insertAdjacentElement('beforeend', createDeleteButton(request.request_id));
        } else {
            requestElement.insertAdjacentElement('beforeend', createEvaluationButton(request.request_id, true));
            requestElement.insertAdjacentElement('beforeend', createEvaluationButton(request.request_id, false));
        }

        // Adding the request div
        requests_div.insertAdjacentElement('beforeend', requestElement);
    })
}

function createDeleteButton(requestID) {
    const btnDelete = document.createElement('button');
    btnDelete.innerText = 'Delete';
    btnDelete.setAttribute('class', 'Del');
    btnDelete.setAttribute('data-id', requestID);

    // Delete specified request with an API call & remove div from list
    btnDelete.addEventListener('click', (e) => {
        const request = new XMLHttpRequest();
        request.open('DELETE', `../api/requests/${e.target.dataset['id']}`);

        request.onreadystatechange = function () {
            if (this.readyState === 4) {
                if (this.status === 204) {
                    requests_div.removeChild(e.target.parentNode);
                } else {
                    alert(JSON.parse(this.response));
                }
            } 
        }

        if (confirm('Are you sure you want to delete this request?')) request.send();
    });

    return btnDelete;
}

function createEditButton(requestID) {
    const btnEdit = document.createElement('button');
    btnEdit.innerText = 'Edit';
    btnEdit.setAttribute('class', 'Edit');
    btnEdit.setAttribute('data-id', requestID);

    btnEdit.addEventListener('click', (e) => {
        const editDialog = document.getElementById('editRequestForm');
        const btnCancel = document.getElementById('cancelEdit');
        
        editDialog.showModal();
        
        // Close dialog form
        btnCancel.addEventListener('click', (e) => {
            e.preventDefault();
            editDialog.close();
        })

        // Get edited values and submit them for API call
        document.getElementById('submitEdit').addEventListener('click', (e) => {
            e.preventDefault();
            const data = {};
            const description = document.getElementById('description'), title = document.getElementById('title');

            if(title.value)
                data['title'] = title.value;
            
            if(description.value)
                data['description'] = description.value;
                
            editRequest(requestID, JSON.stringify(data));
        })
        
    })

    return btnEdit;
}

function createEvaluationButton(requestID, isAccepted) {
    evaluationType = isAccepted ? 'Accept' : 'Reject';
    const btnEvaluate = document.createElement('button');
    btnEvaluate.innerText = evaluationType;
    btnEvaluate.setAttribute('class', evaluationType);
    btnEvaluate.setAttribute('data-id', requestID);

    btnEvaluate.addEventListener('click', (e) => {
        const request = new XMLHttpRequest();
        request.open('PATCH', `../api/requests/${e.target.dataset['id']}`);
        request.setRequestHeader('Content-type', 'application/json');

        request.onreadystatechange = function () {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    requests_div.removeChild(e.target.parentNode);
                } else {
                    alert(JSON.parse(this.response));
                }
            } 
        }

        request.send(JSON.stringify({status: isAccepted ? 1 : -1}));
    })

    return btnEvaluate;
}

// Send PATCH request for specified request
function editRequest(requestID, data) {
    const request = new XMLHttpRequest();
    request.open('PATCH', `../api/requests/${requestID}`);
    request.setRequestHeader('Content-type', 'application/json');

    request.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                if(!alert('Update successful'))
                    window.location.reload();
            } else {
                alert(JSON.parse(this.response).error);
            }
        }
    }

    request.send(data);
}

// Gets the servicer name for each request servicer
function getServicerName(servicerID) {
    return new Promise(function(resolve, reject) {
        const request = new XMLHttpRequest();
        request.open('GET', `../api/users/${servicerID}`);

        request.onreadystatechange = function () {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    resolve(JSON.parse(this.response).username);
                } else if(this.status === 400) {
                    window.location.replace("404.html");
                } else
                    window.location.replace("500.html");
            } 
        }

        request.send();
    })
    
}