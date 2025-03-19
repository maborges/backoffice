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

// Cadastros
// Produtores
$routes->get('cadastro/produtor', 'ProdutorController::index');
$routes->get('cadastro/produtor_locate', 'ProdutorController::findLikeName');

// Produtores
$routes->get('cadastro/produto', 'ProdutoController::index');
$routes->get('cadastro/produto_locate', 'ProdutoController::findLikeDescription');

// Gerencial
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

// Cadastros
// Regiões
$routes->get('cadastro/regiao', 'RegiaoController::index');
$routes->get('cadastro/regiao_cria', 'RegiaoController::cria');
$routes->post('cadastro/regiao_grava', 'RegiaoController::grava');
$routes->get('cadastro/regiao_edita/(:alphanum)', 'RegiaoController::edita/$1');
$routes->post('cadastro/regiao_atualiza', 'RegiaoController::atualiza');
$routes->get('cadastro/regiao_exclui/(:alphanum)', 'RegiaoController::exclui/$1');
$routes->get('cadastro/regiao_locate', 'RegiaoController::findLikeName');

// Produtores
$routes->get('cadastro/produtor', 'ProdutorController::index');
$routes->get('cadastro/produtor_edita/(:alphanum)', 'ProdutorController::edita/$1'); 
$routes->post('cadastro/produtor_atualiza', 'ProdutorController::atualiza');
$routes->get('cadastro/produtor_lista_ativos', 'ProdutorController::listaAtivos');
//$routes->get('cadastro/produtor_getativos/(:any)/(:any)', 'ProdutorController::getAtivos/$1/$2');
$routes->get('cadastro/produtor_getativos', 'ProdutorController::getAtivos');
