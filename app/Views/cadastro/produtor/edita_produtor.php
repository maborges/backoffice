<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>


<section class="content">
    <?= form_open_multipart('/cadastro/produtor_atualiza', ['novalidate' => true]) ?>
    
    <input type="hidden" name="codigo" id="codigo" value="<?= $produtor->codigo ?>">

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-6">
                <div class="card card-solidy shadow-sm">
                    <div class="card-body">
                        <div class="row">
                            <div class="form-group col-12">
                                <label for="nome">Nome do Produtor</label>
                                <input type="text" class="form-control form-control-sm" name="nome" id="nome" readonly
                                    value="<?= $produtor->nome ?>">
                            </div>

                            <div class="form-group col-4">
                                <label for="cidade">Cidade</label>
                                <input type="text" class="form-control form-control-sm" name="cidade" id="cidade" readonly
                                    value="<?= $produtor->cidade ?>">
                            </div>

                            <div class="form-group col-4">
                                <label for="estado">Estado</label>
                                <input type="text" class="form-control form-control-sm" name="estado" id="estado" readonly
                                    value="<?= $produtor->estado ?>">
                            </div>

                            <div class="form-group col-4">
                                <label for="saldo_credito">Saldo do Crédito</label>
                                <input type="text" class="form-control form-control-sm" name="saldo_credito" id="saldo_credito" readonly
                                    value="<?= normalizeNumber($produtor->saldo_credito, 2) ?>">
                            </div>

                            <div class="form-group col-4">
                                <label for="limite_credito">Limite de Crédito</label>
                                <input type="text" class="form-control form-control-sm" name="limite_credito" id="limite_credito"
                                    value="<?= normalizeNumber($produtor->limite_credito, 2) ?>">
                                <?= displayError('limite_credito', $validation_errors) ?>
                            </div>

                            <div class="form-group col-4">
                                <label for="nome_completo">Comprador</label>
                                <input type="text" class="form-control form-control-sm" name="nome_completo" id="nome_completo" value="<?= $produtor->nome_completo ?>">
                                <input type="hidden" name="comprador" id="comprador" value="<?= $produtor->comprador ?>">
                                <?= displayError('comprador', $validation_errors) ?>
                            </div>

                            <div class="form-group col-4">
                                <label for="nome_regiao">Região do Produtor</label>
                                <input type="text" class="form-control form-control-sm" name="nome_regiao" id="nome_regiao" value="<?= $produtor->nome_regiao ?>">
                                <input type="hidden" name="codigo_regiao" id="codigo_regiao" value="<?= $produtor->codigo_regiao ?>">
                                <?= displayError('codigo_regiao', $validation_errors) ?>
                            </div>

                        </div>


                        <div class="row">
                            <div class="col-4">

                                <div class="row">

                                </div>

                                <div class="row">
                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="cadastro_validado" id="cadastro_validado"
                                                value="<?= $produtor->cadastro_validado == 'S' ? 'S' : '' ?>" <?= $produtor->cadastro_validado == 'S' ? 'checked' : '' ?> disabled>
                                            <label class="form-check-label">Cadastro Ok</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="validado_serasa" id="validado_serasa"
                                                value="<?= $produtor->validado_serasa == 'S' ? 'S' : '' ?>" <?= $produtor->validado_serasa == 'S' ? 'checked' : '' ?>>
                                            <label class="form-check-label">SERASA Ok</label>
                                        </div>
                                    </div>

                                    <div class="form-group">
                                        <div class="form-check">
                                            <input type="checkbox" class="form-check-input" name="embargado" id="embargado"
                                                value="<?= $produtor->embargado == 'S' ? 'S' : '' ?>" <?= $produtor->embargado == 'S' ? 'checked' : '' ?>>
                                            <label class="form-check-label">Embargado</label>
                                        </div>
                                    </div>
                                </div>

                            </div>

                            <div class="col-2">
                                <label for="categoria" class="form-label">Categoria: </label>
                                <div class="form-group">
                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="categoria" id="categoriaP" value="P" <?= $produtor->categoria == 'P' ? 'checked' : '' ?>>
                                        <label for="categoriaP" class="form-check-label">Pequeno</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="categoria" id="categoriaM" value="M" <?= $produtor->categoria == 'M' ? 'checked' : '' ?>>
                                        <label for="categoriaM" class="form-check-label">Médio</label>
                                    </div>

                                    <div class="form-check">
                                        <input type="radio" class="form-check-input" name="categoria" id="categoriaG" value="G" <?= $produtor->categoria == 'G' ? 'checked' : '' ?>>
                                        <label for="categoriaG" class="form-check-label">Grande</label>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="card-footer">
                            <div class="row ">
                                <div class="col pb-0 justify-content-end">
                                    <a href="<?= site_url('/cadastro/produtor') ?>" class="btn btn-sm btn-outline-secondary btn-flat shadow-sm px-4"><i
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

<?= $this->section('scripts'); ?>

<script>
    $(document).ready(() => {
        $("#nome_regiao").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/cadastro/regiao_locate",
                    type: "GET",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.nome_regiao,
                                value: item.nome_regiao,
                                id: item.id
                            };
                        }));
                    }
                });
            },
            select: function(event, ui) {
                $("#codigo_regiao").val(ui.item.id);
            },
            minLength: 2
        });

        $("#nome_completo").autocomplete({
            source: function(request, response) {
                $.ajax({
                    url: "/cadastro/comprador_locate",
                    type: "GET",
                    data: {
                        term: request.term
                    },
                    success: function(data) {
                        response($.map(data, function(item) {
                            return {
                                label: item.nome_completo,
                                value: item.nome_completo,
                                id: item.username
                            };
                        }));
                    }
                });
            },
            select: function(event, ui) {
                $("#comprador").val(ui.item.id);
            },
            minLength: 2
        });

    });
</script>

<?= $this->endSection() ?>