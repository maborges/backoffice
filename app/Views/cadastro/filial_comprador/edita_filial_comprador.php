<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <?= form_open_multipart('/cadastro/filial_comprador_atualiza', ['novalidate' => true]) ?>

    <input type="hidden" name="filial" id="filial" value="<?= $filialComprador->filial ?>">
    <input type="hidden" name="comprador" id="comprador" value="<?= $filialComprador->comprador ?>">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solidy shadow-sm">

                    <div class="card-header d-flex justify-content-end">
                        <div class="mb-0 flex-end">
                            <p>Filial: <?= $filialComprador->filial ?> | Comprador: <?= $filialComprador->comprador ?></p>
                        </div>
                    </div>

                    <div class="card-body mt-0">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="filial">Filial</label>
                                <input type="text" class="form-control form-control-sm" name="filial" id="filial" value="<?= $filialComprador->filial ?>" disabled>
                            </div>
                            <div class="form-group col-12">
                                <label for="comprador">Comprador</label>
                                <input type="text" class="form-control form-control-sm" name="comprador" id="comprador" value="<?= $filialComprador->comprador ?>" disabled>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row ">
                            <div class="col pb-0 justify-content-end">
                                <a href="<?= site_url('/cadastro/filial_comprador') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4">
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

<!-- Mensagens de erro, aviso e sucesso -->
<?php
if (!empty($server_error)) {
    sweetToast('error', $server_error, $title);
}
?>

<?= $this->endSection() ?> 