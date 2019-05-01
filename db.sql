CREATE TABLE `candidates` (
  `id_candidate` int(11) NOT NULL,
  `name` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

INSERT INTO `candidates` (`id_candidate`, `name`) VALUES
(1, 'Kerlliest'),
(2, 'Roodal'),
(3, 'Thimine (& Kazutsuki)'),
(4, 'Haranagon');


CREATE TABLE `candidates_votes` (
  `id_candidate_vote` int(11) NOT NULL,
  `id_vote` int(11) NOT NULL,
  `id_candidate` int(11) NOT NULL,
  `note` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

CREATE TABLE `votes` (
  `id_vote` int(11) NOT NULL,
  `nickname` varchar(100) NOT NULL,
  `creation_date` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

ALTER TABLE `candidates`
  ADD PRIMARY KEY (`id_candidate`);

ALTER TABLE `candidates_votes`
  ADD PRIMARY KEY (`id_candidate_vote`);

ALTER TABLE `votes`
  ADD PRIMARY KEY (`id_vote`);

ALTER TABLE `candidates`
  MODIFY `id_candidate` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

ALTER TABLE `candidates_votes`
  MODIFY `id_candidate_vote` int(11) NOT NULL AUTO_INCREMENT;

ALTER TABLE `votes`
  MODIFY `id_vote` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;
