UPDATE compras cpr
JOIN cadastro_pessoa pes ON pes.codigo = cpr.fornecedor
JOIN cadastro_produto prd ON cpr.cod_produto = prd.codigo
SET 
    cpr.valor_inss = ROUND(cpr.valor_total * (0.012 * (pes.tipo='PJ')), 2),
    cpr.valor_rat = ROUND(cpr.valor_total * (0.001 * (pes.tipo='PJ')), 2),
    cpr.valor_senar = ROUND(cpr.valor_total * (0.002 * (pes.tipo='PJ')), 2),
    cpr.valor_funrural = ROUND(cpr.valor_total * (0.015 * (pes.tipo='PJ')), 2),
    cpr.valor_frete = cpr.quantidade * (prd.valor_desconto_frete * (IFNULL(cpr.modalidade_frete, '')='CIF')),
    cpr.valor_gerencial = cpr.valor_total - (ROUND(cpr.valor_total * (0.015 * (pes.tipo='PJ')), 2) + (cpr.quantidade * (prd.valor_desconto_frete * (IFNULL(cpr.modalidade_frete, '')='CIF')))),
    cpr.valor_media_gerencial = IF(cpr.quantidade > 0, 
                                 (cpr.valor_total - (ROUND(cpr.valor_total * (0.015 * (pes.tipo='PJ')), 2) + (cpr.quantidade * (prd.valor_desconto_frete * (IFNULL(cpr.modalidade_frete, '')='CIF'))))) / cpr.quantidade,
                                 0)
WHERE cpr.estado_registro = 'ATIVO' AND cpr.movimentacao = 'COMPRA';