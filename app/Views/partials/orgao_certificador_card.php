<div class="col-lg-3 col-12 mb-3">
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
            <h5><?= $orgao->sigla ?></h5>
            <h6 class="info-box-text"><?= $orgao->nome ?></h6>
        </div>

        <div class="small-box-footer mt-auto p-0 bd-highlight">
            <a href="<?= site_url('/certificacao/orgao_certificador_edita/') . $orgao->codigo ?>">
                <i class="fa-regular fa-pen-to-square shadow-sm"></i>
            </a>
            <a href="<?= site_url('/certificacao/orgao_certificador_exclui/') . $orgao->codigo ?>">
                <i class="fa-regular fa-trash-can text-danger"></i>
            </a>
        </div>

    </div>
</div>