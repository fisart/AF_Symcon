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
			SO_create_sonos_reader_socket("");
			SO_create_sonos_text_parser(" ");
			SO_create_sonos_content_variable("");
			SO_define_sonos_text_parser(" ");
			SO_sonos_content(" ");

			
		}

		public function define_sonos_text_parser()
		{
			global  $text_parser_id,$Var_ID1,$sonos_reader_id;
			$Rule = '[{"Variable":'.$Var_ID1.',"TagTwo":"<MediaServers>","TagOne":"ZPSupportInfo","ParseType":4}]';
			IPS_SetProperty ( $text_parser_id,"Rules", $Rule);
			IPS_ApplyChanges($text_parser_id);
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

 
	


public function sonos_content()
{
	$Sonos_Data = SO_read_sonos_data();


	SO_build_or_fix_sonos_variables($Sonos_Data);
	SO_build_or_fix_sonos_controls($Sonos_Data);
	SO_populate_variables($Sonos_Data);
	SO_create_profile();
	SO_build_or_fix_profile($Sonos_Data);

	return $Sonos_Data;
}

public function build_or_fix_sonos_controls(&$Data)
{
		$cat_id = 16169 /*[Scripte\SONOS\Variables\SONOS_ACTION]*/;

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



public function populate_variables($Sonos_Data)
{
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


public function build_or_fix_sonos_variables(&$Data)
{
	$root_list = IPS_GetObject(34117 /*[Scripte\SONOS\Variables\Player Data]*/)['ChildrenIDs'];
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




public function delete_var($Name,$root)
{

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
  		if ($Action) {IPS_SetVariableCustomAction ( $ID, 38913 /*[Scripte\SONOS\Variables\Variable Ändern]*/ );}
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


?>