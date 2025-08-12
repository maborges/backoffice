<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>


<section class="content">
    <?= form_open('/cadastro/contrato_posicao_estoque_atualiza', ['novalidate' => true]) ?>

    <input type="hidden" name="codigo" id="codigo" value="<?= $contrato->codigo ?>">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solidy shadow-sm">


                    <div class="card-body mt-0">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="numero_contrato">Número do Contrato</label>
                                <input type="text" class="form-control form-control-sm" name="numero_contrato" id="numero_contrato"
                                    value="<?= old('numero_contrato', $contrato->numero_contrato) ?>" readonly>
                            </div>

                            <div class="form-group col-6">
                                <label for="data_referencia">Data de Referência</label>
                                <input type="date" class="form-control form-control-sm" name="data_referencia" id="data_referencia"
                                    value="<?= old('data_referencia', $contrato->data_referencia) ?>" readonly>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-6">
                                <label for="nome_produto">Produto</label>
                                <input type="text" class="form-control form-control-sm" name="nome_produto" id="nome_produto"
                                    value="<?= old('nome_produto', $contrato->nome_produto) ?>" readonly>
                                <input type="hidden" name="produto" id="produto" value="<?= old('produto', $contrato->produto) ?>">
                            </div>

                            <div class="form-group col-6">
                                <label for="nome_filial">Filial</label>
                                <input type="text" class="form-control form-control-sm" name="nome_filial" id="nome_filial"
                                    value="<?= old('nome_filial', $contrato->nome_filial) ?>" readonly>
                                <input type="hidden" name="filial" id="filial" value="<?= old('filial', $contrato->filial) ?>">
                            </div>
                        </div>

                        <div class="row">

                            <div class="form-group col-3">
                                <label for="quantidade">Fixação</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" name="fixacao" id="fixacao"
                                    value="<?= old('fixacao', $contrato->fixacao) ?>">
                                <?= displayError('fixacao', $validation_errors) ?>
                            </div>

                            <div class="form-group col-3">
                                <label for="quebra">Quebra</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" name="quebra" id="quebra"
                                    value="<?= old('quebra', $contrato->quebra) ?>">
                                <?= displayError('quebra', $validation_errors) ?>
                            </div>

                            <div class="form-group col-3">
                                <label for="desconto">Desconto</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" name="desconto" id="desconto"
                                    value="<?= old('desconto', $contrato->desconto) ?>">
                                <?= displayError('desconto', $validation_errors) ?>
                            </div>
                        </div>

                        <div class="row">
                            <div class="form-group col-12">
                                <label for="observacao">Observação</label>
                                <textarea class="form-control form-control-sm" name="observacao" id="observacao" rows="3"><?= old('observacao', $contrato->observacao) ?></textarea>
                            </div>
                        </div>

                        <div class="card-footer">
                            <div class="row ">
                                <div class="col pb-0 justify-content-end">
                                    <a href="<?= site_url('/cadastro/contrato_posicao_estoque') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4"><i
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