
-- Modificacion de Tabla de Ventas
-- Para preparar los campos de fecha_promesa que servira para saber cuando se debe entregrar el trabajo
ALTER TABLE `bdpvt`.`venta`
  ADD COLUMN `idusuario_termina` INT NULL AFTER `costo_trabajo_sp`,
  ADD COLUMN `idusuario_cancela` INT NULL AFTER `idusuario_termina`,
  ADD COLUMN `fecha_promesa` DATETIME NULL AFTER `idestatus`,
  ADD COLUMN `fecha_cancela` DATETIME NULL AFTER `fecha_venta`;
