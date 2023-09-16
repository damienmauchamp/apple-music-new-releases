```sql
CREATE TABLE `token`
(
	`id`       int(11)      NOT NULL,
	`token`    varchar(255) NOT NULL,
	`expiracy` datetime     NOT NULL,
	`notes`    varchar(100) NOT NULL
) ENGINE = InnoDB
  DEFAULT CHARSET = utf8mb4;

--
-- Index pour les tables déchargées
--

--
-- Index pour la table `token`
--
ALTER TABLE `token`
	ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT pour les tables déchargées
--

--
-- AUTO_INCREMENT pour la table `token`
--
ALTER TABLE `token`
	MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
```