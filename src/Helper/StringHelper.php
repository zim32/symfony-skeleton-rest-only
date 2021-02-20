<?php

namespace App\Helper;

use Doctrine\Inflector\Inflector;
use Doctrine\Inflector\NoopWordInflector;

class StringHelper
{
    /**
     * @var null|Inflector
     */
    static $inflector = null;

    public static function initInflector()
    {
        if (self::$inflector === null) {
            self::$inflector = new Inflector(new NoopWordInflector(), new NoopWordInflector());
        }
    }

    /**
     * Convert entity name to doctrine table format (Fixed version to handle multiple uppercase letters)
     * Example: ProjectFTPResource ==> project_ftp_resource
     *
     * @param string $source
     * @return string
     */
    public static function tableize(string $source)
    {
        self::initInflector();

        $letters = str_split($source);
        $result  = [];

        for ($i=0; $i<count($letters); $i++) {
            $currentLetter = $letters[$i];
            $nextLetter = ($i < count($letters) - 1) ? $letters[$i+1] : null;
            $prevLetter = ($i > 0) ? $letters[$i-1] : null;

            if (
                $prevLetter &&
                ctype_upper($currentLetter) &&
                (($nextLetter && ctype_upper($nextLetter)) || !$nextLetter) &&
                ctype_upper($prevLetter)
            ) {
                $result[] = strtolower($currentLetter);
            } else {
                $result[] = $currentLetter;
            }
        }

        $result = implode($result);

        return self::$inflector->tableize($result);
    }

    public static function slugify(string $source)
    {
        return str_replace('_', '-', self::tableize($source));
    }

    public static function removeNonPrintableCharacters(string $source)
    {
        return preg_replace('/[^\x{0020}-\x{007E}\x{0400}-\x{04FF}]/u', '', $source);
    }

    public static function classify(string $source)
    {
        self::initInflector();
        return self::$inflector->classify($source);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function startsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return (substr($haystack, 0, $length) === $needle);
    }

    /**
     * @param string $haystack
     * @param string $needle
     *
     * @return bool
     */
    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }

        return (substr($haystack, -$length) === $needle);
    }

}