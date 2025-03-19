<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<div class="col-lg-6 col-12 mb-3">
    <div class="info-box bg-gradient-white h-100">
        <div class="info-box-content">
            <h4><strong><?= $questionario->nome ?></strong></h4>
            <p class="info-box-text"><?= $questionario->descricao ?></p>
            <p class="text-danger mb-3=">Tem certeza que deseja excluir o registro?</p>
            <div class="small-box-footer mt-auto p-0 bd-highlight">
                <a href="<?= site_url('/certificacao/questionario') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4">
                    <i class="fas fa-ban pr-1"></i>Cancelar
                </a>
                <a href="<?= site_url('/certificacao/questionario_confirma/' . $questionario->codigo) ?>" class="btn btn-sm btn-outline-danger btn-flat shadow-sm px-4">
                    <i class="fas fa-trash pr-1"></i>Excluir
                </a>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>