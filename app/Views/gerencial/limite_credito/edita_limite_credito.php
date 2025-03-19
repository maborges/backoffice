<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>


<section class="content">
    <?= form_open_multipart('/gerencial/limite_credito_atualiza', ['novalidate' => true]) ?>

    <input type="hidden" name="fldId" id="fldId" value="<?= $limiteCredito->id ?>">
    <input type="hidden" name="fldProdutor" id="fldProdutor" value="<?= $limiteCredito->id_produtor ?>">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solidy shadow-sm">

                    <div class="card-header d-flex justify-content-end">
                        <div class="mb-0 flex-end">
                            <p>Id: <?= $limiteCredito->id ?></p>
                        </div>
                    </div>

                    <div class="card-body mt-0">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="fldNomeProdutor">Nome do Produtor</label>
                                <input type="text" class="form-control form-control-sm" name="fldNomeProdutor" id="fldNomeProdutor" readonly
                                    value="<?= $limiteCredito->nome_produtor ?>">
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-3">
                                <label for="fldLimiteCredito">Valor do Limite</label>
                                <input type="number" class="form-control form-control-sm" name="fldLimiteCredito" id="fldLimiteCredito" min="0"
                                    value="<?= old('fldLimiteCredito', $limiteCredito->valor_limite) ?>">
                                <?= displayError('fldLimiteCredito', $validation_errors) ?>
                            </div>

                            <div class="form-group col-3">
                                <label for="fldLimiteUtilizado">Valor Utilizado</label>
                                <input type="number" class="form-control form-control-sm" name="fldLimiteUtilizado" id="fldLimiteUtilizado" readonly
                                    value="<?= old('fldLimiteUtilizado', $limiteCredito->valor_utilizado) ?>">
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