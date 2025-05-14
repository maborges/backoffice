# SUIF
   1. Desconto de qualidade
      Na tela de desconto de qualidade (compras/produtos/acerto_quantidade) 
      [X] Permitir somente uma alteração de quantidade;
      [ ] Lista as faturas onde o usuário informará o valor de desconto
          Este valor será gravado no campo AD_DESCONTOSUIF do SANKHYA
          Como o valor deve ser distribuído pelas faturas, contrlar o saldo até
          que seja zerado e efetuvar as atualizações;

   2. CRON
      [ ] Criar programa em PHP faça a chamada da rotina de criação das faturas de armazenagem do SANKHYA 

  
# Alterações
   1. [X] Agrupar os produtores por CPF
   2. [X] Permitir alterar a classificação do produtor no momento da compra (p/m/g)
      1. [X] É necessário definir como será a estrutura de dados para fazer esta alteração

# Reunião - 06/12/2024 - Rubens/Saulo
## Alterações no SUIF
   1. [X] Criar campo no SUIF "posto" ou "a puxar" na tela de entrada de romaneio
      [X] Colocar o desconto do frete no cadastro de produtos conforme unidade de comercialização.
   2. [X] Criar no SUIF indicador se o cliente está ativo ou não;
      1. [X] Criar parametro de quantidade de "dias" para extração de clientes ativos
            [X] Alterar relatório do BO para contemplar parâmetro em config
                Este relatório já existe e não é filtrado por produto. 
      2. [X] Parametros e relatórios devem ser por produto
         Implica fazer levantamento das funcionalidades onde esta informação será e como será usada;
         Hoje a indicação de um produtor ativo ou não é específiada pela quantidade de tempo sem efetuar
         uma movimentação
         [X] Criar novo relatório que seja filtrado por produto e que considere a quantidade de dias
             do cadastro de produtos.
   3. [X] Definição do prazo que o cliente permanecerá ativo após a última compra
         Inicialmente vamos trabalhar com 2 anos (24 meses);      
         **Definir se a atualização desta informação deve ser feita automática por job**
         - [X] Se sim, criar job para atualização automática (a definir);
   4. [X] Todos os clientes ativos devem ter um comprador vinculado;

## Categorizar o Produtor
   1. [X] Na categoria do produtor, estará associado o limite de crédito do produtor para pagamento a favorecidos
         e o limite de compras por produto.

      Definição:
         A estrutura do cadastro será mantida no Sankhya;
         Fazer a carga das informações para tabelas locais, conforme abaixo especificadas;
         
   2. [X] Criar estrutura para manter informações no Sankhya, a saber:
         Nome da Tabela: categoria_produtor
            codigo - Identificador 
            nome - Nome da Categoria
            limite_credito - Valor do limite de crédito para pagamento a favorecidos

         Nome da Tabela: categoria_limite_compra
            codigo - Identificador 
            categoria_produtor - Código da categoria cadastrada em categoria_produtor
            produto - código do produto cadastrado em cad_produto
            
             - Valor do limite de compra do produto
   3. [ ] Criar tela de consulta e carga de atualização do Sankhya
          **Precisa definir as tabelas para executar esta taréfa**
   4. [X] Alterar tela de manutenção de pessoa para carregar a categoria do produtor

## Limite de Compras
   1. [X] Colocar separador de milhar nos valores.
   2. [X] O limite de compras será verificado conforme a categoria do produtor
          (Pequeno, Médio ou Grande) que estará definido no Sankhya;
   3. [X] O limite será atribuído de forma automática, conforme categoria
   4. [X] Colocar filtro por produtor e produto (Filtros do datatables)

## Limite de Crédito
   1. [X] Colocar separador de milhar nos valores.
   2. [X] O limite de crédito estará vinculada à categoria do produtor;

## Preço Gerencial na Compra
   1. [X] Incluir empresas nos dados apresentados;
   2. [X] Descontar Funrural das empresas;
   3. [X] Criar coluna com valor unitário;
   4. [X] Quadro com soma da quantidade e média ponderada do preço liquido; (igual ao SUIF)
   5. [ ] Descontar frete quando "Frete Posto (CIF)";
         - [ ] **O valor a ser descontado é por Kg? O frete ainda não está definido na compra, somente na entrada**
             - **e a quantidade pode não ser a mesma da compra**
          [X] Tirar as colunas de detalhes do FUNRURAL
     1. [X] Apresentar todos os produtores, pessoa física e jurídica

## Agrupar Produtor por Região
   1. [X] Cadastro será mantido no Sankhya e carregado no SUIF
      **Criar estrutura no Sankhya para fazer a carga. Esta estrutura já foi criada no SUIF**
   2. [ ] Criar rotina de carga;
         **Precisa definir as tabelas para executar esta taréfa** 

## Clientes/Produtores Ativos
   1. [ ] Criar relatório de clientes ativos por período e por comprador;
         **Hoje o produtor é ativo pela quantidade de dias parametrizados por produto e por parâmetro geral em configurações**

## Carteira de Produtores
   No sistema alterado o produtor está associado ao comprador pelo cadastro de pessoa.
   1. [ ] Quando não houver compra de um produtor por um determinado período, o mesmo deverá ser incluído numa carteira de disponíveis;
         **Este processo será executado diariamente verificando a compra por produto e pela quantidade de dias em configuraçã?**
         **Como verificar, haja vista que um produtor pode ser inativo por produto ou pela quantidade de dias em config?**
      1. [X] Verificar período para colocar nas configurações (730 dias = 2 anos)
      2. [X] Verificar se o processo de movimentação automática já está implementado (criar cron)
   2. [ ] Quando da compra de um produtor da carteira de disponíveis, o produtor será integrado automaticamente à carteira do comprador;

## Controle de Cadastro 
   1. [X] Permitir somente uma compra quando de um novo cliente e seu cadastro ainda não estiver validado;
   
## Dashboard de Qualidade

## Compra X Entrega Final
   Produtos comprados e entregues.
   1. [X] Relatório de quanto tempo se passou da compra até a entrega, com filtro por filial, comprador e produto;

 ## Saldo Analitico (SUIF)
   https://suif-homolog.grancafe.com.br/sis/compras/relatorios/saldo_armazenado_analitico.php
   1. [X] Cria uma coluna no final e colocar um semaforo conforme abaixo
      - Verde     = 0 a 30 dias;
      - Amarelo   = 31 a 60 dias;
      - Vermelho  = + de 61 dias;




