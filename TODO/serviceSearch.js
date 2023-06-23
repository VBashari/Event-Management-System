// Pagination
const limit = 5;
var offset = 0;

// Elements
const list_div = document.getElementById('list');
const btnPrev = document.getElementById('prev');
const btnNext = document.getElementById('next');

// Initalize page with service posts
getServices();

// Add 'next' button functionality
btnNext.addEventListener('click', (e) => {
    offset += limit;

    list_div.innerHTML = '';
    btnPrev.disabled = false;

    getServices();
});

// Add 'previous' button functionality
btnPrev.addEventListener('click', (e) => {
    if(offset - limit < 0)
        e.target.disabled = true;
    else {
        offset -= limit;
        
        list_div.innerHTML = '';
        btnNext.disabled = false;

        getServices();
    }
});

// Call API, format resulting answer
function getServices() {
    var urlParams = new URLSearchParams(window.location.search);
    var searchQuery = urlParams.get('q');

    const request = new XMLHttpRequest();
    request.open('GET', `../api/services?q=${searchQuery}&limit=${limit}&offset=${offset}`);

    request.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 200) {
                const result = JSON.parse(this.response);

                // No more elements
                if(result.length == 0)
                    btnNext.disabled = true;

                formatServices(result);
            } else if(this.status === 400) {
                window.location.replace("404.html");
            } else
                window.location.replace("500.html");
        } 
    }

    request.send();
}

// Create the html elements for the API results
function formatServices(result) {
    if(Object.keys(result).length == 0) {
        list_div.insertAdjacentHTML('beforeend', '<p>No results</p>');
        return;
    }

    const no_image = {photo_reference: '../frontend/no_image.jpg',
                        alt_text: 'No image available'};

    result.forEach(service => {
        const primaryPhoto = 'photos' in service ? service.photos[0] : no_image;
        const description = service.description === null ? 'No description provided' : service.description;

        if('tags' in service) {
            var tagsDiv = '<div>';
            
            service.tags.forEach(tag => {
                const tagLook = `
                    <div class="tags" style="border-radius: 40px;">
                        <b class="ttag">${tag}</b>
                    </div>`;
                
                tagsDiv += tagLook;
            })

            tagsDiv += '</div>';
        }

        const look = `
        <div class="Servs" style="display:flex">
            <div class="Img">
                <img src="../photos/services/${primaryPhoto.photo_reference}" alt="${primaryPhoto.alt_text}">
            </div>
            <div class="description">
                <b class="STittle">${service.title}<span class="price" style="font-weight: normal">$${service.avg_price}</span></b>
            <p></p>
            
            ${typeof tagsDiv !== 'undefined' ? tagsDiv : ''}
            
            <div style="text-overflow:ellipsis">
                <p class="Description" style="font-size: small;">${description}</p>
            </div>
            

            </div>
        </div>`;

        list_div.insertAdjacentHTML('beforeend', look);
    })
}