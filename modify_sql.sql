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

ALTER TABLE t_sgcv_agenda_viajes CHANGE modified_at updated_at DATETIME;
ALTER TABLE t_sgcv_actividades_viajes CHANGE modified_at updated_at DATETIME;

UPDATE t_sgcv_opciones SET opcion = 'Calendario' WHERE id = 6;
UPDATE t_sgcv_opciones SET num_orden = 4 WHERE id = 7;
INSERT INTO t_sgcv_opciones VALUES (null, 'Agendas', 'agenda.pending','cil-book',null,3,2,5,2,1,'2022-06-25 12:00:00','2022-06-25 12:00:00');
INSERT INTO t_sgcv_opcion_perfil VALUES (null, 1, 10, 1, '2022-06-25 12:00:00', '2022-06-25 12:00:00');

UPDATE t_sgcv_opciones SET url = 'user.index' WHERE id = 9;
INSERT INTO t_sgcv_opciones VALUES (null, 'Perfiles', 'user.profiles', 'cil-user',null, 3, 3, 8, 2, 1, '2022-06-27 12:00:00','2022-06-27 12:00:00');
INSERT INTO t_sgcv_opcion_perfil VALUES (null, 1, 11, 1, '2022-06-27 12:00:00', '2022-06-27 12:00:00');

ALTER TABLE t_sgcv_posiciones ADD COLUMN es_gerente TINYINT DEFAULT 0 AFTER area_id;
UPDATE t_sgcv_posiciones SET nombre = 'Gerente de Prueba' where id = 3;

INSERT INTO t_sgcv_posiciones VALUES (null, 'Secretario de Administracion', 4, 0, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');
INSERT INTO t_sgcv_posiciones VALUES (null, 'Gerente de Administracion', 4, 1, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');

INSERT INTO t_sgcv_posiciones VALUES (null, 'Secretario de Finanzas', 5, 0, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');
INSERT INTO t_sgcv_posiciones VALUES (null, 'Gerente de Finanzas', 5, 1, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');

INSERT INTO t_sgcv_posiciones VALUES (null, 'Secretario de Comercial', 6, 0, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');
INSERT INTO t_sgcv_posiciones VALUES (null, 'Gerente de Comercial', 6, 1, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');

INSERT INTO t_sgcv_posiciones VALUES (null, 'Secretario de Operaciones', 7, 0, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');
INSERT INTO t_sgcv_posiciones VALUES (null, 'Gerente de Operaciones', 7, 1, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');

INSERT INTO t_sgcv_posiciones VALUES (null, 'Secretario de DDNN', 8, 0, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');
INSERT INTO t_sgcv_posiciones VALUES (null, 'Gerente de DDNN', 8, 1, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');

INSERT INTO t_sgcv_posiciones VALUES (null, 'Secretario de Legal', 9, 0, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');
INSERT INTO t_sgcv_posiciones VALUES (null, 'Gerente de Legal', 9, 1, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');

INSERT INTO t_sgcv_posiciones VALUES (null, 'Secretario de Gestión Humana', 10, 0, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');
INSERT INTO t_sgcv_posiciones VALUES (null, 'Gerente de Gestión Humana', 10, 1, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');

INSERT INTO t_sgcv_posiciones VALUES (null, 'Secretario de Gestion', 11, 0, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');
INSERT INTO t_sgcv_posiciones VALUES (null, 'Gerente de Gestion', 11, 1, 1, '2022-07-06 12:00:00', '2022-07-06 12:00:00');

ALTER TABLE t_sgcv_reporte_actividades CHANGE modified_at updated_at DATETIME;

ALTER TABLE t_sgcv_agenda_viajes ADD COLUMN val_uno_por BIGINT NULL AFTER validacion_dos;
ALTER TABLE t_sgcv_agenda_viajes ADD COLUMN val_dos_por BIGINT NULL AFTER val_uno_por;

ALTER TABLE t_sgcv_agenda_viajes ADD COLUMN finalizado TINYINT NOT NULL DEFAULT 0 AFTER val_dos_por;

CREATE TABLE `t_sgcv_report_files` (
  `id` BIGINT NOT NULL AUTO_INCREMENT,
  `agenda_viaje_id` BIGINT NOT NULL,
  `documento_id` BIGINT NOT NULL,
  `estado` TINYINT NULL DEFAULT 1,
  `created_at` DATETIME NULL,
  `updated_at` DATETIME NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_t_sgcv_report_files_T_SGCV_Agenda_Viajes1_idx` (`agenda_viaje_id` ASC),
  INDEX `fk_t_sgcv_report_files_T_SGCV_Documentos1_idx` (`documento_id` ASC),
  CONSTRAINT `fk_t_sgcv_report_files_T_SGCV_Agenda_Viajes1`
    FOREIGN KEY (`agenda_viaje_id`)
    REFERENCES `t_sgcv_agenda_viajes` (`id`),
  CONSTRAINT `fk_t_sgcv_report_files_T_SGCV_Documentos1`
    FOREIGN KEY (`documento_id`)
    REFERENCES `t_sgcv_documentos` (`id`));

-- Everything on top of this on Live Server