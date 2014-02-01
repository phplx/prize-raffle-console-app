<?php

/*
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013-2014 phplx.net
 * (c) 2013-2014 Composer for part of the code
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Phplx;

use Symfony\Component\Finder\Finder;

/**
 * The Compiler class compiles the whole application into a phar
 *
 * @author Daniel Gomes <me@danielcsgomes.com>
 */
class Compiler
{
    /**
     * Compiles the whole application into a single phar file
     *
     * @throws \RuntimeException
     * @param  string            $pharFile The full path to the file to create
     */
    public function compile($pharFile = 'prize-raffle.phar')
    {
        if (file_exists($pharFile)) {
            unlink($pharFile);
        }

        $phar = new \Phar($pharFile, 0, 'prize-raffle.phar');
        $phar->setSignatureAlgorithm(\Phar::SHA512);

        $phar->startBuffering();

        if (!file_exists(__DIR__ . '/../../config/parameters.yaml')) {
            throw new \RuntimeException('File \'config/parameters.yaml\' is mandatory. Make sure you have it defined.');
        }

        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../config/parameters.yaml'));

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->notName('Compiler.php')
            ->in(__DIR__ . '/..');

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $finder = new Finder();
        $finder->files()
            ->ignoreVCS(true)
            ->name('*.php')
            ->exclude('Tests')
            ->in(__DIR__ . '/../../vendor/symfony')
            ->in(__DIR__ . '/../../vendor/sensio')
            ->in(__DIR__ . '/../../vendor/danielcsgomes')
            ->in(__DIR__ . '/../../vendor/kriswallsmith/');

        foreach ($finder as $file) {
            $this->addFile($phar, $file);
        }

        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/autoload.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/autoload_namespaces.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/autoload_classmap.php'));
        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/autoload_real.php'));
        if (file_exists(__DIR__ . '/../../vendor/composer/include_paths.php')) {
            $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/include_paths.php'));
        }
        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../vendor/composer/ClassLoader.php'));
        $this->addAppBin($phar);

        // Stubs
        $phar->setStub($this->getStub());

        $phar->stopBuffering();

        $this->addFile($phar, new \SplFileInfo(__DIR__ . '/../../LICENSE'), false);

        unset($phar);
    }

    /**
     * @param \Phar $phar
     * @param mixed $file
     * @param bool  $strip
     */
    private function addFile(\Phar $phar, $file, $strip = true)
    {
        $path = strtr(
            str_replace(dirname(dirname(__DIR__)) . DIRECTORY_SEPARATOR, '', $file->getRealPath()),
            '\\',
            '/'
        );

        $content = file_get_contents($file);
        if ($strip) {
            $content = $this->stripWhitespace($content);
        } elseif ('LICENSE' === basename($file)) {
            $content = "\n" . $content . "\n";
        }

        $phar->addFromString($path, $content);
    }

    /**
     * @param \Phar $phar
     */
    private function addAppBin(\Phar $phar)
    {
        $content = file_get_contents(__DIR__ . '/../../bin/phplx');
        $content = preg_replace('{^#!/usr/bin/env php\s*}', '', $content);
        $phar->addFromString('bin/phplx', $content);
    }

    /**
     * Removes whitespace from a PHP source string while preserving line numbers.
     *
     * @param  string $source A PHP string
     * @return string The PHP string with the whitespace removed
     */
    private function stripWhitespace($source)
    {
        if (!function_exists('token_get_all')) {
            return $source;
        }

        $output = '';
        foreach (token_get_all($source) as $token) {
            if (is_string($token)) {
                $output .= $token;
            } elseif (in_array($token[0], array(T_COMMENT, T_DOC_COMMENT))) {
                $output .= str_repeat("\n", substr_count($token[1], "\n"));
            } elseif (T_WHITESPACE === $token[0]) {
                // reduce wide spaces
                $whitespace = preg_replace('{[ \t]+}', ' ', $token[1]);
                // normalize newlines to \n
                $whitespace = preg_replace('{(?:\r\n|\r|\n)}', "\n", $whitespace);
                // trim leading spaces
                $whitespace = preg_replace('{\n +}', "\n", $whitespace);
                $output .= $whitespace;
            } else {
                $output .= $token[1];
            }
        }

        return $output;
    }

    private function getStub()
    {
        $stub = <<<'EOF'
#!/usr/bin/env php
<?php

/*
 * This file is part of the phplx Prize Raffle Console Application package.
 *
 * (c) 2013-2014 phplx.net
 *
 * @author Daniel Gomes <me@danielcsgomes.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

Phar::mapPhar('prize-raffle.phar');

EOF;

        return $stub . <<<'EOF'
require 'phar://prize-raffle.phar/bin/phplx';

__HALT_COMPILER();
EOF;
    }
}
