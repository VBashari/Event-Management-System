const urlParams = new URLSearchParams(window.location.search);
const serviceID= urlParams.get('id');
const contentDiv = document.getElementById('content');

showElements('GET', `../api/services/${serviceID}`, formatPage, contentDiv);


function formatPage(result) {
    const format =
        `<div id="service" class="d-flex">
            ${createPhotoCarousel(result, '../photos/services')}

            <div>
                <h3 class="font-weight-bold">${result.title} <span class="ml-3" style="color: lightslategrey">$${result.avg_price}</span></h3>

                ${result.description === null ? '' : `<p>${result.description}</p>`}
                
                <form method="POST" action="./request_form.php">
                    <button id="send-req" name="id" value="${result.servicer_id}" type="submit" class="float-right btn font-weight-bold px-4 py-2">Send request</button>
                </form>
            </div>
        </div>

        <form id="servicer-profile" method="GET" action="./servicer_services.php">
            <button id="more" name="id" value="${result.servicer_id}" type="submit" class="btn font-weight-bold text-uppercase mt-3 px-5">More from this servicer</button>
        </form>`;
        
    
    contentDiv.insertAdjacentHTML('beforeend', format);
}