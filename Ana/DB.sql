CREATE DATABASE entrega_kudeaketa;

USE entrega_kudeaketa;

CREATE TABLE LANGILEA (
  nan VARCHAR(20) PRIMARY KEY,
  izena VARCHAR(50) NOT NULL,
  abizena VARCHAR(50) NOT NULL,
  erabiltzailea VARCHAR(50) UNIQUE NOT NULL,
  pasahitza VARCHAR(100) NOT NULL,
  rola ENUM('kudeatzailea', 'banatzailea') NOT NULL,
  telefonoa VARCHAR(20),
  helbidea VARCHAR(100),
  aktibo BOOLEAN DEFAULT TRUE
);

CREATE TABLE PAKETEA (
  pakete_id VARCHAR(50) PRIMARY KEY,
  tamaina ENUM('txikia', 'ertaina', 'handia') NOT NULL,
  helbidea VARCHAR(100) NOT NULL,
  hartzailea VARCHAR(50) NOT NULL
);

CREATE TABLE BANAKETA (
  id_banaketa INT AUTO_INCREMENT PRIMARY KEY,

  data_esleipena DATETIME NOT NULL,
  data_entrega DATETIME NULL,

  egoera ENUM('zain','banatzen','entregatua') DEFAULT 'zain',

  nan_langilea VARCHAR(20) NOT NULL,
  pakete_id VARCHAR(50) NOT NULL,

  FOREIGN KEY (nan_langilea) REFERENCES LANGILEA(nan),
  FOREIGN KEY (pakete_id) REFERENCES PAKETEA(pakete_id)
);

CREATE TABLE HISTORIALA (
  log_id INT AUTO_INCREMENT PRIMARY KEY,
  data_ordua DATETIME NOT NULL,
  azalpena VARCHAR(255) NOT NULL,
  pakete_id VARCHAR(50) NOT NULL,
  nan_langilea VARCHAR(20),

  FOREIGN KEY (pakete_id) REFERENCES PAKETEA(pakete_id),
  FOREIGN KEY (nan_langilea) REFERENCES LANGILEA(nan)
);


INSERT INTO LANGILEA (nan, izena, abizena, erabiltzailea, pasahitza, rola, telefonoa, helbidea) VALUES
('12345678A', 'Julio', 'Filloy', 'jfilloy', 'jfilloy1', 'banatzailea', '600111222', 'Bilbao'),
('23456789B', 'Aiert', 'Leunda', 'aleunda', 'aleunda2', 'banatzailea', '600222333', 'Donostia'),
('34567890C', 'Fatima', 'Ouahzizi', 'fouahzizi', 'fouahzizi3', 'kudeatzailea', '600333444', 'Vitoria');


INSERT INTO PAKETEA (pakete_id, tamaina, helbidea, hartzailea) VALUES
('PK001', 'txikia', 'Calle Mayor 12, Bilbao', 'Juan Pérez'),
('PK002', 'ertaina', 'Gran Vía 45, Bilbao', 'Ana Ruiz'),
('PK003', 'handia', 'Avenida Libertad 10, Donostia', 'Carlos Gómez'),
('PK004', 'txikia', 'Calle San Juan 3, Vitoria', 'Laura Martínez'),
('PK005', 'ertaina', 'Plaza Nueva 8, Bilbao', 'Mikel Arrieta');


INSERT INTO BANAKETA (data_esleipena, data_entrega, egoera, nan_langilea, pakete_id) VALUES
(NOW(), NULL, 'zain', '12345678A', 'PK001'),
(NOW(), NULL, 'zain', '12345678A', 'PK002'),
(NOW(), NULL, 'zain', '23456789B', 'PK003'),
(NOW(), NULL, 'zain', '23456789B', 'PK004'),
(NOW(), NULL, 'zain', '12345678A', 'PK005');