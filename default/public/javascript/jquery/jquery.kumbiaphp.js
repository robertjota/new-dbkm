/**
 * KumbiaPHP web & app Framework
 *
 * LICENSE
 *
 * This source file is subject to the new BSD license that is bundled
 * with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://wiki.kumbiaphp.com/Licencia
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@kumbiaphp.com so we can send you a copy immediately.
 *
 * Plugin para jQuery que incluye los callbacks basicos para los Helpers
 *
 * @copyright  Copyright (c) 2005-2014 Kumbia Team (http://www.kumbiaphp.com)
 * @license	http://wiki.kumbiaphp.com/Licencia	 New BSD License
 */

(function($) {
    /**
     * Objeto KumbiaPHP
     *
     */
    $.KumbiaPHP = {
        /**
         * Ruta al directorio public en el servidor
         *
         * @var String
         */
        publicPath : null,

        /**
         * Plugins cargados
         *
         * @var Array
         */
        plugin: [],

/**
         * Muestra mensaje de confirmacion con SweetAlert
         *
         * @param Object event
         */
        cConfirm: function(event) {
            event.preventDefault();
            var este        = $(this);            
            var data_body   = este.attr('msg');
            var data_title  = este.attr('msg-title');
            if(data_title==undefined) {
                data_title = '¿Estás seguro?';
            }
            if(data_body==undefined) {
                data_body = '¿Estás seguro de continuar con esta operación?';
            }
            
            var href = este.attr('href');
            
            Swal.fire({
                title: data_title,
                text: data_body,
                icon: 'warning',
                showCancelButton: true,
                confirmButtonText: 'Aceptar',
                cancelButtonText: 'Cancelar',
                confirmButtonColor: '#d98f0e',
                cancelButtonColor: '#6c757d'
            }).then(function(result) {
                if (result.isConfirmed) {
                    if(este.attr('on-confirm')!=undefined) {
                        fn = este.attr('on-confirm')+'(este)';
                        eval(fn);
                    } else {
                        window.location.href = href;
                    }
                }
            });
            
        },
        
        /**
         * Muestra mensaje para seleccionar el tipo de reporte
         *
         * @param Object event
         */
        cReport: function(event) {
            event.preventDefault();
            var este = $(this);
            var reporte = $("#modal_reporte");
            var data_title = este.attr('msg-title');
            var data_format = este.attr('data-report-format').split('|');
            if(data_title===undefined) {
                data_title = 'Imprmir reporte';
            }
            if ($("#modal_confirmar").length > 0 ){
                reporte.empty();
            } else {                
                reporte = $('<div id="modal_reporte" tabindex="-1" role="dialog" aria-labelledby="modal_confirmar" aria-hidden="true"></div>');
            }

            var tmp_check = '';
            for(i=0 ; i < data_format.length ; i++) {
                tmp_checked = (i==0) ? 'checked="checked"' : '';
                tmp_check = tmp_check + '<label class="checkbox-inline" style="font-size: 12px;"><input name="report-format-type" type="radio" '+tmp_checked+' value="'+data_format[i].toLowerCase()+'" style="margin: 0px;">&nbsp;'+data_format[i].toUpperCase()+'</label>';
            }
            var tmp_form = '<div class="row"><form>'+tmp_check+'</form></div>';

            reporte.addClass('modal fade');
            
            var cajon       = $('<div class="modal-dialog"></div>');
            var contenedor  = $('<div class="modal-content"></div>');
            var header      = $('<div><button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button><h4 class="modal-title"><i class="icon-warning-sign" style="padding-right:5px; margin-top:5px;"></i>'+data_title+'</h4></div>').addClass('modal-header');
            var cuerpo      = $('<div><p>En qué formato deseas ver este reporte?</p><p>Recuerda reciclar el papel</p>'+tmp_form+'</div>').addClass('modal-body');
            var footer      = $('<div></div>').addClass('modal-footer');

            contenedor.append(header);
            contenedor.append(cuerpo);
            contenedor.append(footer);                                                                                    
            cajon.append(contenedor);
            reporte.append(cajon);            
                                    
            footer.append('<button class="btn btn-success">Aceptar</a>');
            footer.append('<button class="btn btn-default" data-dismiss="modal" aria-hidden="true">Cancelar</button>');
            $('.btn-success', reporte).on('click',function(){
                reporte.modal('hide')
                checked = $("input:checked", reporte).val();
                popup_url = rtrim(este.attr('href'), '/')+'/'+checked+'/';
                popupReport(popup_url);
            });
            reporte.modal();
            $(reporte).on('shown', function () {
                $('input[type=radio]:checked', reporte).focus();
            });
        },

        /**
         * Aplica un efecto a un elemento
         *
         * @param String fx
         */
        cFx: function(fx) {
            return function(event) {
                event.preventDefault();
                var este=$(this),
                    rel = $('#'+este.data('to'));
                rel[fx]();
            }
        },

        /**
         * Carga con AJAX usando kload
         *
         * @param Object event
         */
        cRemote: function(event) {
            
            event.preventDefault();
            var este = $(this);
            if(este.hasClass('no-ajax')) {
                if(este.attr('href') != '#' && este.attr('href') != '#/' && este.attr('href') != '#!/') {
                    location.href = ""+este.attr('href')+"";
                }
            }
            if(este.hasClass('no-load')) {
                return false;
            }
            if(este.hasClass('js-confirm')) {
                return this.cRemoteConfirm;
            }
            var val = true;
            var capa        = (este.attr('data-to')!=undefined) ? este.attr('data-to') : 'shell-content';
            var spinner     = este.hasClass('js-spinner') ? true : false;
            var change_url  = este.hasClass('js-url') ? true : false;            
            var url         = este.attr('href');
            var before_load = este.attr('before-load');
            var after_load  = este.attr('after-load');
            if(before_load!=null) {
                try { val = eval(before_load); } catch(e) { }
            }
            
            if(val) {                
                // Verificar que la URL no sea un ancla o hash
                var hashCheck = $.KumbiaPHP.publicPath+'#';
                if(url != hashCheck && url.indexOf('#') !== 0) {
                    var self = this;
                    // Usar $.kpost para detectar JSON en la respuesta
                    $.ajax({
                        url: url,
                        type: 'GET',
                        beforeSend: function(xhr) {
                            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                        }
                    }).done(function(response) {
                        // Intentar parsear como JSON
                        var jsonResponse = null;
                        var isJson = false;
                        if (typeof response === 'object' && response !== null) {
                            jsonResponse = response;
                            isJson = true;
                        } else if (typeof response === 'string') {
                            try {
                                jsonResponse = JSON.parse(response);
                                isJson = true;
                            } catch(e) {}
                        }
                        
                        // Si es JSON, manejar como respuesta JSON
                        if (isJson && jsonResponse && jsonResponse.status) {
                            if (jsonResponse.status === 'success') {
                                Swal.fire({
                                    icon: 'success',
                                    title: 'Éxito',
                                    text: jsonResponse.message || 'Operación completada',
                                    timer: 2000,
                                    showConfirmButton: false
                                }).then(function() {
                                    if (jsonResponse.url && jsonResponse.reloadMenu) {
                                        location.href = jsonResponse.url;
                                    } else if (jsonResponse.url) {
                                        $.kload({ url: jsonResponse.url, spinner: true, change_url: true });
                                    } else if (jsonResponse.reloadMenu) {
                                        location.reload();
                                    }
                                });
                            } else if (jsonResponse.status === 'error') {
                                Swal.fire({
                                    icon: 'error',
                                    title: 'Error',
                                    text: jsonResponse.message
                                });
                            } else if (jsonResponse.status === 'validation') {
                                Swal.fire({
                                    icon: 'warning',
                                    title: 'Validación',
                                    html: jsonResponse.message + '<br>' + (jsonResponse.errors ? Object.values(jsonResponse.errors).join('<br>') : '')
                                });
                            }
                        } else {
                            // Si la URL es de lista, hacer full reload para DataTables
                            if (url.indexOf('listar') > -1) {
                                location.reload();
                            } else {
                                $('#' + capa).hide().html(response).show('fast');
                            }
                        }
                    });
                }
            }
            
        },

        /**
         * Carga con AJAX y Confirmacion
         *
         * @param Object event
         */
        cRemoteConfirm: function(event) {
            var este=$(this), rel = $('#'+este.data('to'));
            event.preventDefault();
            if(confirm(este.data('msg'))) {
                rel.load(this.href);
            }
        },

        /**
         * Enviar formularios de manera asincronica, via POST
         * Y los carga en un contenedor
         */
        cFRemote: function(event){
            event.preventDefault();
            var este = $(this);
            var button = $('[type=submit]', este);
            button.attr('disabled', 'disabled');
            var url = este.attr('action');
            var div = este.attr('data-to');
            
            // Si tiene clase js-remote, usar kpost para formularios AJAX
            if (este.hasClass('js-remote')) {
                $.kpost({
                    url: url,
                    data: este.serialize(),
                    spinner: !este.hasClass('no-spinner')
                });
                // El botón se habilita en el callback de kpost
            } else {
                // Comportamiento original para formularios normales
                var spinner = este.hasClass('no-spinner') ? false : true;
                var options = { capa: div, spinner: spinner, msg: true, url: url, change_url: false, method: 'POST', data: este.serialize() };
                if($.kload(options)) {
                    button.attr('disabled', null);
                }
            }
            
        },

        /**
         * Carga con AJAX al cambiar select
         *
         * @param Object event
         */
        cUpdaterSelect: function(event) {
            var $t = $(this),$u= $('#' + $t.data('update'))
            url = $t.data('url');
            $u.empty();
            $.get(url, {'id':$t.val()}, function(d){
                for(i in d){
                    var a = $('<option />').text(d[i]).val(i);
                    $u.append(a);
                }
            }, 'json');
        },                
        
        /**
         * Pasa al siguiente tab
         * 
         * @param Object event
         */
        cNextTab: function(event) {
            var tabs = $(this).parents('div.tab-pane:first');
            var next = tabs.next();
            if(next.hasClass('tab-pane')) {                
                $('[href="#'+next.attr('id')+'"]').click();
                next.find(':input:first').focus();
            }
        },  
        
        /**
         * Pasa al anterior tab
         * 
         * @param Object event
         */
        cPrevTab: function(event) {
            var tabs = $(this).parents('div.tab-pane:first');
            var next = tabs.prev();
            if(next.hasClass('tab-pane')) {                
                $('[href="#'+next.attr('id')+'"]').click();
                next.find(':input:first').focus();
            }
        },

        /**
         * Enlaza a las clases por defecto
         *
         */
        bind : function() {
            // Enlace y boton con confirmacion
            $("body").on('click', 'a.js-confirm, input.js-confirm', this.cConfirm);

            // Enlace ajax (legacy)
            $("body").on('click', 'a.js-remote', this.cRemote);
            
            // Enlace ajax con js-link class (main AJAX navigation)
            $("body").on('click', 'a.js-link, a.js-spinner', this.cRemote);

            // Enlace ajax con confirmacion
            $("a.js-remote-confirm").on('click', this.cRemoteConfirm);

            // Efecto show
            $("body").on('click', 'a.js-show', this.cFx('show'));

            // Efecto hide
            $("body").on('click', 'a.js-hide', this.cFx('hide'));

            // Efecto toggle
            $("body").on('click', 'a.js-toggle', this.cFx('toggle'));

            // Efecto fadeIn
            $("body").on('click', 'a.js-fade-in', this.cFx('fadeIn'));

            // Efecto fadeOut
            $("body").on('click', 'a.js-fade-out', this.cFx('fadeOut'));

            // Formulario ajax
            $("body").on('submit', "form.js-remote", this.cFRemote);
                        
            // Lista desplegable que actualiza con ajax
            $("body").on('click', 'select.js-remote', this.cUpdaterSelect);
            
            // Next tab
            $("body").on('click', ".js-next-tab", this.cNextTab);
            
            // Back tab
            $("body").on('click', ".js-prev-tab", this.cPrevTab);
            
            // Report
            $("body").on('click', ".js-report", this.cReport);
                        
            // Enlazar DatePicker
            $.KumbiaPHP.bindDatePicker();
            
            // Enlazar Upload
            $.KumbiaPHP.bindFileUpload();

        },

        /**
         * Implementa la autocarga de plugins, estos deben seguir
         * una convención para que pueda funcionar correctamente
         */
        autoload: function(){
            var elem = $("[class*='jp-']");
            $.each(elem, function(i, val){
                var este = $(this);
                var classes = este.attr('class').split(' ');
                for (i in classes){
                    if(classes[i].substr(0, 3) == 'jp-'){
                        if($.inArray(classes[i].substr(3),$.KumbiaPHP.plugin) != -1)
                            continue;
                        $.KumbiaPHP.plugin.push(classes[i].substr(3))
                    }
                }
            });
            var head = $('head');
            for(i in $.KumbiaPHP.plugin){
                $.ajaxSetup({ cache: true});
                head.append('<link href="' + $.KumbiaPHP.publicPath + 'css/' + $.KumbiaPHP.plugin[i] + '.css" type="text/css" rel="stylesheet"/>');
                $.getScript($.KumbiaPHP.publicPath + 'javascript/jquery/jquery.' + $.KumbiaPHP.plugin[i] + '.js', function(data, text){});
            }
        },

        /**
         * Carga y Enlaza DatePicker si es necesario
         * Nota: No se usa datetimepicker en este proyecto
         */
        bindDatePicker: function() {
            // DatePicker no requerido en este proyecto
            return;
        },
        
        /**
         * Carga de archivos por ajax
         */
        bindFileUpload: function() {
            var files = $('.js-upload');
            
            files.each(function() {  
                var este = $(this);
                var id = este.attr('id');                    
                var bar = 'progress_'+id;
                if($('#'+bar).length === 0) {
                    este.parent().after('<div id="'+bar+'" class="progress fade progress-striped active" style="margin-top: 5px;"><div class="progress-bar progress-bar-success"></div></div>');
                }
                var prgss = $('#'+bar);
                                
                $('#'+id).fileupload({
                    url: este.attr('data-to'),
                    dataType: (este.attr('data-type') === undefined) ? 'json' : este.attr('data-type'),
                    maxFileSize: (este.attr('data-size') === undefined) ? 5000000 : este.attr('data-size'),
                    acceptFileTypes: (este.attr('data-files') === undefined) ? /(\.|\/)(gif|jpe?g|png)$/i : este.attr('data-files'),                    
                    start: function() {                        
                        prgss.removeClass('fade');
                        prgss.find('.progress-bar:first').removeClass('progress-bar-danger').addClass('progress-bar-success');
                        $('[type=submit]').attr('disabled', 'disabled');
                    },
                    progress: function (e, data) {
                        var progress = parseInt(data.loaded / data.total * 100, 10);
                        prgss.find('.progress-bar:first').css('width', progress + '%');
                    },
                    add: function (e, data) {
                        var jqXHR = data.submit()
                        .done(function (result, textStatus, jqXHR) {
                            if(textStatus!='success' || result.error==true) {
                                prgss.find('.progress-bar:first').removeClass('progress-bar-success').addClass('progress-bar-danger');
                                flashError('Oops! el archivo no se ha podido cargar. <br />Detalle del error: '+(result.message!=null) ? result.message : textStatus);
                            } else {
                                flashValid((result.message === undefined) ? 'El archivo se ha cargado correctamente!' : result.message);
                                if(este.attr('data-success') != undefined) {
                                    fn = este.attr('data-success')+'(result, este)';
                                    eval(fn);
                                }                                
                            }
                        })
                        .fail(function (jqXHR, textStatus, errorThrown) {
                            if(textStatus!=null){
                                flashError('Oops! el archivo no se ha podido cargar. <br />Detalle del error: '+textStatus);                    
                            } else {
                                flashError('Oops! al parecer el archivo no es de un formato valido. <br />Intenta con otro archivo.');
                            }
                            prgss.find('.progress-bar:first').removeClass('progress-bar-danger').addClass('progress-bar-success');
                            prgss.addClass('fade');
                        })
                        .always(function () {
                            prgss.addClass('fade'); prgss.find('.progress-bar:first').css('width','0%');
                            $('[type=submit]').removeAttr('disabled');
                        });
                    }
                });

            }); 
            
        },

        /**
         * Inicializa el plugin
         *
         */
        initialize: function() {
            // Simply set publicPath to root since we're in a subdirectory
            this.publicPath = '/';

            $(function(){
                $.KumbiaPHP.bind();
                $.KumbiaPHP.autoload();
            });
        }
    }

    // Alias for compatibility
    $.Kumbia = $.KumbiaPHP;

    $.KumbiaPHP.initialize();
})(jQuery);
