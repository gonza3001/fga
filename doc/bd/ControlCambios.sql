
-- Modificacion de Tabla de Ventas
-- Para preparar los campos de fecha_promesa que servira para saber cuando se debe entregrar el trabajo
ALTER TABLE `bdpvt`.`venta`
  ADD COLUMN `idusuario_termina` INT NULL AFTER `costo_trabajo_sp`,
  ADD COLUMN `idusuario_cancela` INT NULL AFTER `idusuario_termina`,
  ADD COLUMN `fecha_promesa` DATETIME NULL AFTER `idestatus`,
  ADD COLUMN `fecha_cancela` DATETIME NULL AFTER `fecha_venta`;

USE `bdpvt`;
DROP procedure IF EXISTS `sp_CancelarTraspaso`;

DELIMITER $$
USE `bdpvt`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_CancelarTraspaso`(
  in ridTraspaso INT,
  in rNoUsuario INT
)
  BEGIN

    DECLARE v_finished INTEGER DEFAULT 0;
    DECLARE v_idarticulo INT DEFAULT 0;
    DECLARE v_cantidad INT DEFAULT 0;
    DECLARE v_almacenO INT DEFAULT 0;
    DECLARE v_almacenD INT DEFAULT 0;
    DECLARE v_idempresa INT DEFAULT 0;
    DECLARE v_idestado INT DEFAULT 0;
    DECLARE vIdestado int default 0;
    DECLARE vMensaje VARCHAR(15);
    -- declare cursor for employee email
    DEClARE traspaso_cursor CURSOR FOR
      SELECT b.idestado,b.idempresa,a.idarticulo,a.cantidad,b.idalmacen_origen,b.idalmacen_destino FROM detalle_traspasos as a
        LEFT JOIN traspasos as b
          ON a.idtraspaso = b.idtraspaso
      WHERE a.idtraspaso = ridTraspaso;

    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1;

    SET vIdestado = ( SELECT idestado FROM traspasos WHERE idtraspaso = ridTraspaso);

    IF vIdestado = 2 THEN


      OPEN traspaso_cursor;
      get_traspaso: LOOP

        FETCH traspaso_cursor INTO v_idestado,v_idempresa,v_idarticulo,v_cantidad,v_almacenO,v_almacenD;

        IF v_finished = 1 THEN
          LEAVE get_traspaso;
        END IF;

        UPDATE
          traspasos SET fecha_um=now(),idestado=3,idusuario_cancela=rNoUsuario
        WHERE idempresa = v_idempresa AND idtraspaso = ridTraspaso;

        UPDATE
          almacen_articulos
        SET
          existencias = ( existencias + v_cantidad)
        WHERE
          idempresa = v_idempresa AND
          idalmacen = v_almacenO AND
          idarticulo = v_idarticulo;
        SET vMensaje = 'ok';

      END LOOP get_traspaso;
      CLOSE traspaso_cursor;

      SELECT vMensaje;

    ELSE
      SET vMensaje = 'error';
      SELECT vMensaje;
    END IF;
  END$$

DELIMITER ;

USE `bdpvt`;
DROP procedure IF EXISTS `sp_execute_traspaso`;

DELIMITER $$
USE `bdpvt`$$
CREATE DEFINER=`root`@`localhost` PROCEDURE `sp_execute_traspaso`(
  IN `ridestado` INT,
  IN `ridtraspaso` INT,
  IN `ridempresa` INT,
  IN `ridusuario_autoriza` INT,
  IN `ridalmacen_origen` INT,
  IN `ridalmacen_destino` INT,
  IN `rcantidad` decimal(10,2),
  IN `ridarticulo` INT,
  IN `rtipo_articulo` VARCHAR(5))
  BEGIN

    DECLARE v_finished INTEGER DEFAULT 0;
    DECLARE v_idarticulo INT DEFAULT 0;
    DECLARE v_cantidad INT DEFAULT 0;
    DECLARE v_almacenO INT DEFAULT 0;
    DECLARE v_almacenD INT DEFAULT 0;
    DECLARE v_idempresa INT DEFAULT 0;
    DECLARE v_idestado INT DEFAULT 0;
    DECLARE v_tipo_articulo VARCHAR(5);
    DECLARE vMensaje VARCHAR(100);

    -- declare cursor for employee email
    DEClARE traspaso_cursor CURSOR FOR
      SELECT b.idestado,b.idempresa,a.idarticulo,a.cantidad,b.idalmacen_origen,b.idalmacen_destino,a.tipo_articulo FROM detalle_traspasos as a
        LEFT JOIN traspasos as b
          ON a.idtraspaso = b.idtraspaso
      WHERE a.idtraspaso = ridTraspaso;

    -- declare NOT FOUND handler
    DECLARE CONTINUE HANDLER FOR NOT FOUND SET v_finished = 1;

    CASE ridestado

      WHEN 1 THEN
      -- Ejecutar Nuevo Traspaso y Meter al almacen
      SELECT @total := COUNT(a.idalmacen)
      FROM almacen_articulos as a
      WHERE
        a.idalmacen = ridalmacen_destino AND
        a.idempresa = ridempresa AND
        a.idarticulo = ridarticulo ;

      UPDATE traspasos
      SET idusuario_autoriza = ridusuario_autoriza,
        fecha_um = now(),
        idusuario_um = ridusuario_autoriza
      WHERE idtraspaso = ridtraspaso ;

      UPDATE almacen_articulos
      SET existencias = (existencias - rcantidad )
      WHERE idalmacen = ridalmacen_origen AND idempresa = ridempresa AND idarticulo = ridarticulo ;

      if @total > 0 THEN

        UPDATE almacen_articulos
        SET existencias = (existencias + rcantidad )
        WHERE idalmacen = ridalmacen_destino AND idempresa = ridempresa AND idarticulo = ridarticulo ;

      ELSE

        INSERT INTO almacen_articulos
        VALUES (
          ridempresa,ridalmacen_destino,ridarticulo,rtipo_articulo,rcantidad
        );

      END IF;

      WHEN 2 THEN
      -- Solicitar Traspaso, Descontar del almacen pero dejar en transito los articulos
      SELECT @total := COUNT(a.idalmacen)
      FROM almacen_articulos as a
      WHERE
        a.idalmacen = ridalmacen_destino AND
        a.idempresa = ridempresa AND
        a.idarticulo = ridarticulo ;

      UPDATE almacen_articulos
      SET existencias = (existencias - rcantidad )
      WHERE idalmacen = ridalmacen_origen AND idempresa = ridempresa AND idarticulo = ridarticulo ;

      WHEN 3 THEN

      SET v_idestado = ( SELECT idestado FROM traspasos WHERE idtraspaso = ridtraspaso AND idempresa= ridempresa);

      CASE v_idestado

        WHEN 1 THEN
        SET v_finished = 0;
        SET vMensaje = 'El traspaso se ya se encuentra realizado';
        WHEN 2 THEN

        UPDATE traspasos SET idestado=1, idusuario_autoriza=ridusuario_autoriza, fecha_um=now() WHERE idtraspaso = ridtraspaso AND idempresa= ridempresa;
        SET v_finished = 1;
        SET vMensaje = 'ok';
      END CASE;

      if v_finished = 1 THEN

        SET v_finished = 0;
        SET vMensaje = '';

        OPEN traspaso_cursor;

        get_traspaso: LOOP

          FETCH traspaso_cursor INTO v_idestado,v_idempresa,v_idarticulo,v_cantidad,v_almacenO,v_almacenD,v_tipo_articulo;

          IF v_finished = 1 THEN
            LEAVE get_traspaso;
          END IF;

          SET @Total = (SELECT FN_ExisteArticuloInventario(v_almacenD,v_idempresa,v_idarticulo));

          if @Total > 0 THEN
            -- Ya Existe Solo Actualizar
            UPDATE almacen_articulos
            SET existencias = (existencias + v_cantidad )
            WHERE idalmacen = v_almacenD AND idempresa = v_idempresa AND idarticulo = v_idarticulo ;
          ELSE
            -- No Existe Hay que ingresar
            INSERT INTO almacen_articulos
            VALUES (
              v_idempresa,v_almacenD,v_idarticulo,v_tipo_articulo,v_cantidad
            );

          END IF;

        END LOOP get_traspaso;
        CLOSE traspaso_cursor;

      ELSE

        SELECT 1 as Estado ,vMensaje as Mensaje;

      END IF;

    END CASE;


  END$$

DELIMITER ;

USE `bdpvt`;
DROP function IF EXISTS `FN_ExisteArticuloInventario`;

DELIMITER $$
USE `bdpvt`$$
CREATE DEFINER=`root`@`localhost` FUNCTION `FN_ExisteArticuloInventario`(
  ridAlmacen INT,
  ridEmpresa INT,
  ridArticulo INT
) RETURNS int(11)
READS SQL DATA
  COMMENT 'access to without the "-" GUID'
  BEGIN

    RETURN ( SELECT COUNT(a.idalmacen)
             FROM almacen_articulos as a
             WHERE
               a.idalmacen = ridAlmacen AND
               a.idempresa = ridEmpresa AND
               a.idarticulo = ridArticulo
    );
  END$$

DELIMITER ;





