<?php

namespace Nkrumahthis\XMLToArray;

class XMLToArray
{
    public static function parse($xmlString)
    {
        $xmlArray = [];
        $parser = xml_parser_create();
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parse_into_struct($parser, $xmlString, $xmlValues, $xmlIndices);
        xml_parser_free($parser);

        $current = &$xmlArray;
        foreach ($xmlValues as $data) {
            $tag = $data['tag'];
            $value = isset($data['value']) ? $data['value'] : null;
            $attributes = isset($data['attributes']) ? $data['attributes'] : null;

            if ($data['type'] == 'open') {
                // Check if the element has attributes
                if ($attributes) {
                    $current[$tag] = ['@attributes' => $attributes];
                    $current = &$current[$tag];
                } else {
                    $current[$tag] = [];
                    $current = &$current[$tag];
                }
            } elseif ($data['type'] == 'complete') {
                $current[$tag] = $value;
            } elseif ($data['type'] == 'close') {
                $current = &$xmlArray;
            }
        }

        return $xmlArray;
    }
}
