<?
	class SonosAF extends IPSModule
	{
		public function Create()

		{
			//Never delete this line!
			parent::Create();

			$this->RegisterPropertyString("Sonos_Master_IP", "192.168.0.63");//Erzeugt die Eigenschaft

		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

			$this->RegisterVariableString ("Sonos_Master_IP", "Sonos Master IP", ""); // Erzeugt die Variable
		}

		/**
		* This function will be available automatically after the module is imported with the module control.
		* Using the custom prefix this function will be callable from PHP and JSON-RPC through:
		*
		* SO_RequestInfo($id);
		*
		*/
		public function Install_framework()
		{

			global $parent_id, $ID_IP,$player_data_id,$Var_ID1,$Sonos_Data,$list ;
			$Sonos_Master_IP = $this->ReadPropertyString("Sonos_Master_IP"); //Liest die Eigenschaft
			$ID_IP = $this->GetIDForIdent("Sonos_Master_IP");
			SetValue($ID_IP, $Sonos_Master_IP); //Beschreibt die Variable
			$parent_id = IPS_GetObject($ID_IP)['ParentID'];
			SO_create_sonos_reader_socket($parent_id);
			SO_create_sonos_text_parser($parent_id);
			SO_create_sonos_content_variable($parent_id);
			SO_define_sonos_text_parser($parent_id);
			SO_define_categories($parent_id);
			SO_read_sonos_data($parent_id);
//			print_r($Sonos_Data);
//			SO_build_or_fix_sonos_variables($parent_id);
		}



		function build_or_fix_sonos_variables()
		{
			global $player_data_id,$Sonos_Data;
			$root_list = IPS_GetObject($player_data_id)['ChildrenIDs'];
			foreach ($root_list as $cat_key => $cat_id)//Loop alle Kategorien
			{

   				$ii = 0;
   				$Var_Names[] = NULL;
   				$Var_ID[] = NULL;
				foreach( IPS_GetObject ($cat_id)['ChildrenIDs']as $index => $ID) // Loop all Variablen unterhalb der Kategorie und erstellt array mit Namen+ID
				{
					$Var_Names[$ii] = IPS_GetObject($ID)['ObjectName'];
					$Var_ID[$ii] = $ID;
					$ii++;
				}
				$i = 0;
				foreach($Data as $z) // Looped durch SONOS Array
				{
					if(in_array ($Data[$i]['Name'],$Var_Names )) //Name bereits vorhanden
					{
			 			$Data[$i][IPS_GetObject($cat_id)['ObjectName']."_ID"] = $Var_ID[array_search($Data[$i]['Name'], $Var_Names)];
					}
					else
					{
						$Data[$i][IPS_GetObject ($cat_id)['ObjectName']."_ID"] = create_var($Data[$i]['Name'],$cat_id,1,IPS_GetObject($cat_id)['ObjectName'],false);

					}
					$i++;
				}
			}
		}




		public function define_categories()
		{

			global $parent_id,$action_ID, $player_data_id;
			$action = "Sonos_Action";
			$ALL_IDS = IPS_GetChildrenIDs($parent_id);
			$InstanzID = 0;
			foreach ($ALL_IDS as $key => $value)
			{
				if(IPS_GetName($value) ==$action)
				{
					$InstanzID = $value;
				}
			}
			if ($InstanzID == 0)
			{
				$action_ID = IPS_CreateCategory();       // Kategorie anlegen
				IPS_SetName($action_ID, $action); // Kategorie benennen
				IPS_SetParent($action_ID, $parent_id);
			}
			else
			{
				$action_ID = $InstanzID;
			}
			$player = "Player_Data";
			$ALL_IDS = IPS_GetChildrenIDs($parent_id);
			$InstanzID = 0;
			foreach ($ALL_IDS as $key => $value)
			{
				if(IPS_GetName($value) == $player)
				{
					$InstanzID = $value;
				}
			}
			if ($InstanzID == 0)
			{
				$player_data_id = IPS_CreateCategory();       // Kategorie anlegen
				IPS_SetName($player_data_id, $player); // Kategorie benennen
				IPS_SetParent($player_data_id, $parent_id);
			}
			else
			{
				$player_data_id = $InstanzID;
			}

		}

		public function define_sonos_text_parser()
		{

			global  $text_parser_id,$Var_ID1,$sonos_reader_id;
			if(@IPS_DisconnectInstance (  $text_parser_id ))
			{
			}
			else
			{
				$Rule = '[{"Variable":'.$Var_ID1.',"TagTwo":"<MediaServers>","TagOne":"ZPSupportInfo","ParseType":4}]';
				IPS_SetProperty ( $text_parser_id,"Rules", $Rule);
				IPS_ApplyChanges($text_parser_id);
			}
			IPS_ConnectInstance ( $text_parser_id,$sonos_reader_id );

		}


 		public function create_sonos_content_variable()
		{
			global $Var_ID1,$parent_id, $ID_IP,$text_parser_id ;
			$name_content_var = "Sonos_Content";
			$ALL_IDS = IPS_GetChildrenIDs($text_parser_id);
			$InstanzID = 0;
			foreach ($ALL_IDS as $key => $value)
			{
				if(IPS_GetName($value) ==$name_content_var)
				{
					$InstanzID = $value;

				}
			}
			if ($InstanzID == 0)
			{
				$Var_ID1 = IPS_CreateVariable (3);
				IPS_SetName ( $Var_ID1, $name_content_var );
				IPS_SetParent ( $Var_ID1, $text_parser_id );
			}
			else
			{
				$Var_ID1 = $InstanzID;
			}

		}


		public function create_sonos_text_parser()
		{
			global $Var_ID1, $text_parser_id,$parent_id;
			$parser_name = "Sonos_Text_Parser" ;
			$ALL_IDS = IPS_GetObjectList ( );
			$InstanzID = 0;
			foreach ($ALL_IDS as $key => $value)
			{
				if(IPS_GetName($value) ==$parser_name)
				{
					$InstanzID = $value;
				}
			}
			if ($InstanzID == 0)
			{
     				$id = IPS_CreateInstance ('{4B00C7F7-1A6D-4795-A2D2-08151854D259}');
				IPS_SetName ( $id,$parser_name);
				IPS_SetParent ( $id, $parent_id );

			}
			else
			{
     				$id = $InstanzID;
			}
 			$text_parser_id = $id;

		}

		public function create_sonos_reader_socket()
		{
			global $sonos_reader_id,$ID_IP;
			$socket_name = "Sonos_Reader_Socket" ;
			$Sonos_Master_IP = GetValueString($ID_IP);
			$ALL_IDS = IPS_GetObjectList ( );
			$InstanzID = 0;
			foreach ($ALL_IDS as $key => $value)
			{
				if(IPS_GetName($value) ==$socket_name)
				{
					$InstanzID = $value;
				}
			}
			if ($InstanzID == 0)
			{
     				$id = IPS_CreateInstance ('{4CB91589-CE01-4700-906F-26320EFCF6C4}');
     				$URL = "http://".$Sonos_Master_IP.":1400/status/topology";
    				IPS_SetProperty($id, "URL", $URL);
				IPS_SetProperty($id,"Active",true);
				IPS_SetProperty($id,"UseBasicAuth",false);
				IPS_SetProperty($id,"AuthUser","");
				IPS_SetProperty($id,"AuthPass","");
				IPS_SetProperty($id,"Interval",1);
				IPS_ApplyChanges($id);
				IPS_SetName ( $id,$socket_name);
			}
			else
			{
     				$id = $InstanzID;
     				$URL = "http://".$Sonos_Master_IP.":1400/status/topology";
    				IPS_SetProperty($id, "URL", $URL);
				IPS_SetProperty($id,"Active",true);
				IPS_SetProperty($id,"UseBasicAuth",false);
				IPS_SetProperty($id,"AuthUser","");
				IPS_SetProperty($id,"AuthPass","");
				IPS_SetProperty($id,"Interval",1);
				IPS_ApplyChanges($id);
			}
			$sonos_reader_id =$id;

		}

		public function read_sonos_data()
		{
         global $Var_ID1,$Sonos_Data,$parent_id,$value,$list;
			//echo $Var_ID1;
			$Text = GetValueString($Var_ID1);
			// $Text = strip_tags($Text);
			$result = explode("<",$Text);
			//echo $Text;
			print_r( $result);
			$i = 1;

			foreach ($result as$key => $value)
			{
 				if(stripos($value,"RINCON") > 0)
 				{
					SO_get_sonos_details($parent_id,$value);
					echo " i ".$i." IP : ".$list[$i]['IP']." ";
					$sonos = new PHPSonos($list[$i]['IP']); //Sonos ZP IPAdresse
					$list[$i]['Volume'] = $sonos->GetVolume();
					$list[$i]['Mute'] = $sonos->GetMute();
					$ZoneAttributes = $sonos->GetZoneAttributes();
					$list[$i]['Name'] = $ZoneAttributes['CurrentZoneName'];
					$i = $i+1;
					//echo $value;
 				}
 				else
 				{
 				}
			}
//			print_r ($list);
			$Sonos_Data = $list;
		}





 		public function get_sonos_details($value)
 		{
			global $list;
			$list['Master_RINCON'] = substr($value,stripos($value,"RINCON"),24);
			$tmp = substr($value,stripos($value,"http://"),24);
			$start = stripos($tmp,"/") + 2;
			$stop = stripos ($tmp,":1400")- 7;
			$list["IP"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"coordinator"),19);
			$start = stripos($tmp,"'=")+12;
			$stop = stripos ($tmp," ");
			$list["COORD"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"bootseq="),20);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"uuid")-11;
			$list["bootseq"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"1400:"),8);
			$start = stripos($tmp,":")+1;
			$stop = stripos ($tmp,"'")-5;
			$list["GroupNr"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"uuid='"),30);
			$start = stripos($tmp,"='")+2;
			$stop = $start + 18;
			$list["Player_RINCON"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"wirelessmode"),24);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"channel")-16;
			$list["Wireless_Mode"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"channelfreq"),29);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"behindwifi")-15;
			$list["Channel_Freq"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"behindwifiext"),29);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"location")-17;
			$list["Behind_Wifi_Ext"] = substr($tmp,$start,$stop);
		 }





public function sonos_content()
{
	global $Data,$Sonos_Data,$parent_id;
	$Sonos_Data = SO_read_sonos_data($parent_id);
	$Data = $Sonos_Data;
	SO_build_or_fix_sonos_variables("","");
	SO_build_or_fix_sonos_controls("","");
	SO_populate_variables($Sonos_Data);
	SO_create_profile("","");
	SO_build_or_fix_profile($Sonos_Data);

	return $Sonos_Data;
}

public function build_or_fix_sonos_controls()
{

	global $action_ID,$Data;

	$cat_id = $action_ID;
   	$ii = 0;
   	$Var_Names[] = NULL;
   	$Var_ID[] = NULL;

		foreach( IPS_GetObject($cat_id)['ChildrenIDs']as $index => $ID) // Loop all Variablen unterhalb der Kategorie und erstellt array mit Namen+ID
		{
			$Var_Names[$ii] = IPS_GetObject($ID)['ObjectName'];
			$Var_ID[$ii] = $ID;
			$ii++;
		}
		$i = 0;
		foreach($Data as $z) // Looped durch SONOS Array
		{
			if(in_array ($Data[$i]['Name'],$Var_Names )) //Name bereits vorhanden
			{
			 	$Data[$i][IPS_GetObject($cat_id)['ObjectName']] = $Var_ID[array_search($Data[$i]['Name'], $Var_Names)];
			}
			else
			{
				$Data[$i][IPS_GetObject ($cat_id)['ObjectName']] = create_var($Data[$i]['Name'],$cat_id,1,IPS_GetObject($cat_id)['ObjectName'],true);
			}
			$i++;
		}

}



public function populate_variables()
{
  global $Sonos_Data;
  $i = 0;

  foreach($Sonos_Data as $z)
  {
			$group_number[$i] = $Sonos_Data[$i]['GroupNr'];
			$Master_Rincon[$i] = $Sonos_Data[$i]['Master_RINCON'];
			$Player_Rincon[$i] = $Sonos_Data[$i]['Player_RINCON'];
  			SO_populate_mute($Sonos_Data,$i);
  			SO_populate_volume($Sonos_Data,$i);
  			SO_populate_master($Sonos_Data,$i);

			$i++;
  }
  foreach($Master_Rincon as $key => $value)
  {
		if(
				($value == $Master_Rincon[$key])
				AND
				($Sonos_Data[$key]['COORD'])
		  )
		{
  			foreach($Master_Rincon as $i => $unique_value)
  			{
  			   if($value == $unique_value)
  			   {
 					SetValueInteger($Sonos_Data[$i]['SONOS_MASTER_ID'],$key);
  			   }
  			   else
  			   {
  			   }
  			}
		}
		else
		{
		}
  }
}

public function  populate_mute($Sonos_Data,$i)
{
	if($Sonos_Data[$i]['Mute'] == 1)
	{
		SetValueInteger($Sonos_Data[$i]['Mute_ID'],1);
	}
	else
	{
		SetValueInteger($Sonos_Data[$i]['Mute_ID'],0);
	}
}

public function populate_volume($Sonos_Data,$i)
{
		SetValueInteger($Sonos_Data[$i]['Volume_ID'],$Sonos_Data[$i]['Volume']);

}

public function populate_master($Sonos_Data,$i)
{
	if($Sonos_Data[$i]['COORD'])
	{
		SetValueInteger($Sonos_Data[$i]['SONOS_MASTER_ID'],$i);
	}
	else
	{

	}
}



public function build_or_fix_profile($Data)
{
	$key = 0;
	foreach($Data as $i)
	{
	 $Color = [0x15EB4A,0xF21344,0x1833DE,0xE8DA10,0xF21BB9,0x1BCEF2,0x1BF2C0,0x1A694C,0xF2981B,0x48508A,0x912A41];
	 IPS_SetVariableProfileAssociation ('SONOS_MASTER',$key,$Data[$key]['Name'],'',  $Color[$key]);
	 $key++;
	}

}


public function 	create_profile()
{
	if(IPS_VariableProfileExists ( 'SONOS_MASTER' ))
	{
//      IPS_DeleteVariableProfile ( 'SONOS_MASTER' );
	}
	else
	{
		IPS_CreateVariableProfile ( 'SONOS_MASTER', 1 );
	}

}





public function create_var($Name,$Root,$Type,$Profile,$Action)
{
  $ID = IPS_GetVariableIDByName ( $Name, $Root );
  if ($ID)
  {
  }
  else
  {
  		$ID = IPS_CreateVariable ( $Type );
  		IPS_SetName ( $ID,$Name );
  		IPS_SetParent ( $ID, $Root );
  		if ($Action) {IPS_SetVariableCustomAction ( $ID, 38913 /*[Object #38913 does not exist]*/ );}
  		IPS_SetVariableCustomProfile ( $ID, $Profile);
  }

  return $ID;

}

public function create_link($Parent,$Name,$Root,$ID)
{
  $LID = IPS_CreateLink ( );
  IPS_SetName ( $LID,$Name );
  IPS_SetParent ( $LID, $Parent);
  IPS_SetLinkTargetID ( $LID, $ID );
}

}


/******************* PHPSonos.inc.php **************************************
Sonos PHP Script
Copyright: Michael Maroszek
Version: 1.0, 09.07.2009

Comment:

- andre added setter functions
- 110108  - br added comments based on UPNP information from devicespy;
also added the function XMLsendPacket to get non filtered answers
- 110120 - br added Set and GetLEDState
- 110202 - br added GetZoneAttributes
- 110202 - br added GetZoneInfo
- 110203 - br added gestposinfo TrackURI (contains x-rincon of the zone master if we are slave)
- 110206 - br added AddMember(Rincon...) and RemoveMember(Rincon...)
- 110207 - br added RamptoVolume
- 110208 - br added calculation of Content-Length to some functions
- 110318 - br fiddled with Playmode (maybe fixed a bug)
- 110318 - br added Get and Set CrossfadeMode
- 110318 - br added SaveQueue
- 110328 - ta lun added GetPlaylist($value)
- 110328 - ta lun added GetImportedPlaylists()
- 110328 - ta lun added GetSonosPlaylists()
- 110328 - ta lun added GetCurrentPlaylist()
- 110328 - br corrected titel to title and other things...
- 110328 - br added optional parameter id to SaveQueue
- 110406 - br edited Seek to accept UPNP Unit parameter as option (sec. arg is Target then)
- 110406 - br edited GetPositionInfo to also reflect UPNP return value names
- 110406 - br fixed non valid soap request in seek()
- 110406 - br added return of CurrentURI and CurrentUriMetaData to GetMediaInfo (Current File or QUEUE)
				This info is needed to restart a queue, pl or radiostation
- 110407 - br consolidated SetRadio, SetQueue and SetAVTransportURI
				two last now also Support MetaData as 2nd parameter; SetRadio supports the name of a radiostation as second parameter
=============================================================================
Useful links:
------------------
http://travelmarx.blogspot.com/2010/06/exploring-sonos-via-upnp.html
http://travelmarx.blogspot.com/2011/01/extracting-sonos-playlist-simple-sonos.html,
http://opentools.homeip.net/dev-tools-for-upnp
http://www.ip-symcon.de/forum/f53/php-sonos-klasse-ansteuern-einzelner-player-7676/
http://your.sonos.i.p:1400/status (Cool!!)
http://play.er.i.p:1400/xml/zone_player.xml
***************************************************************************/


class PHPSonos {
	private $address = "";

	public function __construct( $address ) {
	   $this->address = $address;

	}


/******************* urn:schemas-upnp-org:device:ZonePlayer:1 *************
* http://play.er.i.p:1400/xml/zone_player.xml
***************************************************************************/

/* urn:upnp-org:serviceId:AlarmClock */
	// Not implemented
/* urn:upnp-org:serviceId:AudioIn */
	// Not implemented
/* urn:upnp-org:serviceId:DeviceProperties */
	public function GetZoneAttributes() // added br
	{
/*
$content='POST /DeviceProperties/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:DeviceProperties:1#GetZoneAttributes"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400
Content-Length: 295

<?xml version="1.0" encoding="utf-8"?>
<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body>
<u:GetZoneAttributes xmlns:u="urn:schemas-upnp-org:service:DeviceProperties:1"/>
</s:Body>
</s:Envelope>'
;
*/
$header='POST /DeviceProperties/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:DeviceProperties:1#GetZoneAttributes"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400';
$xml='<?xml version="1.0" encoding="utf-8"?>
<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body>
<u:GetZoneAttributes xmlns:u="urn:schemas-upnp-org:service:DeviceProperties:1"/>
</s:Body>
</s:Envelope>';
$content=$header . '
Content-Length: '. strlen($xml) .'

'. $xml;

$returnContent = $this->XMLsendPacket($content);


		$xmlParser = xml_parser_create("UTF-8");
		xml_parser_set_option($xmlParser, XML_OPTION_TARGET_ENCODING, "ISO-8859-1");
		xml_parse_into_struct($xmlParser, $returnContent, $vals, $index);
		xml_parser_free($xmlParser);


		$ZoneAttributes = Array();

		$key="CurrentZoneName"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneAttributes[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneAttributes[$key] = ""; }

		$key="CurrentIcon"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneAttributes[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneAttributes[$key] = ""; }


		return $ZoneAttributes; //Assoziatives Array
 	}

	public function GetZoneInfo() // added br
	{
/*
$content='POST /DeviceProperties/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:DeviceProperties:1#GetZoneInfo"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400
Content-Length: 295

<?xml version="1.0" encoding="utf-8"?>
<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body>
<u:GetZoneInfo xmlns:u="urn:schemas-upnp-org:service:DeviceProperties:1"/>
</s:Body>
</s:Envelope>'
;
*/
$header='POST /DeviceProperties/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:DeviceProperties:1#GetZoneInfo"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400';
$xml='<?xml version="1.0" encoding="utf-8"?>
<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body>
<u:GetZoneInfo xmlns:u="urn:schemas-upnp-org:service:DeviceProperties:1"/>
</s:Body>
</s:Envelope>';
$content=$header . '
Content-Length: '. strlen($xml) .'

'. $xml;

$returnContent = $this->XMLsendPacket($content);


		$xmlParser = xml_parser_create("UTF-8");
		xml_parser_set_option($xmlParser, XML_OPTION_TARGET_ENCODING, "ISO-8859-1");
		xml_parse_into_struct($xmlParser, $returnContent, $vals, $index);
		xml_parser_free($xmlParser);


		$ZoneInfo = Array();

		$key="SerialNumber"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneInfo[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneInfo[$key] = ""; }

		$key="SoftwareVersion"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneInfo[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneInfo[$key] = ""; }

		$key="SoftwareVersion"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneInfo[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneInfo[$key] = ""; }

		$key="DisplaySoftwareVersion"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneInfo[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneInfo[$key] = ""; }

		$key="HardwareVersion"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneInfo[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneInfo[$key] = ""; }

		$key="IPAddress"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneInfo[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneInfo[$key] = ""; }


		$key="MACAddress"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneInfo[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneInfo[$key] = ""; }


		$key="CopyrightInfo"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneInfo[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneInfo[$key] = ""; }


		$key="ExtraInfo"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneInfo[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneInfo[$key] = ""; }


		return $ZoneInfo; //Assoziatives Array
 	}





	public function SetLEDState($state) // added br
	{
   if($state=="On") { $state = "On"; } else
		{	if($state=="Off") { $state = "Off"; } else {
      		if($state) { $state = "On"; } else { $state = "Off"; }
      	}
		}

$content='POST /DeviceProperties/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 250
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:DeviceProperties:1#SetLEDState"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:SetLEDState xmlns:u="urn:schemas-upnp-org:service:DeviceProperties:1"><DesiredLEDState>' .$state. '</DesiredLEDState><u:SetLEDState></s:Body></s:Envelope>';

		return (bool)$this->sendPacket($content);
	}
	public function GetLEDState() // added br
	{

$content='POST /DeviceProperties/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 250
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:DeviceProperties:1#GetLEDState"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetLEDState xmlns:u="urn:schemas-upnp-org:service:DeviceProperties:1"><InstanceID>0</InstanceID><u:GetLEDState></s:Body></s:Envelope>';

		if ($this->sendPacket($content)=="On") { return(true); }else return(false);
	}
/* urn:upnp-org:serviceId:GroupManagement */

	public function AddMember($MemberID) // added br
	// Joins a group 1st Arg is RINCON_MAC1400
	// Returns assoziative Array with CurrentSettings and GroupUUIDJoined
		{

$header='POST /GroupManagement/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:GroupManagement:1#AddMember"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400';
$xml='<?xml version="1.0" encoding="utf-8"?><s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body><u:AddMember xmlns:u="urn:schemas-upnp-org:service:GroupManagement:1"><MemberID>' . $MemberID . '</MemberID>
</u:AddMember></s:Body></s:Envelope>';
$content=$header . '
Content-Length: '. strlen($xml) .'

'. $xml;


$returnContent = $this->XMLsendPacket($content);

		$xmlParser = xml_parser_create("UTF-8");
		xml_parser_set_option($xmlParser, XML_OPTION_TARGET_ENCODING, "ISO-8859-1");
		xml_parse_into_struct($xmlParser, $returnContent, $vals, $index);
		xml_parser_free($xmlParser);


		$ZoneAttributes = Array();

		$key="CurrentTransportSettings"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneAttributes[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneAttributes[$key] = ""; }

		$key="GroupUUIDJoined"; // Lookfor
		if ( isset($index[strtoupper($key)][0]) and isset($vals[ $index[strtoupper($key)][0] ]['value'])) {$ZoneAttributes[$key] = $vals[ $index[strtoupper($key)][0] ]['value'];
      } else { $ZoneAttributes[$key] = ""; }


		return $ZoneAttributes; //Assoziatives Array
		// set AVtransporturi ist notwendig
 	}

/* urn:upnp-org:serviceId:MusicServices */
	// Not implemented
/* urn:upnp-org:serviceId:SystemProperties */
	// Not implemented
/* urn:upnp-org:serviceId:ZoneGroupTopology */
	// Not implemented

	public function RemoveMember($MemberID) // added br
	// Leaves a Group
	// Returns None
		{

$header='POST /GroupManagement/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:GroupManagement:1#RemoveMember"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400';
$xml='<?xml version="1.0" encoding="utf-8"?><s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body><u:RemoveMember xmlns:u="urn:schemas-upnp-org:service:GroupManagement:1"><MemberID>' . $MemberID . '</MemberID>
</u:RemoveMember></s:Body></s:Envelope>';
$content=$header . '
Content-Length: '. strlen($xml) .'

'. $xml;

	echo $this->sendPacket($content);

		// set AVtransporturi ist f�r STOP notwendig
 	}

/* urn:upnp-org:serviceId:MusicServices */
	// Not implemented
/* urn:upnp-org:serviceId:SystemProperties */
	// Not implemented
/* urn:upnp-org:serviceId:ZoneGroupTopology */
	// Not implemented


/******************* urn:schemas-upnp-org:device:MediaRenderer:1 ***********

***************************************************************************/

/* urn:upnp-org:serviceId:RenderingControl */

	public function RampToVolume($ramp_type, $volume) //added br
	/* Ramps Vol to $volume using the Method mentioned in $ramp_type as string:
	"SLEEP_TIMER_RAMP_TYPE" - mutes and ups Volume per default within 17 seconds to desiredVolume
	"ALARM_RAMP_TYPE" -Switches audio off and slowly goes to volume
	"AUTOPLAY_RAMP_TYPE" - very fast and smooth; Implemented from Sonos for the unofficial autoplay feature.
	When you switch on a Sonos Device it plays its autoplay Command/Playlist and Settings. It�s all there but currently no GUI setting is possible.
	*Should return Rampseconds* but this is not implemented! */
	{


$header='POST /MediaRenderer/RenderingControl/Control HTTP/1.1
HOST: '.$this->address.':1400
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:RenderingControl:1#RampToVolume"
';
$xml='<?xml version="1.0" encoding="utf-8"?><s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body><u:RampToVolume xmlns:u="urn:schemas-upnp-org:service:RenderingControl:1"><InstanceID>0</InstanceID><Channel>Master</Channel><RampType>'.$ramp_type.'</RampType><DesiredVolume>'.$volume.'</DesiredVolume>
</u:RampToVolume></s:Body></s:Envelope>';
$content=$header . 'Content-Length: '. strlen($xml) .'

'. $xml;


		return (int) $this->sendPacket($content);

	}
/* urn:upnp-org:serviceId:AVTransport */
// If you don�t set $id you may get duplicate playlists!!!

 	public function SaveQueue($title,$id="") // added br
    {

        $header='POST /MediaRenderer/AVTransport/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#SaveQueue"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400';
$xml='<?xml version="1.0" encoding="utf-8"?>
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body>
<u:SaveQueue xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID><Title>'.$title.'</Title><ObjectID>'.$id.'</ObjectID></u:SaveQueue>
</s:Body>
</s:Envelope>';
$content=$header . '
Content-Length: '. strlen($xml) .'

'. $xml;

    $returnContent = $this->sendPacket($content);
	}

	public function GetCrossfadeMode() // added br
	{

$header='POST /MediaRenderer/AVTransport/Control HTTP/1.1
HOST: '.$this->address.':1400
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#GetCrossfadeMode"
';
$xml='<?xml version="1.0" encoding="utf-8"?><s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body><u:GetCrossfadeMode xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID>
</u:GetCrossfadeMode></s:Body></s:Envelope>';
$content=$header . 'Content-Length: '. strlen($xml) .'

'. $xml;

		return (bool)$this->sendPacket($content);
	}

	public function SetCrossfadeMode($mode) // added br
	{


		if($mode) { $mode = "1"; } else { $mode = "0"; }
$header='POST /MediaRenderer/AVTransport/Control HTTP/1.1
HOST: '.$this->address.':1400
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#SetCrossfadeMode"
';
$xml='<?xml version="1.0" encoding="utf-8"?><s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/" xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body><u:SetCrossfadeMode xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID><CrossfadeMode>'.$mode.'</CrossfadeMode></u:SetCrossfadeMode></u:SetCrossfadeMode></s:Body></s:Envelope>';
$content=$header . 'Content-Length: '. strlen($xml) .'

'. $xml;

	$this->sendPacket($content);


	}

	public function Stop()
	{
$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 250
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#Stop"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:Stop xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID></u:Stop></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}




	public function Pause()
	{
$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 252
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#Pause"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:Pause xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID></u:Pause></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function Play()
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 266
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#Play"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:Play xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID><Speed>1</Speed></u:Play></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function Next()
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 250
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#Next"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:Next xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID></u:Next></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function Previous()
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 258
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#Previous"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:Previous xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID></u:Previous></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function Seek($arg1,$arg2="NONE")
	{
// Abw�rtskompatibel zu Paresys Original sein
	if ($arg2=="NONE"){
		$Unit="REL_TIME"; $position=$arg1;
	} else {$Unit=$arg1; $position=$arg2;}

 $header='POST /MediaRenderer/AVTransport/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#Seek"
CONTENT-TYPE: text/xml; charset="utf-8"
CONNECTION: close
HOST: '.$this->address.':1400';
$xml='<?xml version="1.0" encoding="utf-8"?>
<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:Seek xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID><Unit>'. $Unit .'</Unit><Target>'.$position.'</Target></u:Seek></s:Envelope></s:Body></s:Envelope>';
$content=$header . '
Content-Length: '. strlen($xml) .'

'. $xml;

    $returnContent = $this->sendPacket($content);



	}

	public function Rewind()
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 296
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#Seek"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:Seek xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID><Unit>REL_TIME</Unit><Target>00:00:00</Target></u:Seek></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function SetVolume($volume)
	{

$content='POST /MediaRenderer/RenderingControl/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 32'.strlen($volume).'
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:RenderingControl:1#SetVolume"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:SetVolume xmlns:u="urn:schemas-upnp-org:service:RenderingControl:1"><InstanceID>0</InstanceID><Channel>Master</Channel><DesiredVolume>'.$volume.'</DesiredVolume></u:SetVolume></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function GetVolume()
	{

$content='POST /MediaRenderer/RenderingControl/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 290
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:RenderingControl:1#GetVolume"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetVolume xmlns:u="urn:schemas-upnp-org:service:RenderingControl:1"><InstanceID>0</InstanceID><Channel>Master</Channel></u:GetVolume></s:Body></s:Envelope>';

		return (int)$this->sendPacket($content);
	}

	public function SetMute($mute)
	{

		if($mute) { $mute = "1"; } else { $mute = "0"; }

$content='POST /MediaRenderer/RenderingControl/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 314
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:RenderingControl:1#SetMute"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:SetMute xmlns:u="urn:schemas-upnp-org:service:RenderingControl:1"><InstanceID>0</InstanceID><Channel>Master</Channel><DesiredMute>'.$mute.'</DesiredMute></u:SetMute></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function GetMute()
	{

$content='POST /MediaRenderer/RenderingControl/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 286
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:RenderingControl:1#GetMute"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetMute xmlns:u="urn:schemas-upnp-org:service:RenderingControl:1"><InstanceID>0</InstanceID><Channel>Master</Channel></u:GetMute></s:Body></s:Envelope>';

		return (bool)$this->sendPacket($content);
	}

	public function SetPlayMode($mode)
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: '.(291+strlen($mode)).'
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#SetPlayMode"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:SetPlayMode xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID><NewPlayMode>'.$mode.'</NewPlayMode></u:SetPlayMode></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function GetTransportSettings()
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 282
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#GetTransportSettings"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetTransportSettings xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID></u:GetTransportSettings></s:Body></s:Envelope>';

		$returnContent = $this->sendPacket($content);

//	echo "\n===" . $this->address. "====\n" . $returnContent . "\n===\n";

		if (strstr($returnContent, "NORMAL") !== false) {
			return Array (
				"repeat" => false,
				"shuffle" => false
			);
		} elseif (strstr($returnContent, "REPEAT_ALL") !== false) {
			return Array (
				"repeat" => true,
				"shuffle" => false
			);

		} elseif (strstr($returnContent, "SHUFFLE_NOREPEAT") !== false) {
			return Array (
				"repeat" => false,
				"shuffle" => true
			);

		} elseif (strstr($returnContent, "SHUFFLE") !== false) {
			return Array (
				"repeat" => true,
				"shuffle" => true
			);
   	}
   	/* what is PLAYING??? br */
   /*	} elseif (strstr($returnContent, "PLAYING") !== false) {
			return Array (
				"repeat" => false,
				"shuffle" => true
			);
   	} */

	}

	public function GetTransportInfo()
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 274
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#GetTransportInfo"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetTransportInfo xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID></u:GetTransportInfo></s:Body></s:Envelope>';

		$returnContent = $this->sendPacket($content);

		if (strstr($returnContent, "PLAYING") !== false) {
		   return 1;
		} elseif (strstr($returnContent, "PAUSED_PLAYBACK") !== false) {
		   return 2;
		} elseif (strstr($returnContent, "STOPPED") !== false) {
		   return 3;
		}

	}

		public function GetMediaInfo()
	{

// NOTEBR: diese F. ist aktuell noch buggy

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 266
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#GetMediaInfo"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetMediaInfo xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID></u:GetMediaInfo></s:Body></s:Envelope>';

		$returnContent = $this->XMLsendPacket($content);

/*		$returnContent = substr($returnContent, stripos($returnContent, '&lt;'));
		$returnContent = substr($returnContent, 0, strrpos($returnContent, '&gt;') + 4);
		$returnContent = str_replace(array("&lt;", "&gt;", "&quot;", "&amp;", "%3a", "%2f", "%25"), array("<", ">", "\"", "&", ":", "/", "%"), $returnContent);
*/
		$xmlParser = xml_parser_create("UTF-8");
		xml_parser_set_option($xmlParser, XML_OPTION_TARGET_ENCODING, "ISO-8859-1");
		xml_parse_into_struct($xmlParser, $returnContent, $vals, $index);
		xml_parser_free($xmlParser);

		$mediaInfo = Array();

		if (isset($index["DC:TITLE"]) and isset($vals[$index["DC:TITLE"][0]]["value"])) {
			$mediaInfo["title"] = $vals[$index["DC:TITLE"][0]]["value"];
		} else {
			$mediaInfo["title"] = "";
		}
		if (isset($vals[5]["value"])) {
			$mediaInfo["CurrentURI"] = $vals[5]["value"];
		} else {
			$mediaInfo["CurrentURI"] = "";
		}
				if (isset($vals[6]["value"])) {
			$mediaInfo["CurrentURIMetaData"] = $vals[6]["value"];
		} else {
			$mediaInfo["CurrentURIMetaData"] = "";
		}
		return $mediaInfo;

	}

	public function GetPositionInfo()
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 272
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#GetPositionInfo"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:GetPositionInfo xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID></u:GetPositionInfo></s:Body></s:Envelope>';

		$returnContent = $this->sendPacket($content);

		$position = substr($returnContent, stripos($returnContent, "NOT_IMPLEMENTED") - 7, 7);

		$returnContent = substr($returnContent, stripos($returnContent, '&lt;'));
		$returnContent = substr($returnContent, 0, strrpos($returnContent, '&gt;') + 4);
		$returnContent = str_replace(array("&lt;", "&gt;", "&quot;", "&amp;", "%3a", "%2f", "%25"), array("<", ">", "\"", "&", ":", "/", "%"), $returnContent);


		$xmlParser = xml_parser_create("UTF-8");
		xml_parser_set_option($xmlParser, XML_OPTION_TARGET_ENCODING, "UTF-8");
		xml_parse_into_struct($xmlParser, $returnContent, $vals, $index);
		xml_parser_free($xmlParser);

		$positionInfo = Array ();

		$positionInfo["position"] = $position;
		$positionInfo["RelTime"] = $position;


		if (isset($index["RES"]) and isset($vals[$index["RES"][0]]["attributes"]["DURATION"])) {
			$positionInfo["duration"] = $vals[$index["RES"][0]]["attributes"]["DURATION"];
				$positionInfo["TrackDuration"] = $vals[$index["RES"][0]]["attributes"]["DURATION"];
		} else {
			$positionInfo["duration"] = "";
				$positionInfo["TrackDuration"] = "";
		}


		if (isset($index["RES"]) and isset($vals[$index["RES"][0]]["value"])) {
			$positionInfo["URI"] = $vals[$index["RES"][0]]["value"];
			$positionInfo["TrackURI"] = $vals[$index["RES"][0]]["value"];
		} else {
			$positionInfo["URI"] = "";
			$positionInfo["TrackURI"] = "";
		}

		if (isset($index["DC:CREATOR"]) and isset($vals[$index["DC:CREATOR"][0]]["value"])) {
			$positionInfo["artist"] = $vals[$index["DC:CREATOR"][0]]["value"];
		} else {
			$positionInfo["artist"] = "";
		}

		if (isset($index["DC:TITLE"]) and isset($vals[$index["DC:TITLE"][0]]["value"])) {
			$positionInfo["title"] = $vals[$index["DC:TITLE"][0]]["value"];
		} else {
			$positionInfo["title"] = "";
		}

		if (isset($index["UPNP:ALBUM"]) and isset($vals[$index["UPNP:ALBUM"][0]]["value"])) {
			$positionInfo["album"] = $vals[$index["UPNP:ALBUM"][0]]["value"];
		} else {
			$positionInfo["album"] = "";
		}

		if (isset($index["UPNP:ALBUMARTURI"]) and isset($vals[$index["UPNP:ALBUMARTURI"][0]]["value"])) {
			$positionInfo["albumArtURI"] = "http://" . $this->address . ":1400" . $vals[$index["UPNP:ALBUMARTURI"][0]]["value"];
		} else {
			$positionInfo["albumArtURI"] = "";
		}

		if (isset($index["R:ALBUMARTIST"]) and isset($vals[$index["R:ALBUMARTIST"][0]]["value"])) {
			$positionInfo["albumArtist"] = $vals[$index["R:ALBUMARTIST"][0]]["value"];
		} else {
			$positionInfo["albumArtist"] = "";
		}

		if (isset($index["UPNP:ORIGINALTRACKNUMBER"]) and isset($vals[$index["UPNP:ORIGINALTRACKNUMBER"][0]]["value"])) {
			$positionInfo["albumTrackNumber"] = $vals[$index["UPNP:ORIGINALTRACKNUMBER"][0]]["value"];
		} else {
			$positionInfo["albumTrackNumber"] = "";
		}

		if (isset($index["R:STREAMCONTENT"]) and isset($vals[$index["R:STREAMCONTENT"][0]]["value"])) {
			$positionInfo["streamContent"] = $vals[$index["R:STREAMCONTENT"][0]]["value"];
		} else {
			$positionInfo["streamContent"] = "";
		}
		// added br if this contains "rincon" we are slave to a coordinator mentioned in this field (otherwise path to the file is provided)!
		// implemented via second XMLsendpacket to not break michaels current code


/*		if (isset($index["RES"][0]) and isset($vals[($index["RES"][0])]["value"])) {
			$positionInfo["trackURI"] = $vals[($index["RES"][0])]["value"];

        } else {
*/
			$returnContent = $this->XMLsendPacket($content);

			$xmlParser = xml_parser_create("UTF-8");
			xml_parser_set_option($xmlParser, XML_OPTION_TARGET_ENCODING, "ISO-8859-1");
			xml_parse_into_struct($xmlParser, $returnContent, $vals, $index);
			xml_parser_free($xmlParser);
//	  }

			if (isset($index["TRACKURI"][0]) and isset($vals[($index["TRACKURI"][0])]["value"])) {
			$positionInfo["trackURI"] = $vals[($index["TRACKURI"][0])]["value"];
			$positionInfo["TrackURI"] = $vals[($index["TRACKURI"][0])]["value"];
			} else {
				$positionInfo["trackURI"] = "";
			}

			// Track Number in Playlist
			if (isset($index["TRACK"][0]) and isset($vals[($index["TRACK"][0])]["value"])) {
			$positionInfo["Track"] = $vals[($index["TRACK"][0])]["value"];;

			} else {
				$positionInfo["Track"] = "";
			}



		return $positionInfo;
	}

	public function SetRadio($radio,$Name="IP-Symcon Radio")
	{
	$Metadata="&lt;DIDL-Lite xmlns:dc=&quot;http://purl.org/dc/elements/1.1/&quot; xmlns:upnp=&quot;urn:schemas-upnp-org:metadata-1-0/upnp/&quot; xmlns:r=&quot;urn:schemas-rinconnetworks-com:metadata-1-0/&quot; xmlns=&quot;urn:schemas-upnp-org:metadata-1-0/DIDL-Lite/&quot;&gt;&lt;item id=&quot;R:0/0/0&quot; parentID=&quot;R:0/0&quot; restricted=&quot;true&quot;&gt;&lt;dc:title&gt;".$Name."&lt;/dc:title&gt;&lt;upnp:class&gt;object.item.audioItem.audioBroadcast&lt;/upnp:class&gt;&lt;desc id=&quot;cdudn&quot; nameSpace=&quot;urn:schemas-rinconnetworks-com:metadata-1-0/&quot;&gt;SA_RINCON65031_&lt;/desc&gt;&lt;/item&gt;&lt;/DIDL-Lite&gt;";

 	$this->SetAVTransportURI($radio,$MetaData);

	}

	public function SetAVTransportURI($tspuri,$MetaData="")
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: '.(342+strlen(htmlspecialchars($tspuri))+strlen(htmlspecialchars($MetaData))).'
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#SetAVTransportURI"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:SetAVTransportURI xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID><CurrentURI>'.htmlspecialchars($tspuri).'</CurrentURI><CurrentURIMetaData>'.htmlspecialchars($MetaData).'.</CurrentURIMetaData></u:SetAVTransportURI></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function SetQueue($queue,$MetaData="")
	{
 	$this->SetAVTransportURI($queue,$MetaData);

	}

	public function ClearQueue()
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: 290
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#RemoveAllTracksFromQueue"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:RemoveAllTracksFromQueue xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID></u:RemoveAllTracksFromQueue></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function AddToQueue($file)
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: '.(438+strlen(htmlspecialchars($file))).'
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#AddURIToQueue"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:AddURIToQueue xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID><EnqueuedURI>'.htmlspecialchars($file).'</EnqueuedURI><EnqueuedURIMetaData></EnqueuedURIMetaData><DesiredFirstTrackNumberEnqueued>0</DesiredFirstTrackNumberEnqueued><EnqueueAsNext>1</EnqueueAsNext></u:AddURIToQueue></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function RemoveFromQueue($track)
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: '.(307+strlen($track)).'
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#RemoveTrackFromQueue"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:RemoveTrackFromQueue xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID><ObjectID>Q:0/'.$track.'</ObjectID></u:RemoveTrackFromQueue></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}

	public function SetTrack($track)
	{

$content='POST /MediaRenderer/AVTransport/Control HTTP/1.1
CONNECTION: close
HOST: '.$this->address.':1400
CONTENT-LENGTH: '.(288+strlen($track)).'
CONTENT-TYPE: text/xml; charset="utf-8"
SOAPACTION: "urn:schemas-upnp-org:service:AVTransport:1#Seek"

<s:Envelope xmlns:s="http://schemas.xmlsoap.org/soap/envelope/" s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"><s:Body><u:Seek xmlns:u="urn:schemas-upnp-org:service:AVTransport:1"><InstanceID>0</InstanceID><Unit>TRACK_NR</Unit><Target>'.$track.'</Target></u:Seek></s:Body></s:Envelope>';

		$this->sendPacket($content);
	}
/******************* // urn:schemas-upnp-org:device:MediaServer:1 ***********

***************************************************************************/
/* urn:upnp-org:serviceId:ContentDirectory */
 // MERKERTITLE
    //Gibt ein Array mit den Songs der aktuellen Playlist
    public function GetCurrentPlaylist()
    {
        $header='POST /MediaServer/ContentDirectory/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:ContentDirectory:1#Browse"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400';
$xml='<?xml version="1.0" encoding="utf-8"?>
<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body>
<u:Browse xmlns:u="urn:schemas-upnp-org:service:ContentDirectory:1"><ObjectID>Q:0</ObjectID><BrowseFlag>BrowseDirectChildren</BrowseFlag><Filter></Filter><StartingIndex>0</StartingIndex><RequestedCount>1000</RequestedCount><SortCriteria></SortCriteria></u:Browse>
</s:Body>
</s:Envelope>';
$content=$header . '
Content-Length: '. strlen($xml) .'

'. $xml;

    $returnContent = $this->sendPacket($content);

        $returnContent = substr($returnContent, stripos($returnContent, '&lt;'));
        $returnContent = substr($returnContent, 0, strrpos($returnContent, '&gt;') + 4);
        $returnContent = str_replace(array("&lt;", "&gt;", "&quot;", "&amp;", "%3a", "%2f", "%25"), array("<", ">", "\"", "&", ":", "/", "%"), $returnContent);

        $xml = new SimpleXMLElement($returnContent);
        $liste = array();
        for($i=0,$size=count($xml);$i<$size;$i++)
        {
            $aktrow = $xml->item[$i];
            $albumart = $aktrow->xpath("upnp:albumArtURI");
            $title = $aktrow->xpath("dc:title");
            $artist = $aktrow->xpath("dc:creator");
            $album = $aktrow->xpath("upnp:album");
            $liste[$i]['listid']=$i+1;
            if(isset($albumart[0])){
                $liste[$i]['albumArtURI']="http://" . $this->address . ":1400".(string)$albumart[0];
            }else{
                $liste[$i]['albumArtURI'] ="";
            }
            $liste[$i]['title']=(string)$title[0];
            if(isset($artist[0])){
                $liste[$i]['artist']=(string)$artist[0];
            }else{
                $liste[$i]['artist']="";
            }
            if(isset($album[0])){
                $liste[$i]['album']=(string)$album[0];
            }else{
                $liste[$i]['album']="";
            }
        }
return $liste;
    }



    //Liefert ein Array mit allen Sonos Wiedergabelisten und deren Aufrufinformationen
    public function GetSonosPlaylists()
    {
        $header='POST /MediaServer/ContentDirectory/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:ContentDirectory:1#Browse"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400';
$xml='<?xml version="1.0" encoding="utf-8"?>
<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body>
<u:Browse xmlns:u="urn:schemas-upnp-org:service:ContentDirectory:1"><ObjectID>SQ:</ObjectID><BrowseFlag>BrowseDirectChildren</BrowseFlag><Filter></Filter><StartingIndex>0</StartingIndex><RequestedCount>100</RequestedCount><SortCriteria></SortCriteria></u:Browse>
</s:Body>
</s:Envelope>';
$content=$header . '
Content-Length: '. strlen($xml) .'

'. $xml;

    $returnContent = $this->sendPacket($content);
    $returnContent = substr($returnContent, stripos($returnContent, '&lt;'));
        $returnContent = substr($returnContent, 0, strrpos($returnContent, '&gt;') + 4);
        $returnContent = str_replace(array("&lt;", "&gt;", "&quot;", "&amp;", "%3a", "%2f", "%25"), array("<", ">", "\"", "&", ":", "/", "%"), $returnContent);

        $xml = new SimpleXMLElement($returnContent);
        $liste = array();
        for($i=0,$size=count($xml);$i<$size;$i++)
        {
            $attr = $xml->container[$i]->attributes();
            $liste[$i]['id'] = (string)$attr['id'];
            $title = $xml->container[$i];
            $title = $title->xpath('dc:title');
            $liste[$i]['title'] = (string)$title[0];
            $liste[$i]['typ'] = "Sonos";
            $liste[$i]['file'] = (string)$xml->container[$i]->res;
        }


return $liste;
    }



    //Liefert ein Array mit allen "Importierte Playlisten" Wiedergabelisten und deren Aufrufinformationen
    public function GetImportedPlaylists()
    {
        $header='POST /MediaServer/ContentDirectory/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:ContentDirectory:1#Browse"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400';
$xml='<?xml version="1.0" encoding="utf-8"?>
<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body>
<u:Browse xmlns:u="urn:schemas-upnp-org:service:ContentDirectory:1"><ObjectID>A:PLAYLISTS</ObjectID><BrowseFlag>BrowseDirectChildren</BrowseFlag><Filter></Filter><StartingIndex>0</StartingIndex><RequestedCount>100</RequestedCount><SortCriteria></SortCriteria></u:Browse>
</s:Body>
</s:Envelope>';
$content=$header . '
Content-Length: '. strlen($xml) .'

'. $xml;

    $returnContent = $this->sendPacket($content);
    $returnContent = substr($returnContent, stripos($returnContent, '&lt;'));
        $returnContent = substr($returnContent, 0, strrpos($returnContent, '&gt;') + 4);
       $returnContent = str_replace(array("&lt;", "&gt;", "&quot;", "&amp;", "%3a", "%2f", "%25"), array("<", ">", "\"", "&", ":", "/", "%"), $returnContent);

        $xml = new SimpleXMLElement($returnContent);
        $liste = array();
        for($i=0,$size=count($xml);$i<$size;$i++)
        {
            $attr = $xml->container[$i]->attributes();
            $liste[$i]['id'] = (string)$attr['id'];
            $title = $xml->container[$i];
            $title = $title->xpath('dc:title');
				// br substring use cuts my playlist names at the 4th char

				$liste[$i]['title'] = (string)$title[0];
					$liste[$i]['title']=preg_replace("/^(.+)\.m3u$/i","\\1",$liste[$i]['title']);
            $liste[$i]['typ'] = "Import";
            $liste[$i]['file'] = (string)$xml->container[$i]->res;
        }


return $liste;
    }



    //Gibt ein Array mit den einzelnen Songs der Playlist wieder
    //ObjektID aus GetSonosPlaylists() oder GetImportetPlaylists()
    public function GetPlaylist($value)
    {
        $header='POST /MediaServer/ContentDirectory/Control HTTP/1.1
SOAPACTION: "urn:schemas-upnp-org:service:ContentDirectory:1#Browse"
CONTENT-TYPE: text/xml; charset="utf-8"
HOST: '.$this->address.':1400';
$xml='<?xml version="1.0" encoding="utf-8"?>
<s:Envelope s:encodingStyle="http://schemas.xmlsoap.org/soap/encoding/"
xmlns:s="http://schemas.xmlsoap.org/soap/envelope/">
<s:Body>
<u:Browse xmlns:u="urn:schemas-upnp-org:service:ContentDirectory:1"><ObjectID>'.$value.'</ObjectID><BrowseFlag>BrowseDirectChildren</BrowseFlag><Filter></Filter><StartingIndex>0</StartingIndex><RequestedCount>1000</RequestedCount><SortCriteria></SortCriteria></u:Browse>
</s:Body>
</s:Envelope>';
$content=$header . '
Content-Length: '. strlen($xml) .'

'. $xml;

    $returnContent = $this->sendPacket($content);
    $xmlParser = xml_parser_create();
        $returnContent = substr($returnContent, stripos($returnContent, '&lt;'));
        $returnContent = substr($returnContent, 0, strrpos($returnContent, '&gt;') + 4);
        $returnContent = str_replace(array("&lt;", "&gt;", "&quot;", "&amp;", "%3a", "%2f", "%25"), array("<", ">", "\"", "&", ":", "/", "%"), $returnContent);

        $xml = new SimpleXMLElement($returnContent);
        $liste = array();
        for($i=0,$size=count($xml);$i<$size;$i++)
        {
            $aktrow = $xml->item[$i];
            $albumart = $aktrow->xpath("upnp:albumArtURI");
            $title = $aktrow->xpath("dc:title");
            $artist = $aktrow->xpath("dc:creator");
            $album = $aktrow->xpath("upnp:album");
            $liste[$i]['listid']=$i+1;
            if(isset($albumart[0])){
                $liste[$i]['albumArtURI']="http://" . $this->address . ":1400".(string)$albumart[0];
            }else{
                $liste[$i]['albumArtURI'] ="";
            }
            $liste[$i]['title']=(string)$title[0];
            if(isset($interpret[0])){
                $liste[$i]['artist']=(string)$artist[0];
            }else{
                $liste[$i]['artist']="";
            }
            if(isset($album[0])){
                $liste[$i]['album']=(string)$album[0];
            }else{
                $liste[$i]['album']="";
            }
        }
return $liste;
    }






/***************************************************************************
				Helper / sendPacket
***************************************************************************/

/*
Unter LocalUID findet sich der String, den man im $sonos->SetQueue Befehl braucht.
Den String kann man z.B. �ber folgendes Codeschnipsel bekommen:
$url = "http://".<ipAdresse des ZonePlayers>.":1400/status/zp";
$xml = simpleXML_load_file($url);
$result = $xml->ZPInfo->LocalUID;
Quelle: http://www.ip-symcon.de/forum/f53/php-sonos-klasse-ansteuern-einzelner-player-7676/#post87054
*/

// do not filter xml answer

	private function XMLsendPacket( $content )
	{
		$fp = fsockopen($this->address, 1400 /* Port */, $errno, $errstr, 10);
		if (!$fp)
		    throw new Exception("Error opening socket: ".$errstr." (".$errno.")");

		fputs ($fp, $content);
		$ret = "";
		$buffer = "";
		while (!feof($fp)) {
			$buffer = fgets($fp,128);
		//	echo "\n;" . $buffer . ";\n"; //DEBUG
			$ret.= $buffer;
		}

//		echo "\n\nReturn:" . $ret . "!!\n";
		fclose($fp);

		if(strpos($ret, "200 OK") === false)
			throw new Exception("Error sending command: ".$ret);
		$array = preg_split("/\n/", $ret);

		return $array[count($array) - 1];
	}



	private function sendPacket( $content )
	{
		$fp = fsockopen($this->address, 1400 /* Port */, $errno, $errstr, 10);
		if (!$fp)
		    throw new Exception("Error opening socket: ".$errstr." (".$errno.")");

		fputs ($fp, $content);
		$ret = "";
		while (!feof($fp)) {
			$ret.= fgetss($fp,128); // filter xml answer
		}
		fclose($fp);

		if(strpos($ret, "200 OK") === false)
   	throw new Exception("Error sending command: ".$ret);

		// echo "sendPacketDebug: "; //DEBUG
		// print_r($ret);

		$array = preg_split("/\n/", $ret);

		return $array[count($array) - 1];
	}

}


?>