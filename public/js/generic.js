$(document).ready(function() {
    //alert('ahhh');
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


//  --------  Validar si el Curso Estará en el carrusel ---------- ///
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
    $('.flash_time').fadeToggle(4000);

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

//    -------------Resaltar input file ----------------//
//    var cambio = $( "#imagen").val();
//    alert(cambio);
//    if(cambio == 'yes'){
//        $( "#perfil").css({'border-style' : 'solid', 'border-color' : '#1968b3'});
//        $( "#file_perfil").css({'background-color' : '#fff'});
//        $( "#borde").css({'padding' : '8px'});
//
//    }

//    -----------------------------------------------------

//------------------------ Modal notas(profesor) ------------------------------------------//
//    $("#agregar_nota").on("click", function(){
//        $("#notasModal").modal();
//    });

   //$(".edit_nota").on("click", function () { // Click to only happen on announce links
   //     var id = $(this).data('id');
   //     var curso = $(this).data('curso');
   //     var seccion = $(this).data('seccion');
   //     var part = $(this).data('part');
   //     //alert('id: ' + id + ' curso:  '+curso+' Secc: '+seccion+' part:  '+part);
   //     /*$("#notaId").val($(this).data('id'));
   //     $('input[name=evaluacion]').val("'{{$nota["+id+"]->evaluacion}}'");
   //     $('#notasEditModal').modal('show');*/
   //
   //     $.ajax({
   //         cache: false,
   //         type: 'GET',
   //         url: '/profesor/cursos/'+curso+'/secciones/'+seccion+'/participantes/'+part+'/notas/'+id,
   //         success: function(data)
   //         {
   //             alert(data[0]);
   //             $('#notasEditModal').modal('show');
   //             //$('#modalContent').show().html(data);
   //         },
   //         error: function(jqXHR, textStatus, errorThrown) {
   //           console.log(textStatus, errorThrown);
   //         }
   //     });
   //
   //});

    $(".edit_nota").on("click", function () {
        var id = $(this).data('id');
        var curso = $(this).data('curso');
        var seccion = $(this).data('seccion');
        var part = $(this).data('part');

        $.ajax({
            url:        "/nota/"+id,
            dataType:   "json",
            success:    function(data){
                $('#eval').empty();
                $('#calif').empty();
                $('#porct').empty();
                $('#id_nota').val(null);
                $('#id_nota').val(String(id));
                $.each(data, function(index, Obj){
                    console.log(Obj.nota);
                    $('#eval').append('<input type="text" name="evaluacion" class="form-control" value="'+Obj.evaluacion+'" required />');
                    $('#calif').append('<input type="text" name="nota" class="form-control" value="'+Obj.nota+'" required />');
                    $('#porct').append('<input type="text" name="porcentaje" class="form-control" value="'+Obj.porcentaje+'" required />');

                    alert(String(id));
                    $('#notasEditModal').modal('show');
                });
             },
             error: function(jqXHR, textStatus, errorThrown) {
             console.log(textStatus, errorThrown);
             }/*
             error: function (request, status, error) {
             console.log(request.responseText);
             //alert(request.responseText);
             }*/

        });

    });


    /*$("#edit_nota").on("click", function () {
        var myBookId = $(this).data('id');
        $("#notasEditModal ").val( myBookId );
        $('#notasEditModal').modal('show');
    });*/


//-----------------------------------------------------------------------------------------//

//---------------------------------- Buscador ---------------------------------------------//
    $( '#param' ).change(function() {
        //alert($( "#param option:selected" ).val());
        if (($( '#param option:selected').val()) == 'rol'){
            $('#busqueda').hide();
            $('#busqueda2').show();
        }else{
            $('#busqueda').show();
            $('#busqueda2').hide();
        }
    });

    $( '#param1' ).change(function() {
        if (($( '#param1 option:selected').val()) == 'tipo'){
            $('#busq').hide();
            $('#busq2').show();
        }else{
            $('#busq').show();
            $('#busq2').hide();
        }
    });
//-----------------------------------------------------------------------------------------//

// --------------------------------- Imagen Crop ------------------------------------------//

    $('#enviar').hide();
    $('#imagen2').hide();
    $('#aceptar').hide();
    $('#alerta_img').hide();
    $( "#file_perfil" ).on("change", function(e){
        var nombre = $( "#file_perfil").val();
        var ext = nombre.split('.').pop();
        if(ext!= null && ((ext == 'jpeg') || (ext == 'jpg'))) {
            var dir = e.target.files;
            var romper = true;
            $.each(dir, function (index, file) {

                var fileReader = new FileReader();

                // When the filereader loads initiate a function
                fileReader.onload = (function (file) {

                    return function (e) {

                        // This is the image
                        var image = this.result;
                        $('#imagen2').attr('src', image);
                        //$('#imagen2').hide();

                    };


                })(dir[index]);

                // For data URI purposes
                fileReader.readAsDataURL(file);
                if (romper) return false;
            });

            setTimeout(function () {
                var imagee = $('#imagen2');
                var source = imagee.attr('src');
                $('#imagen').attr('src', source);
                $('.esconder').show();
                jQuery("#imagenModal").modal({backdrop: 'static', keyboard: false});
                resizeableImage($('.resize-image'));
            }, 50);
            $('#nombre_hidden').val($('#nombre').val());
            $('#descripcion_hidden').val($('#descripcion').val());
        }else{
            $('#alerta_img').show();
        }
    });
//------------------------------------------------------------------------------------------//

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
            text: "Si lo activa, aparecerá en la lista de cursos disponibles",
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

// ------------------------Función para activar webinar --------------------------------------------//

function activarWebinar(id) {
    swal({
            title: "¿Está seguro que desea activarlo?",
            text: "Si lo activa, aparecerá en la lista de webinars disponibles",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: 'green',
            confirmButtonText: "Activar",
            closeOnConfirm: false
        },
        function(){
            $('#webinar_activar'+id).submit();
        })
}
//------------------------------------------------------------------------------//

//------------------------Función para eliminar participante de un curso --------------------------------------------//
function eliminarPart(id) {
    swal({
            title: "¿Está seguro que desea eliminar el participante del curso?",
            text: "Si lo elimina no aparecerá en la lista de alumnos del curso y se eliminará su historial",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: "Eliminar",
            closeOnConfirm: false
        },
        function(){
            $('#form_eliminar_part'+id).submit();
        })

}
//------------------------------------------------------------------------------//

// ------------------------Función para agregar participante a un curso --------------------------------------------//

function agregarPart(id) {
    swal({
            title: "¿Está seguro que desea agregar el participante al curso?",
            text: "Si lo agrega, aparecerá en la lista de participantes del curso",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: 'green',
            confirmButtonText: "Agregar",
            closeOnConfirm: false
        },
        function(){
            $('#part_agregar'+id).submit();
        })
}
//------------------------------------------------------------------------------//

//------------------------Función para eliminar profesores de un curso --------------------------------------------//
function eliminarProf(id) {
    swal({
            title: "¿Está seguro que desea eliminar el profesor del curso?",
            text: "Si lo elimina no aparecerá en la lista de profesores que dictan el curso",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: "Eliminar",
            closeOnConfirm: false
        },
        function(){
            $('#form_eliminar_prof'+id).submit();
        })

}
//------------------------------------------------------------------------------//

// ------------------------Función para agregar profesores a un curso --------------------------------------------//

function agregarProf(id) {
    swal({
            title: "¿Está seguro que desea agregar el profesor al curso?",
            text: "Si lo agrega, aparecerá en la lista de profesores que dictan el curso",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: 'green',
            confirmButtonText: "Agregar",
            closeOnConfirm: false
        },
        function(){
            $('#prof_agregar'+id).submit();
        })
}
//------------------------------------------------------------------------------//
//------------------------Función para eliminar participante de un webinar --------------------------------------------//
function eliminarPartW(id) {
    swal({
            title: "¿Está seguro que desea eliminar el participante del webinar?",
            text: "Si lo elimina no aparecerá en la lista de alumnos del webinar y se eliminará su historial",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: "Eliminar",
            closeOnConfirm: false
        },
        function(){
            $('#eliminar_part_web'+id).submit();
        })

}
//------------------------------------------------------------------------------//

// ------------------------Función para agregar participante a un webinar --------------------------------------------//

function agregarPartW(id) {
    swal({
            title: "¿Está seguro que desea agregar el participante al webinar?",
            text: "Si lo agrega, aparecerá en la lista de participantes del webinar",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: 'green',
            confirmButtonText: "Agregar",
            closeOnConfirm: false
        },
        function(){
            $('#part_agregar_web'+id).submit();
        })
}
//------------------------------------------------------------------------------//

//------------------------Función para eliminar profesores de un webinar --------------------------------------------//
function eliminarProfW(id) {
    swal({
            title: "¿Está seguro que desea eliminar el profesor del webinar?",
            text: "Si lo elimina no aparecerá en la lista de profesores que dictan el webinar",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: '#DD6B55',
            confirmButtonText: "Eliminar",
            closeOnConfirm: false
        },
        function(){
            $('#eliminar_prof_web'+id).submit();
        })

}
//------------------------------------------------------------------------------//

// ------------------------Función para agregar profesores a un webinar --------------------------------------------//

function agregarProfW(id) {
    swal({
            title: "¿Está seguro que desea agregar el profesor al webinar?",
            text: "Si lo agrega, aparecerá en la lista de profesores que dictan el webinar",
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: 'green',
            confirmButtonText: "Agregar",
            closeOnConfirm: false
        },
        function(){
            $('#prof_agregar_web'+id).submit();
        })
}
//------------------------------------------------------------------------------//
//------------------------Función para desactivar preinscripcion ---------------//

function desactivarPrecurso(id) {
    swal({
            title: "¿Está seguro que desea desactivar el curso?",
            text: "Si lo desactiva, no estará disponible en la preinscripción",
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

//------------------------Función para activar preinscripcion ------------------//

function activarPrecurso(id) {
    swal({
            title: "¿Está seguro que desea activarlo?",
            text: "Si lo activa, aparecerá en la lista de cursos disponibles",
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


CKEDITOR.replace('.ckeditor');

