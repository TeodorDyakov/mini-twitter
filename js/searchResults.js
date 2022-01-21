    function searchResults(){
    const urlParams = new URLSearchParams(window.location.search);
    const query = urlParams.get('query');
    console.log(query);
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {
           let users = JSON.parse(this.responseText);
           console.log(users);
           for(let i = 0; i < users.length; i++){
               createUserDOMelement(users[i]);

           }
        }
    };

    xhttp.open("GET", "searchUser.php?searchQuery=" + query + "&username=" +
    localStorage.getItem("user"), true);
    xhttp.send();
}   

searchResults();

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