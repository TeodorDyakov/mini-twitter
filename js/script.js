function logIn(){
    var username = document.getElementById("username").value;
    var pass = document.getElementById("password").value;
    
    var url = "login.php";

    var JSON_string = JSON.stringify({
        "username": username,
        "pass": pass,
    });
    
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.status == 200) { 
            localStorage.setItem("user", username);
            window.location="newsfeed.html";
        }
        else if(this.status == 409){
            var p = document.getElementById('alert');
            p.style.display="block";
            p.style.color="red";
        }
    };
    
    xhttp.open("POST", url, true);
    xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhttp.send(JSON_string);
    
}

function ajaxPOST(url, json){
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function() {
        if (this.readyState == 4 && this.status == 200) {  
        }
    };
    
    xhttp.open("POST", url, true);
    xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
    xhttp.send(json);
}

function register(){
    var username = document.getElementById("username").value;
    var pass = document.getElementById("password").value;
    var repeatPass = document.getElementById("repeatPassword").value;
    
    var url = "register.php";

    var JSON_string = JSON.stringify({
        "username": username,
        "pass": pass,
    });
    let tooShort = document.getElementById("too-short");
    let usernameTaken = document.getElementById("username-taken");
    let dontMatch = document.getElementById("dont-match");
    tooShort.style.display = "none";
    usernameTaken.style.display = "none";
    dontMatch.style.display = "none";
    
    let passOk = true;
    
    if(pass.length < 6){
        tooShort.style.display = "block";
        passOk = false;
    }
    if(pass != repeatPass){
        dontMatch.style.display = "block";
        passOk = false;
    }

    if(passOk){
        var xhttp = new XMLHttpRequest();
        xhttp.onreadystatechange = function() {
            if (this.status == 200) {
                localStorage.setItem("user", username);
                window.location="newsfeed.html";
            }

            else if(this.status==409){
                usernameTaken.style.display = "block";
            }
        };
        
        xhttp.open("POST", url, true);
        xhttp.setRequestHeader("Content-Type", "application/json;charset=UTF-8");
        xhttp.send(JSON_string);
    }
}
