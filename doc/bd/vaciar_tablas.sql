truncate almacen_articulos;
truncate compra;
truncate detalle_compra;
truncate detalle_traspasos;
truncate detalle_venta;
truncate movimientos_caja;
truncate traspasos;
truncate venta;

-- Eliminar Catalogos de Lista de Impresoras y Costos de Trabajo
DELETE FROM catalogo_general WHERE idcatalogo = 7 AND idempresa = 1 AND opc_catalogo >=1;
DELETE FROM catalogo_general WHERE idcatalogo = 8 AND idempresa = 1 AND opc_catalogo >=1 AND opc_catalogo2 >=1;