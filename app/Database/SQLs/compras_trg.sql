DELIMITER $

$

CREATE OR REPLACE TRIGGER trg_compras_ins BEFORE INSERT ON compras
FOR EACH ROW BEGIN 
	DECLARE id_limite_compra DECIMAL(11,3);

	/*
	 * Movimento com pendência de nota fiscal
	*/
	IF NEW.movimentacao IN ('COMPRA', 'TRANSFERENCIA_SAIDA', 'SAIDA', 'SAIDA_FUTURO') AND NEW.estado_registro = 'ATIVO' THEN
		/*
		 * Trata a quantidade limite de compra do produto 
		 */
		SELECT a.id
		  INTO id_limite_compra 
		  FROM limite_compra a
		 WHERE a.id_produtor = NEW.fornecedor
		   AND a.id_produto  = NEW.cod_produto;
		  
		IF id_limite_compra IS NULL THEN 
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de compra para este produtor/produto não encontrado.';
		END IF;
	
		UPDATE limite_compra 
		   SET quantidade_utilizada = quantidade_utilizada + NEW.quantidade
		 WHERE id = id_limite_compra;
		
	/*
	 * Movimento para baixar nota fiscal
	*/
    ELSEIF NEW.movimentacao IN ('ENTRADA', 'TRANSFERENCIA_ENTRADA', 'ENTRADA_FUTURO') AND NEW.estado_registro = 'ATIVO' THEN
		SELECT a.id
		  INTO id_limite_compra 
		  FROM limite_compra a
		 WHERE a.id_produtor = NEW.fornecedor
		   AND a.id_produto  = NEW.cod_produto;
		  
		IF id_limite_compra IS NULL THEN 
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de compra para este produtor/produto não encontrado.';
		END IF;
	
		UPDATE limite_compra 
		   SET quantidade_utilizada = quantidade_utilizada - NEW.quantidade
		 WHERE id = id_limite_compra;
    END IF;
END;

$

CREATE OR REPLACE TRIGGER trg_compras_upd BEFORE UPDATE ON compras
FOR EACH ROW BEGIN 
	DECLARE id_limite_compra DECIMAL(11,3);
	/*
	 * Movimento com pendência de nota fiscal
	*/
	IF  NEW.movimentacao IN ('COMPRA', 'TRANSFERENCIA_SAIDA', 'SAIDA', 'SAIDA_FUTURO') AND 
	   (NEW.estado_registro = 'EXCLUIDO' AND OLD.estado_registro = 'ATIVO') THEN
		SELECT a.id
		  INTO id_limite_compra 
		  FROM limite_compra a
		 WHERE a.id_produtor = NEW.fornecedor
		   AND a.id_produto  = NEW.cod_produto;
		  
		IF id_limite_compra IS NULL THEN 
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de compra para este produtor/produto não encontrado.';
		END IF;
	
		UPDATE limite_compra 
		   SET quantidade_utilizada = quantidade_utilizada - NEW.quantidade
		 WHERE id = id_limite_compra;
		
	/*
	 * Movimento para baixar nota fiscal
	*/
    ELSEIF NEW.movimentacao IN ('ENTRADA', 'TRANSFERENCIA_ENTRADA', 'ENTRADA_FUTURO') AND 
	   (NEW.estado_registro = 'EXCLUIDO' AND OLD.estado_registro = 'ATIVO') THEN
		SELECT a.id
		  INTO id_limite_compra 
		  FROM limite_compra a
		 WHERE a.id_produtor = NEW.fornecedor
		   AND a.id_produto  = NEW.cod_produto;
		  
		IF id_limite_compra IS NULL THEN 
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de compra para este produtor/produto não encontrado.';
		END IF;
	
		UPDATE limite_compra 
		   SET quantidade_utilizada = quantidade_utilizada + NEW.quantidade
		 WHERE id = id_limite_compra;
    END IF;
	
END;


$

DELIMITER ;
