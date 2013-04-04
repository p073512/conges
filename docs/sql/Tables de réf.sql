insert into entite (Id, libelle) VALUES
(1, 'ITSP'),
(2, 'CSM'),
(3, 'SS-T');

insert into fonction (id, libelle) VALUES
(1, 'Analyste'),
(2, 'Developpeur'),
(3, 'Expert'),
(4, 'Manager');

insert into modalite (Id, Code, Libelle) VALUES
(1, '1', 'Ancienne modalite 1'),
(2, '2', 'Ancienne modalite 2'),
(3, '3', 'Ancienne modalite 3'),
(4, 'MS', 'Modalite Standard'),
(5, 'RM', 'Realisation Mission'),
(6, 'AC', 'Autonomie Complete'),
(7, 'NO', 'Aucune modalite');

insert into pole (id, libelle) VALUES
(1, 'Pole 1'),
(2, 'Pole 2'),
(3, 'Pole 3'),
(4, 'Pole 4'),
(5, 'P2000'),
(6, 'Projets'),
(7, 'Pilotes');

insert into type_conge (Id, Code, libelle) VALUES
(1, 'CP', 'Conges payes '),
(2, 'Q1', 'RTT '),
(3, 'Q2', 'RTT facultatives '),
(4, 'P', 'Prévisions '),
(5, 'EX', 'Conges exceptionnels '),
(6, '45', '4 sur 5 '),
(7, 'R', 'Reliquat '),
(8, 'F', 'Formations '),
(9, 'AP', 'Autre projet '),
(10, 'M', 'Maladies '),
(11, 'DF', 'Formations DIF '),
(12, 'AS', 'Astreintes CSM '),
(13, 'PC', 'Contraintes personnelles');
