-- inclui limimites considerando a maior média mensal
insert into limite_compra (id_produtor, id_produto, quantidade_limite, criado_em, criado_por)
SELECT 
    fornecedor, 
    cod_produto, 
    MAX(media_mensal) AS maior_media_mensal,
    now(),
    "MARCOS.BORGES"
FROM (
    SELECT 
        fornecedor, 
        cod_produto, 
        YEAR(data_compra) AS ano,
        MONTH(data_compra) AS mes, 
        AVG(cast(valor_total as int)) AS media_mensal
    FROM 
        compras
   where estado_registro = 'ATIVO'
     and movimentacao = 'COMPRA' 
    GROUP BY 
        fornecedor, cod_produto, ano, mes
) AS subquery
GROUP BY 
    fornecedor, cod_produto;
   


   
