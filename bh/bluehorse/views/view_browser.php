<!doctype html>
<html lang="es">
    <head>
        <?php include VIEW_HEAD; ?>
    </head>
    <body style="margin-bottom: 100px;">
        <div class="container-fluid" style="padding:15px;">
            <div class="row mb-2">
                <div class="col-sm-8 col-xs-12">
                    <h4 class="h4 font-weight-normal text-muted"><?=$title?></h4>
                </div>
                <div class="col-sm-4 text-right d-none d-sm-block">
            	<?php if ($canAdd) { ?>
                    <a class="btn btn-primary" href="<?=$_SERVER['PHP_SELF']?>?p=<?=FILE?>&mode=editor"><?=$addLabel?></a>
                <?php } ?>
                </div>
                <div class="d-block d-sm-none">
                    <?php if ($canAdd) { ?>
                    <a class="btn btn-primary"  style="position: fixed; bottom: 15px;right: 15px; border-radius: 25px; width: 46px;padding: 10px;height: 46px;" href="<?=$_SERVER['PHP_SELF']?>?p=<?=FILE?>&mode=editor"><i class="material-icons">add</i></a>
                    <?php } ?>
                </div>
            </div>
            <div class="card">
                <div class="card-body">
                    <?=$data?>
                </div>
            </div>

            <script>
                $(function(){
                    $('#browser').find('input, select').on('keydown', function(event){
                        if (event.which == 13) {
                            $('#browser').submit();
                        }
                    });
                    $('#browser').find('a[data-id="delete"]').on('click', function(){
                        let href = $(this).data('href');
                        let alert = bootbox.dialog({
                            message: '¿Desea <strong>eliminar</strong> el registro seleccionado?',
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
                });
            </script>
            <?php if (isset($_SESSION["msgs"])) { ?>
            <div class="alert alert-warning" id="snackbar"><?=$_SESSION["msgs"]?></div>
            <?php unset($_SESSION["msgs"]); } ?>
        </div>
    </body>
</html>
