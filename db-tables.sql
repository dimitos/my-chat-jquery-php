
--
-- Создать таблицу `users`
--
CREATE TABLE <db-name>.users (
  id int(11) NOT NULL,
  login varchar(100) NOT NULL,
  password varchar(64) NOT NULL,
  name varchar(100) NOT NULL DEFAULT 'NO',
  role int(1) NOT NULL DEFAULT 0 COMMENT '0 - обычный 1 - админ',
  login_date datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (id)
)

--
-- Создать таблицу `messages`
--
CREATE TABLE <db-name>.messages (
  id int(11) NOT NULL AUTO_INCREMENT,
  user_id varchar(100) DEFAULT NULL,
  user_name varchar(255) DEFAULT NULL,
  message varchar(1000) DEFAULT NULL,
  date datetime DEFAULT CURRENT_TIMESTAMP,
  status int(1) DEFAULT 1,
  PRIMARY KEY (id)
)
