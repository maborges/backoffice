-- Verifica saldo limite compra

DELIMITER $

$
CREATE OR REPLACE TRIGGER trg_limite_compra_ins BEFORE INSERT ON limite_compra 
FOR EACH ROW 
BEGIN 
	IF NEW.quantidade_utilizada < 0 THEN 
		SET NEW.quantidade_utilizada = 0;
	END IF;

	IF NEW.quantidade_utilizada > NEW.quantidade_limite THEN 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de compras excedito para este produtor/produto';
	END IF;
END;

$
CREATE OR REPLACE TRIGGER trg_limite_compra_upd BEFORE UPDATE ON limite_compra 
FOR EACH ROW 
BEGIN 
	IF NEW.quantidade_utilizada < 0 THEN 
		SET NEW.quantidade_utilizada = 0;
	END IF;

	IF NEW.quantidade_utilizada > NEW.quantidade_limite THEN 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de compras excedito para este produtor/produto';
	END IF;
END;


$

DELIMITER ;
