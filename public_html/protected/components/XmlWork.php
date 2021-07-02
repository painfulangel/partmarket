<?php

class XmlWork {

    public static function getArray($node) {
        $array = false;
        if ($node->hasAttributes()) {
            foreach ($node->attributes as $attr) {
                $array[$attr->nodeName] = iconv(mb_detect_encoding($attr->nodeValue), 'utf-8', $attr->nodeValue);
            }
        }
        if ($node->hasChildNodes()) {
            if ($node->childNodes->length == 1) {
                $array[$node->firstChild->nodeName] = iconv(mb_detect_encoding($node->firstChild->nodeValue), 'utf-8', $node->firstChild->nodeValue);
            } else {
                foreach ($node->childNodes as $childNode) {
                    if ($childNode->nodeType == XML_CDATA_SECTION_NODE) {
                        $array[$childNode->nodeName][] = iconv(mb_detect_encoding($childNode->textContent), 'utf-8', $childNode->textContent);
                    } else
                    if ($childNode->nodeType != XML_TEXT_NODE) {
                        $array[$childNode->nodeName][] = self::getArray($childNode);
                    }
                }
            }
        }

        return $array;
    }

}
