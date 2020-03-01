
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

CREATE TABLE `articles` (
  `idArticle` int(11) NOT NULL,
  `name` varchar(45) DEFAULT NULL,
  `lastEdit` datetime DEFAULT NULL,
  `content` longtext,
  `author` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `comments` (
  `idcomment` int(11) NOT NULL,
  `User` varchar(45) DEFAULT NULL,
  `Text` mediumtext,
  `publications_id` int(11) NOT NULL,
  `publications_content` int(11) NOT NULL,
  `date` datetime NOT NULL DEFAULT '0000-00-00 00:00:00' COMMENT 'date of comment'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

CREATE TABLE `images` (
  `idimage` int(11) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `images` (`idimage`, `name`) VALUES
(1, 'default.png');


CREATE TABLE `publications` (
  `idPublication` int(11) NOT NULL,
  `date` date NOT NULL,
  `content` int(11) NOT NULL,
  `preview` int(11) DEFAULT '0',
  `idSection` int(11) NOT NULL DEFAULT '0',
  `title` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `publications_has_tag` (
  `publications_idpublication` int(11) NOT NULL,
  `publications_content` int(11) NOT NULL,
  `Tag_idTag` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


CREATE TABLE `sections` (
  `idSection` int(11) NOT NULL,
  `Name` varchar(45) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


INSERT INTO `sections` (`idSection`, `Name`) VALUES
(1, 'Generic');



CREATE TABLE `tag` (
  `idTag` int(11) NOT NULL,
  `Name` varchar(56) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;



CREATE TABLE `users` (
  `idUser` int(11) NOT NULL,
  `username` varchar(45) NOT NULL,
  `password` varchar(255) NOT NULL,
  `isAdmin` int(11) DEFAULT NULL,
  `email` varchar(45) NOT NULL,
  `recovery` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;


ALTER TABLE `articles`
  ADD PRIMARY KEY (`idArticle`),
  ADD KEY `fk_articles_users` (`author`);


ALTER TABLE `comments`
  ADD PRIMARY KEY (`idcomment`),
  ADD KEY `fk_comments_publications1` (`publications_id`,`publications_content`);


ALTER TABLE `images`
  ADD PRIMARY KEY (`idimage`);


ALTER TABLE `publications`
  ADD PRIMARY KEY (`idPublication`,`content`),
  ADD KEY `fk_publications_Section` (`idSection`),
  ADD KEY `fk_publications_articles1` (`content`),
  ADD KEY `fk_publications_images1` (`preview`);


ALTER TABLE `publications_has_tag`
  ADD PRIMARY KEY (`publications_idpublication`,`publications_content`,`Tag_idTag`),
  ADD KEY `fk_publications_has_Tag_Tag1` (`Tag_idTag`);

ALTER TABLE `sections`
  ADD PRIMARY KEY (`idSection`);


ALTER TABLE `tag`
  ADD PRIMARY KEY (`idTag`,`Name`);


ALTER TABLE `users`
  ADD PRIMARY KEY (`idUser`,`username`);

ALTER TABLE `articles`
  MODIFY `idArticle` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;


ALTER TABLE `comments`
  MODIFY `idcomment` int(11) NOT NULL AUTO_INCREMENT;


ALTER TABLE `images`
  MODIFY `idimage` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;


ALTER TABLE `publications`
  MODIFY `idPublication` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=25;


ALTER TABLE `sections`
  MODIFY `idSection` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;


ALTER TABLE `tag`
  MODIFY `idTag` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=76;


ALTER TABLE `users`
  MODIFY `idUser` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;


ALTER TABLE `articles`
  ADD CONSTRAINT `fk_articles_users` FOREIGN KEY (`author`) REFERENCES `users` (`idUser`) ON DELETE CASCADE ON UPDATE CASCADE;


ALTER TABLE `comments`
  ADD CONSTRAINT `fk_comments_publications1` FOREIGN KEY (`publications_id`,`publications_content`) REFERENCES `publications` (`idPublication`, `content`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `publications`
  ADD CONSTRAINT `fk_publications_Section` FOREIGN KEY (`idSection`) REFERENCES `sections` (`idSection`) ON DELETE NO ACTION ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_publications_articles1` FOREIGN KEY (`content`) REFERENCES `articles` (`idArticle`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_publications_images1` FOREIGN KEY (`preview`) REFERENCES `images` (`idimage`) ON DELETE SET NULL ON UPDATE CASCADE;


ALTER TABLE `publications_has_tag`
  ADD CONSTRAINT `fk_publications_has_Tag_Tag1` FOREIGN KEY (`Tag_idTag`) REFERENCES `tag` (`idTag`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_publications_has_Tag_publications1` FOREIGN KEY (`publications_idpublication`,`publications_content`) REFERENCES `publications` (`idPublication`, `content`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;
