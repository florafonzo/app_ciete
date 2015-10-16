$(document).ready(function() {
	//alert('Heyyyyyyyyy');
	//$( "#boton" ).click(function() {
	//    var cl1 = $('#clave').val();
	//    var cl2 = $('#clave2').val();
	//    var ok = true;
	//    if (cl1 != cl2) {
	//        alert("Passwords Do not match");
	//        $('clave').val().style.borderColor = "#E34234";
	//        $('clave2').val().style.borderColor = "#E34234";
	//        ok = false;
	//    }
	//    else {
	//        alert("Passwords Match!!!");
	//    }
	//});

    //$('.dropdown-toggle').dropdown();

    $("#menu-button").on('click', function(e) {
        e.preventDefault();
        $('ul.menu').slideToggle();
    });

});