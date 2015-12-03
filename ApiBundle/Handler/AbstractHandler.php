<?php

namespace TBCA\ApiBundle\Handler;

use Symfony\Component\HttpFoundation\JsonResponse;

class AbstractHandler {

	/**
	 * Create xml.
	 *
	 * @return SimpleXMLElement
	 */
	protected function xml(\SimpleXMLElement $object, array $data)
	{   
		foreach ($data as $key => $value)
		{   
			if (is_array($value))
			{   
				$new_object = $object->addChild($key);
				$this->xml($new_object, $value);
			}   
			else
			{   
				$object->addChild($key, $value);
			}   
		}

		return $object;
	}

	protected function serveFormat($data, $rootLabel)
	{
		if($this->content == "application/xml"){
			// Converte para XML
			$xmlObject = new \SimpleXMLElement('<'.$rootLabel.'/>');
			$this->xml($xmlObject, $data);

			$data = $xmlObject->asXML();
		} else {
			// Converte para JSON
			$response = new JsonResponse();
			$response->setData($data);
			
			return $response;
		}

		return $data;
	}

}