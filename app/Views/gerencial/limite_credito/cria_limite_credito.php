<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>


<section class="content">
    <?= form_open('/gerencial/limite_credito_grava', ['novalidate' => true]) ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solidy shadow-sm">

                    <div class="card-body mt-0">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="fldNomeProdutor">Nome do Produtor</label>
                                <input type="text" class="form-control form-control-sm" name="fldNomeProdutor" id="fldNomeProdutor" value="<?= old('fldNomeProdutor', '') ?>">
                                <input type="hidden" id="fldProdutor" name="fldProdutor" value="<?= old('fldProdutor', 0) ?>">
                                <?= displayError('fldProdutor', $validation_errors) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-3">
                                <label for="fldLimiteCredito">Valor do Limite</label>
                                <input type="number" class="form-control form-control-sm" name="fldLimiteCredito" id="fldLimiteCredito" min="0"
                                    placeholder="100" value="<?= old('fldLimiteCredito', 0) ?>">
                                <?= displayError('fldLimiteCredito', $validation_errors) ?>
                            </div>

                            <div class="form-group col-3">
                                <label for="fldLimiteUtilizado">Valor Utilizado</label>
                                <input type="number" class="form-control form-control-sm" name="fldLimiteUtilizado" id="fldLimiteUtilizado" readonly
                                    placeholder="100" value="<?= old('fldLimiteUtilizado', 0) ?>">
                                <?= displayError('fldLimiteUtilizado', $validation_errors) ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row ">
                            <div class="col pb-0 justify-content-end">
                                <a href="<?= site_url('/gerencial/limite_credito') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4"><i
                                        class="fas fa-ban mr-1"></i>Cancelar</a>
                                <button type="submit" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4"><i class="fas fa-check mr-1"></i>Salvar</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <?= form_close() ?>
</section>

<!--
    Mensagens de erro, aviso e sucesso 
-->
<?php
if (!empty($server_error)) {
    sweetToast('error', $server_error, $title);
}
?>

<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>

<script>
    $(document).ready(() => {
        $("#fldNomeProdutor").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/cadastro/produtor_locate",
                    type: "GET",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            console.log(item);
                            return {
                                label: item.nome,
                                value: item.nome,
                                id: item.codigo
                            };
                        }));
                    }
                });
            },
            select: function(event, ui) {
                $("#fldProdutor").val(ui.item.id);
            },
            minLength: 2
        });

    });
</script>

<?= $this->endSection() ?>