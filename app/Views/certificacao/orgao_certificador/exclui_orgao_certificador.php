<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<div class="col-lg-6 col-12 mb-3">
    <div class="info-box bg-gradient-white h-100">

        <?php 
            $imagem = base_url('assets/images/uploads/' . $orgao->imagem);

            // deve-se verifica se o arquivo exist no file server
            $tmp = ROOTPATH . 'public/assets/images/uploads/' . $orgao->imagem;

            if (!file_exists($tmp)) {
                $imagem = base_url('assets/images/uploads/no_image.png');
            }
        ?>
        
        <div class="info-box-icon">
            <img class="img-fluid pad" src="<?= $imagem ?>" alt="<?= $orgao->imagem ?>">
        </div>

        <div class="info-box-content">
            <h3><strong><?= $orgao->sigla ?></strong></h3>
            <p class="info-box-text"><strong><?= $orgao->nome ?></strong></p>
            <p class="text-danger mb-3=">Tem certeza que deseja excluir o registro?</p>
            <div class="small-box-footer mt-auto p-0 bd-highlight">
                <a href="<?= site_url('/certificacao/orgao_certificador') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4">
                    <i class="fas fa-ban pr-1"></i>Cancelar
                </a>
                <a href="<?= site_url('/certificacao/orgao_certificador_confirma/' . $orgao->codigo) ?>" class="btn btn-sm btn-outline-danger btn-flat shadow-sm px-4">
                    <i class="fas fa-trash pr-1"></i>Excluir
                </a>
            </div>
        </div>

    </div>
</div>

<?= $this->endSection() ?>