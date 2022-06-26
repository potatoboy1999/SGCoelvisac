update t_sgcv_opciones SET estado = 0 WHERE id in (2,3);
update t_sgcv_opciones SET opcion = 'Matriz', url_img = 'cil-apps' WHERE id = 4;
ALTER TABLE t_sgcv_actividades ADD COLUMN `cumplido` TINYINT(1) DEFAULT 0 AFTER doc_adjunto_id;

ALTER TABLE t_sgcv_actividades DROP FOREIGN KEY fk_activities_documents1; 
ALTER TABLE t_sgcv_actividades DROP COLUMN `doc_adjunto_id`;

CREATE TABLE `t_sgcv_act_docs` (
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
    REFERENCES `t_sgcv_actividades` (`id`),
  CONSTRAINT `fk_t_sgcv_act_docs_T_SGCV_Documentos1`
    FOREIGN KEY (`documento_id`)
    REFERENCES `t_sgcv_documentos` (`id`));

INSERT INTO t_sgcv_sedes VALUES
(null, 'Villacurí', null, null, 1, '2022-06-21 12:00:00', '2022-06-21 12:00:00'),
(null, 'Olmos', null, null, 1, '2022-06-21 12:00:00', '2022-06-21 12:00:00'),
(null, 'Andahuasi', null, null, 1, '2022-06-21 12:00:00', '2022-06-21 12:00:00');

UPDATE t_sgcv_opciones SET `url` = 'agenda.index' where id = 6;

-- Everything on top of this on Live Server

ALTER TABLE t_sgcv_agenda_viajes CHANGE modified_at updated_at DATETIME;
ALTER TABLE t_sgcv_actividades_viajes CHANGE modified_at updated_at DATETIME;

UPDATE t_sgcv_opciones SET opcion = 'Calendario' WHERE id = 6;
UPDATE t_sgcv_opciones SET num_orden = 4 WHERE id = 7;
INSERT INTO t_sgcv_opciones VALUES (null, 'Agendas', 'agenda.pending','cil-book',null,3,2,5,2,1,'2022-06-25 12:00:00','2022-06-25 12:00:00');
INSERT INTO t_sgcv_opcion_perfil VALUES (null, 1, 10, 1, '2022-06-25 12:00:00', '2022-06-25 12:00:00');