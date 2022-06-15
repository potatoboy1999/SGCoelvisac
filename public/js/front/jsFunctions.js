
menuOpen = false;
function openNav(pNav, pcConten) {
    if (menuOpen){
        menuOpen = false;
    } else {
        menuOpen = true;
    }

    if (menuOpen == true) {
        document.getElementById(pNav).style.width = "250px";
        document.getElementById(pcConten).style.marginLeft = "250px";
    } else {
        document.getElementById(pNav).style.width = "0";
        document.getElementById(pcConten).style.marginLeft= "0";
    }
}

function closeNav(pNav, pcConten) {
    document.getElementById(pNav).style.width = "0";
    document.getElementById(pcConten).style.marginLeft= "0";
}