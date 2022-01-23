var lastId = 0;

loadPosts();
setLoggedUserData();
setInterval(getLastPosts, 1000);

var input = document.getElementById("postInput");


input.addEventListener("keyup",function(event){
    if(event.keyCode === 13){
        event.preventDefault();
        createPost();
    }
});

function loadPosts(){
    var url = "posts.php?gt=" + 0;
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {  
            var posts = JSON.parse(xhttp.responseText);
            posts.sort(function(a, b) {
                var id1 = a["id"];
                var id2 = b["id"];
                return parseInt(id2) - parseInt(id1);
            });
            
            for(let i = 0; i < posts.length; i++){
                const postList = document.getElementById("postList");
                const postDiv = createPostDOM(posts[i]);
                postList.appendChild(postDiv);

                var postId = parseInt(posts[i]["id"]);
                if(lastId<postId){
                    lastId = postId;
                }
            }
        }else{
            console.log(this.status);
        }
    };    
    xhttp.open("GET", url, true);
    xhttp.send();
}

function changeProfilePicture(){
    var url = "profilePic.php";
    var xhttp = new XMLHttpRequest();

    var obj = {
        imgURL:document.getElementById("imgURL").value,
    }
    var data = JSON.stringify(obj);
    console.log(data);
    xhttp.open("POST", url, true);
    xhttp.send(data);
}

function fetchAndCreateProfilePic(username, imgElement){
    var url = "getUser.php?username=";
    var xhttp = new XMLHttpRequest();
    url += username;

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {  
            var user = JSON.parse(xhttp.responseText);
            if(user["imgURL"] != null){
                imgElement.src  = user["imgURL"];
            }else{
                imgElement.src = "profile.webp";
            }
        }
    }; 

    xhttp.open("GET", url, true);
    xhttp.send();
}

function setLoggedUserData(){
    var url = "getUser.php";
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {  
            var user = JSON.parse(xhttp.responseText);
            if(user["imgURL"] != null){
                document.getElementById("big-profile-pic").src = user["imgURL"];
                document.getElementById("small-profile-pic").src = user["imgURL"];
            }else{
                document.getElementById("big-profile-pic").src = "profile.webp";
                document.getElementById("small-profile-pic").src = "profile.webp";
            }
            document.getElementById("user").innerText = user["username"];
        }
    }; 
    xhttp.open("GET", url, true);
    xhttp.send();
}

function createPostDOM(post){
    const postDiv = document.createElement("div");
    const flex2Div = document.createElement("div");
    flex2Div.classList.add("flex2");

    postDiv.classList.add("post");
    
    const profilePic = document.createElement("img");

    fetchAndCreateProfilePic(post["username"], profilePic);

    profilePic.classList.add("profilePic");
    postDiv.appendChild(profilePic);
    postDiv.appendChild(flex2Div);
    
    profilePic.src = "profile.webp";

    const user = document.createElement("h3");
    user.innerText = post["username"];

    const date = document.createElement("label");
    date.innerText = post["date"];
    
    flex2Div.appendChild(user);
    flex2Div.appendChild(date);
    
    const p = document.createElement("p");
    postDiv.appendChild(p);
    if(post["img"]){
        const img = document.createElement("img");
        img.src=post["img"];
        img.style.width = "100%";
        postDiv.appendChild(img);
    }
    
    var iconDiv = document.createElement("div");

    var icon = document.createElement("i");
    icon.classList.add('far');
    icon.classList.add('fa-thumbs-up');
    var likes = document.createElement('label');
    likes.innerText = post["likes"];

    icon.appendChild(likes);
    iconDiv.appendChild(icon);
    iconDiv.width="100%";
    icon.onclick = function(event) {
        updateLikes(post["id"], likes);
    }

    postDiv.appendChild(iconDiv);
    p.innerHTML = post["content"];
    return postDiv;
}

function updateLikes(postId, likesElement){
    const http = new XMLHttpRequest();

    http.onload = function(){
        var likes = JSON.parse(http.responseText)["likes"];
        likesElement.innerText = likes;    
    }
    var url = "likes.php";
    http.open("POST", url, true);

    http.send(JSON.stringify({
        "postId" : postId
    }));
}

function getLastPosts(){
    const http = new XMLHttpRequest();
    http.onload = function(){
        var posts = JSON.parse(http.responseText);
        for(let i=0;i<posts.length;i++){
            
            const postList = document.getElementById("postList");   
            const postDiv = createPostDOM(posts[i]);
            postList.insertBefore(postDiv, postList.firstChild);            
            var postId = parseInt(posts[i]["id"]);

            if(lastId < postId){
                lastId = postId;
            }
        }
    }  
    var url = "posts.php?gt=" + lastId;
    http.open("GET", url, true);
    http.send();
}

function createPost(){
    var file = null;
    if(document.querySelector('#fileToUpload').length != 0){
        file = document.querySelector('#fileToUpload').files[0];
    }

    var postContent = document.getElementById("postInput").value;
    
    document.getElementById("postInput").value = "";
    
    var post = {
        "img": "",
        "title" : "",
        "content" : postContent
    }
    if(file){
        var reader = new FileReader();
        reader.readAsDataURL(file);

        reader.onload = function () {
            var base64 = reader.result;
            post["img"] = base64;
            postAJAX(post);
        };

        reader.onerror = function (error) {
            console.log('Error: ', error);
        };
    }else{
        postAJAX(post);
    }    
}

function postAJAX(body){
    var url = "addPost.php";
    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4) {   
            if (this.status == 409){
                alert("You are not logged in!");
            }
        }
    };   

    xhttp.open("POST", url, true);
    console.log(body);
    xhttp.send(JSON.stringify(body));
}

function logout(){
    window.location.href = "index.html";
}

function searchResult(){
    const div1 = document.getElementById("centerProfile");
    div1.style.display= "none";
    const div2 = document.getElementById("center-search-result");
    div2.style.display= "block";
    const usersLink = document.getElementById("usersLink");
    usersLink.className = "active";
    const homeLink = document.getElementById("homeLink");
    homeLink.className = "";
    searchResults();
    return false;
}

function home(){
    const postList = document.getElementById("postList");
    postList.innerHTML = "";
    loadPosts();
    const div1 = document.getElementById("centerProfile");
    div1.style.display= "flex";
    const div2 = document.getElementById("center-search-result");
    div2.style.display= "none";
    const usersLink = document.getElementById("usersLink");
    usersLink.className = "";
    const homeLink = document.getElementById("homeLink");
    homeLink.className = "active";
    return false;
}

function searchResults(){
    const query = document.getElementById("search").value;

    var xhttp = new XMLHttpRequest();

    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           let users = JSON.parse(this.responseText);
            
           const resultsDiv = document.getElementById("resultsDiv");
           resultsDiv.innerHTML = "";

           for(let i = 0; i < users.length; i++){
               createUserDOMelement(users[i]);
           }
        }
    };

    xhttp.open("GET", "searchUser.php?searchQuery=" + query + "&username=" +
    localStorage.getItem("user"), true);
    xhttp.send();
}   

function createUserDOMelement(user){
    const div = document.createElement("div");
    div.classList.add("user-flex");
    const label = document.createElement("label");
    label.textContent = user["username"];
    const followButton = document.createElement("button");
 
    followButton.innerText = "Follow";
    
    if(user["isFollowed"] == true){
        changeStyleOnFollow(followButton);
        followButton.onclick = function(){
            unfollowAction(user["username"], followButton);
        }
    }else{
        followButton.onclick = function(){
            followAction(user["username"], followButton);
        }
    }
 
    followButton.classList.add("follow-button");

    div.appendChild(label);
    div.appendChild(followButton);
    
    const resultsDiv = document.getElementById("resultsDiv");
    resultsDiv.appendChild(div);
}

function changeStyleOnFollow(followButton){
    followButton.style.backgroundColor = "red";
    followButton.innerText = "Unfollow";
}

function followAction(toFollow, followButton){
    followUser(localStorage.getItem("user"), toFollow);
    changeStyleOnFollow(followButton);
    followButton.onclick = function(){unfollowAction(toFollow, followButton)};
};

function unfollowAction(toUnfollow, followButton){
    unfollowUser(localStorage.getItem("user"), toUnfollow);
    followButton.style.backgroundColor = "teal";
    followButton.innerText = "Follow";
    followButton.onclick = function(){followAction(toUnfollow, followButton)};
}

function followUser(follower, following){

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

        }
    };

    xhttp.open("POST", "followers.php");
    
    xhttp.send(JSON.stringify({
        "follower" : follower,
        "following" : following,
        "follow" : true 
    }));
}

function unfollowUser(follower, following){

    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {

        }
    };

    xhttp.open("POST", "followers.php");
    
    xhttp.send(JSON.stringify({
        "follower" : follower,
        "following" : following,
        "follow" : false 
    }));
}