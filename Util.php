<?php
if(!function_exists('getWebContent')){
/**
 * Gets the URL using CURL or fget.
 * @param string $url the url to access
 * @return string the parsed page
 */
function getWebContent($url)
{
	$page = null;
	if(ini_get('allow_url_fopen')) {
		$page = file_get_contents($url);
	}
	else{
	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	    curl_setopt($ch, CURLOPT_URL, $url);
	    $page = curl_exec($ch);
	    curl_close($ch);
	}
	
	return $page;
}
}

if(!function_exists('getTokens')){
function getTokens($input, $regex)
{
    $result = array();
    preg_match('/' . $regex . '/', $input, $result);
    return $result;
}
}

function cast($obj, $to_class)
{
    if($to_class == 'string')
    {
        return (string)$obj;
    }
    elseif($to_class == 'integer' || $to_class == 'int'){
        return (int)((string)$obj);
    }
    elseif($to_class == 'float' || $to_class == 'double'){
        return (float)((string)$obj);
    }
    elseif($to_class == 'boolean' || $to_class == 'bool'){
        $val = (string)$obj;
        switch (strtolower($val)){
            case "true":
            case "1":
                return true;
            case "false":
            case "0":
                return false;
            default:
                return !empty($val);
        }
    }
    elseif(class_exists($to_class))
    {
        $obj_in = serialize($obj);
        $obj_out = 'O:' . strlen($to_class) . ':"' . $to_class . '":' . substr($obj_in, $obj_in[2] + 7);
        return unserialize($obj_out);
    }
    else return false;
}

function get_class_name($object = null)
{
    if (!is_object($object) && !is_string($object)){
        return false;
    }
    $class = explode('\\', (is_string($object) ? $object : get_class($object)));
    return $class[count($class) - 1];
}


function is_scalar_type($type = null){
    if(!isset($type)){
        return false;
    }
    return in_array($type, array('string', 'boolean', 'bool', 'integer', 'int', 'float', 'double'));
}

function str_starts_with($haystack, $needle)
{
    $length = strlen($needle);
    return (substr($haystack, 0, $length) === $needle);
}


function str_ends_with($haystack, $needle)
{
    $length = strlen($needle);
    if ($length == 0) {
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

function is_collection($var)
{
    return (is_array($var)|| $var instanceof ArrayObject || $var instanceof ArrayAccess);
}

function set_value($object, $attribute, $value){
    if(isset($value)){
        //functions get first priority. invoke functions to assign value
        if(is_callable(array($object, $attribute))){
            if(is_collection($value)){
                foreach($value as $entry){
                    call_user_func(array($object, $attribute), $entry);
                }
            }
            else{
                call_user_func(array($object, $attribute), $value);
            }
        }
        elseif(property_exists($object, $attribute)){
            if(is_collection($object->$attribute)){
                //var_dump($value);
                //add arrays entry by entry
                if(is_collection($value)){
                    foreach($value as $entry){
                        $object->{$attribute}[] = $entry;
                    }
                }
                //adding a single value
                else{
                    $object->{$attribute}[] = $value;
                }
            }
            else{
                //if the target does not expect an array and yet given one, use only the first entry
                if(is_collection($value) && !empty($value)){
                    $object->$attribute = $value[0];
                }
                else{
                    $object->$attribute = $value;
                }
            }
        }
    }

    return $object;
}