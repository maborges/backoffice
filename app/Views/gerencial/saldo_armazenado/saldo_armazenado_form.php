<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <?php 
    $action = isset($saldo) ? site_url('saldo-armazenado/update/' . $saldo->codigo) : site_url('saldo-armazenado/create');
    ?>
    <?= form_open($action, ['novalidate' => true]) ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solidy shadow-sm">
                    <div class="card-header">
                        <h3 class="card-title"><?= isset($saldo) ? 'Editar' : 'Novo' ?> Saldo Armazenado</h3>
                    </div>

                    <div class="card-body mt-0">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="produto">Produto</label>
                                <select class="form-control form-control-sm" name="produto" id="produto" <?= isset($saldo) ? 'disabled' : '' ?>>
                                    <option value="">Selecione um produto</option>
                                    <?php foreach ($produtos as $produto) : ?>
                                        <option value="<?= $produto->codigo ?>" <?= isset($saldo) && $saldo->produto == $produto->codigo ? 'selected' : '' ?>>
                                            <?= $produto->descricao ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($saldo)) : ?>
                                    <input type="hidden" name="produto" value="<?= $saldo->produto ?>">
                                <?php endif; ?>
                                <?= isset($validation) ? $validation->showError('produto') : '' ?>
                            </div>

                            <div class="form-group col-6">
                                <label for="filial">Filial</label>
                                <select class="form-control form-control-sm" name="filial" id="filial" <?= isset($saldo) ? 'disabled' : '' ?>>
                                    <option value="">Selecione uma filial</option>
                                    <?php foreach ($filiais as $filial) : ?>
                                        <option value="<?= $filial->codigo ?>" <?= isset($saldo) && $saldo->filial == $filial->codigo ? 'selected' : '' ?>>
                                            <?= $filial->descricao ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <?php if (isset($saldo)) : ?>
                                    <input type="hidden" name="filial" value="<?= $saldo->filial ?>">
                                <?php endif; ?>
                                <?= isset($validation) ? $validation->showError('filial') : '' ?>
                            </div>

                        </div>

                        <div class="row">
                            <div class="form-group col-6">
                                <label for="data_saldo">Data do Saldo</label>
                                <input type="date" class="form-control form-control-sm" name="data_saldo" id="data_saldo" 
                                    value="<?= isset($saldo) ? $saldo->data_saldo : date('Y-m-d') ?>" <?= isset($saldo) ? 'readonly' : '' ?>>
                                <?= isset($validation) ? $validation->showError('data_saldo') : '' ?>
                            </div>

                            <div class="form-group col-6">
                                <label for="saldo">Saldo</label>
                                <input type="number" step="0.01" class="form-control form-control-sm" name="saldo" id="saldo" 
                                    value="<?= isset($saldo) ? $saldo->saldo : '' ?>">
                                <?= isset($validation) ? $validation->showError('saldo') : '' ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row ">
                            <div class="col pb-0 justify-content-end">
                                <a href="<?= site_url('/saldo-armazenado') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4">
                                    <i class="fas fa-ban mr-1"></i>Cancelar
                                </a>
                                <button type="submit" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4">
                                    <i class="fas fa-check mr-1"></i>Salvar
                                </button>
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
if (!empty($server_warning)) {
    sweetToast('warning', $server_warning, $title);
}
?>

<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>

<script>
    $(document).ready(() => {
        // Inicializa os selects com select2
        $('#produto, #filial').select2({
            theme: 'bootstrap4',
            width: '100%'
        });
    });
</script>

<?= $this->endSection() ?>