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
		
			global $parent_id, $ID_IP ;
			$Sonos_Master_IP = $this->ReadPropertyString("Sonos_Master_IP"); //Liest die Eigenschaft
			$ID_IP = $this->GetIDForIdent("Sonos_Master_IP");
			SetValue($ID_IP, $Sonos_Master_IP); //Beschreibt die Variable
			$parent_id = IPS_GetObject($ID_IP)['ParentID'];
			SO_create_sonos_content_variable("");


			SO_create_sonos_reader_socket("");
			SO_create_sonos_text_parser(" ");
			SO_build_sonos_static_data(" ");
			
		}
 		public function create_sonos_content_variable()
		{
			global $Var_ID1,$parent_id, $ID_IP ;
			$name_content_var = "Sonos_Content";
			$ALL_IDS = IPS_GetChildrenIDs($parent_id);
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
				IPS_SetParent ( $Var_ID1, $parent_id );
			}


		}
   
		public function build_sonos_static_data()
		{

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
				$Rule = '[{"Variable":'.$Var_ID1.',"TagTwo":"<MediaServers>","TagOne":"ZPSupportInfo","ParseType":4}]';
				IPS_SetProperty ( $id,"Rules", $Rule);
				IPS_ApplyChanges($id);
				IPS_SetName ( $id,$parser_name);
				IPS_SetParent ( $id, $parent_id );

			}
			else
			{
     				$id = $InstanzID;
				$Rule = '[{"Variable":'.$Var_ID1.',"TagTwo":"<MediaServers>","TagOne":"ZPSupportInfo","ParseType":4}]';
				IPS_SetProperty ( $id,"Rules", $Rule);
				IPS_ApplyChanges($id);
			}
 			$text_parser_id = $id;

		}

		public function create_sonos_reader_socket()
		{
			global $sonos_reader_id,$ID_IP;
			$socket_name = "Sonos_Reader_Socket" ;
			$ALL_IDS = IPS_GetObjectList ( );
			$ID_IP = 0;
			$InstanzID = 0;
			foreach ($ALL_IDS as $key => $value) 
			{
				if(IPS_GetName($value) ==$socket_name)
				{
					$InstanzID = $value;
				}
			}
			if ($ID_IP == 0)
			{
				return false;
			}
			$Sonos_Master_IP = GetValueString($ID_IP);
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
			$Text = GetValueString(36164 /*[Scripte\SONOS\Static Data\Sonos\Sonos text cutter\Topology]*/);
			// $Text = strip_tags($Text);
			$result = explode("<",$Text);
			//echo $Text;
			//print_r( $result);
			$i = 0;
			foreach ($result as$key => $value)
			{
 				if(stripos($value,"RINCON") > 0)
 				{
					$list[] = get_sonos_details($value);
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
			return $list;
		}




 
 		public function get_sonos_details($value)
 		{
			$List['Master_RINCON'] = substr($value,stripos($value,"RINCON"),24);
			$tmp = substr($value,stripos($value,"http://"),24);
			$start = stripos($tmp,"/") + 2;
			$stop = stripos ($tmp,":1400")- 7;
			$List["IP"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"coordinator"),19);
			$start = stripos($tmp,"'=")+12;
			$stop = stripos ($tmp," ");
			$List["COORD"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"bootseq="),20);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"uuid")-11;
			$List["bootseq"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"1400:"),8);
			$start = stripos($tmp,":")+1;
			$stop = stripos ($tmp,"'")-5;
			$List["GroupNr"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"uuid='"),30);
			$start = stripos($tmp,"='")+2;
			$stop = $start + 18;
			$List["Player_RINCON"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"wirelessmode"),24);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"channel")-16;
			$List["Wireless_Mode"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"channelfreq"),29);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"behindwifi")-15;
			$List["Channel_Freq"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"behindwifiext"),29);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"location")-17;
			$List["Behind_Wifi_Ext"] = substr($tmp,$start,$stop);



			return $List;
		 }

 
	}
?>