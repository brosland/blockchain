<?php

$path = __DIR__ . '/../../../../vendor/autoload.php';
	
if ((!$loader = @include $path) && (!$loader = @include $path))
{
	echo "Install Nette Tester using `composer update --dev`\n";
	exit(1);
}

$loader->add('Brosland', __DIR__ . '/../src');
$loader->add('BroslandTests', __DIR__ . '/BroslandTests');

Tester\Environment::setup();
