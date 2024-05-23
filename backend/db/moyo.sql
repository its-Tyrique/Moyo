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
