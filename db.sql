SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET AUTOCOMMIT = 0;
START TRANSACTION;
SET time_zone = "+00:00";

--
-- Struttura della tabella `UTENTE`
--

CREATE TABLE `UTENTE` (
    `username` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
    `password` varchar(100) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `UTENTE`
--

INSERT INTO `UTENTE` (`username`, `password`) VALUES
('user', 'user');

-- ----------------------------------------------------------

--
-- Struttura della tabella `POST`
--

CREATE TABLE `POST` (
    `title` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
    `user` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
    `category` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
    `date` timestamp NOT NULL DEFAULT current_timestamp(),
    `description` varchar(319) COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `POST`
--

INSERT INTO `POST` (`title`, `user`, `category`, `date`, `description`) VALUES
('Titolo post', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 2', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 3', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 4', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 5', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 6', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 7', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 8', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 9', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 10', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 11', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post'),
('Titolo post - 12', 'user', 'politic', '2022-07-22 12:00:00', 'Descrizione post');

-- ----------------------------------------------------------

--
-- Struttura della tabella `COMMENTO`
--

CREATE TABLE `COMMENTO` (
    `id` int(11) NOT NULL,
    `post` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
    `user` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
    `text` varchar(319) COLLATE utf8_unicode_ci NOT NULL,
    `date` timestamp NOT NULL DEFAULT current_timestamp(),
    `reply` int(11) DEFAULT `0`
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Dump dei dati per la tabella `POST`
--

INSERT INTO `COMMENTO` (`id`, `post`, `user`, `text`, `date`, `reply`) VALUES
(0, 'Titolo post', 'user', 'Hello world!', '2022-07-22 12:00:00', '0');

-- ----------------------------------------------------------

--
-- Indici per le tabelle scaricate
--

ALTER TABLE `UTENTE`
    ADD PRIMARY KEY (`username`);

ALTER TABLE `POST`
    ADD PRIMARY KEY (`title`),
    ADD KEY `user` (`user`);

ALTER TABLE `COMMENTO`
    ADD PRIMARY KEY (`id`),
    ADD KEY `post` (`post`),
    ADD KEY `user` (`user`);

--
-- AUTO_INCREMENT per le tabelle scaricate
--

ALTER TABLE `COMMENTO`
    MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Limiti per le tabelle scaricate
--

ALTER TABLE `POST`
    ADD CONSTRAINT `POST_ibfk_1` FOREIGN KEY (`user`) REFERENCES `UTENTE` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

ALTER TABLE `COMMENTO`
    ADD CONSTRAINT `COMMENTO_ibfk_1` FOREIGN KEY (`post`) REFERENCES `POST` (`title`) ON DELETE CASCADE ON UPDATE CASCADE,
    ADD CONSTRAINT `COMMENTO_ibfk_2` FOREIGN KEY (`user`) REFERENCES `UTENTE` (`username`) ON DELETE CASCADE ON UPDATE CASCADE;

COMMIT;
