update t_sgcv_opciones SET estado = 0 WHERE id in (2,3);
update t_sgcv_opciones SET opcion = 'Matriz', url_img = 'cil-apps' WHERE id = 4;
ALTER TABLE t_sgcv_actividades ADD COLUMN `cumplido` TINYINT(1) DEFAULT 0 AFTER doc_adjunto_id;

-- Everything on top of this on Live Server

ALTER TABLE t_sgcv_actividades DROP FOREIGN KEY fk_activities_documents1; 
ALTER TABLE t_sgcv_actividades DROP COLUMN `doc_adjunto_id`;

CREATE TABLE IF NOT EXISTS `mg_db`.`t_sgcv_act_docs` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `actividad_id` BIGINT NOT NULL,
  `documento_id` BIGINT NOT NULL,
  `estado` TINYINT NOT NULL,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_t_sgcv_act_docs_T_SGCV_Actividades1_idx` (`actividad_id` ASC),
  INDEX `fk_t_sgcv_act_docs_T_SGCV_Documentos1_idx` (`documento_id` ASC),
  CONSTRAINT `fk_t_sgcv_act_docs_T_SGCV_Actividades1`
    FOREIGN KEY (`actividad_id`)
    REFERENCES `mg_db`.`T_SGCV_Actividades` (`id`),
  CONSTRAINT `fk_t_sgcv_act_docs_T_SGCV_Documentos1`
    FOREIGN KEY (`documento_id`)
    REFERENCES `mg_db`.`T_SGCV_Documentos` (`id`));