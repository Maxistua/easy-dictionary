<?php

namespace EasyDictionary;

/**
 * Class RegularExpression
 * @package EasyDictionary
 */
class RegularExpression
{
    /**
     * Create search pattern
     *
     * @param string|array $searchPhrases
     * @param bool $strictMode
     *
     * @return string
     */
    public static function createSearchPattern($searchPhrases, bool $strictMode = false):string
    {
        if (!is_array($searchPhrases)) {
            $searchPhrases = explode(',', $searchPhrases);
        }

        $patternGroups = [];
        foreach ($searchPhrases as $phrase) {
            $phrase = preg_quote($phrase, '/');
            $patternGroups[] = $strictMode ? "((\s+)?{$phrase}(\s+)?)" : "({$phrase})";
        }

        $pattern = '/' . implode('|', $patternGroups) . '/i';

        return $pattern;
    }
}
