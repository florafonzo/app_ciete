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
    $('#flash_success').fadeToggle(4000);

//--------------------------------------------


// ------ Mostrar estado, ciudad, municipio y parroquia si el País es igual a Venezuela ------//
    $('.pais option:eq(0)').prop('selected', true)
    $(".localidad").hide();
    $(".localidad1").hide();
    $(".localidad2").hide();
    $(".localidad3").hide();

    $("#id_pais" ).change(function() {
        var pais = $("#id_pais :selected").text();

        if( pais == 'Venezuela'){
            $(".localidad").show();
        }else{
            $(".localidad").hide()
        }
    });


    $('#id_est').on('change', function(e) {

        var estado_id = e.target.value;
            //alert(estado_id);
        $.ajax({
            url:        "/ciudad/"+estado_id,
            dataType:   "json",
            success:    function(data){
                $('#ciudad').empty();
                $('#ciudad').append('<option value="0"  selected="selected"> Seleccione una ciudad</option>');
                $.each(data, function(index, ciudadObj){
                //console.log(ciudadObj.ciudad);
                    $('#ciudad').append('<option value="'+ciudadObj.id_ciudad+'">'+ciudadObj.ciudad+'</option>');
                    $(".localidad1").show();
                });
            }/*,
            error: function(jqXHR, textStatus, errorThrown) {
              console.log(textStatus, errorThrown);
            }
            error: function (request, status, error) {
                console.log(request.responseText);
                //alert(request.responseText);
            }*/

        });

        $.ajax({
            url:        "/municipio/"+estado_id,
            dataType:   "json",
            success:    function(data){
                $('#municipio').empty();
                $('#municipio').append('<option value="0"  selected="selected"> Seleccione un municipio</option>');
                $.each(data, function(index, ciudadObj){
                //console.log(ciudadObj.ciudad);
                    $('#municipio').append('<option value="'+ciudadObj.id_municipio+'">'+ciudadObj.municipio+'</option>');
                    $(".localidad2").show();
                });
            }/*,
            error: function(jqXHR, textStatus, errorThrown) {
              console.log(textStatus, errorThrown);
            }
            error: function (request, status, error) {
                console.log(request.responseText);
                //alert(request.responseText);
            }*/

        });


    });

 $('#municipio').on('change', function(e) {

//            alert('holaaaas');
        //console.log(e);
        $(".localidad3").show();
        var municipio_id = e.target.value;
        //alert(municipio_id);
        $.ajax({
            url:        "/parroquia/"+municipio_id,
            dataType:   "json",
            success:    function(data){
                $('#parroquia').empty();
                $('#parroquia').append('<option value="0"  selected="selected"> Seleccione una parroquia</option>');
                $.each(data, function(index, ciudadObj){
                //console.log(ciudadObj.ciudad);
                    $('#parroquia').append('<option value="'+ciudadObj.id_parroquia+'">'+ciudadObj.parroquia+'</option>');
                    
                });
            }/*,
            error: function(jqXHR, textStatus, errorThrown) {
              console.log(textStatus, errorThrown);
            }
            error: function (request, status, error) {
                console.log(request.responseText);
                //alert(request.responseText);
            }*/

        });

    });
//------------------------------------------------------------------------------//




});
//------------------------Función para eliminar --------------------------------------------//
function mostrarModal(id) {
    swal({
            title: "¿Está seguro que desea eliminar?",
            text: "Si lo elimina no podrá recuperarlo nuevamente!",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: "Eliminar",
            closeOnConfirm: false
        },
        function(){
            $('#form_eliminar'+id).submit();
        })

}
//------------------------------------------------------------------------------//

//------------------------Función para desactivar curso --------------------------------------------//

function desactivarCurso(id) {
    swal({
            title: "¿Está seguro que desea desactivar el curso?",
            text: "Si lo desactiva, se eliminará de la lista de cursos disponibles",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: "Desactivar",
            closeOnConfirm: false
        },
        function(){
            $('#form_desactivar'+id).submit();
        })
}
//------------------------------------------------------------------------------//

//------------------------Función para activar curso --------------------------------------------//

function activarCurso(id) {
    swal({
            title: "¿Está seguro que desea activarlo?",
            text: "Si lo activa, aparecera en la lista de cursos disponibles",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: 'green',
            confirmButtonText: "Activar",
            closeOnConfirm: false
        },
        function(){
            $('#form_activar'+id).submit();
        })
}
//------------------------------------------------------------------------------//

