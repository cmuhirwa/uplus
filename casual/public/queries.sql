
CREATE TABLE casual.employees (
 id INT(11) NOT NULL AUTO_INCREMENT , 
 name VARCHAR(255) NOT NULL , 
 nid VARCHAR(255) NOT NULL , 
 phone VARCHAR(255) NOT NULL , 
 img VARCHAR(255) NULL , 
 finger VARCHAR(255) NULL , 
 createdBy VARCHAR(255) NULL , 
 createdDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updatedBy VARCHAR(255) NULL , 
 updatedDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 arhived ENUM('NO','YES') NOT NULL DEFAULT 'NO',
 archivedBy VARCHAR(255) NULL,
 archivedDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
 PRIMARY KEY (`id`)
  ) ENGINE = InnoDB;


--------------------------------------------------------------------

INSERT INTO `employees` (
	`name`, `nid`, `phone`, `img`, `finger`, `createdBy`, )
VALUES (
	'MUHIRWA Clement', '1199380018360077', '0784848236', 'avatar/1.jpg', '1', '1',
	'MUHIRWA Clement', '1199380018360077', '0784848236', 'avatar/1.jpg', '1', '1',
	'MUHIRWA Clement', '1199380018360077', '0784848236', 'avatar/1.jpg', '1', '1',
	'MUHIRWA Clement', '1199380018360077', '0784848236', 'avatar/1.jpg', '1', '1'

	);

---------------------------------------------

CREATE TABLE casual.transactions (
 id INT(11) NOT NULL AUTO_INCREMENT , 
 amount VARCHAR(255) NOT NULL , 
 account VARCHAR(255) NOT NULL , 
 operation ENUM('IN','OUT') NOT NULL, 
 createdDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updatedBy VARCHAR(255) NULL , 
 updatedDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 arhived ENUM('NO','YES') NOT NULL DEFAULT 'NO',
 archivedBy VARCHAR(255) NULL,
 archivedDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
 PRIMARY KEY (`id`)
  ) ENGINE = InnoDB;
=======
CREATE TABLE casual.employees (
 id INT(11) NOT NULL AUTO_INCREMENT , 
 name VARCHAR(255) NOT NULL , 
 nid VARCHAR(255) NOT NULL , 
 phone VARCHAR(255) NOT NULL , 
 img VARCHAR(255) NULL , 
 finger VARCHAR(255) NULL , 
 createdBy VARCHAR(255) NULL , 
 createdDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updatedBy VARCHAR(255) NULL , 
 updatedDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 arhived ENUM('NO','YES') NOT NULL DEFAULT 'NO',
 archivedBy VARCHAR(255) NULL,
 archivedDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
 PRIMARY KEY (`id`)
  ) ENGINE = InnoDB;


--------------------------------------------------------------------

INSERT INTO `employees` (
	`name`, `nid`, `phone`, `img`, `finger`, `createdBy`, )
VALUES (
	'MUHIRWA Clement', '1199380018360077', '0784848236', 'avatar/1.jpg', '1', '1',
	'MUHIRWA Clement', '1199380018360077', '0784848236', 'avatar/1.jpg', '1', '1',
	'MUHIRWA Clement', '1199380018360077', '0784848236', 'avatar/1.jpg', '1', '1',
	'MUHIRWA Clement', '1199380018360077', '0784848236', 'avatar/1.jpg', '1', '1'

	);

---------------------------------------------

CREATE TABLE casual.transactions (
 id INT(11) NOT NULL AUTO_INCREMENT , 
 amount VARCHAR(255) NOT NULL , 
 account VARCHAR(255) NOT NULL , 
 operation ENUM('IN','OUT') NOT NULL,L , 
 createdDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updatedBy VARCHAR(255) NULL , 
 updatedDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 arhived ENUM('NO','YES') NOT NULL DEFAULT 'NO',
 archivedBy VARCHAR(255) NULL,
 archivedDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
 PRIMARY KEY (`id`)
  ) ENGINE = InnoDB;


----------------------------------------------------------------------------

CREATE TABLE casual.attendance (
 id INT(11) NOT NULL AUTO_INCREMENT , 
 casualId VARCHAR(255) NOT NULL , 
 payrollId VARCHAR(255) NOT NULL , 
 attendanceType ENUM('CHECKIN','CHECKOUT') NULL , 
 createdBy VARCHAR(255) NULL , 
 createdDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 updatedBy VARCHAR(255) NULL , 
 updatedDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
 arhived ENUM('NO','YES') NOT NULL DEFAULT 'NO',
 archivedBy VARCHAR(255) NULL,
 archivedDate TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP, 
 PRIMARY KEY (`id`)
  ) ENGINE = InnoDB;
