.checkout
=========

After the database is created and schema is updated you need to run the following sample sql query:

INSERT INTO `categories` (`id`, `name`) VALUES
	(1, 'Cars'),
	(2, 'Decoration'),
	(3, 'Clothes');
	
INSERT INTO `cities` (`id`, `name`) VALUES
	(1, 'Sliven'),
	(2, 'Sofia'),
	(3, 'Nesebar');
	
INSERT INTO `roles` (`id`, `name`) VALUES
	(1, 'ROLE_ADMIN'),
	(2, 'ROLE_USER');
	
