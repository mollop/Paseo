<?php

header('Content-Type: text/html; charset=utf-8');

//array of deliminters for each site. Where sites do not have a delimiter, the name of each category is specified as an array
$site_delimiters = [
	'Carros' => [
		'Precio',
		'Tipo de vehículo',
		'Combustible',
		'Dirección',
		'Cilindraje',
		'Kilometraje',
		'Ubicación',
		'Publicado el',
		'Año',
		'Transmisión'
	],
	'Demotores' => ': ',
	'OLX' => ': ',
	'MercadoLibre' => ': ',
	'MiCarro24' => [
		'Precio',
		'Año',
		'Kilometraje',
		'Colores',
		'Puertas',
		'Combustible'
	],
	'TuCarro' => ": "
];

//array of columns in SQL table
$db_columns = [
	'ID',
	'Title',
	'Author',
	'Price',
	'Location',
	'Airbag',
	'Asientos',
	'Color',
	'Financiamiento',
	'Dueno',
	'Combustible',
	'Transmision',
	'Motor',
	'Negociable',
	'Estereo',
	'Vidrios',
	'Marca',
	'Sub-Tipo',
	'Tipo',
	'Ano',
	'Cupo',
	'Puertas',
	'Kilometros',
	'Modelo',
	'Version',
	'Placa',
	'Motor_reparado',
	'Cilindros',
	'Traccion',
	'PubDate',
	'Usado'
];

//columns that should be reduced to only numeric characters
$numeric_columns = [
'ID',
'Price',
'Ano',
'Kilometros'
];

//array of exceptions for description fields to make sure they match columns in MySQL DB
$exceptions = [
	'Doy financiamiento' => 'Financiamiento',
	'Único dueño' => 'Dueno',
	'Transmisión' => 'Transmision',
	'Estéreo' => 'Estereo',
	'Año' => 'Ano',
	'Cant. de puertas' => 'Puertas',
	'Kilómetros' => 'Kilometros',
	'Kilometraje' => 'Kilometros',
	'Tracción' => 'Traccion',
	'Versión' => 'Version',
	'Nro. de Cilindros' => 'Cilindros',
	'Cilindraje' => 'Cilindros',
	'Publicado el' => 'PubDate',
	'Ubicación' => 'Location',
	'Tipo de vehículo' => 'Usado',
	'Dirección' => 'Direccion',
	'Colores' => 'Color',
	'Precio' => 'Price',
	'Fecha de publicación' => 'PubDate'
];

$sites = [
'Carros' => 0,
'Demotores' => 0,
'OLX' => 0,
'MercadoLibre' => 1,
'MiCarro24' => 1,
'TuCarro' => 0
];

$link_pages = [
	'Carros' => [
		'http://www.carros.com.co/categoria/taxis'
	],
	'Demotores' => [
		"http://carros.demotores.com.co/autos-usados/vtZ1QQssvZ19121QQnZ0"
	],
	'OLX' => [
		"http://bogota.olx.com.co/nf/autos-cat-378/taxi",
		"http://bogota.olx.com.co/nf/autos-cat-378-p-2/taxi",
		"http://bogota.olx.com.co/nf/autos-cat-378-p-3/taxi",
		"http://bogota.olx.com.co/nf/autos-cat-378-p-4/taxi",
		"http://bogota.olx.com.co/nf/autos-cat-378-p-5/taxi",
		"http://bogota.olx.com.co/nf/autos-cat-378-p-6/taxi",
		"http://bogota.olx.com.co/nf/autos-cat-378-p-7/taxi"
	],
	'MercadoLibre' => [
		"http://vehiculos.mercadolibre.com.co/otros/taxis/",
	 	"http://vehiculos.mercadolibre.com.co/otros/taxis/_Desde_49",
	 	"http://vehiculos.mercadolibre.com.co/otros/taxis/_Desde_97",
	 	"http://vehiculos.mercadolibre.com.co/otros/taxis/_Desde_145",
	 	"http://vehiculos.mercadolibre.com.co/otros/taxis/_Desde_193",
		"http://vehiculos.mercadolibre.com.co/otros/taxis/_Desde_241"	
	],
	'MiCarro24' => [
		"http://www.micarro24.com/buscar/_Taxi/resultados.html?page=1&search%5Btypes%5D%5B0%5D=Taxi&search%5Bmode%5D=for_sale_only",
		"http://www.micarro24.com/buscar/_Taxi/resultados.html?page=2&search%5Btypes%5D%5B0%5D=Taxi"
	],
	'TuCarro' => [
	"http://listado.tucarro.com.co/otros-vehiculos/taxis/",
 	"http://listado.tucarro.com.co/otros-vehiculos/taxis/_Desde_49",
 	"http://listado.tucarro.com.co/otros-vehiculos/taxis/_Desde_97",
 	"http://listado.tucarro.com.co/otros-vehiculos/taxis/_Desde_145",
 	"http://listado.tucarro.com.co/otros-vehiculos/taxis/_Desde_193"
 	]
];

$link_pages_xpath = [
	'Carros' => [
		'xpath' => "//div[@class='img_anunciogal']//a",
		'prepend' => "http://www.carros.com.co"
	],
	'Demotores' => [
		'xpath' => "//div[@class='image-slider-container']//a",
		'prepend' => null
	],
	'OLX' => [
		'xpath' => "//a[@class='pics-lnk']",
		'prepend' => null
	],
	'MercadoLibre' => [
		'xpath' => "//*[@class='list-view-item-title']//a",
		'prepend' => null
	],
	'MiCarro24' => [
		'xpath' => "//li[@class='result_item hproduct ']//a",
		'prepend' => "http://www.micarro24.com/"
	],
	'TuCarro' => [
		'xpath' => "//*[@class='list-view-item-title']//a",
		'prepend' => null
	]
];

$header_xpaths = [
	'Carros' => [
		'Author' => "//*[@id='tab_box']/div[1]/div[2]/strong[3]/a",
		'Title' => "//p[@id='mercalist_item_title']",
		'Price' => "//div[@class='ad_rightprice']/strong"
	],
	'Demotores' => [
		'ID' => "//a[@class='fav icon hint--top']/@data-id",
		'Title' => "/html/head/title",
		'Price' => "//span[@itemprop='price']",
		'Location' => "//*[@itemprop='address']",
		'PubDate' => "//div[@class='visitas small']/text()[2]"

	],
	'OLX' => [
		'ID' => "//input[@id='itemId']/@value",
		'Title' => "/html/head/title",
		'Price' => "//*[@id='itm-table']/div[1]/strong",
		'Location' => "//*[@id='olx_item_title']/div[2]/p[2]/text()"
	],
	'MercadoLibre' => [
		'ID' => "//span[@class='id-item']",
		'Title' => "//h1[@itemprop='name']",
		'Price' => "//strong[@class='ch-price ch-price principal']",
		'Location' => "//dd[@class='ubic']"
	],
	'MiCarro24' => [
		'Author' => "//*[@id='inserat']/fieldset[4]/div[2]",
		'Title' => "//*[@id='breadcrumbs']/li[2]/a",
	],
	'TuCarro' => [
		'ID' => "//span[@class='id-item']",
		'Title' => "//h1[@itemprop='name']",
		'Location' => "//dd[@class='ubic']",
		'Price' => "//*[@class='ch-price ch-price principal']"
	]
];

$details_xpath = [
	'Carros' => "//ul[@class='info_items']//li",
	'Demotores' => "//div[@class='size3of4 unit']/ul//li",
	'OLX' => "//*[@id='description-text']/ul//li",
	'MercadoLibre' => "//*[@class='technical-details ']//li",
	'MiCarro24' => "//div[@class='car_data']//div",
	'TuCarro' => "//*[@id='description']/div[1]/ul[1]//li"
];