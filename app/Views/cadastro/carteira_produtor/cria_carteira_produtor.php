<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>


<section class="content">
    <?= form_open('/cadastro/carteira_produtor_grava', ['novalidate' => true]) ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solidy shadow-sm">

                    <div class="card-body mt-0">
                        <div class="row">
                            <div class="form-group col-6">
                                <label for="nome_carteira_produtor">Nome da Carteira de Produtores</label>
                                <input type="text" class="form-control form-control-sm" name="nome_carteira_produtor" id="nome_carteira_produtor" value="<?= old('nome_carteira_produtor', '') ?>">
                                <?= displayError('nome_carteira_produtor', $validation_errors) ?>
                            </div>

                            <div class="form-group col-6">
                                <label for="id_comprador">Comprador</label>
                                <input type="text" class="form-control form-control-sm" name="id_comprador" id="id_comprador" value="<?= old('id_comprador', '') ?>">
                                <?= displayError('id_comprador', $validation_errors) ?>
                            </div>

                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row ">
                            <div class="col pb-0 justify-content-end">
                                <a href="<?= site_url('/cadastro/carteira_produtor') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4"><i
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