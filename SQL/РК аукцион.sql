-- --------------------------------------------------------
-- Хост:                         127.0.0.1
-- Версия сервера:               8.0.15 - MySQL Community Server - GPL
-- Операционная система:         Win64
-- HeidiSQL Версия:              10.1.0.5464
-- --------------------------------------------------------

/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET NAMES utf8 */;
/*!50503 SET NAMES utf8mb4 */;
/*!40014 SET @OLD_FOREIGN_KEY_CHECKS=@@FOREIGN_KEY_CHECKS, FOREIGN_KEY_CHECKS=0 */;
/*!40101 SET @OLD_SQL_MODE=@@SQL_MODE, SQL_MODE='NO_AUTO_VALUE_ON_ZERO' */;


-- Дамп структуры базы данных card_game
DROP DATABASE IF EXISTS `card_game`;
CREATE DATABASE IF NOT EXISTS `card_game` /*!40100 DEFAULT CHARACTER SET utf8 */;
USE `card_game`;

-- Дамп структуры для таблица card_game.auction_cards
DROP TABLE IF EXISTS `auction_cards`;
CREATE TABLE IF NOT EXISTS `auction_cards` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `seller` varchar(50) NOT NULL,
  `card_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `start_price` int(11) NOT NULL,
  `start_date` datetime NOT NULL,
  `sell_date` datetime NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_auction_cards_cards` (`card_id`),
  KEY `FK_auction_cards_collections` (`seller`,`card_id`),
  CONSTRAINT `FK_auction_cards_cards` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_auction_cards_collections` FOREIGN KEY (`seller`, `card_id`) REFERENCES `collections` (`login`, `card_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_auction_cards_user_auth` FOREIGN KEY (`seller`) REFERENCES `user_auth` (`login`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=13 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.auction_cards: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `auction_cards` DISABLE KEYS */;
INSERT INTO `auction_cards` (`id`, `seller`, `card_id`, `quantity`, `start_price`, `start_date`, `sell_date`) VALUES
	(4, 'admin', 2, 1, 1, '2019-05-14 19:31:51', '2019-05-16 19:31:53');
/*!40000 ALTER TABLE `auction_cards` ENABLE KEYS */;

-- Дамп структуры для событие card_game.auction_cleaning
DROP EVENT IF EXISTS `auction_cleaning`;
DELIMITER //
CREATE DEFINER=`root`@`%` EVENT `auction_cleaning` ON SCHEDULE EVERY 1 SECOND STARTS '2019-05-18 00:20:54' ON COMPLETION PRESERVE ENABLE DO BEGIN
	declare id_clean int;
	declare price_clean int;
	declare seller_clean varchar(50);
	declare buyer_clean varchar(50);
	declare buyer_current varchar(50);
	declare price_current int;
	declare money_buyer int;
	declare money_seller int;
	DECLARE cur1 CURSOR FOR SELECT id, seller FROM auction_cards where sell_date<=NOW();
	DECLARE cur2 CURSOR FOR SELECT member, price FROM auction_queue where auction_id=id_clean;
	OPEN cur1;
	OPEN cur2;
	set autocommit=0;
	 read_loop: LOOP
    FETCH cur1 INTO id_clean, seller_clean;
    IF done THEN
      LEAVE read_loop;
    END IF;
    		clean_loop: LOOP
    		FETCH cur2 INTO buyer_current, price_current;
    		
    		IF done THEN
    				
      		LEAVE read_loop;
    		END IF;
    		
    		start transaction;
    		select money from user_auth where login=buyer_current into money_buyer;
    		if money_buyer>=price_current then
    			update user_auth set money=money_buyer-price_current where login=buyer_current;
    			select money from user_auth where login=seller_clean into money_seller;
    			update user_auth set money=money_seller+price_current where login=seller_clean;
    			delete from auction_queue where auction_id=id_clean;
    			commit;
    			LEAVE read_loop;
    		end if;
  			END LOOP;
  END LOOP;

  CLOSE cur1;
  CLOSE cur2;
END//
DELIMITER ;

-- Дамп структуры для таблица card_game.auction_queue
DROP TABLE IF EXISTS `auction_queue`;
CREATE TABLE IF NOT EXISTS `auction_queue` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `auction_id` int(11) unsigned NOT NULL,
  `member` varchar(50) NOT NULL,
  `price` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_auction_queue_auction_cards` (`auction_id`),
  KEY `FK_auction_queue_user_auth` (`member`),
  CONSTRAINT `FK_auction_queue_auction_cards` FOREIGN KEY (`auction_id`) REFERENCES `auction_cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_auction_queue_user_auth` FOREIGN KEY (`member`) REFERENCES `user_auth` (`login`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=16 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.auction_queue: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `auction_queue` DISABLE KEYS */;
/*!40000 ALTER TABLE `auction_queue` ENABLE KEYS */;

-- Дамп структуры для таблица card_game.cards
DROP TABLE IF EXISTS `cards`;
CREATE TABLE IF NOT EXISTS `cards` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `title` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `rarity_title` varchar(50) NOT NULL,
  `mana_cost` int(11) NOT NULL,
  `life` int(11) NOT NULL,
  `attack` int(11) NOT NULL,
  `kind` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_cards_rarity` (`rarity_title`),
  KEY `FK_cards_kind` (`kind`),
  CONSTRAINT `FK_cards_kind` FOREIGN KEY (`kind`) REFERENCES `kind` (`title`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_cards_rarity` FOREIGN KEY (`rarity_title`) REFERENCES `rarity` (`title`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=29 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.cards: ~20 rows (приблизительно)
/*!40000 ALTER TABLE `cards` DISABLE KEYS */;
INSERT INTO `cards` (`id`, `title`, `description`, `rarity_title`, `mana_cost`, `life`, `attack`, `kind`) VALUES
	(1, 'Беретор', 'Эльф тестовый', 'Редкая', 1, 1, 2, 'Человек'),
	(2, 'Арагорн', 'Тестовый герой', 'Редкая', 10, 10, 10, 'Человек'),
	(5, 'Тест', 'Тестовый герой', 'Редкая', 10, 10, 10, 'Человек'),
	(6, 'lalala', 'Тестовый герой', 'Редкая', 10, 10, 10, 'Человек'),
	(7, 'lol', 'lol', 'Редкая', 10, 10, 10, 'Человек'),
	(8, 'lol', 'lol', 'Редкая', 10, 10, 10, 'Человек'),
	(9, 'max', 'max', 'Редкая', 10, 10, 10, 'Человек'),
	(10, 'as', 'as', 'Редкая', 13, 10, 10, 'Человек'),
	(11, 'qwe', 'qwe', 'Редкая', 12, 12, 1, 'Человек'),
	(12, 'qwqweqw', 'qweqw', 'Редкая', 23, 1, 1, 'Человек'),
	(16, 'Аватар', 'автар', 'Редкая', 12, 12, 12, 'Человек'),
	(17, 'qwqweqw', 'qweqw', 'Редкая', 23, 1, 1, 'Человек'),
	(18, 'qwqweqw', 'qweqw', 'Редкая', 23, 1, 1, 'Человек'),
	(19, 'Беретор', 'Старый тестовый герой', 'Редкая', 10, 1, 1, 'Человек'),
	(20, 'Беретор', 'Старый тестовый герой', 'Редкая', 10, 1, 1, 'Человек'),
	(24, 'Беретор', 'Старый тестовый герой', 'Обычная', 10, 1, 1, 'Человек'),
	(25, 'lol', 'as', 'Обычная', 1, 1, 1, 'Человек'),
	(26, 'lol', 'as', 'Обычная', 1, 1, 1, 'Человек'),
	(27, '1', '1', 'Обычная', 1, 1, 1, 'Человек'),
	(28, 'aaaaaaaaaaaa', 'aaaaaaaaaaa', 'Редкая', 12, 12, 12, 'Человек');
/*!40000 ALTER TABLE `cards` ENABLE KEYS */;

-- Дамп структуры для таблица card_game.chats
DROP TABLE IF EXISTS `chats`;
CREATE TABLE IF NOT EXISTS `chats` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `login1` varchar(50) NOT NULL,
  `login2` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_chats_user_auth` (`login1`),
  KEY `FK_chats_user_auth_2` (`login2`),
  CONSTRAINT `FK_chats_user_auth` FOREIGN KEY (`login1`) REFERENCES `user_auth` (`login`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_chats_user_auth_2` FOREIGN KEY (`login2`) REFERENCES `user_auth` (`login`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=11 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.chats: ~3 rows (приблизительно)
/*!40000 ALTER TABLE `chats` DISABLE KEYS */;
INSERT INTO `chats` (`id`, `login1`, `login2`) VALUES
	(1, 'admin', 'pag'),
	(5, 'admin', 'lol'),
	(6, 'pag', 'lol'),
	(8, 'admin', 'alex');
/*!40000 ALTER TABLE `chats` ENABLE KEYS */;

-- Дамп структуры для таблица card_game.collections
DROP TABLE IF EXISTS `collections`;
CREATE TABLE IF NOT EXISTS `collections` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `card_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`),
  KEY `card_id` (`card_id`),
  KEY `index` (`login`,`card_id`),
  CONSTRAINT `FK_collections_cards` FOREIGN KEY (`card_id`) REFERENCES `cards` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_collections_user_auth` FOREIGN KEY (`login`) REFERENCES `user_auth` (`login`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.collections: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `collections` DISABLE KEYS */;
INSERT INTO `collections` (`id`, `login`, `card_id`, `quantity`) VALUES
	(1, 'admin', 1, 2),
	(2, 'admin', 2, 2),
	(3, 'pag', 5, 1);
/*!40000 ALTER TABLE `collections` ENABLE KEYS */;

-- Дамп структуры для таблица card_game.decks
DROP TABLE IF EXISTS `decks`;
CREATE TABLE IF NOT EXISTS `decks` (
  `id` int(11) NOT NULL,
  `login` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `card_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `login` (`login`),
  KEY `card_id` (`card_id`),
  KEY `FK_decks_collections` (`login`,`card_id`),
  CONSTRAINT `FK_decks_collections` FOREIGN KEY (`login`, `card_id`) REFERENCES `collections` (`login`, `card_id`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.decks: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `decks` DISABLE KEYS */;
/*!40000 ALTER TABLE `decks` ENABLE KEYS */;

-- Дамп структуры для таблица card_game.experience
DROP TABLE IF EXISTS `experience`;
CREATE TABLE IF NOT EXISTS `experience` (
  `lvl` int(11) NOT NULL,
  `exp` int(11) NOT NULL,
  PRIMARY KEY (`lvl`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.experience: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `experience` DISABLE KEYS */;
/*!40000 ALTER TABLE `experience` ENABLE KEYS */;

-- Дамп структуры для таблица card_game.friends
DROP TABLE IF EXISTS `friends`;
CREATE TABLE IF NOT EXISTS `friends` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `subscriber` varchar(50) NOT NULL,
  `player` varchar(50) NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_friends_user_auth` (`subscriber`),
  KEY `FK_friends_user_auth_2` (`player`),
  CONSTRAINT `FK_friends_user_auth` FOREIGN KEY (`subscriber`) REFERENCES `user_auth` (`login`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_friends_user_auth_2` FOREIGN KEY (`player`) REFERENCES `user_auth` (`login`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.friends: ~2 rows (приблизительно)
/*!40000 ALTER TABLE `friends` DISABLE KEYS */;
INSERT INTO `friends` (`id`, `subscriber`, `player`) VALUES
	(1, 'admin', 'pag'),
	(2, 'pag', 'admin'),
	(3, 'admin', 'lol');
/*!40000 ALTER TABLE `friends` ENABLE KEYS */;

-- Дамп структуры для таблица card_game.kind
DROP TABLE IF EXISTS `kind`;
CREATE TABLE IF NOT EXISTS `kind` (
  `title` varchar(50) NOT NULL,
  PRIMARY KEY (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.kind: ~1 rows (приблизительно)
/*!40000 ALTER TABLE `kind` DISABLE KEYS */;
INSERT INTO `kind` (`title`) VALUES
	('Человек');
/*!40000 ALTER TABLE `kind` ENABLE KEYS */;

-- Дамп структуры для таблица card_game.messages
DROP TABLE IF EXISTS `messages`;
CREATE TABLE IF NOT EXISTS `messages` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `chat_id` int(11) NOT NULL,
  `sender_login` varchar(50) NOT NULL,
  `message` text NOT NULL,
  PRIMARY KEY (`id`),
  KEY `FK_messages_chats` (`chat_id`),
  KEY `FK_messages_user_auth` (`sender_login`),
  CONSTRAINT `FK_messages_chats` FOREIGN KEY (`chat_id`) REFERENCES `chats` (`id`) ON DELETE CASCADE ON UPDATE CASCADE,
  CONSTRAINT `FK_messages_user_auth` FOREIGN KEY (`sender_login`) REFERENCES `user_auth` (`login`) ON DELETE CASCADE ON UPDATE CASCADE
) ENGINE=InnoDB AUTO_INCREMENT=71 DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.messages: ~54 rows (приблизительно)
/*!40000 ALTER TABLE `messages` DISABLE KEYS */;
INSERT INTO `messages` (`id`, `chat_id`, `sender_login`, `message`) VALUES
	(1, 1, 'pag', 'hello!'),
	(2, 1, 'admin', 'hello, Pag!'),
	(3, 1, 'admin', 'Как дела'),
	(4, 1, 'admin', 'Как дела'),
	(5, 1, 'admin', 'lol'),
	(6, 1, 'admin', 'lol'),
	(7, 1, 'admin', 'kek'),
	(8, 1, 'admin', 'haha'),
	(9, 1, 'pag', 'Все оки!'),
	(10, 1, 'pag', 'просто супер'),
	(11, 1, 'pag', 'лфлф'),
	(12, 1, 'pag', 'лала'),
	(13, 5, 'admin', 'Привет!'),
	(15, 6, 'pag', 'lol'),
	(16, 1, 'pag', 'привет'),
	(17, 1, 'admin', 'привет'),
	(18, 1, 'pag', 'lol'),
	(19, 1, 'admin', 'dsdsd'),
	(20, 1, 'admin', 'dsdsds'),
	(21, 1, 'admin', 'dsdsd'),
	(22, 1, 'admin', 'dsdsd'),
	(23, 1, 'admin', 'fdfdf'),
	(24, 1, 'admin', 'ghghgh'),
	(25, 1, 'admin', 'привет'),
	(26, 1, 'admin', 'как дела'),
	(27, 1, 'admin', 'все хорошо?'),
	(28, 1, 'admin', 'sdgjhsjghjksflhgjksfhgjlhsfdljghlsdfhglsdjghlsjkdghlsjdhgjsfdhgjkshdjkhdslfhgksdhglksjdhglkjsdghlsjdkhgljshdglkjhsdkjghsldjhgdsgldshglsdkglsdkghsd'),
	(29, 1, 'admin', 'паппапа'),
	(30, 1, 'admin', 'а вот и я'),
	(31, 1, 'admin', 'я добавил таймер!'),
	(32, 1, 'admin', 'Теперь обновляется по урл'),
	(33, 1, 'admin', 'lalala'),
	(34, 1, 'admin', 'lol'),
	(35, 1, 'admin', 'kek'),
	(36, 1, 'pag', 'как дела'),
	(37, 1, 'admin', 'Нормально!'),
	(38, 1, 'admin', 'lala'),
	(39, 1, 'admin', 'lala'),
	(40, 1, 'admin', 'lala'),
	(41, 1, 'admin', '[a[a'),
	(42, 1, 'admin', 'lolkek4eburek'),
	(43, 1, 'admin', 'privet'),
	(44, 1, 'admin', 'privet'),
	(45, 1, 'admin', 'privet'),
	(46, 1, 'admin', 'privet1233'),
	(47, 1, 'admin', 'privet1233'),
	(48, 1, 'admin', 'fdg'),
	(49, 1, 'admin', 'asda'),
	(50, 1, 'admin', 'asda'),
	(51, 1, 'admin', 'asda'),
	(52, 1, 'admin', 'asda'),
	(53, 1, 'admin', 'asadaaa'),
	(54, 1, 'admin', 'keeek'),
	(55, 1, 'admin', 'aa'),
	(56, 1, 'admin', 'aa'),
	(57, 1, 'admin', 's'),
	(58, 1, 'admin', 'red'),
	(59, 1, 'admin', 'lal'),
	(60, 1, 'admin', 'lalfaf'),
	(61, 1, 'admin', 'lalfaf'),
	(62, 1, 'admin', 'afasfd'),
	(63, 1, 'admin', 'lalal'),
	(64, 1, 'admin', 'fadfsadfasfafafasdfafafasdfasfasfadfadfasdfasdfasfasfasfadfaf'),
	(65, 1, 'admin', 'sdasd'),
	(66, 1, 'admin', 'sfsdfds'),
	(67, 1, 'admin', 'sfere'),
	(68, 1, 'admin', 'sfasf'),
	(69, 1, 'admin', 'sdgsdffdhdfhgdfhg'),
	(70, 1, 'admin', 'xczczdfsdfg');
/*!40000 ALTER TABLE `messages` ENABLE KEYS */;

-- Дамп структуры для процедура card_game.myevent
DROP PROCEDURE IF EXISTS `myevent`;
DELIMITER //
CREATE DEFINER=`root`@`%` PROCEDURE `myevent`()
BEGIN
	declare id_clean int;
	declare price_clean int;
	declare seller_clean varchar(50);
	declare buyer_clean varchar(50);
	declare buyer_current varchar(50);
	declare price_current int;
	declare money_buyer int;
	declare money_seller int;
	DECLARE done INT DEFAULT FALSE;
	DECLARE cur1 CURSOR FOR SELECT auction_cards.id, seller, member, price FROM auction_cards INNER JOIN auction_queue ON auction_cards.id=auction_queue.auction_id where sell_date<=NOW();
	DECLARE CONTINUE HANDLER FOR NOT FOUND SET done = TRUE;
	OPEN cur1;
	set autocommit=0;
	
	 read_loop: LOOP
    FETCH cur1 into id_clean, seller_clean, buyer_current, price_current;
    IF done THEN
      LEAVE read_loop;
    END IF;
    		

    start transaction;
    		
	   select money from user_auth where login=buyer_current into money_buyer;
	   if money_buyer>=price_current then
	    	update user_auth set money=money_buyer-price_current where login=buyer_current;
	    	select money from user_auth where login=seller_clean into money_seller;
	    	update user_auth set money=money_seller+price_current where login=seller_clean;
	    	delete from auction_cards where id=id_clean;
	    	commit;
	   end if;
  	END LOOP;

  CLOSE cur1;
END//
DELIMITER ;

-- Дамп структуры для таблица card_game.rarity
DROP TABLE IF EXISTS `rarity`;
CREATE TABLE IF NOT EXISTS `rarity` (
  `title` varchar(50) NOT NULL,
  `build_cost` int(11) NOT NULL,
  `spray_cost` int(11) NOT NULL,
  PRIMARY KEY (`title`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.rarity: ~0 rows (приблизительно)
/*!40000 ALTER TABLE `rarity` DISABLE KEYS */;
INSERT INTO `rarity` (`title`, `build_cost`, `spray_cost`) VALUES
	('Обычная', 40, 5),
	('Редкая', 100, 20);
/*!40000 ALTER TABLE `rarity` ENABLE KEYS */;

-- Дамп структуры для таблица card_game.user_auth
DROP TABLE IF EXISTS `user_auth`;
CREATE TABLE IF NOT EXISTS `user_auth` (
  `login` varchar(50) NOT NULL,
  `email` varchar(50) NOT NULL,
  `password_hash` varchar(64) NOT NULL,
  `session_hash` varchar(64) NOT NULL DEFAULT '',
  `money` int(11) unsigned NOT NULL DEFAULT '0',
  `dust` int(11) unsigned NOT NULL DEFAULT '0',
  `exp` int(11) unsigned NOT NULL DEFAULT '0',
  PRIMARY KEY (`login`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- Дамп данных таблицы card_game.user_auth: ~6 rows (приблизительно)
/*!40000 ALTER TABLE `user_auth` DISABLE KEYS */;
INSERT INTO `user_auth` (`login`, `email`, `password_hash`, `session_hash`, `money`, `dust`, `exp`) VALUES
	('admin', 'admin@admin.admin', '8c6976e5b5410415bde908bd4dee15dfb167a9c873fc4bb8a81f6f2ab448a918', 'lol', 55, 0, 0),
	('alex', 'alex@alex.alex', '4135aa9dc1b842a653dea846903ddb95bfb8c5a10c504a7fa16e10bc31d1fdf0', '', 0, 0, 0),
	('lal', 'lal', 'a7a8572a4aabf25aac4c4f2eecd2a16e5b20822c5936eb2686414e801fdfb4f8', '', 0, 0, 0),
	('lol', 'lol', '07123e1f482356c415f684407a3b8723e10b2cbbc0b8fcd6282c49d37c9c1abc', '', 104, 0, 0),
	('maks', 'maks@maks.maks', '7ad071cef29a13b5a87653e67f5aa1c43dc4726590a387cc36d3e35e91c4e26c', '', 0, 0, 0),
	('pag', 'pag', '10e583b1e5d93ef8afd8fd6a6f20113825514ebe1fb6fe9110aa24b675c8a605', '', 81, 0, 0);
/*!40000 ALTER TABLE `user_auth` ENABLE KEYS */;

-- Дамп структуры для триггер card_game.auction_cards_check
DROP TRIGGER IF EXISTS `auction_cards_check`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `auction_cards_check` BEFORE INSERT ON `auction_cards` FOR EACH ROW BEGIN
	 DECLARE x INT;
  	 SET x = (SELECT quantity FROM collections WHERE (login = new.seller and card_id=new.card_id));
	 IF x<new.quantity then
	 	SIGNAL SQLSTATE 'ERROR' SET MESSAGE_TEXT = 'Недостаточно карт для аукциона';
	 end if; 
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Дамп структуры для триггер card_game.auction_cards_repeat_check
DROP TRIGGER IF EXISTS `auction_cards_repeat_check`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `auction_cards_repeat_check` BEFORE INSERT ON `auction_cards` FOR EACH ROW BEGIN
	declare x int;
	SELECT card_id FROM auction_cards WHERE seller=new.seller and card_id=new.card_id INTO @x;
	if (x is null) then
		SIGNAL SQLSTATE 'ERROR' SET MESSAGE_TEXT = 'Карта уже выставлена на торги';
	end if;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

-- Дамп структуры для триггер card_game.auction_queue_before_insert
DROP TRIGGER IF EXISTS `auction_queue_before_insert`;
SET @OLDTMP_SQL_MODE=@@SQL_MODE, SQL_MODE='ONLY_FULL_GROUP_BY,STRICT_TRANS_TABLES,NO_ZERO_IN_DATE,NO_ZERO_DATE,ERROR_FOR_DIVISION_BY_ZERO,NO_ENGINE_SUBSTITUTION';
DELIMITER //
CREATE TRIGGER `auction_queue_before_insert` BEFORE INSERT ON `auction_queue` FOR EACH ROW BEGIN
	declare max_price int;
	declare del_id int;
	declare member_user varchar(50);
	select max(price) from auction_queue where auction_id=new.auction_id into max_price;
	if max_price>=new.price then
		SIGNAL SQLSTATE 'ERROR' SET message_text='Недостаточно денег для покупки';
	end if;
	select seller from auction_cards where id=new.auction_id and seller=new.member into member_user;
	if member_user is not null then
		SIGNAL SQLSTATE 'ERROR' SET message_text='Продавец не может быть покупателем';
	end if;
END//
DELIMITER ;
SET SQL_MODE=@OLDTMP_SQL_MODE;

/*!40101 SET SQL_MODE=IFNULL(@OLD_SQL_MODE, '') */;
/*!40014 SET FOREIGN_KEY_CHECKS=IF(@OLD_FOREIGN_KEY_CHECKS IS NULL, 1, @OLD_FOREIGN_KEY_CHECKS) */;
/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
