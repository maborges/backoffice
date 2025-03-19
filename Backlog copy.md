# Alterações
   1. [X] Agrupar os produtores por CPF
   2. [ ] Permitir alterar a classificação do produtor no momento da compra (p/m/g)
      1. [ ] É necessário definir como será a estrutura de dados para fazer esta alteração

# Reunião - 06/12/2024 - Rubens/Saulo
## Alterações no SUIF
   1. [X] Criar campo no SUIF "posto" ou "a puxar" na tela de entrada de romaneio
   2. [ ] Criar no SUIF indicador se o cliente está ativo ou não;
         Implica fazer levantamento das funcionalidades onde esta informação será e como será usada;
         Hoje a indicação de um produtor ativo ou não é específiada pela quantidade de tempo sem efetuar
         uma movimentação
   3. [ ] Definição do prazo que o cliente permanecerá ativo após a última compra
         Inicialmente vamos trabalhar com 2 anos (24 meses);      
         **Definir se a atualização desta informação deve ser feita automática por job**
         - [ ] Se sim, criar job para atualização automática (a definir);
   4. [ ] Criar no SUIF campo informando o produtor responsável;
         Implica fazer levantamento das funcionalidades onde esta informação será e como será usada;
   5. [X] Todos os clientes ativos devem ter um comprador vinculado;

## Categorizar o Produtor
   Na categoria do produtor, estará associado o limite de crédito do produtor para pagamento a favorecidos
   e o limite de compras por produto.

   Definição:
      A estrutura do cadastro será mantida no Sankhya;
      Fazer a carga das informações para tabelas locais, conforme abaixo especificadas;
      
   1. [ ] Cria estrutura no Sankhya conforme especificação abaixo das tabelas;
   2. [ ] Criar estrutura para manter informações no Sankhya, a saber:
         Nome da Tabela: categoria_produtor
            codigo - Identificador 
            nome - Nome da Categoria
            limite_credito - Valor do limite de crédito para pagamento a favorecidos

         Nome da Tabela: limite_compra
            id - Identificador 
            categoria_produtor - Código da categoria cadastrada em categoria_produtor
            produto - código do produto cadastrado em cad_produto
            nome - Nome da Categoria
            limite_compra - Valor do limite de compra do produto
   3. [ ] Criar tela de consulta e carga de atualização do Sankhya
   4. [ ] Alterar tela de manutenção de pessoa para carregar a categoria do produtor

## Limite de Compras
   1. [X] Colocar separador de milhar nos valores.
   2. [ ] O limite de compras será verificado conforme a categoria do produtor
          (Pequeno, Médio ou Grande) que estará definido no Sankhya;
   3. [ ] O limite será atribuído de forma automática, conforme categoria
   4. [ ] Colocar filtro por produtor e produto

## Limite de Crédito
   1. [X] Colocar separador de milhar nos valores.
   2. [ ] O limite de crédito estará vinculada à categoria do produtor;

## Preço Gerencial na Compra
   1. [X] Incluir empresas nos dados apresentados;
   2. [ ] Descontar Funrural das empresas;
   3. [ ] Criar coluna com valor unitário;
   4. [ ] Incluir quadro com soma da quantidade e média ponderada do preço liquido;
   5. [ ] Descontar frete ondo "posto";
         - [ ] **O valor a ser descontado é de R$ 5,00 por Kg?**
   6. [X] Apresentar todos os produtores, pessoa física e jurídica
   7. [ ] Quadro com soma da quantidade e média ponderada do preço liquido;

## Agrupar Produtor por Região
   1. [X] Cadastro será mantido no Sankhya e carregado no SUIF
   2. [ ] Criar rotina de carga;

## Clientes/Produtores Ativos
   1. [ ] Criar relatório de clientes ativos por período e por comprador;
         **Especificar relatório**

## Carteira de Produtores
   1. [ ] Quando não houver compra de um produtor por um determinado período, o mesmo deverá ser incluído numa carteira de disponíveis;
      1. [ ] Verificar período para colocar nas configurações
      2. [ ] Verificar se o processo de movimentação automática já está implementado
   2. [ ] Quando da compra de um produtor da carteira de disponíveis, o produtor será integrado automaticamente à carteira do comprador;

## Controle de Cadastro 
   1. [X] Permitir somente uma compra quando de um novo cliente e seu cadastro ainda não estiver validado;
   
## Dashboard de Qualidade

## Compra X Entrega Final
   Produtos comprados e entregues.
   1. [X] Relatório de quanto tempo se passou da compra até a entrega, com filtro por filial, comprador e produto;

## Compras não Entregues
   Produtos comprados e ainda não puxados (entregues)
   1. [ ] Criar relatório no SUIF por quanto tempo os produtores estão em aberto no "a puxar";
   2. [ ] Classificar por cor, onde:
         - Verde = 1 a 30 dias;
         - Amarelo = 31 a 60 dias;
         - Vermelho = + de 61 dias;


