const events_div = document.getElementById('events');

const date = new Date();
const month = ["January","February","March","April","May","June","July","August","September","October","November","December"];


document.querySelector('#events > h2').innerText = month[date.getMonth()];
showElements('GET', `../../api/events/user/${userID}?month=${date.getMonth() + 1}&year=${date.getFullYear()}`, formatEvents, events_div);


function formatEvents(result) {
    if(Object.keys(result).length == 0) {
        events_div.insertAdjacentHTML('beforeend', '<p>No events as of yet</p>');
        return;
    }

    result.forEach(event => {
        const date_db = event.scheduled_date.split(/[- :]/);
        const date = new Date(Date.UTC(date_db[0], date_db[1]-1, date_db[2], date_db[3], date_db[4], date_db[5]));

        const event_el = `
            <div class="event row d-flex align-items-center m-4 p-2" data-id="${event.event_id}">
                <i class="fa fa-calendar fa-2x col-auto" ></i>
                
                <div class="text-center">
                    <h5 class="mb-0 pl-0">${date.toDateString()}</h5>
                    <p class="my-0">${date.toLocaleTimeString()}</p>
                </div>
                
                <div class="col-auto ml-4">
                    <p class="m-0 font-weight-bold">${event.title}</p>
                    <p class="m-0 font-italic">Organizer: ${event.organizer_username}</p> 
                </div>
            </div>`;

        events_div.insertAdjacentHTML('beforeend', event_el);
    })
}