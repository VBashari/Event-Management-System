const userID = 8; // TODO get servicer's id from url
const limit = 9;
var offset = 0;

const posts_div = document.getElementById('posts');
const prevBtn = document.getElementById('prev'), nextBtn = document.getElementById('next');
const showPosts = () => showElements('GET', `../../../api/posts/user/${userID}?limit=${limit}&offset=${offset}`, formatPosts, posts_div);

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