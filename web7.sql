-- MySQL Script generated by MySQL Workbench
-- Thu Mar 16 14:26:59 2017
-- Model: New Model    Version: 1.0
-- MySQL Workbench Forward Engineering

SET @OLD_UNIQUE_CHECKS=@@UNIQUE_CHECKS, UNIQUE_CHECKS=0;
SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0;
SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='TRADITIONAL,ALLOW_INVALID_DATES';

-- -----------------------------------------------------
-- Schema group18
-- -----------------------------------------------------

-- -----------------------------------------------------
-- Schema group18
-- -----------------------------------------------------
CREATE SCHEMA IF NOT EXISTS `group18` DEFAULT CHARACTER SET utf8 ;
USE `group18` ;

-- -----------------------------------------------------
-- Table `group18`.`User`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `group18`.`User` (
  `ID` VARCHAR(8) NOT NULL,
  `FirstName` VARCHAR(30) NOT NULL,
  `LastName` VARCHAR(30) NOT NULL,
  `Email` VARCHAR(50) NOT NULL,
  `Password` VARCHAR(50) NOT NULL,
  `Reputation` INT ZEROFILL NULL,
  PRIMARY KEY (`ID`),
  UNIQUE INDEX `Email_UNIQUE` (`Email` ASC))
ENGINE = InnoDB
COMMENT = '										';


-- -----------------------------------------------------
-- Table `group18`.`Task`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `group18`.`Task` (
  `idTaskNo` INT NOT NULL,
  `UserCreated` VARCHAR(8) NOT NULL,
  `Title` VARCHAR(100) NOT NULL,
  `Type` VARCHAR(50) NOT NULL,
  `Description` VARCHAR(200) NOT NULL,
  `Pages` INT NOT NULL,
  `Words` INT NOT NULL,
  `Format` VARCHAR(10) NOT NULL,
  `Sample` TEXT NULL,
  `DeadlineClaiming` DATETIME NOT NULL,
  `DeadlineSubmission` DATETIME NOT NULL,
  PRIMARY KEY (`idTaskNo`),
  INDEX `UserCreated_idx` (`UserCreated` ASC),
  CONSTRAINT 
    FOREIGN KEY (`UserCreated`)
    REFERENCES `group18`.`User` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `group18`.`Banned`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `group18`.`Banned` (
  `ID` VARCHAR(8) NOT NULL,
  `Moderator` VARCHAR(8) NOT NULL,
  `Date` DATETIME NOT NULL,
  `Reason` VARCHAR(50) NULL,
  PRIMARY KEY (`ID`),
  INDEX `moderator_idx` (`Moderator` ASC),
  CONSTRAINT 
    FOREIGN KEY (`ID`)
    REFERENCES `group18`.`User` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT 
    FOREIGN KEY (`Moderator`)
    REFERENCES `group18`.`User` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `group18`.`Major`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `group18`.`Major` (
  `ID` VARCHAR(8) NOT NULL,
  `Major` VARCHAR(50) NOT NULL,
  PRIMARY KEY (`ID`),
  CONSTRAINT 
    FOREIGN KEY (`ID`)
    REFERENCES `group18`.`User` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `group18`.`Tags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `group18`.`Tags` (
  `idTags` INT NOT NULL,
  `Description` VARCHAR(30) NOT NULL,
  PRIMARY KEY (`idTags`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `group18`.`UserTags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `group18`.`UserTags` (
  `idUserTags` INT NOT NULL,
  `ID` VARCHAR(8) NOT NULL,
  `Tag` INT NOT NULL,
  PRIMARY KEY (`idUserTags`),
  INDEX `ID_idx` (`ID` ASC),
  INDEX `idTags_idx` (`Tag` ASC),
  CONSTRAINT 
    FOREIGN KEY (`ID`)
    REFERENCES `group18`.`User` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT 
    FOREIGN KEY (`Tag`)
    REFERENCES `group18`.`Tags` (`idTags`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `group18`.`TaskTags`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `group18`.`TaskTags` (
  `idTaskTags` INT NOT NULL,
  `TaskNo` INT NOT NULL,
  `Tag` INT NOT NULL,
  PRIMARY KEY (`idTaskTags`),
  INDEX `taskNo_idx` (`TaskNo` ASC),
  INDEX `idTags_idx` (`Tag` ASC),
  CONSTRAINT 
    FOREIGN KEY (`Tag`)
    REFERENCES `group18`.`Tags` (`idTags`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT 
    FOREIGN KEY (`TaskNo`)
    REFERENCES `group18`.`Task` (`idTaskNo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `group18`.`StatusName`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `group18`.`StatusName` (
  `idStatusName` INT NOT NULL,
  `Status` VARCHAR(13) NOT NULL,
  PRIMARY KEY (`idStatusName`))
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `group18`.`Claimed`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `group18`.`Claimed` (
  `idClaimed` INT NOT NULL,
  `ID` VARCHAR(8) NOT NULL,
  `TaskNo` INT NOT NULL,
  `date` DATETIME NOT NULL,
  PRIMARY KEY (`idClaimed`),
  INDEX `ID_idx` (`ID` ASC),
  INDEX `TaskNo_idx` (`TaskNo` ASC),
  CONSTRAINT 
    FOREIGN KEY (`ID`)
    REFERENCES `group18`.`User` (`ID`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT 
    FOREIGN KEY (`TaskNo`)
    REFERENCES `group18`.`Task` (`idTaskNo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


-- -----------------------------------------------------
-- Table `group18`.`Status`
-- -----------------------------------------------------
CREATE TABLE IF NOT EXISTS `group18`.`Status` (
  `idStatus` INT NOT NULL,
  `TaskNo` INT NOT NULL,
  `StatusName` INT NOT NULL,
  `Date` DATETIME NULL,
  PRIMARY KEY (`idStatus`),
  INDEX `idStatus_idx` (`StatusName` ASC),
  INDEX `TaskNo_idx` (`TaskNo` ASC),
  CONSTRAINT 
    FOREIGN KEY (`StatusName`)
    REFERENCES `group18`.`StatusName` (`idStatusName`)
    ON DELETE CASCADE
    ON UPDATE CASCADE,
  CONSTRAINT 
    FOREIGN KEY (`TaskNo`)
    REFERENCES `group18`.`Task` (`idTaskNo`)
    ON DELETE CASCADE
    ON UPDATE CASCADE)
ENGINE = InnoDB;


SET SQL_MODE=@OLD_SQL_MODE;
SET FOREIGN_KEY_CHECKS=@OLD_FOREIGN_KEY_CHECKS;
SET UNIQUE_CHECKS=@OLD_UNIQUE_CHECKS;
