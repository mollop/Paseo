# Paseo

This project pulls data from auto sales websites. The aim of the project is to track secondary market values for taxi medallions ("cupos") 
in Colombia. Colombian taxi medallions are limited in number, attached to a specific vehicle, and may not be traded independently. Consequently, registered taxis enjoy inflated prices on the secondary market compared to vehicles without these licenses.

Assuming that 

[Vehicle Sale Price] - [Vehicle value] = [Medallion value]

Vehicle value can be determined by comparing sales prices of comparable models, as well as the value shown in FASECOLDA. 

The script taxiscraper.php and supporting files use cURL to download an array of HTTP pages determined by a set of parameters defined in taxiscraper-master-array-list.php. The script works through that array to find relevant data based on xPath parameters in the master array list. It then cleans the data to prepare it for insertion into a MySQL database table. The tucarro.sql file contains the output table and some sample data.
