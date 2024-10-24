<?php

namespace App\Service;

use App\Value\ResponseFormatterContentType;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

class ResponseFormatter
{
    private function arrayToXml(array $data, &$xmlData) {
        foreach( $data as $key => $value ) {
            if( is_array($value) ) {
                if( is_numeric($key) ){
                    $key = 'item'.$key; //dealing with <0/>..<n/> issues
                }
                $subnode = $xmlData->addChild($key);
                $this->arrayToXml($value, $subnode);
            } else {
                $xmlData->addChild("$key",htmlspecialchars("$value"));
            }
        }

        return $xmlData;
    }

    public function formatResponse(
        array                        $content,
        ResponseFormatterContentType $contentType
    ) {
        if ($contentType === ResponseFormatterContentType::APPLICATION_JSON) {
            return new JsonResponse($content);
        } elseif ($contentType === ResponseFormatterContentType::APPLICATION_XML) {
            $xmlData = new \SimpleXMLElement('<?xml version="1.0"?><data></data>');
            return new Response($this->arrayToXml($content, $xmlData)->asXML(), Response::HTTP_OK, [
                'Content-Type' => $contentType->value
            ]);
        } else {
            throw new \LogicException(sprintf('Content type %s not supporeted', $contentType->value));
        }
    }
}
