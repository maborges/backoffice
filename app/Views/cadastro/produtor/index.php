<?= $this->extend('layouts/main') ?>
<?= $this->section('content') ?>
<?= $this->include('partials/page_title') ?>

<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-12">
                <div class="card card-solidy shadow-sm">
                    <?php if (empty($produtores)) : ?>
                        <div class="text-center mt-5 mb-3">
                            <h4 class="opacity-50 mb-3">Não exitem produtores cadastrados.</h4>
                        </div>
                    <?php else : ?>
                        <div class="card-body mt-1 pt-1 ">
                            <div class="row">
                                <table class="display compact" id="tblProdutores" style="width: 100%; height: auto">
                                    <thead class="table-light">
                                        <tr>
                                            <th class="text-center">Código</th>
                                            <th class="text-center">Nome do Produtor</th>
                                            <th class="text-center">Cidade</th>
                                            <th class="text-center">Estado</th>
                                            <th class="text-center">Comprador</th>
                                            <th class="text-center">Região</th>
                                            <th class="text-center">Limite Crédito</th>
                                            <th class="text-center">Saldo Crédito</th>
                                            <th class="text-center">Categoria</th>
                                            <th class="text-center">Cadastro Ok</th>
                                            <th class="text-center">SERASA Ok</th>
                                            <th class="text-center">Embargado</th>
                                            <th class="text-center">Situação</th>
                                            <th class="text-center">Ação</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
</section>

<!-- Mensagens -->
<?php
if (!empty($server_success)) {
    sweetToast('success', $server_success, $title);
} elseif (!empty($server_warning)) {
    sweetToast('warning', $server_warning, $title);
}
?>

<?= $this->endSection() ?>

<?= $this->section('scripts'); ?>


<!-- Datatables -->
<script>
    $(document).ready(() => {
        $('#tblProdutores').DataTable({
            deferRender: true,
            fixedHeader: true,
            scrollCollapse: true,
            scroller: true,
            scrollY: '60vh',
            language: {
                url: "<?= base_url(DATATABLES_PT_BR) ?>"
            },
            layout: {
                topStart: ['buttons', 'pageLength'],
                topEnd: ['search']
            },
            buttons: [{
                    extend: 'copy',
                    text: '<i class="fa-solid fa-copy"></i>', // Ícone FontAwesome
                    className: 'btn btn-sm btn-outline-secondary btn-flat'

                },
                {
                    extend: 'print',
                    text: '<i class="fa-solid fa-print"></i>', // Ícone FontAwesome
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                },
                {
                    extend: 'colvis',
                    text: '<i class="fa-solid fa-table-columns"></i>', // Ícone FontAwesome
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                },
                {
                    extend: 'csv',
                    text: '<i class="fa-solid fa-file-csv"></i>', // Ícone FontAwesome
                    className: 'btn btn-sm btn-outline-secondary btn-flat'
                }
            ],
            data: <?= json_encode($produtores) ?>,
            bPaginate: true,
            bProcessing: true,
            pageLength: 25,
            columns: [{
                    data: 'codigo',
                    visible: false
                },
                {
                    data: 'nome'
                },
                {
                    data: 'cidade'
                },
                {
                    data: 'estado'
                },
                {
                    data: 'nome_completo'
                },
                {
                    data: 'nome_regiao'
                },
                {
                    data: 'limite_credito',
                    "render": function(data, type, row) {
                        return new Intl.NumberFormat('pt-BR', {
                            style: 'decimal',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(data);
                    }
                },
                {
                    data: 'saldo_credito',
                    "render": function(data, type, row) {
                        return new Intl.NumberFormat('pt-BR', {
                            style: 'decimal',
                            minimumFractionDigits: 2,
                            maximumFractionDigits: 2
                        }).format(data);
                    }
                },
                {
                    data: 'categoria',
                    "render": function(data, type, row) {
                        switch (data) {
                            case 'P':
                                return 'Pequeno';
                            case 'M':
                                return 'Médio';
                            case 'G':
                                return 'Grande';
                            default:
                                return 'Desconhecido';
                        }
                    }
                },
                {
                    data: 'cadastro_validado',
                    className: 'text-center',
                    "render": function(data, type, row) {
                        switch (data) {
                            case 'S':
                                return 'Sim';
                            default:
                                return 'Não';
                        }
                    }
                },
                {
                    data: 'cadastro_serasa',
                    className: 'text-center',
                    "render": function(data, type, row) {
                        switch (data) {
                            case 'S':
                                return 'Sim';
                            default:
                                return 'Não';
                        }
                    }
                },
                {
                    data: 'embargado',
                    className: 'text-center',
                    "render": function(data, type, row) {
                        switch (data) {
                            case 'S':
                                return 'Sim';
                            default:
                                return 'Não';
                        }
                    }
                },
                {
                    data: 'situacao',
                    className: 'text-center',
                    "render": function(data, type, row) {
                        if (data === 'I') {
                            return '<span class="badge badge-warning">INATIVO</span>';
                        } else if (data === 'A') {
                            return '<span class="badge badge-success">ATIVO</span>';
                        }
                        return data;
                    }
                },
                {
                    data: null,
                    bSortable: false,
                    mRender: function(data, type, full) {
                        return "<div class='d-sm-flex'>" +
                            "<a class='btn text-primary ms-0 p-0' href=<?= site_url('/cadastro/produtor_edita/') ?>" + data.codigo + '><i class="fa-regular fa-pen-to-square shadow-sm"></i></a>' +
                            "</div>";
                    }
                }
            ]
        });
    })
</script>

<?= $this->endSection() ?>