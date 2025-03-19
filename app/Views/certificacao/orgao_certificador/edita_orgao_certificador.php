<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>


<section class="content">
    <?= form_open_multipart('/certificacao/orgao_certificador_atualiza', ['novalidate' => true]) ?>
    
    <input type="hidden" name="fldCodigo" value="<?= $orgao->codigo ?>">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-solidy shadow-sm">

                    <div class="card-header d-flex justify-content-end">
                        <div class="mb-0 flex-end">
                            <p>Id: <?= $orgao->codigo ?></p>
                        </div>
                    </div>

                    <div class="card-body mt-0">
                        <div class="row">
                            <div class="col-4">

                                <!-- image -->
                                <div class="text-center ">
                                    <img src="<?= base_url('assets/images/uploads/' . $orgao->imagem) ?>" class="logo-image img-fluid" id="logo_image">
                                </div>

                                <!-- file upload -->
                                <div class="mt-3 text-start">
                                    <label for="fldImage" class="form-label">Carrega logo</label>
                                    <input type="file" name="fldImage" id="fldImage" class="form-control" accept="image/png" value="<?= old('fldImage', $orgao->imagem) ?>">
                                    <?= displayError('fldImage', $validation_errors) ?>
                                </div>

                            </div>

                            <div class="col-8">
                                <div class="form-group col-4"> 
                                    <label for="fldSigla">Sigla</label>
                                    <input type="text" class="form-control form-control-sm " name="fldSigla" id="fldSigla" placeholder="Sigla" value="<?= old('fldSigla', $orgao->sigla) ?>" readonly>
                                    <?= displayError('fldSigla', $validation_errors) ?>
                                </div>

                                <div class="form-group col-12">
                                    <label for="fldNome">Nome</label>
                                    <input type="text" class="form-control form-control-sm" name="fldNome" id="fldNome" placeholder="Nome" value="<?= old('fldNome', $orgao->nome) ?>" autofocus>
                                    <?= displayError('fldNome', $validation_errors) ?>
                                </div>

                                <div class="form-check col-2 ml-2">
                                    <input type="checkbox" class="form-check-input" name="fldSituacao" id="fldSituacao" <?= $orgao->situacao ? 'checked' : '' ?>>
                                    <label class="form-check-label" for="fldSituacao">Ativo</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row ">
                            <div class="col pb-0 justify-content-end">
                                <a href="<?= site_url('/certificacao/orgao_certificador') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4"><i class="fas fa-ban mr-1"></i>Cancelar</a>
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

<script>
    document.querySelector("#fldImage").addEventListener('change', function() {
        const logo_image = document.querySelector('#logo_image');
        const file = this.files[0];
        let reader = new FileReader();

        reader.onloadend = function() {
            logo_image.src = reader.result;
        }

        if (file) {
            reader.readAsDataURL(file);
        } else {
            logo_image.removeAttribute('src');
        }
    });
</script>


<?= $this->endSection() ?>