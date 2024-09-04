<?php

use Symfony\Component\Finder\Finder;

return [
	'prefix' => 'PluginNameReplaceMeDependency',
	'finders' => [
		Finder::create()->files()->in('vendor'),
		Finder::create()->files()->in('src'),
	],
	'exclude-namespaces' => [
		'PluginNameReplaceMe\\'
	],
];
