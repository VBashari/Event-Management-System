const requests_div = document.getElementById('requests');
// TODO get logged-in user's id
const userID = 8;

configureDashboardSidebar(userID);
getRequests();

function getRequests() {
    const limit = 5;
    var offset = 0;

    const request = new XMLHttpRequest();
    request.open('GET', `../api/requests/user/${userID}?limit=${limit}&offset=${offset}`);

    request.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {           
                formatRequests(JSON.parse(this.response), false);
            } else if(this.status === 400) {
                window.location.replace("404.html");
            } else
                window.location.replace("500.html");
        } 
    }

    request.send();
}