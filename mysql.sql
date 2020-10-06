CREATE DATABASE IF NOT EXISTS db_1 CHARACTER SET utf8 COLLATE utf8_general_ci;

CREATE TABLE IF NOT EXISTS db_1.comments (
	id int(11) NOT NULL AUTO_INCREMENT,
	name varchar(50) NOT NULL,
	email varchar(100) NOT NULL,
	comment text NOT NULL,
	PRIMARY KEY (id)
) ENGINE=innoDB DEFAULT CHARSET=utf8;

INSERT INTO db_1.comments (id, name, email, comment) VALUES
(1, 'Мария', 'maria@email.loc', 'Привет, меня зовут Мария!'),
(2, 'Сергей', 'sergey@email.loc', 'Хай, меня зовут Сергей!'),
(3, 'Владимир', 'vladimir@email.loc', 'Моё имя Владимир, рад знакомству.'),
(4, 'Елена', 'elena@email.loc', 'Я Елена, давайте знакомиться!'),
(5, 'Игорь', 'igor@email.loc', 'Привет, я Игорь! Как дела?'),
(6, 'Ольга', 'olga@email.loc', 'Хеллоу, моё имя Ольга!');