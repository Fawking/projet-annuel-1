
var nav = document.getElementById("nav");
   function responsiveNavbar() {
    if(nav.className===""){
      nav.className+= "responsive";
    }
    else if(nav.className==="responsive"){
      nav.className="";
    }
  }