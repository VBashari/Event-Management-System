function showElements(method, api_url, funcFormatElements, elementsContainer) {
    return new Promise(function(resolve) {
        const request = new XMLHttpRequest();
        request.open(method, api_url);
    
        request.onreadystatechange = function () {
            if (this.readyState === 4) {
                if (this.status === 200) {
                    const result = JSON.parse(this.response);
                    
                    resolve(result.length == 0 ? 
                        elementsContainer.insertAdjacentHTML('beforeend', '<div class="m-auto text-center"><i class="fa fa-inbox fa-5x" aria-hidden="true"></i><h3 class="font-italic">No results</h3></div>') :
                        funcFormatElements(result));
                }
                else
                    reject(elementsContainer.insertAdjacentHTML('beforeend', '<p class="text-center my-5">Something went wrong. Please try again in a few minutes.</p>'));
            }
        }
    
        request.send();
    })
}

async function nextBtnFunc(btn, prevBtn, elements_div, outputFunc) {
    elements_div.innerHTML = '';
    await outputFunc();
    prevBtn.disabled = false;
    
    if(document.querySelectorAll('.fa-inbox').length)
        btn.disabled = true;
}

function prevBtnFunc(offset, btn, nextBtn, elements_div, outputFunc) {
    elements_div.innerHTML = '';
    nextBtn.disabled = false;
    outputFunc();

    if(offset == 0)
        btn.disabled = true;
}


function createPhotoCarousel(post, photosFolderPath) {
    var photo_container;
    
    // Create a carousel if there's multiple photos for the post
    // Otherwise, add the photo as a single
    if(!('photos' in post))
        photo_container = `<img src="../../photos/frontend/no_image.jpg" alt="No image available" class="card-img">`;
    else if(post.photos.length == 1)
        photo_container = `<img src="${photosFolderPath}/${post.photos[0].photo_reference}" alt="${post.photos[0].alt_text}" class="card-img">`;
    else {
        var photo_carousel_items = '';

        post.photos.forEach((photo, index) => photo_carousel_items += `<div class="carousel-item ` + ((index == 0) ? `active` : ``) + `">
                                        <img src="${photosFolderPath}/${photo.photo_reference}" alt="${photo.alt_text}" class="d-block card-img">
                                        </div>`
        );

        photo_container =
            `<div id="carouselControls${post.post_id}" class="carousel slide carousel-fade" data-ride="carousel">
                <div class="carousel-inner">` + photo_carousel_items + `</div>

                <!-- Carousel controls -->
                <button class="carousel-control-prev" type="button" data-target="#carouselControls${post.post_id}" data-slide="prev">
                    <span class="carousel-control-prev-icon" aria-hidden="true"></span>
                    <span class="sr-only">Previous</span>
                </button>
                <button class="carousel-control-next" type="button" data-target="#carouselControls${post.post_id}" data-slide="next">
                    <span class="carousel-control-next-icon" aria-hidden="true"></span>
                    <span class="sr-only">Next</span>
                </button>
            </div>`;
    }

    return photo_container;
}


function errorOutput(element, errorMessage) {
    const errorDiv = document.getElementById(`${element}-feedback`);
    
    if(errorMessage != undefined) {
        errorDiv.classList.remove('invisible');
        errorDiv.innerText = errorMessage;
    } else
        errorDiv.classList.add('invisible');
}

function readFileAsBase64(file) {
    return new Promise((resolve, reject) => {
        const reader = new FileReader();
    
        reader.onload = () => {
            const base64Data = reader.result.split(',')[1];
            resolve(base64Data);
        };
    
        reader.onerror = () => {
            reject(new Error('Error reading file'));
        };
    
        reader.readAsDataURL(file);
    });
}