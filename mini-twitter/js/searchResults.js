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
 
    if(user["isFollowed"] == true){
        followButton.style.backgroundColor = "green";
        followButton.innerText = "Followed";    
    }else{
        followButton.innerText = "Follow";
    }
 
    followButton.classList.add("follow-button");
    followButton.onclick = function(){
        followUser(localStorage.getItem("user"), user["username"]);
    };
    div.appendChild(label);
    div.appendChild(followButton);
    
    const resultsDiv = document.getElementById("resultsDiv");
    resultsDiv.appendChild(div);
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
        "following" : following 
    }));
}