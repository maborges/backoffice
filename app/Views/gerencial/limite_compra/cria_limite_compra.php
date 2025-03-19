<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>


<section class="content">
    <?= form_open('/gerencial/limite_compra_grava', ['novalidate' => true]) ?>
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
                            <div class="form-group col-12">
                                <label for="fldDescricaoProduto">Descrição do Produto</label>
                                <input type="text" class="form-control form-control-sm" name="fldDescricaoProduto" id="fldDescricaoProduto" value="<?= old('fldDescricaoProduto', '') ?>">
                                <input type="hidden" id="fldProduto" name="fldProduto" value="<?= old('fldProduto', 0) ?>">
                                <?= displayError('fldProduto', $validation_errors) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-3">
                                <label for="fldLimiteCompra">Quantidade Limite</label>
                                <input type="text" class="form-control form-control-sm" name="fldLimiteCompra" id="fldLimiteCompra" min="0" pattern="[0-9]+"
                                    value="<?= old('fldLimiteCompra', '') ?>" disabled>
                                <?= displayError('fldLimiteCompra', $validation_errors) ?>
                            </div>

                            <div class="form-group col-3">
                                <label for="fldLimiteUtilizado">Quantidade Utilizada</label>
                                <input type="text" class="form-control form-control-sm" name="fldLimiteUtilizado" id="fldLimiteUtilizado" readonly
                                    value="<?= old('fldLimiteUtilizado', '') ?>">
                                <?= displayError('fldLimiteUtilizado', $validation_errors) ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row ">
                            <div class="col pb-0 justify-content-end">
                                <a href="<?= site_url('/gerencial/limite_compra') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4"><i
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

        $("#fldDescricaoProduto").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/cadastro/produto_locate",
                    type: "GET",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.descricao,
                                value: item.descricao,
                                id: item.codigo
                            };
                        }));
                    }
                });
            },
            select: function(event, ui) {
                $("#fldProduto").val(ui.item.id);
            },
            minLength: 2
        });
    });
</script>

<?= $this->endSection() ?>