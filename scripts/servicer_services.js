const userID = 8; // TODO get servicer's id from url
const limit = 9;
var offset = 0;

const services_div = document.getElementById('services');
const prevBtn = document.getElementById('prev'), nextBtn = document.getElementById('next');
const showServices = () => showElements('GET', `../../../api/services/user/${userID}?limit=${limit}&offset=${offset}`, formatServices, services_div);


document.querySelector('form[method="get"]').action = './service_search.php';
showServices();


nextBtn.addEventListener('click', async (e) => {
    offset += limit;
    nextBtnFunc(e.target, prevBtn, services_div, showServices);
})

prevBtn.addEventListener('click', (e) => {
    offset -= limit;
    prevBtnFunc(offset, e.target, nextBtn, services_div, showServices);
})


// Functions

function formatServices(result) {
    if(Object.keys(result).length == 0) {
        return;
    }

    result.forEach(service => {
        const post_el = 
            `<div class="card">
                <!-- Image(s) -->
                ${createPhotoCarousel(service, `../photos/services`)}
                
                <div class="card-body">
                    <form id="form-${service.service_id}" method="GET" action="./service_view.php">
                        <input type="text" name="id" value="${service.service_id}" hidden>
                        <a href="#" onclick="document.getElementById('form-${service.service_id}').submit()" class="font-weight-bold" style="color: black; font-size: 1.3em;">${service.title}</a>
                    </form>
                </div>
            </div>`;

        services_div.insertAdjacentHTML('beforeend', post_el);
    })
}