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
    $('#flash_success').fadeToggle(500);

//--------------------------------------------


// ------ Mostrar estado, ciudad, municipio y parroquia si el País es igual a Venezuela ------//

    $(".localidad").hide();
    $(".localidad1").hide();

    $("#id_pais" ).change(function() {
        var pais = $("#id_pais :selected").text();

        if( pais == 'Venezuela'){
            $(".localidad").show();
        }else{
            $(".localidad").hide()
        }
    });

    //$("#id_est").change(function(){
    //    var est= $("#id_est :selected").text();
    //    $.ajax({
    //        type: 'POST',
    //        url: "usuarios.show",
    //        data: est,
    //        success: function(data) {
    //            alert(data);
    //        }
    //    })
    //});

    $('#id_est').on('change', function(e) {

//            alert('holaaaas');
        //console.log(e);
        var estado_id = e.target.value;
            //alert(estado_id);
        $.ajax({
            url:        "/direccion?estado_id="+estado_id,
            dataType:   "json",
            success:    function(data){
                console.log(data);
                $('#ciudad').empty();
                $.each(data, function(index, ciudadObj){
                    $('#ciudad').append('<option value="'+ciudadObj.capital+'">'+ciudadObj.capital+'</option>');
                    $(".localidad1").show();
                    //$('#ciudad').append("{!! Form::select('id_ciudad',"+ ciudadObj.capital+", null, array('required', 'class' => 'form-control', 'id'=>'id_ciudad'))!!}");
                });
            }
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

