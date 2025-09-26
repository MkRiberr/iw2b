CREATE TABLE usuarios (
    `ID` int(11) PRIMARY KEY AUTO_INCREMENT, 
    `Login` varchar (50) NOT NULL,
    `Nome` varchar (150) NOT NULL, 
    `Email` varchar (150) NOT NULL, 
    `Senha` varchar (80) NOT NULL,
    `Ativo` bit (1) DEFAULT b'1'
);
