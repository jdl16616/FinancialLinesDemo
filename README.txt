This project was tested on windows, using mariaDb for database management, php 8+, apache2 to rune the webclient.
I had no prior experience in symfony (doctrine, twig, bootstrap), it took some getting used to since the difference in architecture and syntax compared to Zend or Laminas framework.

# Database
Import the file inside /database_dump to your local database tool.

# Config
Setup the database connectionin /config/packages/doctrine.yaml

# Webclient
Apache2 was used, by placing this whole project /CollectOnline, inside /apache2/htdocs/Projects
http://localhost/Projects/CollectOnline/public/index.php

# Plugins 
Managed by composer

# Demo
/Demo contains screenshots from the webpages and some exports
