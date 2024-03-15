-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';

-- -----------------------------------------------------
-- Schema mydb
-- -----------------------------------------------------
-- -----------------------------------------------------
-- Schema OC_P5_db_blog
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema OC_P5_db_blog
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `OC_P5_db_blog` DEFAULT CHARACTER SET utf8mb4 ;
USE `OC_P5_db_blog` ;

-- -----------------------------------------------------
-- Table `OC_P5_db_blog`.`Post`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `OC_P5_db_blog`.`Post` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `title` VARCHAR(255) NOT NULL,
  `content` LONGTEXT NOT NULL,
  `tagline` VARCHAR(255) NULL DEFAULT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `user_id` INT NOT NULL,
  `slug` VARCHAR(255) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `slug_UNIQUE` (`slug` ASC) VISIBLE,
  INDEX `fk_Post_User_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_Post_User`
    FOREIGN KEY (`user_id`)
    REFERENCES `OC_P5_db_blog`.`User` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 59
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `OC_P5_db_blog`.`Comment`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `OC_P5_db_blog`.`Comment` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `content` LONGTEXT NOT NULL,
  `moderate` TINYINT NOT NULL,
  `status` VARCHAR(45) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `published_at` DATETIME NULL DEFAULT NULL,
  `post_id` INT NOT NULL,
  `user_id` INT NOT NULL,
  PRIMARY KEY (`id`),
  INDEX `fk_Comment_Post1_idx` (`post_id` ASC) VISIBLE,
  INDEX `fk_Comment_User1_idx` (`user_id` ASC) VISIBLE,
  CONSTRAINT `fk_Comment_Post1`
    FOREIGN KEY (`post_id`)
    REFERENCES `OC_P5_db_blog`.`Post` (`id`),
  CONSTRAINT `fk_Comment_User1`
    FOREIGN KEY (`user_id`)
    REFERENCES `OC_P5_db_blog`.`User` (`id`))
ENGINE = InnoDB
AUTO_INCREMENT = 83
DEFAULT CHARACTER SET = utf8mb4;


-- -----------------------------------------------------
-- Table `OC_P5_db_blog`.`user`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `OC_P5_db_blog`.`user` (
  `id` INT NOT NULL AUTO_INCREMENT,
  `role` JSON NULL DEFAULT NULL,
  `firstname` VARCHAR(45) NOT NULL,
  `lastname` VARCHAR(45) NOT NULL,
  `email` VARCHAR(45) NOT NULL,
  `password` VARCHAR(255) NOT NULL,
  `token` VARCHAR(120) NOT NULL,
  `created_at` DATETIME NOT NULL,
  `updated_at` DATETIME NULL DEFAULT NULL,
  `is_enabled` TINYINT NOT NULL,
  `profil_picture` VARCHAR(120) NULL DEFAULT NULL,
  `is_validated` TINYINT NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE INDEX `unique_email` (`email` ASC) VISIBLE)
ENGINE = InnoDB
AUTO_INCREMENT = 63
DEFAULT CHARACTER SET = utf8mb4;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
