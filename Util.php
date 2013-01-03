<?php
if(!function_exists('get_web_content')){
/**
 * Gets the URL using CURL or fget.
 * @param string $url the url to access
 * @return string the parsed page
 */
function get_web_content($url)
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

if(!function_exists('get_tokens')){
    /**
     * Tokenize a string based on a regex
     * @param string $input
     * @param string $regex
     * @return array array of matched tokens
     */
    function get_tokens($input, $regex)
    {
        $result = array();
        preg_match('/' . $regex . '/', $input, $result);
        return $result;
    }
}

/**
 * 
 * @param mixed $obj
 * @param string $to_class class to cast to
 * @return mixed the resulting object 
 */
function cast($obj, $to_class)
{
    if($to_class == 'string'){
        return (string)$obj;
    }
    elseif($to_class == 'integer' || $to_class == 'int'){
        return (int)((string)$obj);
    }
    elseif($to_class == 'float' || $to_class == 'double'){
        return (float)((string)$obj);
    }
    elseif($to_class == 'boolean' || $to_class == 'bool'){
        if(is_bool($obj)){
            return $obj;
        }
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

/**
 * Gets the name of the class only
 * @param mixed $object object to get the class of
 * @return string the name of the class excluding namespace
 */
function get_class_name($object = null)
{
    if (!is_object($object) && !is_string($object)){
        return false;
    }
    $class = explode('\\', (is_string($object) ? $object : get_class($object)));
    return $class[count($class) - 1];
}

/**
 * Determines whether an specified class is a scalar type
 * @param string $type the type to check
 * @return boolean true if class is a scalar type
 */
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
    if ($length == 0){
        return true;
    }
    return (substr($haystack, -$length) === $needle);
}

/**
 * Checks that an object can be accessed like a collection or array
 * @param mixed $var The object to check
 * @return boolean true if item is an array or collection
 */
function is_collection($var){
    return (is_array($var)|| $var instanceof ArrayObject || $var instanceof ArrayAccess);
}

/**
 * 
 * @param type $object
 * @param type $attribute
 * @param type $value
 * @return type
 */
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

/**
 * 
 * @param type $array
 * @param type $escape
 * @return type
 */
function to_key_value_pair($array, $escape = false){
    $result = "";
    foreach($array as $key => $value){
        if($escape){
            $result += "$key='" . htmlspecialchars($value) . "' ";
        }
        else $result += "$key='$value' ";
    }
    return \trim($result);
}
