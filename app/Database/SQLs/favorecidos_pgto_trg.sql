DELIMITER $

$

CREATE OR REPLACE TRIGGER trg_favorecidos_pgto_ins BEFORE INSERT ON favorecidos_pgto
FOR EACH ROW BEGIN 
	DECLARE vlr_limite DECIMAL(11,3);
	DECLARE id_pessoa  INT;

	IF NEW.estado_registro = 'ATIVO' THEN
		SELECT b.codigo, b.limite_credito  
		  INTO id_pessoa, vlr_limite
		  FROM compras a,
		       cadastro_pessoa b
		 WHERE a.numero_compra = NEW.codigo_compra
		   AND b.codigo        = a.fornecedor;
 		  
		IF vlr_limite <= 0  THEN 
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de crédito para este produtor não informado.';
		END IF;
	
		UPDATE cadastro_pessoa 
		   SET acumulado_credito = acumulado_credito + NEW.valor
		 WHERE codigo = id_pessoa;
		
	END IF;
END;

$

CREATE OR REPLACE TRIGGER trg_favorecidos_pgto_del BEFORE DELETE ON favorecidos_pgto
FOR EACH ROW BEGIN 
	DECLARE vlr_limite DECIMAL(11,3);
	DECLARE id_pessoa  INT;

	IF OLD.estado_registro = 'ATIVO' THEN
		SELECT b.codigo, b.limite_credito  
		  INTO id_pessoa, vlr_limite
		  FROM compras a,
		       cadastro_pessoa b
		 WHERE a.numero_compra = OLD.codigo_compra
		   AND b.codigo        = a.fornecedor;
 		  
		IF vlr_limite <= 0  THEN 
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de crédito para este produtor não informado.';
		END IF;
	
		UPDATE cadastro_pessoa 
		   SET acumulado_credito = acumulado_credito - OLD.valor
		 WHERE codigo = id_pessoa;
		
	END IF;
END;

$

CREATE OR REPLACE TRIGGER trg_favorecidos_pgto_upd BEFORE UPDATE ON favorecidos_pgto
FOR EACH ROW BEGIN 
	DECLARE vlr_limite DECIMAL(11,3);
	DECLARE id_pessoa  INT;
	DECLARE valor_aux  DECIMAL(11,3);

    SET valor_aux = 0;

	IF (OLD.estado_registro = 'ATIVO' AND NEW.estado_registro <> 'ATIVO') THEN
		SET valor_aux = OLD.valor;
	ELSEIF OLD.valor <> NEW.valor THEN
		SET valor_aux = NEW.valor - OLD.valor;
	END IF;

	IF valor_aux <> 0 THEN
		SELECT b.codigo, b.limite_credito  
		  INTO id_pessoa, vlr_limite
		  FROM compras a,
		       cadastro_pessoa b
		 WHERE a.numero_compra = OLD.codigo_compra
		   AND b.codigo        = a.fornecedor;
 		  
		IF vlr_limite <= 0  THEN 
			SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de crédito para este produtor não informado.';
		END IF;
	
		UPDATE cadastro_pessoa 
		   SET acumulado_credito = acumulado_credito + valor_aux
		 WHERE codigo = id_pessoa;
		
	END IF;
END;

$

DELIMITER ;
