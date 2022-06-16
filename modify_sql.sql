update t_sgcv_opciones SET estado = 0 WHERE id in (2,3);
update t_sgcv_opciones SET opcion = 'Matriz', url_img = 'cil-apps' WHERE id = 4;
ALTER TABLE t_sgcv_actividades ADD COLUMN `cumplido` TINYINT(1) DEFAULT 0 AFTER doc_adjunto_id;

-- Everything on top of this on Live Server