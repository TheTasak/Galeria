-- phpMyAdmin SQL Dump
-- version 5.1.0
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Czas generowania: 17 Lut 2022, 09:49
-- Wersja serwera: 10.4.18-MariaDB
-- Wersja PHP: 8.0.3

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Baza danych: `tasarz_4ta`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `albumy`
--

CREATE TABLE `albumy` (
  `id` int(11) NOT NULL,
  `tytul` varchar(100) COLLATE utf8_polish_ci NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `id_uzytkownika` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `albumy`
--

INSERT INTO `albumy` (`id`, `tytul`, `data`, `id_uzytkownika`) VALUES
(1, 'Test_album', '2021-12-05 11:34:15', 11),
(2, 'Krajobrazy', '2021-12-05 12:25:00', 12),
(15, 'Nowy album', '2022-02-06 10:37:26', 15);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `uzytkownicy`
--

CREATE TABLE `uzytkownicy` (
  `id` int(11) NOT NULL,
  `login` varchar(16) COLLATE utf8_polish_ci NOT NULL,
  `haslo` varchar(60) COLLATE utf8_polish_ci NOT NULL,
  `email` varchar(128) COLLATE utf8_polish_ci NOT NULL,
  `zarejestrowany` date NOT NULL DEFAULT current_timestamp(),
  `uprawnienia` enum('uzytkownik','moderator','administrator') COLLATE utf8_polish_ci NOT NULL,
  `aktywny` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `uzytkownicy`
--

INSERT INTO `uzytkownicy` (`id`, `login`, `haslo`, `email`, `zarejestrowany`, `uprawnienia`, `aktywny`) VALUES
(11, 'Humingere', '$2y$10$UwSaVQFmD0iLMm32aOfQY.Ei7dsRy3AYHFr2ap17TOZgkC08dM48K', 'przemek433@interia.eu', '2021-12-04', 'moderator', 1),
(12, 'Grzegorzbaryla', '$2y$10$opHIZZLI6.HbDub/MDwbCOhxvAmbbd.1bjt1QxYP4gsXYDtDOMNY.', 'przemek433@interia.eu', '2021-12-12', 'uzytkownik', 1),
(13, 'Testkonto', '$2y$10$5RPZ1DOr3GlL4GnryY9vm.dUMTy/GqDIei7djj2REc9uKaCDVz1wu', 'przemek334@interia.pl', '2021-12-16', 'uzytkownik', 1),
(15, 'Pustaczek', '$2y$10$UwSaVQFmD0iLMm32aOfQY.Ei7dsRy3AYHFr2ap17TOZgkC08dM48K', 'przemek433@interia.eu', '2022-01-08', 'administrator', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia`
--

CREATE TABLE `zdjecia` (
  `id` int(11) NOT NULL,
  `opis` varchar(255) COLLATE utf8_polish_ci NOT NULL,
  `id_albumu` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `zaakceptowane` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zdjecia`
--

INSERT INTO `zdjecia` (`id`, `opis`, `id_albumu`, `data`, `zaakceptowane`) VALUES
(1, 'Opis zdjęcia', 1, '2021-12-16 15:16:21', 1),
(2, 'Zdjęcie 1 albumu testowego', 2, '2021-12-16 15:16:43', 1),
(3, 'Zdjęcia 2 albumu', 2, '2021-12-16 15:16:58', 0),
(21, 'Kontener', 1, '2022-01-06 13:23:10', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia_komentarze`
--

CREATE TABLE `zdjecia_komentarze` (
  `id` int(11) NOT NULL,
  `id_zdjecia` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `data` datetime NOT NULL DEFAULT current_timestamp(),
  `komentarz` text COLLATE utf8_polish_ci NOT NULL,
  `zaakceptowany` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zdjecia_komentarze`
--

INSERT INTO `zdjecia_komentarze` (`id`, `id_zdjecia`, `id_uzytkownika`, `data`, `komentarz`, `zaakceptowany`) VALUES
(1, 21, 11, '2022-01-07 18:36:08', 'Pierwszy!', 1),
(2, 21, 11, '2022-01-07 18:37:02', 'Drugi!', 1),
(3, 21, 11, '2022-01-07 18:51:40', 'Komentarzysko', 1),
(4, 2, 15, '2022-01-08 15:11:03', 'Ładny krajobraz! Też chciałbym tam kiedyś pojechać!', 1),
(7, 1, 11, '2022-01-15 18:56:39', 'Komentarz', 1);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zdjecia_oceny`
--

CREATE TABLE `zdjecia_oceny` (
  `id_zdjecia` int(11) NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `ocena` tinyint(4) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_polish_ci;

--
-- Zrzut danych tabeli `zdjecia_oceny`
--

INSERT INTO `zdjecia_oceny` (`id_zdjecia`, `id_uzytkownika`, `ocena`) VALUES
(21, 11, 4),
(21, 12, 6),
(21, 13, 6),
(21, 15, 10),
(2, 11, 8),
(1, 11, 8),
(1, 15, 10);

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `albumy`
--
ALTER TABLE `albumy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zdjecia`
--
ALTER TABLE `zdjecia`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `zdjecia_komentarze`
--
ALTER TABLE `zdjecia_komentarze`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT dla zrzuconych tabel
--

--
-- AUTO_INCREMENT dla tabeli `albumy`
--
ALTER TABLE `albumy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT dla tabeli `uzytkownicy`
--
ALTER TABLE `uzytkownicy`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT dla tabeli `zdjecia`
--
ALTER TABLE `zdjecia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=28;

--
-- AUTO_INCREMENT dla tabeli `zdjecia_komentarze`
--
ALTER TABLE `zdjecia_komentarze`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
