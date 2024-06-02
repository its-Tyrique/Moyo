-- Create User Role table
CREATE TABLE user_role (
                           Id INT AUTO_INCREMENT PRIMARY KEY,
                           RoleName VARCHAR(50) NOT NULL
);

-- Insert default user roles
INSERT INTO user_role (RoleName) VALUES ('User');
INSERT INTO user_role (RoleName) VALUES ('Admin');

-- Create Account table
CREATE TABLE account (
                         Id INT AUTO_INCREMENT PRIMARY KEY,
                         FullName VARCHAR(50) NULL,
                         FirstName VARCHAR(50) NOT NULL,
                         MiddleNames VARCHAR(150) NULL,
                         LastName VARCHAR(50) NOT NULL,
                         EmailAddress VARCHAR(150) NOT NULL UNIQUE,
                         Username VARCHAR(50) NOT NULL UNIQUE,
                         Password VARCHAR(250) NOT NULL,
                         MaidenName VARCHAR(50) NULL,
                         ProfilePicturePath VARCHAR(250) NULL,
                         MainContactNumber VARCHAR(25) NULL,
                         Title VARCHAR(25) NULL,
                         DateOfBirth DATE NULL,
                         PhysicalAddressLineOne VARCHAR(150) NULL,
                         PhysicalAddressLineTwo VARCHAR(150) NULL,
                         PhysicalAddressPostalCode VARCHAR(150) NULL,
                         PhysicalAddressCountry VARCHAR(150) NULL,
                         PostalAddressLineOne VARCHAR(150) NULL,
                         PostalAddressLineTwo VARCHAR(150) NULL,
                         PostalAddressPostalCode VARCHAR(150) NULL,
                         PostalAddressCountry VARCHAR(150) NULL,
                         IdentificationNumber VARCHAR(50) NULL,
                         Nickname VARCHAR(50) NULL,
                         Status VARCHAR(250) NULL,
                         Gender VARCHAR(25) NULL,
                         AccessBlocked TINYINT(1) DEFAULT 0,
                         BlockedReason TEXT NULL,
                         LastUpdated TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                         UserRole INT NULL,
                         SearchMetaInfo TEXT NULL,
                         FOREIGN KEY (UserRole) REFERENCES user_role(Id) ON UPDATE CASCADE ON DELETE SET NULL
);
DROP TABLE IF EXISTS account;

INSERT INTO account (
    FirstName, LastName, Title, IdentificationNumber, MainContactNumber, EmailAddress, Password, Gender,
    DateOfBirth, ProfilePicturePath, Username, UserRole
) VALUES ('John', 'Doe', 'Mr', '123456789', '1234567890', 'john@example.com', 'hashed_password', 'Male',
          '1990-01-01', '../assets/default_profile.jpg', 'john@example.com', 1);

-- Create index on UserRole column
CREATE INDEX UserRole ON account (UserRole);


-- Bus Table
CREATE TABLE bus (
                     Id INT PRIMARY KEY AUTO_INCREMENT,
                     BusNumber VARCHAR(20) NOT NULL UNIQUE,
                     Capacity INT NOT NULL
);
DROP TABLE IF EXISTS bus;

INSERT INTO bus (BusNumber, Capacity) VALUES ('Bus001', 47);
INSERT INTO bus (BusNumber, Capacity) VALUES ('Bus002', 50);
INSERT INTO bus (BusNumber, Capacity) VALUES ('Bus003', 53);
INSERT INTO bus (BusNumber, Capacity) VALUES ('Bus004', 56);
INSERT INTO bus (BusNumber, Capacity) VALUES ('Bus005', 57);

-- Trip Table
CREATE TABLE trip (
                      Id INT PRIMARY KEY AUTO_INCREMENT,
                      Route INT,
                      Bus INT,
                      DepartureLocation VARCHAR(100) NOT NULL,
                      ArrivalLocation VARCHAR(100) NOT NULL,
                      DepartureTime DATETIME NOT NULL,
                      ArrivalTime DATETIME NOT NULL,
                      Price DECIMAL(10, 2) NOT NULL,
                      FOREIGN KEY (Bus) REFERENCES bus(Id)
);
DROP TABLE IF EXISTS trip;

INSERT INTO trip (Route, Bus, DepartureLocation, ArrivalLocation, DepartureTime, ArrivalTime, Price)
VALUES (1, 1, 'Location A', 'Location B', '2022-12-01 08:00:00', '2022-12-01 10:00:00', 50.00);

INSERT INTO trip (Route, Bus, DepartureLocation, ArrivalLocation, DepartureTime, ArrivalTime, Price)
VALUES (2, 2, 'Location B', 'Location C', '2022-12-02 09:00:00', '2022-12-02 11:00:00', 60.00);

INSERT INTO trip (Route, Bus, DepartureLocation, ArrivalLocation, DepartureTime, ArrivalTime, Price)
VALUES (3, 3, 'Location C', 'Location D', '2022-12-03 10:00:00', '2022-12-03 12:00:00', 70.00);

INSERT INTO trip (Route, Bus, DepartureLocation, ArrivalLocation, DepartureTime, ArrivalTime, Price)
VALUES (4, 4, 'Location D', 'Location E', '2022-12-04 11:00:00', '2022-12-04 13:00:00', 80.00);

INSERT INTO trip (Route, Bus, DepartureLocation, ArrivalLocation, DepartureTime, ArrivalTime, Price)
VALUES (5, 5, 'Location E', 'Location F', '2022-12-05 12:00:00', '2022-12-05 14:00:00', 90.00);

CREATE TABLE bookings (
                          Id INT PRIMARY KEY AUTO_INCREMENT,
                          Account INT NOT NULL,
                          Trip INT NOT NULL,
                          BookingDate DATETIME DEFAULT CURRENT_TIMESTAMP,
                          FOREIGN KEY (Account) REFERENCES account(Id),
                          FOREIGN KEY (Trip) REFERENCES trip(Id)
);
DROP TABLE IF EXISTS bookings;