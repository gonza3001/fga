truncate detalle_venta;
truncate venta;

truncate detalle_traspasos;
truncate traspasos;

truncate detalle_compra;
truncate compra;

truncate almacen_articulos;
truncate movimientos_caja;

-- Eliminar Catalogos de Lista de Impresoras y Costos de Trabajo
DELETE FROM catalogo_general WHERE idcatalogo = 7 AND idempresa = 1 AND opc_catalogo >=1;
DELETE FROM catalogo_general WHERE idcatalogo = 8 AND idempresa = 1 AND opc_catalogo >=1 AND opc_catalogo2 >=1;