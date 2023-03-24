CREATE TABLE IF NOT EXISTS `klinika`.`owner` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `first_name` TEXT NOT NULL,
    `second_name` TEXT NOT NULL,
    `last_name` TEXT NOT NULL,
    `date_of_birth` DATE NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`customer` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `breed_id` INT NOT NULL,
    `species_id` INT NOT NULL,
    `owner_id` INT NOT NULL,
    `name` TEXT NOT NULL,
    `date_of_birth` DATE NOT NULL,
    `weight` INT NOT NULL,
    `height` INT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`breed` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `species_id` INT NOT NULL,
    `name` TEXT NOT NULL,
    `code` TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`species` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`accounts` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `permission_id` INT NOT NULL,
    `specialisation_id` INT NOT NULL,
    `first_name` TEXT NOT NULL,
    `second_name` TEXT NOT NULL,
    `last_name` TEXT NOT NULL,
    `date_of_birth` DATE NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`specialisation` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`appointment` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `appointment_type_id` INT NOT NULL,
    `account_id` INT NOT NULL,
    `customer_id` INT NOT NULL,
    `description` TEXT NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`appointment_types` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`medicine` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `amount` INT NOT NULL,
    `name` TEXT NOT NULL,
    `price` INT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`surgery` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `customer_id` INT NOT NULL,
    `name` TEXT NOT NULL,
    `price` FLOAT NOT NULL,
    `date` DATE NOT NULL,
    `time` TIME NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`treatment` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `customer_id` INT NOT NULL,
    `date_from` DATE NOT NULL,
    `date_to` DATE NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`perrmission` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`newsletter` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `name` TEXT NOT NULL,
    `email` TEXT NOT NULL,
    PRIMARY KEY (`id`)
);

CREATE TABLE IF NOT EXISTS `klinika`.`surgery_medicine` (
    `id` INT NOT NULL AUTO_INCREMENT,
    `surgery_id` INT NOT NULL,
    `medicine_id` INT NOT NULL,
    PRIMARY KEY (`id`)
);

INSERT INTO `newsletter` 
    (`id`, `name`,      `email`)
VALUES
    (NULL, 'Arkadiusz', 'arkadiusz@gmail.com'),
    (NULL, 'Michal',    'michal@gmail.com'),
    (NULL, 'Agnieszka', 'agnieszka@gmail.com'),
    (NULL, 'John',      'john@gmail.com'),
    (NULL, 'Stephen',   'stephen@gmail.com');

INSERT INTO `accounts` 
    (`id`, `permission_id`, `specialisation_id`, `first_name`, `second_name`,  `last_name`,  `date_of_birth`)
VALUES
    (NULL, 1,                1,                  'Arkadiusz',   'Robert',       'Kowalski',   '1996-01-01'),
    (NULL, 2,                2,                  'Michal',      '',             'Pomidorek',  '1999-02-13'),
    (NULL, 3,                3,                  'Jan',         '',             'Malinka',    '2000-03-22'),
    (NULL, 2,                2,                  'Aga',         'Joanna',       'Czekoladka', '2001-11-11'),
    (NULL, 1,                1,                  'Kalina',      'Marta',        'Idealna',    '1994-07-04');

INSERT INTO `owner`
    (`id`, `first_name`, `second_name`, `last_name`,   `date_of_birth`)
VALUES
    (NULL, 'Jan',        '',            'Kowalski',    '1998-01-12'),
    (NULL, 'Maria',      '',            'Mandarynka',  '1999-02-23'),
    (NULL, 'John',       '',            'Pomaranczka', '2000-03-14'),
    (NULL, 'Michael',    '',            'Jorx',        '2001-04-05'),
    (NULL, 'Jax',        '',            'Dani',        '2002-05-16'),
    (NULL, 'Marta',      '',            'Koziol',      '2003-06-27'),
    (NULL, 'Krzysztof',  '',            'March',       '2004-07-18'),
    (NULL, 'Kamil',      '',            'Nazwi',       '2005-08-09');

INSERT INTO `perrmission` 
    (`id`, `name`)
VALUES
    (NULL, 'Admin'),
    (NULL, 'Doctor'),
    (NULL, 'Nurse'),
    (NULL, 'Other');

INSERT INTO `specialisation` 
    (`id`, `name`)
VALUES
    (NULL, 'Doctor'),
    (NULL, 'Technician'),
    (NULL, 'Nurse'),
    (NULL, 'Other');

INSERT INTO `species` 
    (`id`, `name`)
VALUES
    (NULL, 'Cat'),
    (NULL, 'Dog'),
    (NULL, 'Bird'),
    (NULL, 'Fish'),
    (NULL, 'Snake'),
    (NULL, 'Mouse');

INSERT INTO `breed` 
    (`id`, `species_id`, `name`,                           `code`)
VALUES
    (NULL,  1,           'Abyssinian Cat',                 'SAD1'),
    (NULL,  1,           'American Bobtail Cat Breed',     'AD1'),
    (NULL,  1,           'American Curl Cat Breed',        'S123'),
    (NULL,  1,           'American Shorthair Cat',         'S12S'),
    (NULL,  1,           'American Wirehair Cat Breed',    'S1D21'),
    (NULL,  1,           'Balinese-Javanese Cat Breed',    'SADASD'),
    (NULL,  1,           'Bengal Cat',                     'SASD'),
    (NULL,  1,           'Birman Cat Breed',               'SADS'),
    (NULL,  1,           'Bombay Cat',                     'QDW1'),
    (NULL,  1,           'Burmese Cat',                    'SAFF'),
    (NULL,  1,           'Chartreux Cat Breed',            'SAFF'),
    (NULL,  1,           'Cornish Rex Cat Breed',          'SAFF'),
    (NULL,  1,           'Devon Rex Cat Breed',            'SAFF'),
    (NULL,  1,           'Egyptian Mau Cat',               'SAFF'),
    (NULL,  1,           'European Burmese Cat Breed',     'SAFF'),
    (NULL,  1,           'Exotic Shorthair Cat Breed',     'SAFF'),
    (NULL,  1,           'Havana Brown Cat Breed',         'SAFF'),
    (NULL,  1,           'Himalayan Cat Breed',            'SAFF'),
    (NULL,  1,           'Japanese Bobtail Cat Breed',     'SAFF'),
    (NULL,  1,           'Korat Cat Breed',                'SAFF'),
    (NULL,  1,           'LaPerm Cat',                     'SAFF'),
    (NULL,  1,           'Maine Coon Cat Breed',           'SAFF'),
    (NULL,  1,           'Manx Cat',                       'SAFF'),
    (NULL,  2,           'Affenpinscher',                  'SAIU'),
    (NULL,  2,           'Afghan Hound',                   'SAIU'),
    (NULL,  2,           'Airedale Terrier',               'SAIU'),
    (NULL,  2,           'Akita',                          'SAIU'),
    (NULL,  2,           'Alaskan Malamute',               'SAIU'),
    (NULL,  2,           'American English Coonhound',     'SAIU'),
    (NULL,  2,           'American Eskimo Dog',            'SAIU'),
    (NULL,  2,           'American Foxhound',              'SAIU'),
    (NULL,  2,           'American Staffordshire Terrier', 'SAIU'),
    (NULL,  2,           'American Water Spaniel',         'SAIU'),
    (NULL,  2,           'Australian Terrier',             'SAIU'),
    (NULL,  2,           'Anatolian Shepherd Dog',         'SAIU'),
    (NULL,  2,           'Australian Cattle Dog',          'SAIU'),
    (NULL,  2,           'Australian Shepherd',            'SAIU'),
    (NULL,  2,           'Basenji',                        'SAIU'),
    (NULL,  2,           'Beagle',                         'SAIU'),
    (NULL,  2,           'Bearded Collie',                 'SAIU'),
    (NULL,  2,           'Beauceron',                      'SAIU'),
    (NULL,  2,           'Bedlington Terrier',             'SAIU');

INSERT INTO `medicine` 
    (`id`, `name`,  `amount`, `price`)
VALUES
    (NULL, 'Lek 1', '1',      '3'),
    (NULL, 'Lek 2', '2',      '4'),
    (NULL, 'Lek 3', '1',      '5'),
    (NULL, 'Lek 4', '3',      '6'),
    (NULL, 'Lek 5', '4',      '3'),
    (NULL, 'Lek 6', '1',      '1'),
    (NULL, 'Lek 7', '2',      '5'),
    (NULL, 'Lek 8', '1',      '8');

INSERT INTO `customer` 
    (`id`, `breed_id`, `species_id`, `owner_id`, `name`,   `date_of_birth`, `weight`, `height`)
VALUES
    (NULL, 8,          1,             1,         'Barman',  '2021-12-27',    3,        30),
    (NULL, 21,         1,             2,         'Lolka',   '2021-12-27',    4,        40),
    (NULL, 39,         2,             3,         'Abi',     '2022-01-11',    8,        80),
    (NULL, 41,         2,             4,         'Jaxon',   '2021-09-08',    10,       100),
    (NULL, 22,         1,             5,         'Miranda', '2022-01-18',    5,        50),
    (NULL, 10,         1,             6,         'Doll',    '2022-01-06',    3,        30),
    (NULL, 22,         1,             7,         'Bianka',  '2022-01-10',    2,        20),
    (NULL, 16,         1,             8,         'Felix',   '2022-01-07',    6,        60);

INSERT INTO `appointment_types` 
    (`id`, `name`)
VALUES
    (NULL, 'Normal'),
    (NULL, 'After work hours');

INSERT INTO `appointment`
    (`id`, `appointment_type_id`, `account_id`, `customer_id`, `description`,   `date`,       `time`)
VALUES
    (NULL, '1',                   '1',           '1',          'Przeglad',      '2022-01-05', '11:11:10'),
    (NULL, '1',                   '1',           '2',          'Przeglad kota', '2022-01-05', '12:12:12'),
    (NULL, '1',                   '1',           '3',          'Przeglad',      '2022-01-05', '13:13:13');

INSERT INTO `surgery` 
    (`id`, `customer_id`, `name`,          `price`, `date`,       `time`)
VALUES
    (NULL, '1',           'Operacja nosa', '2000',  '2022-01-12', '09:49:51'),
    (NULL, '2',           'Operacja ucha', '1400',  '2022-01-12', '09:49:51');

INSERT INTO `treatment` 
    (`id`, `customer_id`, `date_from`,  `date_to`)
VALUES
    (NULL, '1',           '2022-01-05',  '2022-01-01'),
    (NULL, '3',           '2022-03-31',  '2022-05-14'),
    (NULL, '5',           '2022-03-31',  '2022-05-25'),
    (NULL, '6',           '2022-04-13',  '2022-06-11'),
    (NULL, '8',           '2022-07-021', '2022-09-07');

INSERT INTO `surgery_medicine` 
    (`id`, `surgery_id`, `medicine_id`) 
VALUES 
    (NULL, '1',          '1'), 
    (NULL, '1',          '2'), 
    (NULL, '1',          '3'), 
    (NULL, '1',          '4'), 
    (NULL, '2',          '5'), 
    (NULL, '2',          '6'), 
    (NULL, '2',          '7'), 
    (NULL, '2',          '8');


SELECT
    `customer`.`id`,
    `customer`.`name`,
    `customer`.`date_of_birth`,
    `customer`.`weight`,
    `customer`.`height`,
    `owner`.`first_name`,
    `owner`.`second_name`,
    `owner`.`last_name`,
    `breed`.`name` AS `breed_name`,
    `species`.`name` AS `species_name`
FROM `customer`, `breed`, `species`, `owner`
WHERE `customer`.`breed_id` = `breed`.`id` AND `customer`.`species_id` = `species`.`id` AND `customer`.`owner_id` = `owner`.`id` AND `customer`.`id` = 1 LIMIT 1;

SELECT
    `customer`.`id`,
    `customer`.`name`,
    `customer`.`date_of_birth`,
    `customer`.`weight`,
    `customer`.`height`,
    `owner`.`first_name`,
    `owner`.`second_name`,
    `owner`.`last_name`,
    `breed`.`name` AS `breed_name`,
    `species`.`name` AS `species_name`
FROM `customer`, `breed`, `species`, `owner`
WHERE `customer`.`breed_id` = `breed`.`id` AND `customer`.`species_id` = `species`.`id` AND `customer`.`owner_id` = `owner`.`id` AND `customer`.`id` = 1 LIMIT 1;

SELECT `id`, `name` FROM `species`;

SELECT `id`, `first_name`, `second_name`, `last_name` FROM `owner`;

SELECT `id`, `name` FROM `perrmission`;

SELECT `id`, `name` FROM `specialisation`;

SELECT `id`, `owner_id`, `name` FROM `customer`;

SELECT `id`, `first_name`, `second_name`, `last_name` FROM `accounts`;

SELECT `id`, `species_id`, `name` FROM `breed`;

SELECT `id`, `name` FROM `appointment_types`;

SELECT `id`, `name`, `email` FROM `newsletter`;