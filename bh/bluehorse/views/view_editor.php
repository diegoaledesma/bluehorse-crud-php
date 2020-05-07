<?php
if (! is_null($reference)) {
    if (! $canEdit) {
        header('Location: ' . FILE_APP . '?p=' . FILE . '&mode=browser');
    }
    $action     = md5('update');
    $btnLabel   = 'Actualizar';
    $reference  = '&reference=' . $reference;
    $detailer   = '<a href="' . FILE_APP . '?p=' . FILE . '&mode=detailer' . $reference . '" class="btn btn-info">Detalle</a>';
} else {
    if (! $canAdd) {
        header('Location: ' . FILE_APP . '?p=' . FILE . '&mode=browser');
    }
    $action     = md5('store');
    $btnLabel   = 'Enviar';
    $reference  = '';
    $detailer   = null;
}
$url = FILE_APP . '?p=' . FILE . '&mode=editor&action=' . $action . $reference;
$spinner = URL_IMG . '/spinner.gif';
?>
<!doctype html>
<html lang="es">
    <head>
        <?php include VIEW_HEAD; ?>
    </head>
    <body style="margin-bottom: 100px;">
        <div class="container-fluid" style="padding:15px;">
            <div class="row mb-2">
                <div class="col-sm-12">
                    <h4 class="h4 font-weight-normal text-muted"><?=$title?></h4>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <form action="<?=$url?>" method="POST" id="editor">
                        <?=$data?>
                        <?php if (isset($msgFooterEditor) && ! is_null($msgFooterEditor)) { ?>
                        <div class="row">
                            <div class="col">
                                <div class="alert alert-<?=$msgFooterEditor['type']?>" role="alert">
                                    <?=$msgFooterEditor['msg']?>
                                </div>
                            </div>
                        </div>
                        <?php } ?>
                        <div class="row">
                            <div class="col">
                                <button type="submit" class="btn btn-primary"><?=$btnLabel?></button>
                                <?php if ($showDetailer) { echo $detailer; } ?>
                                <?php if ($showExit) { ?>
                                <a href="<?=$exitEditor?>" class="btn btn-secondary">Salir</a>
                                <?php } ?>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            Existen <strong>campos obligatorios</strong> sin completar.
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="modal fade" id="ddjjModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            <iframe id="ddjj" src="<?=$srcModal?>"></iframe>
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-primary" data-dismiss="modal">Entendido</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION["msgs"])) { ?>
            <div class="alert alert-warning" id="snackbar"><?=$_SESSION["msgs"]?></div>
            <?php unset($_SESSION["msgs"]); } ?>
        </div>
        <script>
            $(function() {
                $('form').on('submit', function(event) {
                    let err = 0;
                    $(this).find('[data-required="true"]').each(function() {
                        let val = $(this).val(),
                            attr = $(this).attr('name');

                        if (val.length === 0) {
                            err++;
                            $(this).css({'border-color':'red'});
                        } else {
                            if (typeof attr !== typeof undefined && attr !== false) {
                                $(this).removeAttr('style');
                            }
                        }
                    });
                    if (err === 0) {
                        let alert = bootbox.dialog({
                            centerVertical: true,
                            message: '<div class="text-center"><img src="<?=$spinner?>" style="width:50px;"></i> Aguarde un instante...</div>',
                            closeButton: false
                        });
                        alert.find('.modal-dialog').addClass('modal-dialog-centered');
                        return;
                    }
                    $('#confirmModal').modal('show');
                    event.preventDefault();
                });

                $('form').on('keyup keypress', function(e) {
                    var keyCode = e.keyCode || e.which;
                    if (keyCode === 13) {
                        e.preventDefault();
                        return false;
                    }
                });
            });
        </script>
    </body>
</html>