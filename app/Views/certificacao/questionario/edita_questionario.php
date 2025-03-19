<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>


<section class="content">
    <?= form_open_multipart('/certificacao/questionario_atualiza', ['novalidate' => true]) ?>
    
    <input type="hidden" name="fldCodigo" value="<?= $questionario->codigo ?>">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="card card-solidy shadow-sm">

                    <div class="card-header d-flex justify-content-end">
                        <div class="mb-0 flex-end">
                            <p>Id: <?= $questionario->codigo ?></p>
                        </div>
                    </div>

                    <div class="card-body mt-0">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="fldNome">Nome</label>
                                <input type="text" class="form-control form-control-sm" name="fldNome" id="fldNome" readonly
                                    placeholder="Nome" value="<?= old('fldNome', $questionario->nome ) ?>">
                            </div>

                            <div class="form-group col-12">
                                <label for="fldDescricao">Descrição</label>
                                <textarea class="form-control form-control-sm" name="fldDescricao" id="fldDescricao" autofocus
                                    rows="3" maxlength="200" placeholder="Breve descrição do questionário..."><?= old('fldDescricao', $questionario->descricao) ?> </textarea>
                                <?= displayError('fldDescricao', $validation_errors) ?>
                            </div>

                            <div class="form-group col-3">
                                <label for="fldPontuacaoMaxima">Pontuação Máxima</label>
                                <input type="number" class="form-control form-control-sm" name="fldPontuacaoMaxima" id="fldPontuacaoMaxima" 
                                    placeholder="100" value="<?= old('fldPontuacaoMaxima', $questionario->pontuacao_maxima) ?>" minlength="3" maxlength="100">
                                <?= displayError('fldPontuacaoMaxima', $validation_errors) ?>
                            </div>

                            <div class="form-group col-3">
                                <label for="fldPontuacaoMinima">Pontuação Mínima</label>
                                <input type="number" class="form-control form-control-sm" name="fldPontuacaoMinima" id="fldPontuacaoMinima" 
                                    placeholder="0" value="<?= old('fldPontuacaoMinima', $questionario->pontuacao_minima) ?>" minlength="3" maxlength="100">
                                <?= displayError('fldPontuacaoMinima', $validation_errors) ?>
                            </div>

                            <div class="form-check col-2 ml-2">
                                <input type="checkbox" class="form-check-input" name="fldAvaliativo" id="fldAvaliativo" <?= $questionario->avaliativo ? 'checked' : '' ?>>
                                <label class="form-check-label" for="fldAvaliativo">Avaliativo</label>
                            </div>                        
                        </div>
                    </div>

                    <div class="card-footer">
                        <div class="row ">
                            <div class="col pb-0 justify-content-end">
                                <a href="<?= site_url('/certificacao/questionario') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4"><i class="fas fa-ban mr-1"></i>Cancelar</a>
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
        sweetToast('error',$server_error,$title);
    }
?>

<?= $this->endSection() ?>