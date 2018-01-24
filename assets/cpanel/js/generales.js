/* ------------------------------------------------------------------------------- */
    /*
        Variable para el idioma del datatable.
    */
    var idioma_espanol = {
        "sProcessing":     "Procesando...",
        "sLengthMenu":     "Mostrar _MENU_ registros",
        "sZeroRecords":    "No se encontraron resultados",
        "sEmptyTable":     "Ningún dato disponible en esta tabla",
        "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
        "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
        "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
        "sInfoPostFix":    "",
        "sSearch":         "Buscar:",
        "sUrl":            "",
        "sInfoThousands":  ",",
        "sLoadingRecords": "Cargando...",
        "oPaginate": {
            "sFirst":    "Primero",
            "sLast":     "Último",
            "sNext":     "Siguiente",
            "sPrevious": "Anterior"
        },
        "oAria": {
            "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
            "sSortDescending": ": Activar para ordenar la columna de manera descendente"
        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Function que deshabilita las taclas en un input, se utiliza usando el
        evento onKeyUp.
    */
    function deshabilitarteclas(e){
        key=e.keyCode || e.which;
        teclado=String.fromCharCode(key);
        numeros="";
        especiales="";//los numeros de esta linea son especiales y es para las flechas
        teclado_escpecial=false;
        for(var i in especiales)
            if (key==especiales[i])
                teclado_escpecial=true;
        if (numeros.indexOf(teclado)==-1 && !teclado_escpecial)
            return false;
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que se encarga de aceptar solo numeros en un input, se utiliza usando el
        evento onKeyUp.
    */
    function solonumeros(e){
        key=e.keyCode || e.which;
        teclado=String.fromCharCode(key);
        numeros="1234567890.-";
        especiales="8-9-17-37-38-46";//los numeros de esta linea son especiales y es para las flechass
        teclado_escpecial=false;
        for(var i in especiales)
            if (key==especiales[i])
                teclado_escpecial=true;
        if (numeros.indexOf(teclado)==-1 && !teclado_escpecial)
            return false;
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que se encargbar de aceptar solo letras en un input, se utiliza 
        usando el evento onKeyUp.
    */
    function sololetras(e){
        key=e.keyCode || e.which;
        teclado=String.fromCharCode(key);
        numeros="qwertyuiopasdfghjklñzxcvbnmQWERTYUIOPASDFGHJKLÑZXCVBNM ";
        especiales="8-9-17-37-38-46";//los numeros de esta linea son especiales y es para las flechass
        teclado_escpecial=false;
        for(var i in especiales)
            if (key==especiales[i])
                teclado_escpecial=true;
        if (numeros.indexOf(teclado)==-1 && !teclado_escpecial)
            return false;
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /* 
        Funcion para mostrar y ocultar los cuadros (div).
    */
    function cuadros(cuadroOcultar, cuadroMostrar){
        $(cuadroOcultar).slideUp("slow"); //oculta el cuadro.
        $(cuadroMostrar).slideDown("slow"); //muestra el cuadro.
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /* 
        Funcion para regresar al listado.
    */
    function regresar(cuadroOcultar){
        listar(cuadroOcultar);
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que envia los datos de los formularios.
    */
    function enviarFormulario(form, controlador, cuadro){
        $(form).submit(function(e){
            e.preventDefault(); //previene el comportamiento por defecto del formulario al darle click al input submit
            var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
            var formData=new FormData($(form)[0]); //obtiene todos los datos de los inputs del formulario pasado por parametros
            var method = $(this).attr('method'); //obtiene el method del formulario
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            $.ajax({
                url:url+controlador,
                type:method,
                dataType:'JSON',
                data:formData,
                cache:false,
                contentType:false,
                processData:false,
                beforeSend: function(){
                    mensajes('info', '<span>Guardando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                },
                error: function (repuesta) {
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    var errores=repuesta.responseText;
                    if(errores!="")
                        mensajes('danger', errores);
                    else
                        mensajes('danger', "<span>Ha ocurrido un error, por favor intentelo de nuevo.</span>");        
                },
                success: function(respuesta){
                    $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
                    mensajes('success', respuesta);
                    if(cuadro!="")
                        listar(cuadro);
                }
            });
        });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que muestra los mensajes al usuario.
        type = [default, primary, info, warning, success, danger]
    */
    function mensajes(type, msj){
        html='<div class="alert alert-'+type+'" role="alert">';
        html+='<button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>';
        html+=msj;
        html+='</div>';
        return $("#alertas").html(html);
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Functio que realiza el cambio del formato de fecha que trae el campo
        de la base de datos.
    */
    function cambiarFormatoFecha(date) {
      var info=date.split('-');
      var fecha=info[2]+'-'+info[1]+'-'+info[0];
      return fecha;
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que se encarga eliminar un registro seleccionado.
    */
    function eliminarConfirmacion(controlador, id, title){
        swal({
            title: title,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, Eliminar!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                swal.close();
                var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
                $.ajax({
                    url:url+controlador,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        id:id,
                    },
                    beforeSend: function(){
                        mensajes('info', '<span>Eliminando datos, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        mensajes('danger', errores);
                    },
                    success: function(respuesta){
                        listar();
                        $("#checkall").prop("checked", false);
                        mensajes('success', respuesta);
                    }
                });
            } else {
                swal("Cancelado", "No se ha eliminado el registro", "error");
            }
        });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que se encarga de cambiar el status de un registro seleccionado.
        status -> valor (1, 2, n...)
        confirmButton -> activar, desactivar
    */
    function statusConfirmacion(controlador, id, status, title, confirmButton){
        swal({
            title: title,
            type: "warning",
            showCancelButton: true,
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Si, "+confirmButton+"!",
            cancelButtonText: "No, Cancelar!",
            closeOnConfirm: true,
            closeOnCancel: false
        },
        function(isConfirm){
            if (isConfirm) {
                var url=document.getElementById('ruta').value; //obtiene la ruta del input hidden con la variable <?=base_url()?>
                $.ajax({
                    url:url+controlador,
                    type: 'POST',
                    dataType: 'JSON',
                    data:{
                        id:id,
                        status:status
                    },
                    beforeSend: function(){
                        mensajes('info', '<span>Guardando cambios, espere por favor... <i class="fa fa-spinner fa-spin" aria-hidden="true"></i></span>');
                    },
                    error: function (repuesta) {
                        var errores=repuesta.responseText;
                        mensajes('danger', errores);
                    },
                    success: function(respuesta){
                        listar();
                        $("#checkall").prop("checked", false);
                        mensajes('success', respuesta);
                    }
                });
            } else {
                swal("Cancelado", "Proceso cancelado", "error");
            }
        });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion anonima para seleccionar y deseleccionar los checkbox de las filas
    */
    $("#checkall").change(function(){
        $(".checkitem").prop("checked", $(this).prop("checked"));
    });
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion anonima captar los value de los checkbox seleccionamos
    */
    function eliminarMultiple(controlador){
        var id=$(".checkitem:checked").map(function(){
            return $(this).val();
        }).get();
        if(Object.keys(id).length>0)
            eliminarConfirmacion(controlador, id, "¿Esta seguro de eliminar los registros seleccionados?");
        else
            swal({
                title: "Debe seleccionar al menos una fila.",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Aceptar!",
                closeOnConfirm: true
            });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion anonima captar los value de los checkbox seleccionamos
    */
    function statusMultiple(controlador, status, confirmButton){
        var id=$(".checkitem:checked").map(function(){
            return $(this).val();
        }).get().join(' ');
        if(Object.keys(id).length>0)
            statusConfirmacion(controlador, id, status, "¿Esta seguro de "+confirmButton+" los registros seleccionados?", confirmButton);
        else
            swal({
                title: "Debe seleccionar al menos una fila.",
                type: "warning",
                confirmButtonColor: "#DD6B55",
                confirmButtonText: "Aceptar!",
                closeOnConfirm: true
            });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion para los input para telefonos
    */
    function telefonoInput(input){
        $(input).inputmask('+99 (999) 999-99-99', { placeholder: '+__ (___) ___-__-__' });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion para los input para telefonos
    */
    function porcentajeInput(input){
        $(input).inputmask('decimal', { rightAlign: true, groupSeparator: '.', autoGroup: true, radixPoint: ',' });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion para limpiar los selects
    */
    function eliminarOptions(select){
        for (var i=0; i<select.length; i++){
            if(select.options[i].value!="")
                select.remove(i);
        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion para mostrar un sweetalert warning
    */
    function warning(title){
        swal({
            title: title,
            type: "warning",
            confirmButtonColor: "#DD6B55",
            confirmButtonText: "Aceptar!",
            closeOnConfirm: true
        });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion para agregar options a los selects
    */
    function agregarOptions(select, value, text){
        $(select).append($('<option>', { 
            value: value,
            text : text
        }));
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion para agregar options a los selects
    */
    function elegirFecha(date){
        $(date).bootstrapMaterialDatePicker({
            format: 'DD-MM-YYYY',
            clearButton: true,
            weekStart: 0,
            time: false,
            clearText: 'Limpiar',
            cancelText: 'Cancelar'
        });
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    //Función para validar una CURP
    function curpValida(curp) {
        var re = /^([A-Z][AEIOUX][A-Z]{2}\d{2}(?:0[1-9]|1[0-2])(?:0[1-9]|[12]\d|3[01])[HM](?:AS|B[CS]|C[CLMSH]|D[FG]|G[TR]|HG|JC|M[CNS]|N[ETL]|OC|PL|Q[TR]|S[PLR]|T[CSL]|VZ|YN|ZS)[B-DF-HJ-NP-TV-Z]{3}[A-Z\d])(\d)$/,
        validado = curp.match(re);
        if (!validado)  //Coincide con el formato general?
            return false;
        //Validar que coincida el dígito verificador
        function digitoVerificador(curp17) {
            //Fuente https://consultas.curp.gob.mx/CurpSP/
            var diccionario  = "0123456789ABCDEFGHIJKLMNÑOPQRSTUVWXYZ",
                lngSuma      = 0.0,
                lngDigito    = 0.0;
            for(var i=0; i<17; i++)
                lngSuma = lngSuma + diccionario.indexOf(curp17.charAt(i)) * (18 - i);
            lngDigito = 10 - lngSuma % 10;
            if (lngDigito == 10) return 0;
            return lngDigito;
        }
        if (validado[2] != digitoVerificador(validado[1])) 
            return false;    
        return true; //Validado
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    //Handler para el evento cuando cambia el input
    //Lleva la CURP a mayúsculas para validarlo
    function validarInputCurp(input) {
        var curp = input.value.toUpperCase(),
            resultado = $("#validCurp"),
            valido = "No válido"; 
        if (curpValida(curp)) { // -> Acá se comprueba
            valido = "Válido";
            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            resultado.removeClass('focused error');
            $(".curpError").html('');
        } else {
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            resultado.addClass('focused error');
            $(".curpError").html('El C.U.R.P. ingresado es incorrecto');
        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    // funcion para validar correo
    function validarCorreo(validar, confirmar, error) {
        var correo1 = $(validar).val(),
            correo2 = $(confirmar).val(),
            resultado = $(error);
        if(correo1==correo2) {
            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            resultado.removeClass('focused error');
            $(".correoError").html('');
        } else {
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            resultado.addClass('focused error');
            $(".correoError").html('Los correos no coinciden');
        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    // funcion para validar correo
    function validarClave(validar, confirmar, error) {
        var clave1 = $(validar).val(),
            clave2 = $(confirmar).val(),
            resultado = $(error);
        if(clave1==clave2) {
            $('input[type="submit"]').removeAttr('disabled'); //activa el input submit
            resultado.removeClass('focused error');
            $(".claveError").html('');
        } else {
            $('input[type="submit"]').attr('disabled','disabled'); //desactiva el input submit
            resultado.addClass('focused error');
            $(".claveError").html('Las contraseñas no coinciden');
        }
    }
/* ------------------------------------------------------------------------------- */


/* ------------------------------------------------------------------------------- */
    /*
        Funcion que muestra la vista previa de la imagen y valida el tipo del file
    */
    function readURL(input, img, avatar){
        var val = $(avatar).val();
        switch(val.substring(val.lastIndexOf('.') + 1).toLowerCase()){
            case 'gif': case 'jpg': case 'png':
                if (input.files && input.files[0]) {
                    var reader = new FileReader();
                    reader.onload = function (e) {
                        $(img).attr('src', e.target.result);
                    };
                    reader.readAsDataURL(input.files[0]);
                }
                break;
            default:
                $(avatar).val('');
                swal({
                    title: "El archivo seleccionado no es una imagen.",
                    type: "error",
                    confirmButtonColor: "#DD6B55",
                    confirmButtonText: "Aceptar!",
                    closeOnConfirm: true
                });
                break;
        }
    }
/* ------------------------------------------------------------------------------- */

/* ------------------------------------------------------------------------------- */
    /*
        Funcion que muestra un loading.
    */
    function loading(){
        var loading = '<div class="demo-preloader" style="text-align: center;"><div class="preloader pl-size-xl">';
        loading += '<div class="spinner-layer pl-blue"><div class="circle-clipper left">';
        loading += '<div class="circle"></div></div><div class="circle-clipper right">';
        loading += '<div class="circle"></div></div></div></div></div>';
        return loading;
    }
/* ------------------------------------------------------------------------------- */