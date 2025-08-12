DELIMITER $

CREATE OR REPLACE TRIGGER trg_compras_calc_before_update BEFORE UPDATE ON compras
FOR EACH ROW
BEGIN
    DECLARE v_tipo_pessoa CHAR(2);
    DECLARE v_valor_desconto_frete DECIMAL(10,2);
    
    -- Obter o tipo de pessoa (PJ ou PF)
    SELECT tipo INTO v_tipo_pessoa 
    FROM cadastro_pessoa 
    WHERE codigo = NEW.fornecedor;
    
    -- Obter o valor de desconto de frete do produto
    SELECT valor_desconto_frete INTO v_valor_desconto_frete 
    FROM cadastro_produto 
    WHERE codigo = NEW.cod_produto;
    
    -- Calcular os valores
    SET NEW.valor_inss = ROUND(NEW.valor_total * (0.012 * (v_tipo_pessoa = 'PJ')), 2);
    SET NEW.valor_rat = ROUND(NEW.valor_total * (0.001 * (v_tipo_pessoa = 'PJ')), 2);
    SET NEW.valor_senar = ROUND(NEW.valor_total * (0.002 * (v_tipo_pessoa = 'PJ')), 2);
    SET NEW.valor_funrural = ROUND(NEW.valor_total * (0.015 * (v_tipo_pessoa = 'PJ')), 2);
    SET NEW.valor_frete = NEW.quantidade * (v_valor_desconto_frete * (IFNULL(NEW.modalidade_frete, '') = 'CIF'));
    SET NEW.valor_gerencial = NEW.valor_total - (NEW.valor_funrural + NEW.valor_frete);
    
    IF NEW.quantidade > 0 THEN
        SET NEW.valor_media_gerencial = NEW.valor_gerencial / NEW.quantidade;
    ELSE
        SET NEW.valor_media_gerencial = 0;
    END IF;
END;
$

DELIMITER ;
