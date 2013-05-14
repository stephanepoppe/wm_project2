SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL';

DROP SCHEMA IF EXISTS `wm_project2` ;
CREATE SCHEMA IF NOT EXISTS `wm_project2` DEFAULT CHARACTER SET latin1 COLLATE latin1_swedish_ci ;
USE `wm_project2` ;

-- -----------------------------------------------------
-- Table `wm_project2`.`users`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `wm_project2`.`users` ;

CREATE  TABLE IF NOT EXISTS `wm_project2`.`users` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(65) NULL ,
  `mail` VARCHAR(65) NULL ,
  `password` VARCHAR(95) NULL ,
  `photo_url` VARCHAR(85) NULL ,
  `created` DATETIME NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wm_project2`.`categories`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `wm_project2`.`categories` ;

CREATE  TABLE IF NOT EXISTS `wm_project2`.`categories` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `name` VARCHAR(45) NULL ,
  PRIMARY KEY (`id`) )
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wm_project2`.`services`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `wm_project2`.`services` ;

CREATE  TABLE IF NOT EXISTS `wm_project2`.`services` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `title` VARCHAR(45) NULL ,
  `description` TEXT NULL ,
  `location_name` VARCHAR(75) NULL ,
  `location_latitude` FLOAT NULL ,
  `location_longitude` FLOAT NULL ,
  `author_id` INT NOT NULL ,
  `added` TIMESTAMP NULL ,
  `deadline` TIMESTAMP NULL ,
  `categories_id` INT NOT NULL ,
  `tags` TEXT NULL ,
  PRIMARY KEY (`id`, `categories_id`) ,
  INDEX `fk_services_users` (`author_id` ASC) ,
  INDEX `fk_services_categories1` (`categories_id` ASC) ,
  CONSTRAINT `fk_services_users`
    FOREIGN KEY (`author_id` )
    REFERENCES `wm_project2`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_services_categories1`
    FOREIGN KEY (`categories_id` )
    REFERENCES `wm_project2`.`categories` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wm_project2`.`users_has_services`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `wm_project2`.`users_has_services` ;

CREATE  TABLE IF NOT EXISTS `wm_project2`.`users_has_services` (
  `users_id` INT NOT NULL ,
  `services_id` INT NOT NULL ,
  PRIMARY KEY (`users_id`, `services_id`) ,
  INDEX `fk_users_has_services_services1` (`services_id` ASC) ,
  INDEX `fk_users_has_services_users1` (`users_id` ASC) ,
  CONSTRAINT `fk_users_has_services_users1`
    FOREIGN KEY (`users_id` )
    REFERENCES `wm_project2`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_users_has_services_services1`
    FOREIGN KEY (`services_id` )
    REFERENCES `wm_project2`.`services` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `wm_project2`.`messages`
-- -----------------------------------------------------
DROP TABLE IF EXISTS `wm_project2`.`messages` ;

CREATE  TABLE IF NOT EXISTS `wm_project2`.`messages` (
  `id` INT NOT NULL AUTO_INCREMENT ,
  `sender_id` INT NOT NULL ,
  `receiver_id` INT NOT NULL ,
  `message` VARCHAR(45) NULL ,
  `date` DATETIME NULL ,
  PRIMARY KEY (`id`) ,
  INDEX `fk_messages_users1` (`sender_id` ASC) ,
  INDEX `fk_messages_users2` (`receiver_id` ASC) ,
  CONSTRAINT `fk_messages_users1`
    FOREIGN KEY (`sender_id` )
    REFERENCES `wm_project2`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION,
  CONSTRAINT `fk_messages_users2`
    FOREIGN KEY (`receiver_id` )
    REFERENCES `wm_project2`.`users` (`id` )
    ON DELETE NO ACTION
    ON UPDATE NO ACTION)
ENGINE = InnoDB;



SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;

-- -----------------------------------------------------
-- Data for table `wm_project2`.`categories`
-- -----------------------------------------------------
START TRANSACTION;
USE `wm_project2`;
INSERT INTO `wm_project2`.`categories` (`id`, `name`) VALUES (1, 'Huishouden');
INSERT INTO `wm_project2`.`categories` (`id`, `name`) VALUES (2, 'Tuin');
INSERT INTO `wm_project2`.`categories` (`id`, `name`) VALUES (3, 'Technisch');
INSERT INTO `wm_project2`.`categories` (`id`, `name`) VALUES (4, 'Creatief');
INSERT INTO `wm_project2`.`categories` (`id`, `name`) VALUES (5, 'School');

COMMIT;
