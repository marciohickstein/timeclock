<?php

/**
 * Return array with errors
 *
 * @param  mixed $erroid
 * @param  mixed $message
 *
 * @return void
 */
/*
 function getHttpResponse($id, $message){
    $data = array(
        "code" => $id,
        "message" => $message
    );
    return $data;
}
*/
function getHttpError($erroid, $message){
    $data = array(
        "errorcode" => $erroid,
        "errormessage" => $message
    );
    return $data;
}
/**
 * Convert array to json or xml and set response 
 *
 * @param  mixed $response
 * @param  mixed $format
 * @param  mixed $data
 *
 * @return void
 */
function writeResponse($response, $format, $data){
    if ($format == "xml")
        $resp = xml_encode('employees', $data);
    else
        $resp = json_encode($data);
    $response->write($resp);
    if ($format == "xml")
        $response->withHeader('Content-type', 'application/xml');
    else
        $response->withHeader('Content-type', 'application/json');
}

//function defination to convert array to xml
function array_to_xml($array, &$xml_user_info) {
    foreach($array as $key => $value) {
        if(is_array($value)) {
            if(!is_numeric($key)){
                $subnode = $xml_user_info->addChild("$key");
                array_to_xml($value, $subnode);
            }else{
                $subnode = $xml_user_info->addChild("item$key");
                array_to_xml($value, $subnode);
            }
        }else {
            $xml_user_info->addChild("$key",htmlspecialchars("$value"));
        }
    }
}

function xml_encode($root, $data = array()){
        //creating object of SimpleXMLElement
        $xml_info = new SimpleXMLElement("<?xml version=\"1.0\"?><$root></$root>");

        //function call to convert array to xml
        array_to_xml($data,$xml_info);

        //xml in string
        return $xml_info->asXML();
}

/**
 * Show message with break line in HTML
 *
 * @param  string $message
 *
 * @return void
 */
function showMessage($message)
{
    echo $message . "</br>";
}

/**
 * Show exception message
 *
 * @param  Exception $e
 *
 * @return void
 */
function showException(Exception $e)
{
    showMessage("Error: " . $e->getMessage());
}

/**
 * Show exception message and abort execution php
 *
 * @param  Exception $e
 *
 * @return void
 */
function showExceptionAndAbort(Exception $e)
{
    echo "Error: " . $e->getMessage();
    exit($e->getCode());
}

?>