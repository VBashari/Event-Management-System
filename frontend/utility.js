function configureNavBar() {
    //TODO
}

async function configureDashboardSidebar(userID) {
    const user = await getUser(userID);
    const sidebar = document.querySelector('.SideBar');

    sidebar.insertAdjacentHTML('beforeend', `<p class="name" style="color: black;">${user.username}</p>`);
    sidebar.insertAdjacentHTML('beforeend', '<a href="./userDashboard.html">Dashboard</a>');
    sidebar.insertAdjacentHTML('beforeend', '<a href="./userRequests.html">Requests sent</a>');

    if(user.user_type != 'USER') {
        sidebar.insertAdjacentHTML('beforeend', '<a href="./userIncomingRequests.html">Incoming requests</a>');
        sidebar.insertAdjacentHTML('beforeend', '<a href="#">My posts</a>');
        sidebar.insertAdjacentHTML('beforeend', '<a href="#">My services</a>');
    
        sidebar.insertAdjacentHTML('beforeend', '<a href="#">Create a post</a>');
        sidebar.insertAdjacentHTML('beforeend', '<a href="#">Create a service</a>');
    }
}

function getUser(userID) {
    return new Promise(function(resolve, reject) {
        const request = new XMLHttpRequest();
        request.open('GET', `../api/users/${userID}`);

        request.onreadystatechange = function () {
            if (this.readyState === 4) {
                if (this.status === 200) {           
                    resolve(JSON.parse(this.response));
                } else if(this.status === 400) {
                    window.location.replace("404.html");
                } else
                    window.location.replace("500.html");
            } 
        }

        request.send();
    })
}