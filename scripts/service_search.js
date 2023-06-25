// Pagination
const limit = 6;
var offset = 0;

// Elements
const list_div = document.getElementById('list');
const btnPrev = document.getElementById('prev');
const btnNext = document.getElementById('next');
const no_image = {photo_reference: '../frontend/no_image.jpg', alt_text: 'No image available'};

const urlParams = new URLSearchParams(window.location.search);
const searchQuery = urlParams.get('q');

const showServices = () => showElements('GET', `../../../api/services?q=${searchQuery}&limit=${limit}&offset=${offset}`, formatResults, list_div);

document.querySelector("form[method='get']").action = '';
showServices();


btnNext.addEventListener('click', function(e) {
    offset += limit;
    nextBtnFunc(e.target, btnPrev, list_div, showServices);
})

btnPrev.addEventListener('click', function(e) {
    offset -= limit;
    prevBtnFunc(offset, e.target, btnNext, list_div, showServices);
})


function formatResults(result) {
    if(Object.keys(result).length == 0) {
        return;
    }

    result.forEach(service => {
        const primaryPhoto = 'photos' in service ? service.photos[0] : no_image;
        const description = service.description === null ? 'No description provided' : service.description;

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
            `<div class="d-flex mb-4" style="gap: 2em;">
                <img src="../photos/services/${primaryPhoto.photo_reference}" class="crop-img" alt="${primaryPhoto.alt_text}" width="300">

                <div>
                    <form id="form-${service.service_id}" method="GET" action="./service_view.php">
                        <input type="text" name="id" value="${service.service_id}" hidden>
                        <a href="#" onclick="document.getElementById('form-${service.service_id}').submit()" class="font-weight-bold" style="color: black; font-size: 1.3em;">
                            ${service.title} <span class="font-italic ml-5" style="color: lightslategrey;">$${service.avg_price}</span>
                        </a>
                    </form>

                    ${typeof tagsDiv !== 'undefined' ? tagsDiv : ''}
                    ${service.description === null ? '' : `<p class="mt-4">${service.description}</p>`}
                </div>
            </div>`;
        
        list_div.insertAdjacentHTML('beforeend', format);
    })
}