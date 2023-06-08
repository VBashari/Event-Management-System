const events_div = document.getElementById('userEvents');
// TODO Get logged-in user's ID 
const userID = 9

configureDashboardSidebar(userID);
getEvents();

function getEvents() {
    const date = new Date();
    const month = date.getMonth() + 1, year = date.getFullYear();
    // TODO get logged-in user ID
    const userID = 1;

    const request = new XMLHttpRequest();
    request.open('GET', `../api/events/user/${userID}?month=${month}&year=${year}`);

    request.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {           
                formatEvents(JSON.parse(this.response));
            } else if(this.status === 400) {
                window.location.replace("404.html");
            } else
                window.location.replace("500.html");
        } 
    }

    request.send();
}

function formatEvents(result) {
    if(Object.keys(result).length == 0) {
        events_div.insertAdjacentHTML('beforeend', '<p>No events as of yet</p>');
        return;
    }

    result.forEach(event => {
        const format = `
            <div data-id="${event.event_id}">
                <h5>${event.title}</h5>
                <p>${event.scheduled_date}</p>
                <p>Organizer: ${event.organizer_username}</p>
            </div>`;

        events_div.insertAdjacentHTML('beforeend', format);
    })
}