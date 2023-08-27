<?php

declare(strict_types=1);

/**
 * Scan project and return array of classes, that implements provided interfaces
 *
 * @return array [
 *               interface::class => [
 *               < all classes that implement the interface >
 *               ]
 *               ]
 */
$fs = new \Illuminate\Filesystem\Filesystem();

$basePath = \dirname(__DIR__).'/app';
$interfaces = [
    \App\Interfaces\SingletonInterface::class,
];

$finder = new \Symfony\Component\Finder\Finder();
$finder->in($basePath)->name('*.php')->files();
$result = [];
foreach ($finder as $file) {
    $filePath = $file->getPathname();
    $className = \str_replace(["{$basePath}/", '/', '.php'], ['App\\', '\\', ''], $filePath);
    if (\class_exists($className)) {
        $implements = \class_implements($className, false);
        if ($implements) {
            foreach (\array_intersect($interfaces, $implements) as $interface) {
                $result[$interface][] = $className;
            }
        }
    }
}

return $result;
