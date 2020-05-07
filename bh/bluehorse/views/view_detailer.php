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
                    <form>
                        <?=$data?>
                        <div class="row">
                            <div class="col">
                                <?php if ($canEdit) { ?>
                                <a href="<?=FILE_APP . '?p=' . FILE . '&mode=editor&reference=' . $reference?>" class="btn btn-primary">Editar</a>
                                <?php } ?>
                                <?php if ($showExit) { ?>
                                <a href="<?=$exitDetailer?>" class="btn btn-secondary">Salir</a>
                                <?php } ?>
                            </div>
                        </div>
                    </form>

                    <?php if (isset($msgFooterDetailer) && ! is_null($msgFooterDetailer)) { ?>
                    <div class="row">
                        <div class="col">
                            <div class="alert alert-<?=$msgFooterDetailer['type']?>" role="alert">
                                <?=$msgFooterDetailer['msg']?>
                            </div>
                        </div>
                    </div>
                    <?php } ?>
                </div>
            </div>

            <?php if (isset($dataList)) { ?>
            <div class="row">
                <div class="col">&nbsp;</div>
            </div>
                    <?=$dataList?>
            <?php } ?>

            <div class="modal fade" id="confirmModal" tabindex="-1" role="dialog" aria-labelledby="modalTitle" aria-hidden="true">
                <div class="modal-dialog modal-dialog-centered" role="document">
                    <div class="modal-content">
                        <div class="modal-body">
                            ¿Desea <strong>eliminar</strong> el registro seleccionado?
                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                <span aria-hidden="true">&times;</span>
                            </button>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" data-dismiss="modal">NO</button>
                            <button type="button" class="btn btn-primary" id="btnConfirm">SI</button>
                        </div>
                    </div>
                </div>
            </div>
            <?php if (isset($_SESSION["msgs"])) { ?>
            <div class="alert alert-warning" id="snackbar"><?=$_SESSION["msgs"]?></div>
            <?php unset($_SESSION["msgs"]); } ?>
            <script>
                $(function(){
                    $('#confirmModal').on('show.bs.modal', function (event) {
                        let button = $(event.relatedTarget);
                        $('#btnConfirm').on('click', function(){
                            $(location).attr("href", button.data('href'));
                        });
                    });
                });
            </script>
        </div>
    </body>
</html>