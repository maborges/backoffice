-- Verifica saldo limite compra

DELIMITER $

$
CREATE OR REPLACE TRIGGER trg_cadastro_pessoa_ins BEFORE INSERT ON cadastro_pessoa 
FOR EACH ROW 
BEGIN 
	IF NEW.acumulado_credito < 0 THEN 
		SET NEW.acumulado_credito = 0;
	END IF;

	IF NEW.acumulado_credito > NEW.limite_credito THEN 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de crédito excedito para este produtor';
	END IF;
END;

$
CREATE OR REPLACE TRIGGER trg_cadastro_pessoa_upd BEFORE UPDATE ON cadastro_pessoa 
FOR EACH ROW 
BEGIN 
	IF NEW.acumulado_credito < 0 THEN 
		SET NEW.acumulado_credito = 0;
	END IF;

	IF NEW.acumulado_credito > NEW.limite_credito THEN 
		SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Limite de crédito excedito para este produtor';
	END IF;
END;

$

CREATE OR REPLACE TRIGGER cadastro_pessoa_trg01 BEFORE insert ON cadastro_pessoa
FOR EACH ROW
BEGIN
  set  new.cpfcnpj = ONLYNUMBERS(IF(UPPER(NEW.TIPO) = 'PF', NEW.CPF, NEW.CNPJ));
END;

$

CREATE OR REPLACE TRIGGER cadastro_pessoa_trg02 BEFORE update ON cadastro_pessoa
FOR EACH ROW
BEGIN
  set  new.cpfcnpj = ONLYNUMBERS(IF(UPPER(NEW.TIPO) = 'PF', NEW.CPF, NEW.CNPJ));
END

$

DELIMITER ;
