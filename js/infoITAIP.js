function myFunction(id) {
    var x = document.getElementById(id);
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}