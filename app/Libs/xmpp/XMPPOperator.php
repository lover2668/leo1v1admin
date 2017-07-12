<?php

require_once( __DIR__ ."/XMPP.php");

class XMPPOperator
{
    private $ip;
    private $port;
    private $sys_id;
    private $passwd;
    private $server_name;

    public function __construct($ip, $port, $sys_id, $passwd, $server_name)
    {
        $this->ip          = $ip;
        $this->port        = $port;
        $this->sys_id      = $sys_id;
        $this->passwd      = $passwd;
        $this->server_name = $server_name;
    }
    public function del_room($roomid) {

        $roomid .= "@conference." . $this->server_name;
        try {
            /**var $conn XMPPHP_XMPP */
            $sys_id=$this->sys_id;
            $conn = new XMPPHP_XMPP($this->ip, $this->port,  $sys_id,
                                    $this->passwd, "sys_user", $this->server_name);

            $ret_conn = $conn->connect();
            $payloads = $conn->processUntil(array('end_stream', 'session_start'));

            $jid=  $sys_id. "@" . $this->server_name;
            /*
            $conn->createChatRoom($jid, $roomid, "xmpphp", [
                'muc#roomconfig_persistentroom'=>false
            ], null);
            */

            echo "JID:$jid, $roomid\n";
            $conn->joinRoom_owner($jid, $roomid,  $sys_id);

            $conn->delRoom ($jid, $roomid);

        } catch(Exception $e){
            echo " Exception   :". $e->getMessage();
            return false;
        }
    }

    public function get_room_user($roomid)
    {
        $roomid .= "@conference." . $this->server_name;
        try {
            /**var $conn XMPPHP_XMPP */
            $conn = new XMPPHP_XMPP($this->ip, $this->port, $this->sys_id,
                                    $this->passwd, 'xmpphp', $this->server_name);

            $ret_conn = $conn->connect();
            $payloads = $conn->processUntil(array('end_stream', 'session_start'));
            //echo "11111\n";

            $conn->joinRoom($this->sys_id . "@" . $this->server_name, $roomid, "xmpphp");

            $conn->getUsers($this->sys_id . "@" . $this->server_name, $roomid);
            $payloads = $conn->processUntil(array('presence', 'end_stream', 'session_start'));

            $buff = "";
            while(strpos($buff, 'testgetusersid') == ""){
                $buff = @fread($conn->socket, 4096);
                if(!$buff) {
                    if($this->reconnect) {
                        $this->doReconnect();
                    } else {
                        fclose($this->socket);
                        $this->socket = NULL;
                        return false;
                    }
                }
            }
            $ret = $this->xml2array($buff);
            $user_list= $ret['iq']['query']['item'];

        } catch(Exception $e){
            return false;
        }



        $user_arr = array();

        if (!empty($user_list) && is_array($user_list)) {
            foreach($user_list as $k => $v) {
                //if (isset( $v['name'] ) && strcmp( $v['name'], 'xmpphp') != 0) {
                if (isset( $v['name'] ) ) {
                    $user_arr[] = $v['name'];
                }
            }
        }

        return $user_arr;
    }

    private function xml2array($contents, $get_attributes=1, $priority = 'tag')
    {
        if(!$contents) return array();

        if(!function_exists('xml_parser_create')) {
            //print "'xml_parser_create()' function not found!";
            return array();
        }

        //Get the XML parser of PHP - PHP must have this module for the parser to work
        $parser = xml_parser_create('');
        # http://minutillo.com/steve/weblog/2004/6/17/php-xml-and-character-encodings-a-tale-of-sadness-rage-and-data-loss
        xml_parser_set_option($parser, XML_OPTION_TARGET_ENCODING, "UTF-8");
        xml_parser_set_option($parser, XML_OPTION_CASE_FOLDING, 0);
        xml_parser_set_option($parser, XML_OPTION_SKIP_WHITE, 1);
        xml_parse_into_struct($parser, trim($contents), $xml_values);
        xml_parser_free($parser);

        if(!$xml_values) return;//Hmm...

        //Initializations
        $xml_array = array();
        $parents = array();
        $opened_tags = array();
        $arr = array();

        $current = &$xml_array; //Refference

        //Go through the tags.
        $repeated_tag_index = array();//Multiple tags with same name will be turned into an array
        foreach($xml_values as $data) {
            unset($attributes,$value);//Remove existing values, or there will be trouble

            //This command will extract these variables into the foreach scope
            // tag(string), type(string), level(int), attributes(array).
            extract($data);//We could use the array by itself, but this cooler.

            $result = array();
            $attributes_data = array();

            if(isset($value)) {
                if($priority == 'tag') $result = $value;
                else $result['value'] = $value; //Put the value in a assoc array if we are in the 'Attribute' mode
            }

            //Set the attributes too.
            if(isset($attributes) and $get_attributes) {
                foreach($attributes as $attr => $val) {
                    if($priority == 'tag') $attributes_data[$attr] = $val;
                    else $result['attr'][$attr] = $val; //Set all the attributes in a array called 'attr'
                }
            }

            //See tag status and do the needed.
            if($type == "open") {//The starting of the tag '<tag>'
                $parent[$level-1] = &$current;
                if(!is_array($current) or (!in_array($tag, array_keys($current)))) { //Insert New tag
                    $current[$tag] = $result;
                    if($attributes_data) $current[$tag. '_attr'] = $attributes_data;
                    $repeated_tag_index[$tag.'_'.$level] = 1;

                    $current = &$current[$tag];

                } else { //There was another element with the same tag name

                    if(isset($current[$tag][0])) {//If there is a 0th element it is already an array
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;
                        $repeated_tag_index[$tag.'_'.$level]++;
                    } else {//This section will make the value an array if multiple tags with the same name appear together
                        $current[$tag] = array($current[$tag],$result);//This will combine the existing item and the new item together to make an array
                        $repeated_tag_index[$tag.'_'.$level] = 2;

                        if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well
                            $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                            unset($current[$tag.'_attr']);
                        }

                    }
                    $last_item_index = $repeated_tag_index[$tag.'_'.$level]-1;
                    $current = &$current[$tag][$last_item_index];
                }

            } elseif($type == "complete") { //Tags that ends in 1 line '<tag />'
                //See if the key is already taken.
                if(!isset($current[$tag])) { //New Key
                    $current[$tag] = $result;
                    $repeated_tag_index[$tag.'_'.$level] = 1;
                    if($priority == 'tag' and $attributes_data) $current[$tag. '_attr'] = $attributes_data;

                } else { //If taken, put all things inside a list(array)
                    if(isset($current[$tag][0]) and is_array($current[$tag])) {//If it is already an array...

                        // ...push the new element into that array.
                        $current[$tag][$repeated_tag_index[$tag.'_'.$level]] = $result;

                        if($priority == 'tag' and $get_attributes and $attributes_data) {
                            $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                        }
                        $repeated_tag_index[$tag.'_'.$level]++;

                    } else { //If it is not an array...
                        $current[$tag] = array($current[$tag],$result); //...Make it an array using using the existing value and the new value
                        $repeated_tag_index[$tag.'_'.$level] = 1;
                        if($priority == 'tag' and $get_attributes) {
                            if(isset($current[$tag.'_attr'])) { //The attribute of the last(0th) tag must be moved as well

                                $current[$tag]['0_attr'] = $current[$tag.'_attr'];
                                unset($current[$tag.'_attr']);
                            }

                            if($attributes_data) {
                                $current[$tag][$repeated_tag_index[$tag.'_'.$level] . '_attr'] = $attributes_data;
                            }
                        }
                        $repeated_tag_index[$tag.'_'.$level]++; //0 and 1 index is already taken
                    }
                }

            } elseif($type == 'close') { //End of tag '</tag>'
                $current = &$parent[$level-1];
            }
        }

        return($xml_array);
    }
}