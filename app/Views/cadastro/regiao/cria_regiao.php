<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>


<section class="content">
    <?= form_open('/cadastro/regiao_grava', ['novalidate' => true]) ?>
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solidy shadow-sm">

                    <div class="card-body mt-0">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="nome_regiao">Nome da Região</label>
                                <input type="text" class="form-control form-control-sm" name="nome_regiao" id="nome_regiao" value="<?= old('nome_regiao', '') ?>">
                                <?= displayError('nome_regiao', $validation_errors) ?>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row ">
                            <div class="col pb-0 justify-content-end">
                                <a href="<?= site_url('/cadastro/regiao') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4"><i
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
