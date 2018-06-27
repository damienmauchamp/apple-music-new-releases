-- phpMyAdmin SQL Dump
-- version 4.6.6deb4
-- https://www.phpmyadmin.net/
--
-- Client :  localhost:3306
-- Généré le :  Mer 27 Juin 2018 à 23:37
-- Version du serveur :  5.7.19
-- Version de PHP :  7.0.19-1

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Base de données :  `applemusic-update`
--

-- --------------------------------------------------------

--
-- Structure de la table `albums`
--

CREATE TABLE `albums` (
  `id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL,
  `artistName` varchar(255) NOT NULL,
  `date` datetime DEFAULT NULL,
  `artwork` varchar(255) DEFAULT NULL,
  `explicit` tinyint(1) NOT NULL DEFAULT '0'
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `albums`
--

INSERT INTO `albums` (`id`, `name`, `artistName`, `date`, `artwork`, `explicit`) VALUES
('1113510576', 'Destins liés', '$-Crew', '2016-06-17 07:00:00', 'https://is2-ssl.mzstatic.com/image/thumb/Music18/v4/ab/ab/d0/ababd0f4-cfe8-6aa1-c3b8-f8975fe82220/source/100x100bb.jpg', 1),
('1362807556', 'Bad Company (feat. BlocBoy JB) - Single', 'A$AP Rocky', '2018-03-28 07:00:00', 'https://is4-ssl.mzstatic.com/image/thumb/Music118/v4/9b/2f/cb/9b2fcbda-987a-022f-1ca4-9f19ff9f4c1e/source/100x100bb.jpg', 1),
('1382707839', 'Trop N\'DA', 'MMZ', '2018-06-22 07:00:00', 'https://is5-ssl.mzstatic.com/image/thumb/Music115/v4/87/3c/75/873c7598-e9c8-0622-73e8-5ba34c99c6ba/source/100x100bb.jpg', 1),
('1388571796', 'Hive Mind', 'The Internet', '2018-07-20 07:00:00', 'https://is1-ssl.mzstatic.com/image/thumb/Music115/v4/8e/31/24/8e3124e4-6e40-d3ee-5774-ec39213f2dbe/source/100x100bb.jpg', 0),
('1391575537', 'Kintsugi', 'VSO', '2018-06-29 07:00:00', 'https://is1-ssl.mzstatic.com/image/thumb/Music125/v4/82/a5/71/82a5714f-4d7d-92fd-cfa0-8e036a4bdec0/source/100x100bb.jpg', 0),
('1393954688', 'Vidalo$$A', 'Dosseh', '2018-07-06 07:00:00', 'https://is4-ssl.mzstatic.com/image/thumb/Music125/v4/18/3d/62/183d6264-d9b4-cb57-7487-03ed90bf4ae0/source/100x100bb.jpg', 1),
('1394668184', 'Bless Up - Single', 'Ghost Loft', '2018-06-21 07:00:00', 'https://is1-ssl.mzstatic.com/image/thumb/Music125/v4/ce/de/a3/cedea32a-3ee1-202f-6197-2ffbe2a3dfbf/source/100x100bb.jpg', 0),
('1395199109', 'Renard', 'Guizmo', '2018-07-13 07:00:00', 'https://is1-ssl.mzstatic.com/image/thumb/Music115/v4/ec/d7/cc/ecd7cc2e-8ac1-3bd6-d65c-b07aecc1f0c0/source/100x100bb.jpg', 1),
('1395621606', 'Cali Life (feat. Snoop Dogg) - Single', 'Czar', '2018-06-29 07:00:00', 'https://is4-ssl.mzstatic.com/image/thumb/Music125/v4/60/90/a5/6090a521-7292-b1be-f5cd-4528b57c0dc8/source/100x100bb.jpg', 0),
('1397525299', 'Which One - Single', 'Jazz Cartier', '2018-06-22 07:00:00', 'https://is2-ssl.mzstatic.com/image/thumb/Music115/v4/f0/9f/c8/f09fc846-af2d-eeb9-8b6a-4486536385d0/source/100x100bb.jpg', 0),
('1397873832', 'Forever Always (feat. Rex Orange County, Chance the Rapper, Daniel Caesar, Madison Ryann Ward & YEBBA) - Single', 'Peter CottonTale', '2018-06-22 07:00:00', 'https://is2-ssl.mzstatic.com/image/thumb/Music115/v4/9c/a9/26/9ca92649-3d1a-736e-fa86-af42aa0f50ed/source/100x100bb.jpg', 0),
('1398061848', 'Still New York - Single', 'MAX & Joey Bada$$', '2018-06-26 07:00:00', 'https://is5-ssl.mzstatic.com/image/thumb/Music125/v4/79/7f/1b/797f1b19-c809-c8ba-02f0-4eb17ce2535e/source/100x100bb.jpg', 0),
('1398340781', 'Elle n\'en a pas l\'air (feat. Nov) - Single', 'Maska', '2018-06-22 07:00:00', 'https://is2-ssl.mzstatic.com/image/thumb/Music115/v4/d1/3b/0a/d13b0acc-2eb1-f9e3-290d-512d62761e48/source/100x100bb.jpg', 1),
('1398449449', 'Queen', 'Nicki Minaj', '2018-08-10 07:00:00', 'https://is4-ssl.mzstatic.com/image/thumb/Music125/v4/5a/80/a6/5a80a661-3803-e95c-218d-8dc75fff4d14/source/100x100bb.jpg', 1),
('1399189434', 'Pyro (feat. Lil Uzi Vert & Goldsmith) - Single', 'Turntup Mir', '2018-06-29 07:00:00', 'https://is1-ssl.mzstatic.com/image/thumb/Music115/v4/8b/14/45/8b144572-15df-b8d6-4c22-5d00621ded8e/source/100x100bb.jpg', 0),
('1399376722', 'Dirty Mind (feat. Ty Dolla $ign) [Disco Fries Remix] - Single', 'Stanaj', '2018-06-22 07:00:00', 'https://is1-ssl.mzstatic.com/image/thumb/Music125/v4/aa/ef/de/aaefde2e-c4f5-60f5-77ad-d555557eda60/source/100x100bb.jpg', 0),
('1399716651', 'Hopeless Romantic (feat. Swae Lee) - Single', 'Wiz Khalifa', '2018-06-21 07:00:00', 'https://is5-ssl.mzstatic.com/image/thumb/Music115/v4/e1/0b/b0/e10bb07f-f3dc-a728-0178-9ca6d03462cf/source/100x100bb.jpg', 1),
('1399778583', 'Where Dat Booty (feat. Juicy J & Trap Beckham) - Single', 'Project Pat', '2018-06-21 07:00:00', 'https://is3-ssl.mzstatic.com/image/thumb/Music125/v4/28/bc/d6/28bcd632-83f8-add3-cbc0-08f9b673cf1e/source/100x100bb.jpg', 0),
('1399778912', 'Where Dat Booty (feat. Juicy J & Trap Beckham) - Single', 'Project Pat', '2018-06-21 07:00:00', 'https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/c4/71/72/c4717287-e094-970d-89e7-21a909e879a8/source/100x100bb.jpg', 1),
('1399803507', 'Scene (feat. Travis Scott) - Single', 'KLOUD9NINE', '2018-06-22 07:00:00', 'https://is1-ssl.mzstatic.com/image/thumb/Music115/v4/77/0d/96/770d962a-bbb4-ae0c-0344-243f07851187/source/100x100bb.jpg', 0),
('1400179408', 'G.O.K.O.U - Single', 'Kaaris', '2018-06-22 07:00:00', 'https://is2-ssl.mzstatic.com/image/thumb/Music125/v4/b2/d2/e0/b2d2e0f3-d754-d845-2e7a-9201ed783120/source/100x100bb.jpg', 0),
('1400267869', 'When I Grow Up - Single', 'Dimitri Vegas & Like Mike & Wiz Khalifa', '2018-06-22 07:00:00', 'https://is2-ssl.mzstatic.com/image/thumb/Music125/v4/05/ad/f8/05adf8b2-5703-5898-9e7f-e75aa4bf7344/source/100x100bb.jpg', 0),
('1400302728', 'Till the World Falls (feat. Cosha & Vic Mensa) - Single', 'Nile Rodgers, Chic & Mura Masa', '2018-06-21 07:00:00', 'https://is4-ssl.mzstatic.com/image/thumb/Music115/v4/9e/fd/c3/9efdc362-5f5a-69ef-ed6a-77a5d4e05ab9/source/100x100bb.jpg', 0),
('1400509858', 'The O.n.E. (feat. Gucci Mane) - Single', 'Mike Rebel', '2018-06-22 07:00:00', 'https://is1-ssl.mzstatic.com/image/thumb/Music125/v4/8b/a3/97/8ba397d0-0f1e-af3c-643a-47a38c6a6eba/source/100x100bb.jpg', 0),
('1400525431', 'Freddie', 'Freddie Gibbs', '2018-06-22 07:00:00', 'https://is4-ssl.mzstatic.com/image/thumb/Music125/v4/3d/b7/85/3db7859e-fc74-6b24-6154-f22794ec8fa4/source/100x100bb.jpg', 0),
('1400566767', 'Drop (feat. Blac Youngsta & BlocBoy JB) - Single', 'G-Eazy', '2018-06-24 07:00:00', 'https://is3-ssl.mzstatic.com/image/thumb/Music115/v4/21/21/d6/2121d66e-15cd-61d1-3589-caaccd3b0ef3/source/100x100bb.jpg', 0),
('1401003754', 'I Like It (Dillon Francis Remix) - Single', 'Cardi B, Bad Bunny & J Balvin', '2018-06-22 07:00:00', 'https://is2-ssl.mzstatic.com/image/thumb/Music115/v4/a8/e1/b7/a8e1b772-73c4-8297-81fe-bea4e637f6cf/source/100x100bb.jpg', 0),
('1401018957', 'Stay Woke (feat. Miguel) - Single', 'Meek Mill', '2018-06-25 07:00:00', 'https://is1-ssl.mzstatic.com/image/thumb/Music125/v4/80/e5/29/80e52959-04ad-092a-cd03-9ba334ee6cc7/source/100x100bb.jpg', 0),
('1401047263', 'Green Gucci Suit (feat. Future) - Single', 'Rick Ross', '2018-06-22 07:00:00', 'https://is3-ssl.mzstatic.com/image/thumb/Music125/v4/54/31/0f/54310f1f-2331-ab40-b6b1-faf192e4e086/source/100x100bb.jpg', 0),
('1402664405', 'À l\'ammoniaque - Single', 'PNL', '2018-06-23 07:00:00', 'https://is2-ssl.mzstatic.com/image/thumb/Music128/v4/87/c3/16/87c316ea-c563-ce71-baf1-a4b00755ead7/source/100x100bb.jpg', 0),
('1402965603', 'K.T.S.E.', 'Teyana Taylor', '2018-06-23 07:00:00', 'https://is2-ssl.mzstatic.com/image/thumb/Music128/v4/b2/2c/fb/b22cfbf6-71c8-ee22-0278-8b025b9877a1/source/100x100bb.jpg', 0),
('1403468752', 'Hors de prix - Single', 'Rohff', '2018-06-26 07:00:00', 'https://is5-ssl.mzstatic.com/image/thumb/Music128/v4/aa/35/42/aa35429d-171b-118b-5e11-337d72c33691/source/100x100bb.jpg', 0),
('1404051384', 'World Premiere (feat. Teyana Taylor) - Single', 'Rayne Storm', '2018-06-22 07:00:00', 'https://is2-ssl.mzstatic.com/image/thumb/Music115/v4/24/18/cb/2418cbe5-31ae-a4fe-828a-450713d16314/source/100x100bb.jpg', 0);

-- --------------------------------------------------------

--
-- Structure de la table `artists`
--

CREATE TABLE `artists` (
  `id` varchar(20) NOT NULL,
  `name` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `artists`
--

INSERT INTO `artists` (`id`, `name`) VALUES
('1009009509', 'Columbine'),
('1056091570', 'Phénomène Bizness'),
('1065981054', 'Billie Eilish'),
('1068985672', 'Desiigner'),
('1080733657', 'Lil Yachty'),
('1083833709', 'Roméo Elvis'),
('1088331223', 'Jorja Smith'),
('1095387555', 'DTF'),
('1096394610', 'TWENTY88'),
('1097177293', '070 Shake'),
('111051', 'Eminem'),
('1119162071', 'MMZ'),
('1128462780', 'Hamza'),
('1164752822', 'Doums'),
('1177518801', 'Sopico'),
('1215111074', 'LORENZO'),
('121829093', 'Mac Tyer'),
('1247692001', 'Angèle'),
('1252679128', 'Laylow'),
('1273783982', 'Quality Control'),
('1278418738', 'Preme'),
('128050210', 'Future'),
('1328533325', 'Moha La Squale'),
('1348763569', 'Benash'),
('1348899249', 'Offset'),
('1352449404', 'JAY Z'),
('1374854358', 'VSO'),
('1396520066', 'KIDS SEE GHOSTS'),
('1419227', 'Beyoncé'),
('14549540', 'IAM'),
('14677454', 'M.I.A.'),
('14934728', 'Pharrell Williams'),
('14953176', 'Rohff'),
('14967', 'T.I.'),
('157749142', 'DJ Khaled'),
('158039925', 'The Internet'),
('159204910', 'Big K.R.I.T.'),
('16586443', 'John Legend'),
('179495463', 'Fauve'),
('18214606', 'Kery James'),
('18280982', 'La Fouine'),
('198431322', 'Jhené Aiko'),
('200331954', 'Guizmo'),
('201714418', 'Wiz Khalifa'),
('203519176', 'Seth Gueko'),
('204678674', 'Jay Rock'),
('212897127', 'Isha'),
('216698214', 'Teyana Taylor'),
('217529867', 'Kalash'),
('21769', 'Snoop Dogg'),
('219204820', 'Médine'),
('219204825', 'Dosseh'),
('251063799', 'Joke'),
('251337995', 'R.E.D.K'),
('252299862', 'Bas'),
('252981331', 'SCH'),
('259169448', 'Taipan'),
('259700865', 'B.o.B'),
('261566293', 'Chich'),
('261727947', 'The Game'),
('26252771', 'Damian \"Jr. Gong\" Marley'),
('262781833', 'Disiz'),
('26485617', 'Booba'),
('26485670', 'Nessbeal'),
('27039838', 'Lino'),
('271256', 'Drake'),
('2715720', 'Kanye West'),
('273058501', 'Kid Cudi'),
('275649746', 'G-Eazy'),
('276371958', 'Euro'),
('278464538', 'Nicki Minaj'),
('280747686', 'Action Bronson'),
('282589976', 'Révolution Urbaine'),
('283347662', 'Macklemore'),
('283928791', 'Gesaffelstein'),
('283949782', 'Maska'),
('2851441', 'Lupe Fiasco'),
('289550', 'Outkast'),
('293423557', 'Orelsan'),
('302166615', 'Freddie Gibbs'),
('302533564', 'Big Sean'),
('304553433', 'Makala'),
('30865945', 'Logic'),
('313618926', 'Columbine'),
('313865761', 'Meek Mill'),
('321672107', 'Benash'),
('322944670', 'P. Reign'),
('326428199', '1995'),
('331066376', 'Dinos'),
('332659150', 'Kaaris'),
('333097675', 'ScHoolboy Q'),
('334089123', 'THE CARTERS'),
('337164366', 'Lefa'),
('35307', 'Nas'),
('35315', 'Dr. Dre'),
('353345047', 'PRhyme'),
('360391', 'The Black Eyed Peas'),
('361434320', 'Lacrim'),
('3643376', 'Georgio'),
('364382773', 'Jazz Cartier'),
('368183298', 'Kendrick Lamar'),
('370571621', 'PNL'),
('376517823', 'Sampha'),
('390837736', 'Brav'),
('392386318', 'Ab-Soul'),
('395410204', 'Mister V'),
('4022281', 'Rick Ross'),
('412716418', 'R.E.D.K.'),
('41864733', 'Sinik'),
('419944559', 'MAC MILLER'),
('420368335', 'Tyler, The Creator'),
('424044507', 'Yelawolf'),
('432942256', 'Charli XCX'),
('433387781', 'Domo Genesis'),
('435300447', '2 Chainz'),
('442122051', 'Frank Ocean'),
('442401450', '3010'),
('445693504', 'Earl Sweatshirt'),
('458552200', 'S.Pri Noir'),
('458552235', 'Alpha Wann'),
('458552238', 'Sneazzy'),
('458589218', 'Nekfeu'),
('458659054', 'Maître Gims'),
('464296584', 'Lana Del Rey'),
('465954501', 'Machine Gun Kelly'),
('466612052', 'Eff Gee'),
('466612065', 'Deen Burbigo'),
('466612068', '2zer'),
('466842536', 'Childish Gambino'),
('474256913', 'Nemir'),
('475816358', 'French Montana'),
('481488005', 'A$AP Rocky'),
('485530148', 'iLoveMakonnen'),
('504848557', 'Barack Adama'),
('511333957', 'Mike WiLL Made-It'),
('527754630', 'Jazzy Bazz'),
('532598188', 'A$AP Twelvyy'),
('532788825', 'Alonzo'),
('5468295', 'Daft Punk'),
('548243536', 'Murkage'),
('549236696', 'Travis Scott'),
('556508733', 'Vald'),
('560677601', 'A$AP Mob'),
('563355119', 'Chance the Rapper'),
('567455167', 'Cashmere Cat'),
('569925101', 'Migos'),
('577261450', 'Joey Bada$$'),
('578667160', 'Hyacinthe'),
('582346993', 'A$AP Ferg'),
('5869117', 'Lil Wayne'),
('598667873', 'Vic Mensa'),
('601425706', 'Ghost Loft'),
('602767352', 'Lorde'),
('602917745', 'Ty Dolla $ign'),
('605391263', 'Isaiah Rashad'),
('605800394', 'SZA'),
('609908892', 'Dehmo'),
('609908895', 'Hache-P'),
('609909194', 'Lomepal'),
('62374520', 'Gucci Mane'),
('626333469', 'Siboy'),
('626510', 'N.E.R.D'),
('628476824', 'Damso'),
('63346553', 'Rihanna'),
('6392055', 'Juicy J'),
('64490', 'Common'),
('663153634', 'S-Crew'),
('666648192', 'PARTYNEXTDOOR'),
('667405945', 'Casseurs Flowters'),
('670534462', 'Metro Boomin'),
('673556643', 'Quavo'),
('682277', 'Pusha T'),
('73705833', 'J. Cole'),
('74687347', 'Nepal'),
('79821216', 'Psy 4 de la Rime'),
('813780216', 'A$AP ANT'),
('81886939', 'Young Thug'),
('82842423', 'Khalid'),
('829356035', 'Rae Sremmurd'),
('855484536', 'Anderson .Paak'),
('868717338', 'Gradur'),
('880680277', 'Twinsmatic'),
('884211644', 'SiR'),
('89876765', 'Youssoupha'),
('940710524', 'Lil Uzi Vert'),
('956078923', 'Cardi B'),
('966309175', 'Post Malone'),
('995119630', '$-Crew');

-- --------------------------------------------------------

--
-- Structure de la table `artists_albums`
--

CREATE TABLE `artists_albums` (
  `id` int(11) NOT NULL,
  `idArtist` varchar(20) NOT NULL,
  `idAlbum` varchar(20) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `artists_albums`
--

INSERT INTO `artists_albums` (`id`, `idArtist`, `idAlbum`) VALUES
(192, '1119162071', '1382707839'),
(166, '128050210', '1401047263'),
(216, '1374854358', '1391575537'),
(200, '14953176', '1403468752'),
(208, '158039925', '1388571796'),
(174, '200331954', '1395199109'),
(219, '201714418', '1399716651'),
(218, '201714418', '1400267869'),
(205, '216698214', '1402965603'),
(204, '216698214', '1404051384'),
(202, '21769', '1395621606'),
(162, '219204825', '1393954688'),
(168, '275649746', '1400566767'),
(194, '278464538', '1398449449'),
(188, '283949782', '1398340781'),
(164, '302166615', '1400525431'),
(190, '313865761', '1401018957'),
(184, '332659150', '1400179408'),
(176, '364382773', '1397525299'),
(196, '370571621', '1402664405'),
(198, '4022281', '1401047263'),
(1, '481488005', '1362807556'),
(210, '549236696', '1399803507'),
(160, '563355119', '1397873832'),
(178, '577261450', '1398061848'),
(214, '598667873', '1400302728'),
(170, '601425706', '1394668184'),
(212, '602917745', '1399376722'),
(172, '62374520', '1400509858'),
(181, '6392055', '1399778583'),
(180, '6392055', '1399778912'),
(186, '940710524', '1399189434'),
(158, '956078923', '1401003754'),
(148, '995119630', '1113510576');

-- --------------------------------------------------------

--
-- Structure de la table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users`
--

INSERT INTO `users` (`id`, `username`, `password`) VALUES
(1, 'damien', '92iveyron'),
(2, 'toto', 'toto');

-- --------------------------------------------------------

--
-- Structure de la table `users_artists`
--

CREATE TABLE `users_artists` (
  `id` int(11) NOT NULL,
  `idUser` int(11) NOT NULL,
  `idArtist` varchar(20) NOT NULL,
  `lastUpdate` datetime DEFAULT NULL,
  `active` tinyint(1) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Contenu de la table `users_artists`
--

INSERT INTO `users_artists` (`id`, `idUser`, `idArtist`, `lastUpdate`, `active`) VALUES
(1, 2, '481488005', '2018-06-21 00:00:00', 1),
(2, 1, '271256', '2018-06-21 00:00:00', 1),
(3, 1, '813780216', '2018-06-21 00:00:00', 1),
(4, 1, '560677601', '2018-06-21 00:00:00', 1),
(5, 1, '458552238', '2018-06-21 00:00:00', 1),
(6, 1, '582346993', '2018-06-21 00:00:00', 1),
(7, 1, '532598188', '2018-06-21 00:00:00', 1),
(8, 1, '392386318', '2018-06-21 00:00:00', 1),
(9, 1, '280747686', '2018-06-21 00:00:00', 1),
(10, 1, '532788825', '2018-06-21 00:00:00', 1),
(11, 1, '458552235', '2018-06-21 00:00:00', 1),
(12, 1, '855484536', '2018-06-21 00:00:00', 1),
(13, 1, '259700865', '2018-06-21 00:00:00', 1),
(14, 1, '504848557', '2018-06-21 00:00:00', 1),
(15, 1, '1419227', '2018-06-21 00:00:00', 1),
(16, 1, '302533564', '2018-06-21 00:00:00', 1),
(17, 1, '159204910', '2018-06-21 00:00:00', 1),
(18, 1, '360391', '2018-06-21 00:00:00', 1),
(19, 1, '26485617', '2018-06-21 00:00:00', 1),
(20, 1, '390837736', '2018-06-21 00:00:00', 1),
(21, 1, '956078923', '2018-06-27 20:46:25', 1),
(22, 1, '567455167', '2018-06-21 00:00:00', 1),
(23, 1, '667405945', '2018-06-21 00:00:00', 1),
(24, 1, '563355119', '2020-12-30 00:00:00', 1),
(25, 1, '432942256', '2018-06-21 00:00:00', 1),
(26, 1, '261566293', '2018-06-21 00:00:00', 1),
(27, 1, '466842536', '2018-06-21 00:00:00', 1),
(28, 1, '313618926', '2018-06-21 00:00:00', 1),
(29, 1, '1009009509', '2018-06-21 00:00:00', 1),
(30, 1, '5468295', '2018-06-21 00:00:00', 1),
(31, 1, '26252771', '2018-06-21 00:00:00', 1),
(32, 1, '628476824', '2018-06-21 00:00:00', 1),
(33, 1, '466612065', '2018-06-21 00:00:00', 1),
(34, 1, '609908892', '2018-06-21 00:00:00', 1),
(35, 1, '1068985672', '2018-06-21 00:00:00', 1),
(36, 1, '304553433', '2018-06-21 00:00:00', 1),
(37, 1, '262781833', '2018-06-21 00:00:00', 1),
(38, 1, '157749142', '2018-06-21 00:00:00', 1),
(39, 1, '433387781', '2018-06-21 00:00:00', 1),
(40, 1, '219204825', '2018-06-21 00:00:00', 1),
(41, 1, '1164752822', '2018-06-21 00:00:00', 1),
(42, 1, '35315', '2018-06-21 00:00:00', 1),
(43, 1, '1095387555', '2018-06-21 00:00:00', 1),
(44, 1, '445693504', '2018-06-21 00:00:00', 1),
(45, 1, '466612052', '2018-06-21 00:00:00', 1),
(46, 1, '111051', '2018-06-21 00:00:00', 1),
(47, 1, '524902253', '2018-06-21 00:00:00', 1),
(48, 1, '276371958', '2018-06-21 00:00:00', 1),
(49, 1, '179495463', '2018-06-21 00:00:00', 1),
(50, 1, '18280982', '2018-06-21 00:00:00', 1),
(51, 1, '442122051', '2018-06-21 00:00:00', 1),
(52, 1, '475816358', '2018-06-21 00:00:00', 1),
(53, 1, '128050210', '2020-12-30 00:00:00', 1),
(54, 1, '3643376', '2018-06-21 00:00:00', 1),
(55, 1, '283928791', '2018-06-21 00:00:00', 1),
(56, 1, '601425706', '2020-12-30 00:00:00', 1),
(57, 1, '868717338', '2018-06-21 00:00:00', 1),
(58, 1, '62374520', '2020-12-30 00:00:00', 1),
(59, 1, '200331954', '2018-06-21 00:00:00', 1),
(60, 1, '609908895', '2018-06-21 00:00:00', 1),
(61, 1, '1128462780', '2018-06-21 00:00:00', 1),
(62, 1, '574431300', '2018-06-21 00:00:00', 1),
(63, 1, '578667160', '2018-06-21 00:00:00', 1),
(64, 1, '14549540', '2018-06-21 00:00:00', 1),
(65, 1, '485530148', '2018-06-21 00:00:00', 1),
(66, 1, '158039925', '2018-06-21 00:00:00', 1),
(67, 1, '605391263', '2018-06-21 00:00:00', 1),
(68, 1, '212897127', '2018-06-21 00:00:00', 1),
(69, 1, '73705833', '2018-06-21 00:00:00', 1),
(70, 1, '334089123', '2018-06-21 00:00:00', 1),
(71, 1, '364382773', '2020-12-30 00:00:00', 1),
(72, 1, '527754630', '2018-06-21 00:00:00', 1),
(73, 1, '198431322', '2018-06-21 00:00:00', 1),
(74, 1, '577261450', '2020-12-30 00:00:00', 1),
(75, 1, '16586443', '2018-06-21 00:00:00', 1),
(76, 1, '527379198', '2018-06-21 00:00:00', 1),
(77, 1, '251063799', '2018-06-21 00:00:00', 1),
(78, 1, '1088331223', '2018-06-21 00:00:00', 1),
(79, 1, '332659150', '2020-12-30 00:00:00', 1),
(80, 1, '217529867', '2018-06-21 00:00:00', 1),
(81, 1, '2715720', '2018-06-21 00:00:00', 1),
(82, 1, '1396520066', '2018-06-21 00:00:00', 1),
(83, 1, '18214606', '2018-06-21 00:00:00', 1),
(84, 1, '82842423', '2018-06-21 00:00:00', 1),
(85, 1, '273058501', '2018-06-21 00:00:00', 1),
(86, 1, '361434320', '2018-06-21 00:00:00', 1),
(87, 1, '464296584', '2018-06-21 00:00:00', 1),
(88, 1, '1252679128', '2018-06-21 00:00:00', 1),
(89, 1, '337164366', '2018-06-21 00:00:00', 1),
(90, 1, '940710524', '2020-12-30 00:00:00', 1),
(91, 1, '5869117', '2018-06-21 00:00:00', 1),
(92, 1, '1080733657', '2018-06-21 00:00:00', 1),
(93, 1, '27039838', '2018-06-21 00:00:00', 1),
(94, 1, '30865945', '2018-06-21 00:00:00', 1),
(95, 1, '609909194', '2018-06-21 00:00:00', 1),
(96, 1, '602767352', '2018-06-21 00:00:00', 1),
(97, 1, '1215111074', '2018-06-21 00:00:00', 1),
(98, 1, '2851441', '2018-06-21 00:00:00', 1),
(99, 1, '14677454', '2018-06-21 00:00:00', 1),
(100, 1, '419944559', '2018-06-21 00:00:00', 1),
(101, 1, '121829093', '2018-06-21 00:00:00', 1),
(102, 1, '283347662', '2018-06-21 00:00:00', 1),
(103, 1, '458659054', '2018-06-21 00:00:00', 1),
(104, 1, '283949782', '2020-12-30 00:00:00', 1),
(105, 1, '219204820', '2018-06-21 00:00:00', 1),
(106, 1, '313865761', '2020-12-30 00:00:00', 1),
(107, 1, '670534462', '2018-06-21 00:00:00', 1),
(108, 1, '569925101', '2018-06-21 00:00:00', 1),
(109, 1, '673556643', '2018-06-21 00:00:00', 1),
(110, 1, '1348899249', '2018-06-21 00:00:00', 1),
(111, 1, '395410204', '2018-06-21 00:00:00', 1),
(112, 1, '1119162071', '2020-12-30 00:00:00', 1),
(113, 1, '1328533325', '2018-06-21 00:00:00', 1),
(114, 1, '548243536', '2018-06-21 00:00:00', 1),
(115, 1, '626510', '2018-06-21 00:00:00', 1),
(116, 1, '35307', '2018-06-21 00:00:00', 1),
(117, 1, '458589218', '2018-06-21 00:00:00', 1),
(118, 1, '474256913', '2018-06-21 00:00:00', 1),
(119, 1, '74687347', '2018-06-21 00:00:00', 1),
(120, 1, '26485670', '2018-06-21 00:00:00', 1),
(121, 1, '293423557', '2018-06-21 00:00:00', 1),
(122, 1, '289550', '2018-06-21 00:00:00', 1),
(123, 1, '322944670', '2018-06-21 00:00:00', 1),
(124, 1, '666648192', '2018-06-21 00:00:00', 1),
(125, 1, '14934728', '2018-06-21 00:00:00', 1),
(126, 1, '1056091570', '2018-06-21 00:00:00', 1),
(127, 1, '370571621', '2020-12-30 00:00:00', 1),
(128, 1, '966309175', '2018-06-21 00:00:00', 1),
(129, 1, '1278418738', '2018-06-21 00:00:00', 1),
(130, 1, '353345047', '2018-06-21 00:00:00', 1),
(131, 1, '79821216', '2018-06-21 00:00:00', 1),
(132, 1, '412716418', '2018-06-21 00:00:00', 1),
(133, 1, '251337995', '2018-06-21 00:00:00', 1),
(134, 1, '829356035', '2018-06-21 00:00:00', 1),
(135, 1, '282589976', '2018-06-21 00:00:00', 1),
(136, 1, '4022281', '2018-06-27 00:00:00', 1),
(137, 1, '63346553', '2018-06-21 00:00:00', 1),
(138, 1, '14953176', '2020-12-30 00:00:00', 1),
(139, 1, '1083833709', '2018-06-21 00:00:00', 1),
(140, 1, '77878647', '2018-06-21 00:00:00', 1),
(141, 1, '663153634', '2018-06-21 00:00:00', 1),
(142, 1, '995119630', '2020-12-30 00:00:00', 1),
(143, 1, '458552200', '2018-06-21 00:00:00', 1),
(144, 1, '252981331', '2018-06-21 00:00:00', 1),
(145, 1, '376517823', '2018-06-21 00:00:00', 1),
(146, 1, '333097675', '2018-06-21 00:00:00', 1),
(147, 1, '203519176', '2018-06-21 00:00:00', 1),
(148, 1, '284607793', '2018-06-21 00:00:00', 1),
(149, 1, '626333469', '2018-06-21 00:00:00', 1),
(150, 1, '41864733', '2018-06-21 00:00:00', 1),
(151, 1, '884211644', '2018-06-21 00:00:00', 1),
(152, 1, '1177518801', '2018-06-21 00:00:00', 1),
(153, 1, '259169448', '2018-06-21 00:00:00', 1),
(154, 1, '216698214', '2020-12-30 00:00:00', 1),
(155, 1, '549236696', '2020-12-30 00:00:00', 1),
(156, 1, '1096394610', '2018-06-21 00:00:00', 1),
(157, 1, '880680277', '2018-06-21 00:00:00', 1),
(158, 1, '420368335', '2018-06-21 00:00:00', 1),
(159, 1, '556508733', '2018-06-21 00:00:00', 1),
(160, 1, '598667873', '2020-12-30 00:00:00', 1),
(161, 1, '1374854358', '2018-06-21 00:00:00', 1),
(162, 1, '424044507', '2018-06-21 00:00:00', 1),
(163, 1, '89876765', '2018-06-21 00:00:00', 1),
(164, 1, '466612068', '2018-06-21 00:00:00', 1),
(165, 1, '326428199', '2018-06-21 00:00:00', 1),
(166, 1, '442401450', '2018-06-21 00:00:00', 1),
(167, 1, '1247692001', '2018-06-21 00:00:00', 1),
(168, 1, '252299862', '2018-06-21 00:00:00', 1),
(169, 1, '1065981054', '2018-06-21 00:00:00', 1),
(170, 1, '64490', '2018-06-21 00:00:00', 1),
(171, 1, '331066376', '2018-06-21 00:00:00', 1),
(172, 1, '275649746', '2020-12-30 00:00:00', 1),
(173, 1, '261727947', '2018-06-21 00:00:00', 1),
(174, 1, '204678674', '2018-06-21 00:00:00', 1),
(175, 1, '1352449404', '2018-06-21 00:00:00', 1),
(176, 1, '6392055', '2020-12-30 00:00:00', 1),
(177, 1, '368183298', '2018-06-21 00:00:00', 1),
(178, 1, '465954501', '2018-06-21 00:00:00', 1),
(179, 1, '511333957', '2018-06-21 00:00:00', 1),
(180, 1, '278464538', '2018-06-21 00:00:00', 1),
(181, 1, '682277', '2018-06-21 00:00:00', 1),
(182, 1, '21769', '2020-12-30 00:00:00', 1),
(183, 1, '605800394', '2018-06-21 00:00:00', 1),
(184, 1, '14967', '2018-06-21 00:00:00', 1),
(185, 1, '602917745', '2020-12-30 00:00:00', 1),
(186, 1, '201714418', '2020-12-30 00:00:00', 1),
(187, 1, '81886939', '2018-06-21 00:00:00', 1),
(188, 1, '435300447', '2018-06-21 00:00:00', 1),
(189, 1, '1097177293', '2018-06-21 00:00:00', 1),
(190, 1, '321672107', '2018-06-21 00:00:00', 1),
(191, 1, '1348763569', '2018-06-21 00:00:00', 1),
(192, 1, '302166615', '2020-12-30 00:00:00', 1),
(193, 1, '1273783982', '2018-06-21 00:00:00', 1),
(194, 1, '829356035', '2018-06-21 00:00:00', 1),
(196, 2, '995119630', '2016-06-16 00:00:00', 1);

--
-- Index pour les tables exportées
--

--
-- Index pour la table `albums`
--
ALTER TABLE `albums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `albums_id_uindex` (`id`);

--
-- Index pour la table `artists`
--
ALTER TABLE `artists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `artists_id_uindex` (`id`);

--
-- Index pour la table `artists_albums`
--
ALTER TABLE `artists_albums`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `artists_albums_id_uindex` (`id`),
  ADD UNIQUE KEY `artists_albums_idArtist_idAlbum_uindex` (`idArtist`,`idAlbum`),
  ADD KEY `artists_albums_artists_id_fk` (`idArtist`),
  ADD KEY `artists_albums_albums_id_fk` (`idAlbum`);

--
-- Index pour la table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users__id_uindex` (`id`),
  ADD UNIQUE KEY `users_username_uindex` (`username`);

--
-- Index pour la table `users_artists`
--
ALTER TABLE `users_artists`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_artists_id_uindex` (`id`);

--
-- AUTO_INCREMENT pour les tables exportées
--

--
-- AUTO_INCREMENT pour la table `artists_albums`
--
ALTER TABLE `artists_albums`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=240;
--
-- AUTO_INCREMENT pour la table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;
--
-- AUTO_INCREMENT pour la table `users_artists`
--
ALTER TABLE `users_artists`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=197;
--
-- Contraintes pour les tables exportées
--

--
-- Contraintes pour la table `artists_albums`
--
ALTER TABLE `artists_albums`
  ADD CONSTRAINT `artists_albums_albums_id_fk` FOREIGN KEY (`idAlbum`) REFERENCES `albums` (`id`),
  ADD CONSTRAINT `artists_albums_artists_id_fk` FOREIGN KEY (`idArtist`) REFERENCES `artists` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
