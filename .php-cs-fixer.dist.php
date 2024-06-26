<?php

declare(strict_types=1);

$finder = PhpCsFixer\Finder::create()
  ->in([
    __DIR__ . '/app',
    __DIR__ . '/config',
    __DIR__ . '/database/seeders',
    __DIR__ . '/routes',
    __DIR__ . '/tests',
  ]);

$config = new PhpCsFixer\Config();

return $config
  ->setRiskyAllowed(true)
  ->setRules([
    '@PSR12' => true,
  ])
  ->setFinder($finder);
