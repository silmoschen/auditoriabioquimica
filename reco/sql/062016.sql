ALTER TABLE `obsocial` ADD COLUMN `orden_completa` BOOLEAN NOT NULL AFTER `practicas_rechazadas`;
ALTER TABLE `cab_auditoria` MODIFY COLUMN `idprof` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;
ALTER TABLE `cab_auditoria_hist` MODIFY COLUMN `idprof` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;
ALTER TABLE `medicos_cab` MODIFY COLUMN `idprof` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;
ALTER TABLE `cab_auditoria` MODIFY COLUMN `profcab` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;
ALTER TABLE `cab_auditoria_hist` MODIFY COLUMN `profcab` VARCHAR(15) CHARACTER SET latin1 COLLATE latin1_general_ci NOT NULL;