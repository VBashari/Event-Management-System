const userID = 8; //TODO get logged in user's id
const limit = 6;
var offset = 0;

const content_div = document.getElementById('services');
const nextBtn = document.getElementById('next'), prevBtn = document.getElementById('prev');

const no_image = {photo_reference: '../frontend/no_image.jpg', alt_text: 'No image available'};
const showServices = () => showElements('GET', `../api/services/user/${userID}?limit=${limit}&offset=${offset}`, formatServices, content_div);


showServices();

nextBtn.addEventListener('click', function(e) {
    offset += limit;
    nextBtnFunc(e.target, prevBtn, content_div, showServices);
})

prevBtn.addEventListener('click', function(e) {
    offset -= limit;
    prevBtnFunc(offset, e.target, nextBtn, content_div, showServices);
})


// Functions

function deleteService(elementID) {
    const request = new XMLHttpRequest();
    request.open('DELETE', `../api/services/${elementID}`);

    request.onreadystatechange = function() {
        if (this.readyState === 4) {
            if (this.status === 204)
                document.getElementById(`service${elementID}`).remove();
            else
                alert('Something went wrong. Try again in a few minutes.');
        }
    }

    if(confirm('Are you sure you want to delete this post?')) 
        request.send();
}

function formatServices(result) {
    if(Object.keys(result).length == 0) {
        return;
    }

    result.forEach(service => {
        const primaryPhoto = 'photos' in service ? service.photos[0] : no_image;

        // Create tags div
        if('tags' in service) {
            var tagsDiv = '<div class="d-flex" style="gap: 0.5em;">';
            
            service.tags.forEach(tag => {
                const tagLook = `<span class="badge badge-pill py-2 px-3">${tag}</span>`;
                tagsDiv += tagLook;
            })

            tagsDiv += '</div>';
        }

        const format =
            `<div id="service${service.service_id}" class="mb-4 p-4" style="background-color: white;">
                <div class="d-flex" style="gap: 2em;">
                    <img src="../photos/services/${primaryPhoto.photo_reference}" class="crop-img" alt="" width="350">

                    <div>
                        <h4>${service.title} <span class="font-italic ml-5" style="color: lightslategrey;">$${service.avg_price}</span></h4>

                        ${typeof tagsDiv !== 'undefined' ? tagsDiv : ''}
                        ${service.description === null ? '' : `<p class="mt-4">${service.description}</p>`}
                    </div>
                </div>

                <div class="text-right">
                    <button class="btn btn-outline-danger mt-4 px-4" data-id="${service.service_id}" onclick="deleteService(this.dataset.id)">Delete</button>
                </div>
            </div>`;
        
        content_div.insertAdjacentHTML('beforeend', format);
    })
}