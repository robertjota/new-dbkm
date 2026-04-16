/**
 * Descripcion: Plugin de jquery para obtener elementos elementos y cargarlos en un container
 *
 * @category
 * @package     Javascript
 * @author      argordmel@gmail.com 
 */
(function($) {
    /**
     *
     * Opciones por defecto
     */
    var defaults = {
        change_url       : true,
        async            : false,
        timeout          : 45000,
        spinner          : true,
        append_data      : false,
        msj              : true,
        response         : 'html',
        capa            : 'shell-content',
        method          : 'GET',
        data            : null
    };

    /**
     * Función ltrim
     */
    function ltrim(str, char) {
        if (typeof str !== 'string') return str;
        if (!char) char = ' ';
        return str.replace(new RegExp('^' + char + '+'), '');
    }

    /**
     * Función rtrim
     */
    function rtrim(str, char) {
        if (typeof str !== 'string') return str;
        if (!char) char = ' ';
        return str.replace(new RegExp(char + '+$'), '');
    }

    /**
     * Función que actualiza la url con pushState
     */
    function updateUrl(url) {
        var publicPath = '/';
        
        // Limpiar la URL - quitar double slashes y trailing slashes inconsistentes
        url = url.replace(/\/+/g, '/');
        
        // Si la URL ya tiene un formato correcto, usarla directamente
        if(publicPath != '/') {
            url = url.split(publicPath);
            url = (url.length > 1) ? url[1] : url[0];
        } else  {
            url = ltrim(url, '/');
        }
        
        // Ensure URL starts with /
        if (url.charAt(0) !== '/') {
            url = '/' + url;
        }
        
        if(typeof window.history.pushState == 'function') {
            history.pushState({ path: url }, url, url);
        } else {
            window.location.hash = "#!/"+url;
        }
        return true;
    }

    /**
     * Objeto para el load
     */
    $.kload = function(options) {
        var request = false;
        var opt = $.extend(true, defaults, options);

        if(opt.spinner==true){
            jsSpinner('show');
        }

        $.ajax({
            type: opt.method, 
            url: opt.url, 
            timeout: opt.timeout, 
            async: opt.async, 
            dataType: opt.response, 
            data: opt.data,
            beforeSend: function(data) {
                $("[rel=tooltip]").tooltip('hide');
            }
        }).done(function(data) {
            if(opt.change_url == true) {
                updateUrl(opt.url);
            }
            if(opt.response == 'html') {
                // Guardar el estado de DataTables antes de reemplazar el contenido
                var dtPage = 0;
                var dtOrder = [[0, 'asc']];
                
                if (typeof $.fn.DataTable !== 'undefined') {
                    $('table[id^="t"]').each(function() {
                        var tableId = '#' + this.id;
                        if ($.fn.DataTable.isDataTable(tableId)) {
                            var dt = $(tableId).DataTable();
                            dtPage = dt.page();
                            dtOrder = dt.order();
                        }
                    });
                }
                
                // Insertar el contenido
                (opt.append_data==true) ? $("#"+opt.capa).append(data) : $("#"+opt.capa).html(data);
                
                // Ejecutar scripts inline del contenido cargado
                $("#"+opt.capa).find('script').each(function() {
                    try {
                        eval(this.textContent);
                    } catch(e) {
                        console.log('Error executing script:', e);
                    }
                });
                
                // Restaurar el estado de DataTables
                if (typeof $.fn.DataTable !== 'undefined') {
                    $('table[id^="t"]').each(function() {
                        var tableId = '#' + this.id;
                        if ($.fn.DataTable.isDataTable(tableId)) {
                            var dt = $(tableId).DataTable();
                            // Verificar que la página sea válida
                            if (dtPage < dt.page.info().pages) {
                                dt.page(dtPage).draw(false);
                            }
                            dt.order(dtOrder).draw(false);
                        }
                    });
                }
                
                $("[rel=tooltip]").tooltip();                
                
                // Solo hacer scroll al inicio si no es una página de lista
                var isListPage = opt.url && opt.url.indexOf('listar') > -1;
                if(opt.capa == 'shell-content' && !isListPage) {
                    $("html, body").animate({scrollTop: 0}, 500);
                }
                request = true;
                
                // Enlazar DatePicker si existe
                if (typeof $.Kumbia !== 'undefined') {
                    $.Kumbia.bindDatePicker();
                    $.Kumbia.bindFileUpload();
                } else if (typeof $.KumbiaPHP !== 'undefined') {
                    $.KumbiaPHP.bindDatePicker();
                    $.KumbiaPHP.bindFileUpload();
                }
                
                // Validate form si existe
                if (typeof $.validateForm !== 'undefined') {
                    $.validateForm.initialize();
                }
                
                // Re-initialize DataTables if present
                if (typeof $.fn.DataTable !== 'undefined') {
                    $('table[id^="t"]').each(function() {
                        var tableId = '#' + this.id;
                        if ($.fn.DataTable.isDataTable(tableId)) {
                            $(tableId).DataTable().destroy();
                        }
                        $(tableId).DataTable({
                            language: {
                                url: "https://cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json"
                            },
                            order: [[0, "asc"]],
                            columnDefs: [
                                { targets: [-1], orderable: false, className: "text-center" }
                            ]
                        });
                    });
                }
            } else {
                request = data;
            }
        }).fail(function (xhr, text, err) {
            var response = xhr.status+" "+xhr.statusText;
            if(opt.msg==true) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Oops! Se ha producido un error en la carga'
                });
            }
            request = false;
        }).always(function() {
            if(opt.spinner==true) {
                jsSpinner('hide');
            }
        });

        defaults.response = 'html';
        defaults.method = 'GET';
        defaults.data = null;
        defaults.spinner = true;

        return request;
    };

    /**
     * Envía formulario con reload simple
     */
    $.kpost = function(options) {
        var opt = $.extend({
            spinner: true,
            method: 'POST',
            data: null
        }, options);

        try {
            if(opt.spinner){
                jsSpinner('show');
            }

            $.ajax({
                type: opt.method,
                url: opt.url,
                data: opt.data,
                // No especificar dataType para que jQuery detecte automáticamente
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
                }
            }).done(function(response) {
                if(opt.spinner){
                    jsSpinner('hide');
                }
                
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
                    } catch(e) {
                        // No es JSON
                    }
                }
                
                // Si es JSON, manejar como respuesta JSON
                if (isJson && jsonResponse) {
                    if (jsonResponse.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Éxito',
                            text: jsonResponse.message || 'Operación completada',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(function() {
                            // Cargar URL de lista si existe
                            if (jsonResponse.url) {
                                $.kload({ url: jsonResponse.url, spinner: true, change_url: true });
                            } else {
                                location.reload();
                            }
                        });
                    } else if (jsonResponse.status === 'validation') {
                        Swal.fire({
                            icon: 'warning',
                            title: 'Validación',
                            html: jsonResponse.message + '<br>' + (jsonResponse.errors ? Object.values(jsonResponse.errors).join('<br>') : '')
                        });
                    } else if (jsonResponse.status === 'error') {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: jsonResponse.message
                        });
                    }
                    return;
                }
                
                // Si no es JSON o es HTML, manejar como HTML
                var responseText = typeof response === 'string' ? response.toLowerCase() : '';
                
                if (responseText.indexOf('flash-error') > -1 || responseText.indexOf('alert-danger') > -1) {
                    $("#shell-content").html(response);
                    $("#shell-content").find('script').each(function() {
                        try { eval(this.textContent); } catch(e) {}
                    });
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Hubo un error al procesar la solicitud'
                    });
                } else if (responseText.indexOf('flash-valid') > -1 || responseText.indexOf('alert-success') > -1) {
                    Swal.fire({
                        icon: 'success',
                        title: 'Éxito',
                        text: 'Operación completada correctamente',
                        timer: 1500,
                        showConfirmButton: false
                    }).then(function() {
                        location.reload();
                    });
                } else {
                    $("#shell-content").html(response);
                    $("#shell-content").find('script').each(function() {
                        try { eval(this.textContent); } catch(e) {}
                    });
                }
            }).fail(function(xhr) {
                if(opt.spinner){
                    jsSpinner('hide');
                }
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Error de conexión: ' + xhr.status
                });
            });
        } catch(e) {
            console.error('kpost error:', e);
            if(opt.spinner){
                jsSpinner('hide');
            }
            alert('Error: ' + e.message);
        }
    };
})(jQuery);
