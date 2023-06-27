const api_baseURL = '../../../api/posts', postsPhotosFolderPath = '../../photos/posts';

const posts_div = document.getElementById('posts');
const modal = document.getElementById('editForm-modal'), modalContent = document.querySelector('.modal-content');

showElements('GET', `${api_baseURL}/user/${userID}`, formatPosts, posts_div);


// Functions

function formatPosts(result) {
    if(Object.keys(result).length == 0) {
        posts_div.insertAdjacentHTML('beforeend', '<p>No events as of yet</p>');
        return;
    }

    result.forEach(post => {
        const post_el = 
            `<div id="card${post.post_id}" class="card">
                <!-- Image(s) -->` + createPhotoCarousel(post, postsPhotosFolderPath) +
                `<!-- Post body -->
                <div class="card-body">
                    <h5>${post.title}</h5>
                    
                    <div class="d-inline float-right">
                        <button class="btn btn-outline-secondary btn-sm" onclick="editPost(this.dataset.id)" data-id="${post.post_id}">Edit</button>
                        <button class="btn btn-outline-danger btn-sm" onclick="deletePost(this.dataset.id)" data-id="${post.post_id}">Delete</button>
                    </div>
                </div>
            </div>`;

        posts_div.insertAdjacentHTML('beforeend', post_el);
    })
}

async function editPost(elementID) {
    const currPost = await getPost(elementID);
    
    var currPhotosDiv = '<div id="curr-photos" class="d-flex flex-wrap rounded p-3" style="background-color: var(--palette-grey); gap: 1em;">';
    currPost.photos.forEach(photo => currPhotosDiv += `<img src="${postsPhotosFolderPath}/${photo.photo_reference}" width="200">`);
    currPhotosDiv += '</div>';

    const modalBody =
        `<div class="modal-header">
            <h5 class="modal-title" id="editForm-modal-label">Edit post</h5>
            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span>
            </button>
        </div>
        <div class="modal-body">
            <label for="title">Title:</label>
            <input id="title" type="text" value="${currPost.title}" class="w-75">

            <br>
            <label>Current photos:</label>` + currPhotosDiv +

            `<br>
            <label for="photos[]" class="my-4">Add new images:</label>
            <input id="photos[]" type="file" multiple>

            <br>
            <button id="send-edit" type="button" class="btn btn-dark float-right">Submit</button>
        </div>`;
    
    modalContent.innerHTML = modalBody;
    document.getElementById('send-edit').addEventListener('click', function() {submitEdit(elementID, currPost.title)});

    new bootstrap.Modal(modal).show();
}

function deletePost(elementID) {
    const request = new XMLHttpRequest();
    request.open('DELETE', `${api_baseURL}/${elementID}`);

    request.onreadystatechange = function () {
        if (this.readyState === 4) {
            if (this.status === 204)
                document.getElementById(`card${elementID}`).remove();
            else
                alert('Something went wrong. Try again in a few minutes.');
        } 
    }

    if(confirm('Are you sure you want to delete this post?')) 
        request.send();
}


// Auxiliary functions

function getPost(postID) {
    return new Promise(function(resolve, reject) {
        const request = new XMLHttpRequest();
        request.open('GET', `${api_baseURL}/${postID}`);

        request.onload = function () { resolve(JSON.parse(this.response)); }
        request.send();
    });
}

async function submitEdit(elementID, postTitle) {
    const request = new XMLHttpRequest();
    request.open('PATCH', `../api/posts/${elementID}`);
    
    request.onreadystatechange = function() {
        if (this.readyState === 4) {
            if (this.status === 200)
                location.reload();
            else
                alert('Something went wrong. Try again in a few minutes.');
        } 
    }

    const newTitle = document.getElementById('title').value.trim();
    const photosInput = document.querySelector("input[type='file']");
    
    var formInput = {};
    
    if(newTitle != postTitle)
        formInput.title = newTitle;

    var files = [];

    for (var i = 0; i < photosInput.files.length; i++) {
        var file = photosInput.files[i];
        var fileData = {};

        fileData.data = await readFileAsBase64(file)
        fileData.filename = file.name;
      
        files.push(fileData);
    }

    if (files.length > 0)
        formInput.photos = files;
    
    request.setRequestHeader('Content-Type', 'application/json');
    request.send(JSON.stringify(formInput));
}