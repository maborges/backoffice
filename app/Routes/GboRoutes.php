<?php

namespace App\Routes;

use Config\Services;

$routes = Services::routes();

// Main
$routes->get('/', 'Main::index');
$routes->get('/changeBranch/(:num)', 'Main::changeBranch/$1');

// Access Controls
$routes->get('/auth/login', 'Auth::login');
$routes->post('/auth/loginSubmit', 'Auth::loginSubmit');
$routes->get('/auth/logout', 'Auth::logout');

// Certificação
// Orgão certificador
$routes->get('certificacao/orgao_certificador', 'OrgaoCertificador::index');
$routes->get('certificacao/orgao_certificador_cria', 'OrgaoCertificador::cria');
$routes->post('certificacao/orgao_certificador_grava', 'OrgaoCertificador::grava');

// Edita Orgão certificador
$routes->get('certificacao/orgao_certificador_edita/(:alphanum)', 'OrgaoCertificador::edita/$1');
$routes->post('certificacao/orgao_certificador_atualiza', 'OrgaoCertificador::atualiza');

// Exclui Orgão certificador
$routes->get('certificacao/orgao_certificador_exclui/(:alphanum)', 'OrgaoCertificador::exclui/$1');
$routes->get('certificacao/orgao_certificador_confirma/(:alphanum)', 'OrgaoCertificador::confirma/$1');

// Questionários
$routes->get('certificacao/questionario', 'Questionario::index');
$routes->get('certificacao/questionario_cria', 'Questionario::cria');
$routes->post('certificacao/questionario_grava', 'Questionario::grava');

// Edita Questionario
$routes->get('certificacao/questionario_edita/(:alphanum)', 'Questionario::edita/$1');
$routes->post('certificacao/questionario_atualiza', 'Questionario::atualiza');

// Exclui Questionario
$routes->get('certificacao/questionario_exclui/(:alphanum)', 'Questionario::exclui/$1');
$routes->get('certificacao/questionario_confirma/(:alphanum)', 'Questionario::confirma/$1');

// Gerencial
// Dashboard
$routes->get('gerencial/dashboard', 'DashboardGerencialController::index');
$routes->post('gerencial/resumo_comprador', 'ComprasController::getResumoComprador');
// $routes->get('gerencial/resumo_comprador', 'ComprasController::getResumoComprador');
$routes->post('gerencial/resumo_filial', 'ComprasController::getResumoFilial');
$routes->post('gerencial/top10_cliente', 'ComprasController::getTop10Cliente');
$routes->post('gerencial/top10_regiao', 'ComprasController::getTop10Regiao');
$routes->post('gerencial/resumo_classificacao', 'ComprasController::getResumoClassificacao');
// $routes->get('gerencial/resumo_classificacao', 'ComprasController::getResumoClassificacao');
$routes->post('gerencial/dashboard_filiais', 'ComprasController::getDashboardFiliais');
//$routes->get('gerencial/dashboard_filiais', 'ComprasController::getDashboardFiliais');
$routes->post('gerencial/dashboard_comprador', 'ComprasController::getDashboardComprador');
//$routes->get('gerencial/dashboard_comprador', 'ComprasController::getDashboardComprador');
$routes->post('gerencial/dashboard_classificacao', 'ComprasController::getDashboardClassificacao');
//$routes->get('gerencial/dashboard_classificacao', 'ComprasController::getDashboardClassificacao');


$routes->post('gerencial/dashboard_categoria', 'ComprasController::getDashboardCategoria');
//$routes->get('gerencial/dashboard_categoria', 'ComprasController::getDashboardCategoria');


// Limite de Compras
$routes->get('gerencial/limite_compra', 'LimiteCompraController::index');
$routes->get('gerencial/limite_compra_cria', 'LimiteCompraController::cria');
$routes->post('gerencial/limite_compra_grava', 'LimiteCompraController::grava');
$routes->get('gerencial/limite_compra_edita/(:alphanum)', 'LimiteCompraController::edita/$1');
$routes->post('gerencial/limite_compra_atualiza', 'LimiteCompraController::atualiza');
$routes->get('gerencial/limite_compra_exclui/(:alphanum)', 'LimiteCompraController::exclui/$1');

// Limite de Crédito
$routes->get('gerencial/limite_credito', 'LimiteCreditoController::index');
$routes->get('gerencial/limite_credito_cria', 'LimiteCreditoController::cria');
$routes->post('gerencial/limite_credito_grava', 'LimiteCreditoController::grava');
$routes->get('gerencial/limite_credito_edita/(:alphanum)', 'LimiteCreditoController::edita/$1');
$routes->post('gerencial/limite_credito_atualiza', 'LimiteCreditoController::atualiza');
$routes->get('gerencial/limite_credito_exclui/(:alphanum)', 'LimiteCreditoController::exclui/$1');

// Compras
//$routes->get('gerencial/compra_get_entrega_pendente', 'ComprasController::getEntregaPendente');
$routes->post('gerencial/compra_get_entrega_pendente', 'ComprasController::getEntregaPendente');
$routes->get('gerencial/compra_entrega_pendente', 'ComprasController::listaEntregasPendentes');

$routes->post('gerencial/compra_get_gap_entrega', 'ComprasController::getGapCompraEntrega');
$routes->get('gerencial/compra_get_gap_entrega', 'ComprasController::getGapCompraEntrega');
$routes->get('gerencial/compra_gap_compra_entrega', 'ComprasController::listaGapCompraEntrega');

$routes->post('gerencial/compra_get_preco_gerencial', 'ComprasController::getPrecoGerencial');
$routes->get('gerencial/compra_preco_gerencial', 'ComprasController::listaPrecoGerencial');

//$routes->post('gerencial/compra_get_preco_gerencial_resumo', 'ComprasController::getPrecoGerencialResumo');
$routes->get('gerencial/compra_get_preco_gerencial_resumo', 'ComprasController::getPrecoGerencialResumo');


// Cadastros
// Regiões
$routes->get('cadastro/regiao', 'RegiaoController::index');
$routes->get('cadastro/regiao_cria', 'RegiaoController::cria');
$routes->post('cadastro/regiao_grava', 'RegiaoController::grava');
$routes->get('cadastro/regiao_edita/(:alphanum)', 'RegiaoController::edita/$1');
$routes->post('cadastro/regiao_atualiza', 'RegiaoController::atualiza');
$routes->get('cadastro/regiao_exclui/(:alphanum)', 'RegiaoController::exclui/$1');
$routes->get('cadastro/regiao_locate', 'RegiaoController::findLikeName');

// Produto
$routes->get('cadastro/produto', 'ProdutoController::index');
$routes->get('cadastro/produto_locate', 'ProdutoController::findLikeDescription');

// Produtores
$routes->get('cadastro/produtor', 'ProdutorController::index');
$routes->get('cadastro/produtor_edita/(:alphanum)', 'ProdutorController::edita/$1'); 
$routes->post('cadastro/produtor_atualiza', 'ProdutorController::atualiza');
$routes->get('cadastro/produtor_lista_ativos', 'ProdutorController::listaAtivos');

$routes->post('cadastro/produtor_get_lista_situacao', 'ProdutorController::getSituacaoProdutor');
$routes->get('cadastro/produtor_lista_situacao', 'ProdutorController::listaSituacaoProdutor');

$routes->post('cadastro/produtor_getativos', 'ProdutorController::getAtivos');
$routes->get('cadastro/produtor_locate', 'ProdutorController::findLikeName');

// Compradores
$routes->get('cadastro/comprador', 'CompradorController::index');
$routes->get('cadastro/comprador_locate', 'CompradorController::findLikeName');

// Filiais
$routes->get('cadastro/filial_locate', 'FilialController::findLikeName');

// Filial x Comprador
$routes->get('cadastro/filial_comprador', 'FilialCompradorController::index');
$routes->get('cadastro/filial_comprador_cria', 'FilialCompradorController::cria');
$routes->post('cadastro/filial_comprador_grava', 'FilialCompradorController::grava');
$routes->get('cadastro/filial_comprador_edita/(:alphanum)/(:alphanum)', 'FilialCompradorController::edita/$1/$2');
$routes->post('cadastro/filial_comprador_atualiza', 'FilialCompradorController::atualiza');
$routes->get('cadastro/filial_comprador_exclui/(:alphanum)/(:alphanum)', 'FilialCompradorController::exclui/$1/$2');

