$(document).ready(function() {

    $("[data-toggle=tooltip]").tooltip();

//    ------- Modal Eliminar Usuario ----------  ////

    //$("#boton_eliminar").click(function() {
    //    var id=$(this).data('id');
    //    alert(id);
    //    $('#modal_eliminar').modal('show');
    //});

    /* $( "#eliminar" ).click(function() {
     //var id=$(this).data('id');
     //alert(id);

     $( "#form_eliminar" ).submit();
     });

     $( "#eliminar_curso" ).click(function() {
     //var id=$(this).data('id');
     //alert(id);

     $( "#form_eliminar_cursos" ).submit();
     });*/
//-------------------------------------------------------------------------------//


//  --------  Validar si el usuario Nuevo a crear será Participante o no ---------- ///

    var es_participante = $('input[name=es_participante]:checked').val();
    var mostrar = document.getElementsByClassName('mostrar');

    //alert("mostrar: " + mostrar.length);

    if(es_participante == 'si'){
        for(var i=0; i<mostrar.length; i++) {
            $('.mostrar').show();
        }
        $('#ocultar').hide();
    }else{
        for(var i = 0; i < mostrar.length; i++) {
            $('.mostrar').hide();
        }
        $('#ocultar').show();
    }
    $( 'input[name=es_participante]:radio' ).change(
        function() {
            var es_part = $('input[name=es_participante]:checked').val();
            //alert("Cambiooo");
            if (es_part == 'si') {
                for (var i = 0; i < mostrar.length; i++) {
                    $('.mostrar').show();
                }
                $('#ocultar').hide();
            } else {
                for (var i = 0; i < mostrar.length; i++) {
                    $('.mostrar').hide();
                }
                $('#ocultar').show();
            }
        }
    );

//-------------------------------------------------------------------------//


//  --------  Validar si el Curso Estara en el carrusel ---------- ///
    // $('#descripcion_carrusel').hide();
    //$('#imagen_carrusel').hide();
    if ($('#activo_carrusel:checkbox:checked').length <= 0) {
        //alert("dgwxfgwxf");
        $('#descripcion_carrusel').hide();
        $('#imagen_carrusel').hide();
    }else{
        $('#descripcion_carrusel').show();
        $('#imagen_carrusel').show();
    }

    $( '#activo_carrusel' ).change(function() {
        if($(this).is(":checked")) {
            $('#descripcion_carrusel').show();
            $('#imagen_carrusel').show();
            return;
        }
        $('#descripcion_carrusel').hide();
        $('#imagen_carrusel').hide();
    });


//-------------------------------------------------------------------------//

// ------ FadeOut para desaparecer notificaciones en un tiempo estimado ------ //
    $('#flash_success').fadeOut(5000);
//--------------------------------------------


// ------ Mostrar estado, ciudad, municipio y parroquia si el País es igual a Venezuela ------//

    $(".localidad").hide();

    $("#id_pais" ).change(function() {
        var pais = $("#id_pais :selected").text();

        if( pais == 'Venezuela'){
            $(".localidad").show();
        }else{
            $(".localidad").hide()
        }
    });

    $("#id_est").change(function(){
        var est= $("#id_est :selected").text();
        $.ajax({
            type: 'POST',
            url: "usuarios.show",
            data: est,
            success: function(data) {
                alert(data);
            }
        })
    });

//------------------------------------------------------------------------------//

});