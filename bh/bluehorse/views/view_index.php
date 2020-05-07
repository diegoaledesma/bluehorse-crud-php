<!doctype html>
<html lang="es">
    <head>
        <?php include VIEW_HEAD; ?>
    </head>
    <body>
        <header class="navbar navbar-expand-lg navbar-dark bg-info sticky-top" style="background-color: #003261 !important;box-shadow: 0px 3px 10px rgba(0,0,0,0.5);">
            <div class="container-fluid">
                <span class="navbar-brand">
                    <img src="<?=URL_IMG?>/logo_header.png" style="width:128px;"/>
                </span>
                <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarNavDropdown" aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNavDropdown">
                    <ul class="navbar-nav ml-md-auto">
                        <li class="nav-item d-block d-sm-none">
                            <a href="<?=FILE_APP?>?p=home" class="nav-link" target="iframeContent">Inicio</a>
                        </li>
                        </li>
                        <li class="nav-item d-block d-sm-none">
                            <a class="nav-link" href="<?=FILE_LOGOUT?>">Cerrar Sesión</a>
                        </li>
                        <li class="nav-item dropdown d-none d-sm-block">
                            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdownMenuLink" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                <?=$name?>
                            </a>
                            <div class="dropdown-menu dropdown-menu-right" aria-labelledby="navbarDropdownMenuLink">
                                <a class="dropdown-item" id="exit" data-href="<?=FILE_LOGOUT?>">
                                    Cerrar Sesión
                                </a>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </header>
        <main rol="main" class="container-fluid">
            <div class="row">
                <div class="col-md-3">
                    <?php include VIEW_MENU; ?>
                </div>
                <div class="col-md-9" style="padding:0px;">
                    <iframe src="<?=FILE_APP?>?p=home" id="iframeContent" name="iframeContent" style="border:1px solid transparent;width:100%;"></iframe>
                </div>
            </div>
        </main>
        <footer class="footer">
            <div class="container-fluid">
                <div class="col-md-12 text-center">
                    <span>
                        BlueHorse Copyrights © <?=date('Y')?> Todos los derechos reservados.
                    </span>
                </div>
            </div>

        </footer>
        <script>
            $(function(){
                $('#exit').on('click', function(){
                    let href = $(this).data('href'),
                        alert = bootbox.dialog({
                            message: '¿Desea <strong>cerrar</strong> la sesión?',
                            centerVertical: true,
                            animate:false,
                            closeButton: false,
                            buttons: {
                                cancel: {
                                    label: "NO"
                                },
                                success: {
                                    label: "SI",
                                    callback: function(){
                                        $(location).attr("href", href);
                                    }
                                }
                            }
                        });
                    alert.find('.modal-dialog').addClass('modal-dialog-centered');
                    alert.find('.bootbox-close-button').css({'margin-top':'0'});
                });

                /* define la altura del iframe */
                let altura = function(a){
                    let topOffset   = parseFloat($('header').outerHeight() + ($('footer').outerHeight())),
                        height      = parseFloat(((window.innerHeight>0)?window.innerHeight:screen.height));
                    if ((height-topOffset)>topOffset) {
                        a.css("height",(height-topOffset)+"px");
                    }
                };
                altura($('#iframeContent'));
                $(window).on('resize', function(){
                    altura($('#iframeContent'));
                });
            });
        </script>
        <script>
            $(function(){
                $('#menu a').not('.disabled').on('click', function(){
                    $('#iframeContent').attr('src', $(this).attr('href'));
                });
            });
        </script>
    </body>
</html>
