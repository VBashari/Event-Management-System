const limit = 9;
var offset = 0;

const posts_div = document.getElementById('posts');
const prevBtn = document.getElementById('prev'), nextBtn = document.getElementById('next');

const urlParams = new URLSearchParams(window.location.search);
const servicerId = urlParams.get('id');

const showPosts = () => showElements('GET', `../../../api/posts/user/${servicerId}?limit=${limit}&offset=${offset}`, formatPosts, posts_div);

httpRequest("GET", `../../../api/users/${servicerId}`, null, (result) => {
    document.getElementById('servicer-name-h3').innerText = result.full_name;
}, (error) => {});

document.getElementById('servicer-services-a').href = `./servicer_services.php?id=${servicerId}`;

showPosts();


nextBtn.addEventListener('click', async (e) => {
    offset += limit;
    nextBtnFunc(e.target, prevBtn, posts_div, showPosts);
})

prevBtn.addEventListener('click', (e) => {
    offset -= limit;
    prevBtnFunc(offset, e.target, nextBtn, posts_div, showPosts);
})


// Functions

function formatPosts(result) {
    if(Object.keys(result).length == 0) {
        return;
    }

    result.forEach(post => {
        const post_el = 
            `<div class="card">
                <!-- Image(s) -->` + createPhotoCarousel(post, `../photos/posts`) +
                `<div class="card-body">
                    <h5 class="card-title">${post.title}</h5>
                </div>
            </div>`;

        posts_div.insertAdjacentHTML('beforeend', post_el);
    })
}