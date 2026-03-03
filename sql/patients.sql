#------------------------------------------------------------
#        Script MySQL.
#------------------------------------------------------------
CREATE DATABASE IF NOT EXISTS `hospitalE2N` CHARACTER SET 'utf8';
USE `hospitalE2N`;

#------------------------------------------------------------
# Table: patients
#------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `patients`(
        `id`        INT (11) AUTO_INCREMENT  NOT NULL ,
        `lastname`  VARCHAR (25) NOT NULL ,
        `firstname` VARCHAR (25) NOT NULL ,
        `birthdate` DATE NOT NULL ,
        `phone`     VARCHAR (25) ,
        `mail`      VARCHAR (100) NOT NULL ,
        PRIMARY KEY (`id`)
)ENGINE=InnoDB;


#------------------------------------------------------------
# Table: appointments
#------------------------------------------------------------

CREATE TABLE IF NOT EXISTS `appointments`(
        `id`         INT (11) AUTO_INCREMENT  NOT NULL ,
        `datehour`   DATETIME NOT NULL ,
        `patient_id` INT (11) NOT NULL ,
        PRIMARY KEY (`id`)
)ENGINE=InnoDB;

ALTER TABLE `appointments` ADD CONSTRAINT FK_appointment_patient FOREIGN KEY (`patient_id`) REFERENCES `patients`(`id`);
