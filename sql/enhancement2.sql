-- Query 1
INSERT INTO clients (clientFirstName, clientLastName, clientEmail, clientPassword, comment) VALUES ("Tony", "Stark", "tony@starkent.com", "Iam1ronM@n", "I am the real Ironman")

--Query 2
UPDATE clients SET clientLevel = "3" WHERE clientId = 2

--Query 3
UPDATE inventory
SET    invDescription = replace(invDescription, 'small interior', 'spacious interior')
WHERE  invMake = "GM" AND invModel = "Hummer";

--Query 4
SELECT invModel, classificationName
FROM inventory
INNER JOIN carclassification ON
inventory.classificationId = carclassification.classificationId
WHERE carclassification.classificationName = "SUV";

--Query 5
DELETE FROM inventory
WHERE invMake = "Jeep" AND invModel = "Wrangler";

--Query 6
UPDATE inventory SET invImage = CONCAT('/phpmotors', invImage), invThumbnail = CONCAT('/phpmotors', invThumbnail)

