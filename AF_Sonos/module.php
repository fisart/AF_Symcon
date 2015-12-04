<?
	class SonosAF extends IPSModule
	{
		public function Create()

		{
			//Never delete this line!
			parent::Create();
			$this->RegisterPropertyString("Sonos_Master_IP", "192.168.0.63");
		}

		public function ApplyChanges()
		{
			//Never delete this line!
			parent::ApplyChanges();

			global $action_ID, $parent_id, $master_IP_id,$player_data_id,$content_var_name_string_id,$Sonos_Data,$list,$var_change_script_id,
					 $content_var_name_string,$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$module_name_string,$master_ip_name_string,
					 $update_script_name_string,$visualisierung_name_string,$command_script_name_string,$Sonos_cat_name,$command_script_id,$Zone_cat_name;

			SO_define_names("");
			$this->RegisterVariableString ("Sonos_Master_IP", "Sonos_Master_IP", "",0); // Erzeugt die Variable
			$Sonos_Master_IP = $this->ReadPropertyString($master_ip_name_string); //Liest die Eigenschaft
			$master_IP_id = $this->GetIDForIdent($master_ip_name_string);
			SetValue($master_IP_id, $Sonos_Master_IP); //Beschreibt die Variable
			$parent_id = IPS_GetObject($master_IP_id)['ParentID'];
			SO_create_sonos_reader_socket($parent_id);
			SO_create_sonos_text_parser($parent_id);
			SO_create_sonos_content_variable($parent_id);
			SO_set_rule_sonos_text_parser($parent_id);
			SO_create_categories($parent_id);
			SO_create_scripts($parent_id);
			SO_create_links($parent_id);

		}

		/**
		* This function will be available automatically after the module is imported with the module control.
		* Using the custom prefix this function will be callable from PHP and JSON-RPC through:
		*
		* SO_RequestInfo($id);
		*
		*/

		public function define_names()
		{
			global
					$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$module_name_string,$master_ip_name_string,$content_var_name_string,
					$update_script_name_string,$event_name_string,$visualisierung_name_string,$command_script_name_string,$Sonos_cat_name,$command_script_id,$Zone_cat_name,
					$var_change_script_name,$content_var_php_class_name_string,	$content_var_php_script_name_string,$group_action_string,$group_add_string,$group_remove_string,
					 $add_var_change_script_name,$remove_var_change_script_name,$add_var_change_script_name_id,$remove_var_change_script_name_id,
					$zone_var_change_script_name,$add_var_change_script_name,$remove_var_change_script_name;

			$action_string 						= "Sonos_Action";
			$volume_string 						= "Volume";
			$mute_string 							= "Mute";
			$player_data_string 					= "Player_Data";
			$sonos_master_string 				= "Sonos_Master";
			$content_var_name_string 			= "Sonos_Content";
			$module_name_string  				= "SonosAF";
			$master_ip_name_string				= "Sonos_Master_IP";
			$update_script_name_string 		= "Sonos_update";
			$event_name_string         		= "Sonos_Content_change";
			$visualisierung_name_string   	= "Visualisierung Link collection";
			$command_script_name_string  	 	= "Sonos_Ansteuerung";
			$Sonos_cat_name               	= "AF SONOS";
			$Zone_cat_name                	= "Zones";
			$var_change_script_name       	= "Change_Var";
			$zone_var_change_script_name      = "Change_Zone_Var";
			$add_var_change_script_name       = "ADD_Zone_Player_Var";
			$remove_var_change_script_name    = "Remove_Zone_Player_Var";

			$content_var_php_class_name_string 	= "sonos_data_with_php_class_name";
			$content_var_php_script_name_string = "Get_Sonos_changes_via_PHP_Classe";
			$group_action_string                ="Group_Action";
			$group_add_string                   ="Group_Add";
			$group_remove_string                ="Group_Remove";


		}


		public function update_sonos_data()
		{
			global $action_ID, $parent_id, $master_IP_id,$player_data_id,$content_var_name_string_id,$Sonos_Data,$list,$var_change_script_id,
					 $content_var_name_string,$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$module_name_string,$master_ip_name_string,
					 $update_script_name_string,$event_name_string,$visualisierung_name_string,$command_script_name_string,$Sonos_cat_name,$command_script_id,$Zone_cat_name,
					 $zone_id,$content_var_php_class_name_string,$sonos_data_via_php_class_id,$content_var_php_script_id,$var_change_script_name,$add_var_change_script_name,$remove_var_change_script_name,
					 $add_var_change_script_name,$remove_var_change_script_name,$add_var_change_script_name_id,$remove_var_change_script_name_id,
					 $content_var_php_script_name_string,$zone_cat_id;

			SO_define_names($parent_id);
			SO_get_static_data($parent_id);
			SO_create_categories($parent_id);
			SO_read_sonos_data($parent_id);
			SO_build_or_fix_sonos_variables($parent_id,"");
			SO_populate_variables($parent_id,"");
			SO_create_profile($parent_id);
			SO_build_or_fix_profile($parent_id,"");
			SO_build_or_fix_sonos_controls($parent_id,"");
			SO_build_action_events($parent_id);
         SO_create_categories_zone_master($parent_id);
//         SO_create_links($parent_id);
//			SO_sonos_content( $parent_id);
	   }


public function get_static_data()
{
			global $action_ID, $parent_id, $master_IP_id,$player_data_id,$content_var_name_string_id,$Sonos_Data,$list,$var_change_script_id,
					 $content_var_name_string,$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$module_name_string,$master_ip_name_string,
					 $update_script_name_string,$event_name_string,$visualisierung_name_string,$command_script_name_string,$Sonos_cat_name,$command_script_id,$Zone_cat_name,
					 $zone_id,$content_var_php_class_name_string,$sonos_data_via_php_class_id,$content_var_php_script_id,$var_change_script_name,
					 $add_var_change_script_name,$remove_var_change_script_name,$add_var_change_script_name_id,$remove_var_change_script_name_id,
					 $content_var_php_script_name_string,$Sonos_Master_id,$zone_var_change_script_name,$zone_var_change_script_id,$zone_cat_id;

			$ALL_IDS = IPS_GetObjectList ( );
			$content_var_name_string_id = 0;
			foreach ($ALL_IDS as $key => $value)
			{
				if(IPS_GetName($value) == $content_var_name_string)
				{
					$content_var_name_string_id = $value;
				}
				elseif(IPS_GetName($value) == $action_string)
				{
					$action_ID = $value;
				}
				elseif(IPS_GetName($value) == $player_data_string)
				{
					$player_data_id= $value;
				}
				elseif(IPS_GetName($value) == $module_name_string)
				{
					$parent_id = $value;
				}
				elseif(IPS_GetName($value) == $master_ip_name_string)
				{
					$master_IP_id = $value;
				}
				elseif(IPS_GetName($value) == $command_script_name_string)
				{
					$command_script_id = $value;
				}
				elseif(IPS_GetName($value) == $Zone_cat_name)
				{
					$zone_cat_id = $value;
				}
				elseif(IPS_GetName($value) == $var_change_script_name)
				{
					$var_change_script_id = $value;
				}
				elseif(IPS_GetName($value) == $add_var_change_script_name)
				{
					$add_var_change_script_name_id = $value;
				}
				elseif(IPS_GetName($value) == $remove_var_change_script_name)
				{
					$remove_var_change_script_name_id = $value;
				}
				elseif(IPS_GetName($value) == $content_var_php_class_name_string)
				{
					$sonos_data_via_php_class_id = $value;
				}
				elseif(IPS_GetName($value) == $content_var_php_script_name_string)
				{
					$content_var_php_script_id = $value;
				}
				elseif(IPS_GetName($value) == $sonos_master_string)
				{
					$Sonos_Master_id = $value;
				}
				elseif(IPS_GetName($value) == $zone_var_change_script_name)
				{
					$zone_var_change_script_id = $value;
				}

				else
				{
				}
			}



}


public function create_zone_member_profiles()

{
	Global 	$zone_cat_id;


	$Color = [	0x15EB4A,//0 Grün
					0xF21344,//1 Rot
					0x1833DE,//2 Blau
					0xE8DA10,//3 Gelb
					0xF21BB9,//4 Violet
					0x1BCEF2,//5 Türkis
					0x1BF2C0,//6 Mint
					0x1A694C,//7 Dunkelgrün
					0xF2981B,//8 Orange
					0x48508A,//9 Purpur
					0x912A41,//10 Dunkelrot
					0x15EB4A,//11 Gift Grün
					0xF21344,//12 Kamin Rot
					0x1833DE,//13 Kobalt Blau
				 	0xA1EFB4,//14 Light Mint
					0xFFA07A,//15 Ocker
					0x808080,//16 Grau
					0x383C42,//17 Schwarz
					0xee2edd,//18 Leucht Violett
					0xFFF200,//19 Leucht Gelb
					0xe34444,//20 Ocker Rot
					0xfaeefb //21 Weiß
				];

	$free_players = "Add_Player_to_this_Zone";
	$free_players_list[] = NULL;
	$zones = IPS_GetObject($zone_cat_id)['ChildrenIDs'];
	if(IPS_VariableProfileExists ($free_players))
	{
	   IPS_DeleteVariableProfile($free_players);
	}
	IPS_CreateVariableProfile( 	$free_players, 1 );
	$iii = 0;
	foreach($zones as $key => $value )
	{
   	$zone_name = IPS_GetName($value);
   	$zone_name_profil = str_replace (" " , "_" , 	$zone_name );
   	$zone_member_var_ids = SO_find_zone_members(1,$zone_name);
		$zone_member_profile_name = "Remove_Player_from_this_Zone_".$zone_name_profil;
		if(IPS_VariableProfileExists ($zone_member_profile_name))
		{
	   	IPS_DeleteVariableProfile($zone_member_profile_name);
		}
		IPS_CreateVariableProfile( 	$zone_member_profile_name, 1 );

		$ii = 0;
		if(count($zone_member_var_ids) == 1)
		{
      		IPS_SetVariableProfileAssociation ($free_players,$iii,IPS_GetName($zone_member_var_ids[0]),"",  $Color[$iii]);
            $free_players_list[$iii] = IPS_GetName($zone_member_var_ids[0]);
      		$iii++;
		}
		foreach($zone_member_var_ids as $i)
		{
			if(IPS_GetName($i) != $zone_name)
			{
      		IPS_SetVariableProfileAssociation ($zone_member_profile_name,$ii,IPS_GetName($i),"",  $Color[$ii]);
   			$ii++;
			}
		}
	}
	return $free_players_list;
}



public function switch_zone_mute($zone,$status)
{
	global $parent_id,$Sonos_Data;
 	SO_read_sonos_php_data($parent_id);
  	$members_id = SO_find_zone_members($parent_id,$zone);
	foreach($members_id as $key1  => $id ) // Looped durch SONOS Array
	{
		$ii = 0;
      foreach($Sonos_Data as $key2)
      {
   		if($Sonos_Data[$ii]["Name"] == IPS_GetObject($id)['ObjectName'] )
			{
				$sonos = new PHPSonos($Sonos_Data[$ii]["IP"]); //Sonos ZP IPAdresse
			   $sonos->SetMute($status);
			}

			$ii++;

      }
	}
}


public function status_zone_mute($zone)
{
	global $parent_id,$Sonos_Data;
 	SO_read_sonos_php_data($parent_id);
  	$members_id = SO_find_zone_members($parent_id,$zone);
   $mute = true;
	foreach($members_id as $key1  => $id ) // Looped durch SONOS Array
	{
		$ii = 0;
      foreach($Sonos_Data as $key2)
      {
   		if($Sonos_Data[$ii]["Name"] == IPS_GetObject($id)['ObjectName'] )
			{
				$sonos = new PHPSonos($Sonos_Data[$ii]["IP"]); //Sonos ZP IPAdresse
			   if ($sonos->GetMute() == true)
			   {

			   }
			   else
			   {
			   	$mute = false;
			   }
			}

			$ii++;

      }
	}
	return $mute;
}

public function find_zone_members($zone)
{
	global $Sonos_Master_id;

	$zone_members[] = NULL;
	$i = 0;
	foreach(IPS_GetObject($Sonos_Master_id)['ChildrenIDs'] as $key => $id)
	{
		$var_content = GetValueInteger($id);
		$profile_name = IPS_GetVariable ($id)["VariableCustomProfile"];
		$master = IPS_GetVariableProfile($profile_name)['Associations'][$var_content]["Name"];
		if ($master == $zone)
		{
		   $zone_members[$i] = $id;
		   $i++;
		}
	}
	return $zone_members;


}



public  function read_sonos_php_data()
{
global $content_var_name_string_id,$sonos_data_with_php_class_name,$sonos_data_via_php_class_id,$parent_id;

			SO_define_names($parent_id);
			SO_get_static_data($parent_id);
			$Text = GetValueString($content_var_name_string_id);
			$result = explode("<",$Text);
			$list[0][0] = NULL;
			$i = 0;
			$sonos_data = NULL;
			foreach ($result as$key => $value)
			{
 				if(stripos($value,"RINCON") > 0)
 				{
					$list[$i] = SO_get_sonos_details(1,$value);
					$sonos = new PHPSonos($list[$i]['IP']); //Sonos ZP IPAdresse
					$list[$i]['Volume'] = $sonos->GetVolume();
					$list[$i]['Mute'] = $sonos->GetMute();
					$ZoneAttributes = $sonos->GetZoneAttributes();
					$list[$i]['Name'] = $ZoneAttributes['CurrentZoneName'];

					$status = $sonos->GetTransportInfo(); // gibt den aktuellen Status
	            $list[$i]['Status'] = $status;
							// des Sonos-Players als Integer zurück, 1: PLAYING, 2: PAUSED, 3: STOPPED
							// status as integer; see above


					$sonos_data = $sonos_data." IP ".$list[$i]['IP']." V ".$list[$i]['Volume']." M ".$list[$i]['Mute']." N ".$list[$i]['Name']." S ".$list[$i]['Status'];
//					print_r ($sonos->GetZoneInfo());
//					print_r ($sonos->GetMediaInfo());
//             print_r ($sonos->GetCurrentPlaylist());
					$i = $i+1;
 				}
 				else
 				{
 				}
			}

			$alt = GetValueString($sonos_data_via_php_class_id);
//			echo " A ".$alt." SD ".$alt." ";
			SetValueString($sonos_data_via_php_class_id,$sonos_data);
			if (strcmp($alt,$sonos_data) != 0)
			{
				SO_update_sonos_data(1);
			}

		}

public function build_action_events()

{
			global $action_ID, $parent_id, $master_IP_id,$player_data_id,$content_var_name_string_id,$Sonos_Data,$list,$var_change_script_id,
					 $content_var_name_string,$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$module_name_string,$master_ip_name_string,
					 $update_script_name_string,$event_name_string,$visualisierung_name_string,$command_script_name_string,$Sonos_cat_name,$command_script_id;

					$list_event_names[] = NULL;
					foreach(IPS_GetObject( $command_script_id)['ChildrenIDs'] as $key => $id)
					{
                  	$list_event_names[$key] = IPS_GetObject($id)['ObjectName'];
					}
					foreach(IPS_GetObject($action_ID)['ChildrenIDs'] as $key1 => $id1)
					{

						if (!in_array(IPS_GetObject($id1)['ObjectName'],$list_event_names))
						{
					 		$event_id = IPS_CreateEvent (0);

							IPS_SetName($event_id , IPS_GetObject($id1)['ObjectName']);
							IPS_SetParent( $event_id, $command_script_id);
 							IPS_SetEventTrigger ($event_id,0,$id1);
 							IPS_SetEventActive ( $event_id, true );
						}

					}
}



		public function create_scripts()
		{
		global   $action_ID, $parent_id, $master_IP_id,$player_data_id,$content_var_name_string_id,$Sonos_Data,$list,$var_change_script_id,$zone_var_change_script_id,
					$content_var_name_string,$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$module_name_string,$master_ip_name_string,
					$update_script_name_string,$event_name_string,$command_script_name_string,$command_script_id,$var_change_script_name,$remove_var_change_script_name_id,
					 $add_var_change_script_name,$remove_var_change_script_name,$add_var_change_script_name_id,$remove_var_change_script_name_id,
					$script0,$script1,$script2,$script3,$script4,$script5,$script6,$content_var_php_class_name_string,$sonos_data_via_php_class_id,$content_var_php_script_name_string,$zone_var_change_script_name ;

					if(@IPS_GetObjectIDByName ($update_script_name_string, $parent_id) == false)
					{
						SO_get_script_content($parent_id);
						$script_id = IPS_CreateScript (0);
						IPS_SetName($script_id , $update_script_name_string);
						IPS_SetParent($script_id , $parent_id);
						IPS_SetScriptContent($script_id,$script0);
// 						$event_id = IPS_CreateEvent (0);
						$event_id = IPS_CreateEvent (1);
						IPS_SetName($event_id , $update_script_name_string);
						IPS_SetParent( $event_id, $script_id);
 //						IPS_SetEventTrigger ($event_id,1,$content_var_name_string_id);
 //						IPS_SetEventActive ( $event_id, true );
						IPS_SetEventCyclic($event_id, 0 , 0 , 0, 0, 1,10);
						IPS_SetEventActive($event_id, true);

					}

					if(@IPS_GetObjectIDByName ($command_script_name_string, $parent_id )== false)
					{
 						$command_script_id = IPS_CreateScript (0);
						IPS_SetName($command_script_id ,$command_script_name_string);
						IPS_SetParent($command_script_id , $parent_id);
						IPS_SetScriptContent($command_script_id,$script1);
					}				


					if(@IPS_GetObjectIDByName ($var_change_script_name, $parent_id )== false)
					{
						$var_change_script_id = IPS_CreateScript (0);
						IPS_SetName($var_change_script_id ,$var_change_script_name);
						IPS_SetParent($var_change_script_id, $parent_id);
						IPS_SetScriptContent($var_change_script_id,$script2);
					}
					if(@IPS_GetObjectIDByName ($add_var_change_script_name, $parent_id )== false)
					{
						$add_var_change_script_name_id = IPS_CreateScript (0);
						IPS_SetName($add_var_change_script_name_id ,$add_var_change_script_name);
						IPS_SetParent($add_var_change_script_name_id, $parent_id);
						IPS_SetScriptContent($add_var_change_script_name_id,$script5);
					}
					if(@IPS_GetObjectIDByName ($remove_var_change_script_name, $parent_id )== false)
					{
						$remove_var_change_script_name_id = IPS_CreateScript (0);
						IPS_SetName($remove_var_change_script_name_id ,$remove_var_change_script_name);
						IPS_SetParent($remove_var_change_script_name_id, $parent_id);
						IPS_SetScriptContent($remove_var_change_script_name_id,$script6);
					}
					if(@IPS_GetObjectIDByName ($zone_var_change_script_name, $parent_id )== false)
					{
						$zone_var_change_script_id = IPS_CreateScript (0);
						IPS_SetName($zone_var_change_script_id ,$zone_var_change_script_name);
						IPS_SetParent($zone_var_change_script_id, $parent_id);
						IPS_SetScriptContent($zone_var_change_script_id,$script4);
					}
					if(@IPS_GetObjectIDByName ($content_var_php_script_name_string, $parent_id )== false)
					{
						$content_var_php_script_id= IPS_CreateScript (0);
						IPS_SetName($content_var_php_script_id ,$content_var_php_script_name_string);
						IPS_SetParent($content_var_php_script_id, $parent_id);
						IPS_SetScriptContent($content_var_php_script_id,$script3);
						$sonos_data_via_php_class_id = IPS_CreateVariable (3);
						IPS_SetName($sonos_data_via_php_class_id ,$content_var_php_class_name_string);
						IPS_SetParent($sonos_data_via_php_class_id, $content_var_php_script_id);
						$eid = IPS_CreateEvent(1);
						IPS_SetParent($eid,$content_var_php_script_id);
						IPS_SetName($eid ," Poll SONOS PHP");
						IPS_SetEventCyclic($eid, 0 , 0 , 0, 0, 1,10);
						IPS_SetEventActive($eid, false);

					}

	}


		public function sonos_content()
		{
			global $action_ID, $parent_id, $master_IP_id,$player_data_id,$content_var_name_string_id,$Sonos_Data,$list,$var_change_script_id ;
//			print_r($Sonos_Data);

			return $Sonos_Data;
		}


		function build_or_fix_sonos_variables()
		{
			global $player_data_id,$Sonos_Data,$parent_id,$action_ID;

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
				foreach($Sonos_Data as $z) // Looped durch SONOS Array
				{
					if(in_array ($Sonos_Data[$i]['Name'],$Var_Names )) //Name bereits vorhanden
					{
			 			$Sonos_Data[$i][IPS_GetObject($cat_id)['ObjectName']."_ID"] = $Var_ID[array_search($Sonos_Data[$i]['Name'], $Var_Names)];
					}
					else
					{																														//$Name,$Root,$Type,$Profile,$switch)
						$Sonos_Data[$i][IPS_GetObject ($cat_id)['ObjectName']."_ID"] = SO_create_variables($parent_id,$Sonos_Data[$i]['Name'],$cat_id,1,IPS_GetObject($cat_id)['ObjectName']);

					}
					$i++;
				}
			}
		}


   public function create_categories_zone_master()
   {
		global 	$parent_id,$action_ID, $player_data_id,$Mute_id,$Volume_id,$Sonos_Master_id ,$Sonos_Data,
					$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$visualisierung_name_string,$Zone_cat_name,$zone_id,
					 $add_var_change_script_name,$remove_var_change_script_name,$add_var_change_script_name_id,$remove_var_change_script_name_id,
					$group_action_string,$var_change_script_id,$zone_var_change_script_id,$zone_cat_id;

		if (IPS_SemaphoreEnter("Create_ZM_Cats", 1000))
		{
			$master_list_var_ids = IPS_GetChildrenIDs($Sonos_Master_id);
			$sonos_zone_names[] = NULL; //SONOS Zonen
			$existing_zone_cat_name[] = NULL;
         foreach ($master_list_var_ids as $key => $master_id)
			{
			   $sonos_zone_names[$key] = IPS_GetVariableProfile($sonos_master_string)['Associations'][GetValueInteger($master_id)]['Name'];
			}
         $sonos_zone_names = array_unique ( $sonos_zone_names );//SONOS Zonen (immer nur einmal) feststellen
			$existing_zones_cat_ids = IPS_GetChildrenIDs($zone_cat_id); // Feststellen welche Zonenkategorien bereits existieren
         foreach ($existing_zones_cat_ids as $key => $single_zone_cat_id)
			{
				$existing_zone_cat_name[$key] = IPS_GetName($single_zone_cat_id);
				if(in_array ($existing_zone_cat_name[$key] , $sonos_zone_names )) //Zonen Cat Name ist bereits vorhanden und wird auch zukünftig benötigt
				{
					$existing_variable_ids = IPS_GetChildrenIDs($single_zone_cat_id);// Status der exisitierenden Zonen aktualisieren
					foreach($Sonos_Data as $i => $x)// Loop alle Player
					{
						if($Sonos_Data[$i]['Name'] == $existing_zone_cat_name[$key] ) // Diese Zone gibt es bereits
						{
							$Player_IP = $Sonos_Data[$i]['IP'];
         				foreach ($existing_variable_ids as $key0 => $value0) //Update Profil der Variablen unterhalb der existierenden Zonen
							{
								if(IPS_GetName($value0) == "Group_Action")
								{
									$profile = SO_find_zone_profile($parent_id,$Player_IP,$Sonos_Data[$i]['Name']);
  									IPS_SetVariableCustomProfile ( $value0, $profile);
								}
							}
						}
						else
						{
						}
					}
				}
				else // Der Zonen Cat Name ist in SONOS nicht mehr vorhanden und kann gelöscht werden
				{
					// Zone Cat is no more needed, delete
						$existing_variable_ids = IPS_GetChildrenIDs($single_zone_cat_id);

         			foreach (	$existing_variable_ids as $key1 => $value1) //Eventuelle Variablen unterhalb des weggefallenen Zonennamen löschen
						{
								IPS_DeleteVariable($value1);
						}
						IPS_DeleteCategory($single_zone_cat_id);
				}

			}
			// Jetzt noch Kategorien anlegen für neu hinzugekommene SONOS Zonen
			$zone_cats_to_create = array_diff ($sonos_zone_names,	$existing_zone_cat_name );//Feststellen welche Zonen hinzugekommen sind
			$zone_cats_to_create = array_unique($zone_cats_to_create);
         foreach ($zone_cats_to_create as $key2 => $value2) //Loop all new Zone Names to create
			{
				if($value2 != "")  //Exclude empty Names
				{
					$zone_name_id = IPS_CreateCategory();       // Kategorie anlegen
					IPS_SetName($zone_name_id,$value2 ); // Kategorie benennen
					IPS_SetParent($zone_name_id, $zone_cat_id);
					foreach($Sonos_Data as $key3 => $value3)  //Loop through the Sonos Data Array
					{
						if($Sonos_Data[$key3]['Name'] == $value2 ) // Find the new zone player
						{
							$Player_IP = $Sonos_Data[$key3]['IP'];
                 		$profile = SO_find_zone_profile($parent_id,$Player_IP,$value2); // find the group status of the zone and create profile
						}
						else
						{
						}
					}
					SO_create_variables_with_action($parent_id,"Group_Action",$zone_name_id,1,$profile,$zone_var_change_script_id); // create the variable to control the zone
				}
			}
			$free_player_list = SO_create_zone_member_profiles($parent_id);
			$zones = IPS_GetObject($zone_cat_id)['ChildrenIDs']; // IDS Zone Categories

			foreach($zones as $key => $single_zone_cat_id)// Loop IDS Zone Categories
			{
			   $zone_name = IPS_GetName($single_zone_cat_id);
   			$zone_name_profil = str_replace (" " , "_" , 	$zone_name );
   			$zone_member_var_ids = SO_find_zone_members($parent_id,$zone_name);
				$zone_member_profile_name = "Remove_Player_from_this_Zone_".$zone_name_profil;
				$var_id = @IPS_GetVariableIDByName ( $zone_member_profile_name, $single_zone_cat_id );// Variablen Name = Profilname
				if($var_id == 0)
				{
               if(!in_array ( $zone_name , $free_player_list ))
               {
						SO_create_variables_with_action($parent_id,$zone_member_profile_name,$single_zone_cat_id,1,$zone_member_profile_name,$remove_var_change_script_name_id); // create the variable to control the zone
					}
					else
					{
					}
				}
				else
				{
              	if(!in_array ( $zone_name , $free_player_list ))
               {
				     	IPS_SetVariableCustomProfile ( $var_id, $zone_member_profile_name);
					}
					else
					{
						IPS_DeleteVariable($var_id );
					}

				}
				$var_id = @IPS_GetVariableIDByName ("Add_Player_to_this_Zone", $single_zone_cat_id);// Variablen Name = Profilname
				if($var_id ==0)//Keine Variable gefunden
				{
echo " A ";
SetValueString(46364," A ");
              if(in_array ( $zone_name , $free_player_list )) //Es handelt sich um einen einzigen PLayer in der Zone : Zone == Player da nur einzelne player in der Free player list stehen
               {
						$adjusted_profile = SO_adjust_profile($parent_id,$zone_name,"Add_Player_to_this_Zone"); // der einzelne Player darf nicht in der Liste der verfügbaren player stehen
						SO_create_variables_with_action($parent_id,"Add_Player_to_this_Zone",$single_zone_cat_id,1,$adjusted_profile,$add_var_change_script_name_id); // create the variable to control the zone
					}
					else // Es gibt mehr als einen Player in der Zone da nur Player die auch Zone sind (Free Player) in der free_player_list stehen
					{//($Name,$Root,$Type,$Profile,$var_change_script_id)
						SO_create_variables_with_action($parent_id,"Add_Player_to_this_Zone",$single_zone_cat_id,1,"Add_Player_to_this_Zone",$add_var_change_script_name_id); // create the variable to control the zone
					}
				}
				else // Die Variable existiert
				{
 echo " B ";
 SetValueString(46364," B ");
             if(!in_array ( $zone_name , $free_player_list )) //Es gibt mehr als einen Player in der Zone da einzlene Player in der free_player_list stehen
               {
//				     	IPS_DeleteVariable($var_id );
					}
					else// der einzelne Player darf nicht in der Liste der verfügbaren player stehen
					{//($Name,$Root,$Type,$Profile,$var_change_script_id)
						$profile = SO_adjust_profile($parent_id,$zone_name,"Add_Player_to_this_Zone");
				     	IPS_SetVariableCustomProfile ( $var_id, 	$profile);
					}
				}

			}
			IPS_SemaphoreLeave("Create_ZM_Cats");
  		}
  		else
  		{
  		}

   }



public function adjust_profile($zone_name,$old_profile_name)
{

	$newprofile = $zone_name."_Single_Player";
	$newprofile = str_replace (" " , "_" ,$newprofile  );
	$associations = IPS_GetVariableProfile ($old_profile_name)["Associations"];
	if(IPS_VariableProfileExists($newprofile))
	{
		IPS_DeleteVariableProfile ($newprofile);
	}
	IPS_CreateVariableProfile ($newprofile, 1 );
	$i = 0;
	foreach($associations  as $key => $value)
	{
		if($value['Name'] != $zone_name)
		{
			IPS_SetVariableProfileAssociation ($newprofile,$i,$value['Name'],$value['Icon'],$value['Color']);
			$i++;
		}
	}

	return $newprofile;
}

				public function find_zone_profile($player,$zone)
				{

							global $group_action_string,$parent_id;
                     $sonos = new PHPSonos($player ); //Sonos ZP IPAdresse
							$status = $sonos->GetTransportInfo(); // gibt den aktuellen Status
							$mute = SO_status_zone_mute($parent_id,$zone);
							// des Sonos-Players als Integer zurück, 1: PLAYING, 2: PAUSED, 3: STOPPED
							// status as integer; see above

						   if($mute == true)
						   {
						      if($status == 1)
						      {
									$profile = $group_action_string."4"; //Stop + Unmute
						      }
						      else
						      {
									$profile = $group_action_string."3"; //Play + Unmute
						      }
						   }
						   else
						   {
						      if($status == 1)
						      {
									$profile = $group_action_string."2"; //Stop + Mute
						      }
						      else
						      {
									$profile = $group_action_string."1"; //Play + Mute
						      }
						   }
					return $profile;
				}



		public function create_categories()
		{

			global 	$parent_id,$action_ID, $player_data_id,$Mute_id,$Volume_id,$Sonos_Master_id ,$Sonos_Data,
						$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$visualisierung_name_string,$Zone_cat_name,
						$visu_id,$zone_id,$zone_cat_id;


			$ALL_IDS = IPS_GetChildrenIDs($parent_id);
			$action_ID = 0;
			$player_data_id = 0;
			$visu_id = 0;
			$zone_cat_id = 0;
			foreach ($ALL_IDS as $key => $value)
			{
				if(IPS_GetName($value) == $action_string)
				{
					$action_ID = $value;
				}
				elseif(IPS_GetName($value) == $player_data_string)
				{
					$player_data_id = $value;
				}
				elseif(IPS_GetName($value) == $visualisierung_name_string)
				{
					$visu_id  = $value;
				}
				elseif(IPS_GetName($value) == $Zone_cat_name)
				{
					$zone_cat_id  = $value;
				}
			}
			if ($action_ID == 0)
			{
				$action_ID = IPS_CreateCategory();       // Kategorie anlegen
				IPS_SetName($action_ID, $action_string); // Kategorie benennen
				IPS_SetParent($action_ID, $parent_id);
			}
			else
			{
			}

			if ($player_data_id == 0)
			{
				$player_data_id = IPS_CreateCategory();       // Kategorie anlegen
				IPS_SetName($player_data_id,$player_data_string); // Kategorie benennen
				IPS_SetParent($player_data_id, $parent_id);
				$Mute_id = IPS_CreateCategory();       // Kategorie anlegen
				IPS_SetName($Mute_id, $mute_string); // Kategorie benennen
				IPS_SetParent($Mute_id, $player_data_id);
				$Volume_id = IPS_CreateCategory();       // Kategorie anlegen
				IPS_SetName($Volume_id, $volume_string); // Kategorie benennen
				IPS_SetParent($Volume_id,$player_data_id);
				$Sonos_Master_id = IPS_CreateCategory();       // Kategorie anlegen
				IPS_SetName($Sonos_Master_id,$sonos_master_string); // Kategorie benennen
				IPS_SetParent($Sonos_Master_id,$player_data_id);

			}
			else
			{
				$Mute_id = 0;
				$Volume_id = 0;
				$Sonos_Master_id = 0;
				foreach (IPS_GetChildrenIDs($player_data_id)as $key => $value)
				{
					if(IPS_GetName($value) == $mute_string)
					{
						$Mute_id = $value;
					}
					elseif (IPS_GetName($value) == $volume_string)
					{
						$Volume_id = $value;
					}
					elseif(IPS_GetName($value) == $sonos_master_string)
					{
						$Sonos_Master_id = $value;
					}
					else
					{
					}
				}
				if ($Mute_id == 0)
				{
					$Mute_id = IPS_CreateCategory();       // Kategorie anlegen
					IPS_SetName($Mute_id, $mute_string); // Kategorie benennen
					IPS_SetParent($Mute_id, $player_data_id);
				}
				if ($Volume_id == 0)
				{
					$Volume_id = IPS_CreateCategory();       // Kategorie anlegen
					IPS_SetName($Volume_id, $volume_string); // Kategorie benennen
					IPS_SetParent($Volume_id,$player_data_id);
				}
				if ($Sonos_Master_id  == 0)
				{
					$Sonos_Master_id = IPS_CreateCategory();       // Kategorie anlegen
					IPS_SetName($Sonos_Master_id,$sonos_master_string); // Kategorie benennen
					IPS_SetParent($Sonos_Master_id,$player_data_id);
				}
			}
			if ($zone_cat_id ==0)
			{
				$zone_cat_id = IPS_CreateCategory();       // Kategorie anlegen
				IPS_SetName($zone_cat_id, $Zone_cat_name); // Kategorie benennen
				IPS_SetParent($zone_cat_id, $parent_id);
			}
			if ($visu_id == 0)
			{
				$visu_id = IPS_CreateCategory();       // Kategorie anlegen
				IPS_SetName($visu_id, $visualisierung_name_string); // Kategorie benennen
				IPS_SetParent($visu_id, $parent_id);
			}


		}



		public function create_links()
		{
			global 	$parent_id,$action_ID, $player_data_id,$Mute_id,$Volume_id,$Sonos_Master_id ,$Sonos_Data,
						$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$visualisierung_name_string,$Zone_cat_name,
						$zone_id,$visu_id,$zone_cat_id;

					$Diff = " View";
					if(@IPS_GetObjectIDByName (IPS_GetObject ($player_data_id)['ObjectName'].$Diff, $visu_id )== false)
					{
						$LinkID = IPS_CreateLink();             // Link anlegen
						IPS_SetName($LinkID,  IPS_GetObject ($player_data_id)['ObjectName'].$Diff); // Link benennen
						IPS_SetParent($LinkID, $visu_id); // Link einsortieren unter dem Objekt mit der ID "12345"
						IPS_SetLinkTargetID($LinkID, $player_data_id);    // Link verknüpfen
					}
					if(@IPS_GetObjectIDByName (IPS_GetObject ($action_ID)['ObjectName'].$Diff, $visu_id )== false)
					{
						$LinkID = IPS_CreateLink();             // Link anlegen
						IPS_SetName($LinkID,  IPS_GetObject ($action_ID)['ObjectName'].$Diff); // Link benennen
						IPS_SetParent($LinkID, $visu_id); // Link einsortieren unter dem Objekt mit der ID "12345"
						IPS_SetLinkTargetID($LinkID, $action_ID);    // Link verknüpfen
					}
					if(@IPS_GetObjectIDByName (IPS_GetObject ($zone_cat_id)['ObjectName'].$Diff, $visu_id )== false)
					{
						$LinkID = IPS_CreateLink();             // Link anlegen
						IPS_SetName($LinkID,  IPS_GetObject ($zone_cat_id)['ObjectName'].$Diff); // Link benennen
						IPS_SetParent($LinkID, $visu_id); // Link einsortieren unter dem Objekt mit der ID "12345"
						IPS_SetLinkTargetID($LinkID, $zone_cat_id);    // Link verknüpfen
					}


		}




		public function set_rule_sonos_text_parser()
		{

			global  $text_parser_id,$content_var_name_string_id,$sonos_reader_id;
			if(@IPS_DisconnectInstance (  $text_parser_id ))
			{
			}
			else
			{
				$Rule = '[{"Variable":'.$content_var_name_string_id.',"TagTwo":"<MediaServers>","TagOne":"ZPSupportInfo","ParseType":4}]';
				IPS_SetProperty ( $text_parser_id,"Rules", $Rule);
				IPS_ApplyChanges($text_parser_id);
			}
			IPS_ConnectInstance ( $text_parser_id,$sonos_reader_id );

		}


 		public function create_sonos_content_variable()
		{
			global $content_var_name_string_id,$parent_id, $master_IP_id,$text_parser_id,$content_var_name_string ;
			$name_content_var = $content_var_name_string ;//"Sonos_Content";
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
				$content_var_name_string_id = IPS_CreateVariable (3);
				IPS_SetName ( $content_var_name_string_id, $name_content_var );
				IPS_SetParent ( $content_var_name_string_id, $text_parser_id );
			}
			else
			{
				$content_var_name_string_id = $InstanzID;
			}

		}


		public function create_sonos_text_parser()
		{
			global $content_var_name_string_id, $text_parser_id,$parent_id;
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
			global $sonos_reader_id,$master_IP_id;
			$socket_name = "Sonos_Reader_Socket" ;
			$Sonos_Master_IP = GetValueString($master_IP_id);
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
         global $content_var_name_string_id,$Sonos_Data,$parent_id,$value,$name_and_ip;
			$Text = GetValueString($content_var_name_string_id/*[Object #36164 does not exist]*/);
			$result = explode("<",$Text);
			$list[0][0] = NULL;
			$i = 0;
			foreach ($result as$key => $value)
			{
 				if(stripos($value,"RINCON") > 0)
 				{
					$list[$i] = SO_get_sonos_details($parent_id,$value);
					$sonos = new PHPSonos($list[$i]['IP']); //Sonos ZP IPAdresse
					$list[$i]['Volume'] = $sonos->GetVolume();
					$list[$i]['Mute'] = $sonos->GetMute();
					$ZoneAttributes = $sonos->GetZoneAttributes();
					$list[$i]['Name'] = $ZoneAttributes['CurrentZoneName'];
					$name_and_ip[$list[$i]['Name']] = $list[$i]['IP'];
					$i = $i+1;
 				}
 				else
 				{
 				}
			}
			$Sonos_Data = $list;
		}




 		public function get_sonos_details($value)
 		{
			$l['Master_RINCON'] = substr($value,stripos($value,"RINCON"),24);
			$tmp = substr($value,stripos($value,"http://"),24);
			$start = stripos($tmp,"/") + 2;
			$stop = stripos ($tmp,":1400")- 7;
			$l["IP"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"coordinator"),19);
			$start = stripos($tmp,"'=")+12;
			$stop = stripos ($tmp," ");
			$l["COORD"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"bootseq="),20);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"uuid")-11;
			$l["bootseq"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"1400:"),8);
			$start = stripos($tmp,":")+1;
			$stop = stripos ($tmp,"'")-5;
			$l["GroupNr"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"uuid='"),30);
			$start = stripos($tmp,"='")+2;
			$stop = $start + 18;
			$l["Player_RINCON"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"wirelessmode"),24);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"channel")-16;
			$l["Wireless_Mode"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"channelfreq"),29);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"behindwifi")-15;
			$l["Channel_Freq"] = substr($tmp,$start,$stop);
			$tmp = substr($value,stripos($value,"behindwifiext"),29);
			$start = stripos($tmp,"='")+2;
			$stop = stripos ($tmp,"location")-17;
			$l["Behind_Wifi_Ext"] = substr($tmp,$start,$stop);
			return $l;
		 }







public function build_or_fix_sonos_controls()
{

		global $action_ID,$parent_id,$Sonos_Data,$var_change_script_id;

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
//		print_r ($Sonos_Data);
		foreach($Sonos_Data as $z) // Looped durch SONOS Array
		{
			if(in_array ($Sonos_Data[$i]['Name'],$Var_Names )) //Name bereits vorhanden
			{
			 	$Data[$i][IPS_GetObject($cat_id)['ObjectName']] = $Var_ID[array_search($Sonos_Data[$i]['Name'], $Var_Names)];
			}
			else
			{																													//$Name,$Root,$Type,$Profile,$switch)
				$Sonos_Data[$i][IPS_GetObject ($cat_id)['ObjectName']] = SO_create_variables_with_action($parent_id,$Sonos_Data[$i]['Name'],$cat_id,1,IPS_GetObject($cat_id)['ObjectName'],$var_change_script_id);
			}
			$i++;
		}

}



public function populate_variables()
{
  global $Sonos_Data,$parent_id;
  $i = 0;

  foreach($Sonos_Data as $z)
  {
			$group_number[$i] = $Sonos_Data[$i]['GroupNr'];
			$Master_Rincon[$i] = $Sonos_Data[$i]['Master_RINCON'];
			$Player_Rincon[$i] = $Sonos_Data[$i]['Player_RINCON'];
  			SO_populate_mute($parent_id,$Sonos_Data,$i);
  			SO_populate_volume($parent_id,$Sonos_Data,$i);
  			SO_populate_master($parent_id,$Sonos_Data,$i);

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
 					SetValueInteger($Sonos_Data[$i]['Sonos_Master_ID'],$key);
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
		SetValueInteger($Sonos_Data[$i]['Sonos_Master_ID'],$i);
	}
	else
	{

	}
}


public function build_or_fix_profile() //Hier wird das Profil für Sonos_Master definiert
{
	global $Sonos_Data,
	       $content_var_name_string,$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$module_name_string,$master_ip_name_string
			 ,$group_action_string,$group_add_string,$group_remove_string,$Color;

	$key = 0;
	$Color = [	0x15EB4A,//0 Grün
					0xF21344,//1 Rot
					0x1833DE,//2 Blau
					0xE8DA10,//3 Gelb
					0xF21BB9,//4 Violet
					0x1BCEF2,//5 Türkis
					0x1BF2C0,//6 Mint
					0x1A694C,//7 Dunkelgrün
					0xF2981B,//8 Orange
					0x48508A,//9 Purpur
					0x912A41,//10 Dunkelrot
					0x15EB4A,//11 Gift Grün
					0xF21344,//12 Kamin Rot
					0x1833DE,//13 Kobalt Blau
				 	0xA1EFB4,//14 Light Mint
					0xFFA07A,//15 Ocker
					0x808080,//16 Grau
					0x383C42,//17 Schwarz
					0xee2edd,//18 Leucht Violett
					0xFFF200,//19 Leucht Gelb
					0xe34444,//20 Ocker Rot
					0xfaeefb //21 Weiß
				];
	foreach($Sonos_Data as $i)
	{
	 	IPS_SetVariableProfileAssociation ($sonos_master_string,$key,$Sonos_Data[$key]['Name'],"",  $Color[$key]);
	 	$key++;
	}
	 	IPS_SetVariableProfileAssociation ($mute_string,0,"ON","",  $Color[14]);
	 	IPS_SetVariableProfileAssociation ($mute_string,1,"Mute","",  $Color[12]);
	 	IPS_SetVariableProfileAssociation ($mute_string,0,"ON","",  $Color[14]);
	 	IPS_SetVariableProfileAssociation ($volume_string,0,"AUS","",  $Color[12]);
	 	IPS_SetVariableProfileAssociation ($volume_string,1,"Leise","",  $Color[19]);
	 	IPS_SetVariableProfileAssociation ($volume_string,26,"Normal","",  $Color[14]);
	 	IPS_SetVariableProfileAssociation ($volume_string,76,"Laut","",  $Color[18]);
	 	IPS_SetVariableProfileAssociation ($action_string,0,"+5","HollowArrowUp",  $Color[14]);
	 	IPS_SetVariableProfileAssociation ($action_string,1,"-5","HollowArrowDown",  $Color[12]);
//	 	IPS_SetVariableProfileAssociation ($action_string,5,"Make me Master","Network",  $Color[13]);
//	 	IPS_SetVariableProfileAssociation ($action_string,6,"Add me as member","Notebook",  $Color[19]);
//	 	IPS_SetVariableProfileAssociation ($action_string,4,"Remove me as member","Cross",  $Color[18]);
	 	IPS_SetVariableProfileAssociation ($action_string,2,"Mute","Cross",  $Color[5]);
	 	IPS_SetVariableProfileAssociation ($action_string,3,"Unmute","Speaker",  $Color[6]);

	 	IPS_SetVariableProfileAssociation ($group_action_string."1",0,"Play","Speaker",  $Color[14]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."1",1,"Mute","Cross",  $Color[12]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."1",2,"+5","HollowArrowUp",  $Color[7]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."1",3,"-5","HollowArrowDown",  $Color[8]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."2",0,"Stop","Cross",  $Color[18]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."2",1,"Mute","Cross",  $Color[12]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."2",2,"+5","HollowArrowUp",  $Color[7]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."2",3,"-5","HollowArrowDown",  $Color[8]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."3",0,"Play","Speaker",  $Color[14]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."3",1,"Unmute","Speaker",  $Color[11]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."3",2,"+5","HollowArrowUp",  $Color[7]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."3",3,"-5","HollowArrowDown",  $Color[8]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."4",0,"Stop","Cross",  $Color[18]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."4",1,"Unmute","Speaker",  $Color[11]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."4",2,"+5","HollowArrowUp",  $Color[7]);
	 	IPS_SetVariableProfileAssociation ($group_action_string."4",3,"-5","HollowArrowDown",  $Color[8]);


}


public function 	create_profile() //Hier wird das Sonos Master Profil angelegt
{
	global $content_var_name_string,$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$module_name_string,$master_ip_name_string
	,$group_action_string,$group_add_string,$group_remove_string;


	if(!IPS_VariableProfileExists ( $sonos_master_string )) IPS_CreateVariableProfile( $sonos_master_string, 1 );
	if(!IPS_VariableProfileExists ($mute_string ))	IPS_CreateVariableProfile ( $mute_string, 1 );
	if(!IPS_VariableProfileExists ($volume_string ))	IPS_CreateVariableProfile ( $volume_string, 1 );
	if(!IPS_VariableProfileExists ($action_string ))	IPS_CreateVariableProfile ( $action_string, 1 );

	if(!IPS_VariableProfileExists ($group_action_string."1" ))	IPS_CreateVariableProfile ( $group_action_string."1", 1 );
	if(!IPS_VariableProfileExists ($group_action_string."2" ))	IPS_CreateVariableProfile ( $group_action_string."2", 1 );
	if(!IPS_VariableProfileExists ($group_action_string."3" ))	IPS_CreateVariableProfile ( $group_action_string."3", 1 );
	if(!IPS_VariableProfileExists ($group_action_string."4" ))	IPS_CreateVariableProfile ( $group_action_string."4", 1 );


}


public function create_variables_with_action($Name,$Root,$Type,$Profile,$var_change_script_id)
{
//  global $var_change_script_id;
  $ID = @IPS_GetVariableIDByName ( $Name, $Root );
  if ($ID)
  {
  }
  else
  {
  		$ID = IPS_CreateVariable ( $Type );
  		IPS_SetName ( $ID,$Name );
  		IPS_SetParent ( $ID, $Root );
		IPS_SetVariableCustomAction ( $ID, $var_change_script_id );
  		IPS_SetVariableCustomProfile ( $ID, $Profile);
  }

  return $ID;

}


public function create_variables($Name,$Root,$Type,$Profile)
{
  global $var_change_script_id;
  $ID = @IPS_GetVariableIDByName ( $Name, $Root );
  if ($ID)
  {
  }
  else
  {
  		$ID = IPS_CreateVariable ( $Type );
  		IPS_SetName ( $ID,$Name );
  		IPS_SetParent ( $ID, $Root );
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

public function get_script_content()
{
global $script0,$script1,$script2,$script3,$script4,$script5,$script6;

$script0 =
'<?
if (IPS_SemaphoreEnter("SU", 1000))
{
	SO_update_sonos_data(1);
   IPS_SemaphoreLeave("SU");
}
else
{

}

?>';

$script1 =
'<?
global 	$action_ID, $parent_id, $master_IP_id,$player_data_id,$content_var_name_string_id,$Sonos_Data,$list,$var_change_script_id,
			$content_var_name_string,$action_string,$volume_string,$mute_string, $player_data_string,$sonos_master_string,$module_name_string,$master_ip_name_string,
			$update_script_name_string,$event_name_string,$visualisierung_name_string;




SO_update_sonos_data(1);


//$Sonos_Player_ID = 23540 /*[Object #23540 does not exist]*/; // Fehler Wert
$befehl = 100;
$Sonos_Player_ID = 100;

if ($_IPS["SENDER"] == "Variable")
{
 	$event_var = $_IPS["EVENT"];
 	$befehl = $_IPS["VALUE"];
	foreach ($Sonos_Data as $key => $value)
	{
		if($Sonos_Data[$key]["Name"] == IPS_GetObject($_IPS["VARIABLE"])["ObjectName"])
		{
			$Sonos_Player_ID = $key;
//			echo " Echo ".$Sonos_Data[$key]["Name"];
		}
		else
		{
		}
	}
}

//print_r($Sonos_Data);
if (	$Sonos_Player_ID < 100)
{
	switch ($befehl)
	{
    case 0: //+5
				$New_Volume =  increase_volume($Sonos_Player_ID,$Sonos_Data);
       break;
    case 1:  // -5
				$New_Volume =  decrease_volume($Sonos_Player_ID,$Sonos_Data);
        break;
    case 2:  //Make me a master
//				make_me_a_master($Sonos_Player_ID,$Sonos_Data);
        break;
    case 3:  // Add me as a Member
//				add_me_as_a_member($Sonos_Player_ID,$Sonos_Data);
        break;
    case 4:  //  Remove me as a member
//				remove_me_as_a_member($Sonos_Player_ID,$Sonos_Data);
        break;
    case 5:  // Mute
				mute($Sonos_Player_ID,$Sonos_Data);
        break;
    case 6:  //Unmute
				unmute($Sonos_Player_ID,$Sonos_Data);
        break;
	 default: break;
	}
}
else
{

}


function increase_volume($Sonos_Player_ID,$Sonos_Data)
{

	$sonos = new PHPSonos($Sonos_Data[$Sonos_Player_ID]["IP"]); //Sonos ZP IPAdresse
   $Volume_Plus = ($sonos->GetVolume()+5);
   $sonos->SetVolume($Volume_Plus);
	return $sonos->GetVolume();
}




function decrease_volume($Sonos_Player_ID,$Sonos_Data)
{
	$sonos = new PHPSonos($Sonos_Data[$Sonos_Player_ID]["IP"]); //Sonos ZP IPAdresse
   $Volume_Plus = ($sonos->GetVolume()-5);
   $sonos->SetVolume($Volume_Plus);
	return $sonos->GetVolume();

}

function make_me_a_master($Sonos_Player_ID,$Sonos_Data)
{

	$Old_Master = GetValueInteger(27534 /*[Object #27534 does not exist]*/);
	foreach ($Sonos_Data  as $key => $value)
	{
		if ($key != $Old_Master)
		{
			change_player_group($Old_Master,"AddMember",$key,$Sonos_Data);
		}
		else
		{
		}
	}
	foreach ($Sonos_Data  as $key => $value)
	{
		if ($key != $Old_Master)
		{
			change_player_group($Old_Master,"RemoveMember",$key,$Sonos_Data);
		}
		else
		{
			$Sonos_Data[$key]["Master"] = false;
		}
	}
//	SetValueInteger(27534 /*[Object #27534 does not exist]*/,$Sonos_Player_ID);
	$Sonos_Data[$Sonos_Player_ID]["Master"] = true;

}


function add_me_as_a_member($Sonos_Player_ID,$Sonos_Data)
{
	$Master = GetValueInteger(27534 /*[Object #27534 does not exist]*/);
	IPSLogger_Inf(c_LogId, " Master ".$Master." Member ".$Sonos_Player_ID." ");
   if($Sonos_Player_ID != $Master){change_player_group($Master,"AddMember",$Sonos_Player_ID,$Sonos_Data);}
}
function remove_me_as_a_member($Sonos_Player_ID,$Sonos_Data)
{
	      $Master = GetValueInteger(27534 /*[Object #27534 does not exist]*/);
			change_player_group($Master,"RemoveMember",$Sonos_Player_ID,$Sonos_Data);
}
function mute($Sonos_Player_ID,$Sonos_Data)
{
			$sonos = new PHPSonos($Sonos_Data[$Sonos_Player_ID]["IP"]); //Sonos ZP IPAdresse
			$sonos->SetMute(true);
}
function unmute($Sonos_Player_ID,$Sonos_Data)
{
			$sonos = new PHPSonos($Sonos_Data[$Sonos_Player_ID]["IP"]); //Sonos ZP IPAdresse
			$sonos->SetMute(false);

}


?>';

$script2 =


'<?
	 $IPS_SENDER = $_IPS["SENDER"];
    if($IPS_SENDER == "WebFront")
	 {
    	$IPS_SELF = $_IPS["SELF"];
	 	$IPS_VALUE = $_IPS["VALUE"];
	 	$IPS_VARIABLE = $_IPS["VARIABLE"];
    	@SetValue($IPS_VARIABLE , $IPS_VALUE);
//    	@SetValue($IP, $IPS_VALUE); // Variable in Webfront umschalten
   }
   else
   {
   }

?>';




$script3 =

'
<?
if (IPS_SemaphoreEnter("GSCVPC", 1000))
{
	SO_read_sonos_php_data(1);
   IPS_SemaphoreLeave("GSCVPC");
}
else
{

}

?>

';

$script4 =


'<?
	 $IPS_SENDER = $_IPS["SENDER"];
    if($IPS_SENDER == "WebFront")
	 {
    	$IPS_SELF = $_IPS["SELF"];
	 	$IPS_VALUE = $_IPS["VALUE"];
	 	$IPS_VARIABLE = $_IPS["VARIABLE"];
    	@SetValue($IPS_VARIABLE , $IPS_VALUE);
    	$zone =  IPS_GetName(IPS_GetParent ( $IPS_VARIABLE));
    	$profile_name = IPS_GetVariable ($IPS_VARIABLE)["VariableCustomProfile"];
		$status = IPS_GetVariableProfile($profile_name)["Associations"][$IPS_VALUE]["Name"];
		SO_update_sonos_data(1);
//		echo $zone;
      switch ($status)
		{
    		case "Mute":
					SO_switch_zone_mute(1,$zone,true);
        			break;
    		case "Play":
					$sonos = new PHPSonos($name_and_ip[$zone]); //Sonos ZP IPAdresse
					$sonos-> Play();

        			break;

    		case "Unmute":
					SO_switch_zone_mute(1,$zone,false);

        			break;

    		case "Stop";
					$sonos = new PHPSonos($name_and_ip[$zone]); //Sonos ZP IPAdresse
			   	$sonos->Stop();
       			break;
    		case "-5";
					change_zone_volume($zone,-5);
       			break;
    		case "+5";
					change_zone_volume($zone,+5);
					break;
    		default:

        			break;

    	}
   }
   else
   {
   }

function change_zone_volume($zone,$delta)
{
	global $parent_id,$Sonos_Data;
 	SO_read_sonos_php_data($parent_id);
  	$members_id = SO_find_zone_members($parent_id,$zone);
	foreach($members_id as $key1  => $id ) // Looped durch SONOS Array
	{
		$ii = 0;
      foreach($Sonos_Data as $key2)
      {
   		if($Sonos_Data[$ii]["Name"] == IPS_GetObject($id)["ObjectName"] )
			{
				$sonos = new PHPSonos($Sonos_Data[$ii]["IP"]); //Sonos ZP IPAdresse
   			$Sonos_Data[$ii]["Volume"] = ($Sonos_Data[$ii]["Volume"]+$delta);
   			$sonos->SetVolume($Sonos_Data[$ii]["Volume"]);
			}

			$ii++;

      }
	}
}

?>';

$script5 =


'<?
Global $Sonos_Data,$name_and_ip;
	 $IPS_SENDER = $_IPS["SENDER"];
    if($IPS_SENDER == "WebFront")
	 {
    	$IPS_SELF = $_IPS["SELF"];
	 	$IPS_VALUE = $_IPS["VALUE"];
	 	$IPS_VARIABLE = $_IPS["VARIABLE"];
    	@SetValue($IPS_VARIABLE , $IPS_VALUE);
		SO_update_sonos_data(1);
    	$zone =  IPS_GetName(IPS_GetParent ( $IPS_VARIABLE)); //Master Name
    	$profile_name = IPS_GetVariable ($IPS_VARIABLE)["VariableCustomProfile"];
		$player_name = IPS_GetVariableProfile($profile_name)["Associations"][$IPS_VALUE]["Name"];
		$sonosip = $name_and_ip[IPS_GetName(IPS_GetParent ($IPS_VARIABLE))];
		foreach($Sonos_Data as $key => $id)
		{
			if($Sonos_Data[$key]["Name"] == $player_name )
			{
//				$sonosip = $name_and_ip[IPS_GetVariableProfile("Sonos_Master")["Associations"][GetValueInteger($Sonos_Data[$key]["Sonos_Master_ID"])]["Name"]];
            $memberip = $Sonos_Data[$key]["IP"];
            $memberid = $Sonos_Data[$key]["Player_RINCON"];
			}
			if($Sonos_Data[$key]["Name"] == $zone )
			{
				$sonosid = $Sonos_Data[$key]["Player_RINCON"];
			}
		}
        $sonos = new PHPSonos($sonosip); //Sonos ZP IPAdresse
        $AddMember = $sonos->AddMember($memberid);
        $sonos = new PHPSonos($memberip); //Slave Sonos ZP IPAddress
        $ret = $sonos->SetAVTransportURI("x-rincon:" . $sonosid);


	}
?>';
$script6 =


'<?
	 $IPS_SENDER = $_IPS["SENDER"];
    if($IPS_SENDER == "WebFront")
	 {
    	$IPS_SELF = $_IPS["SELF"];
	 	$IPS_VALUE = $_IPS["VALUE"];
	 	$IPS_VARIABLE = $_IPS["VARIABLE"];
    	@SetValue($IPS_VARIABLE , $IPS_VALUE);
    	$zone =  IPS_GetName(IPS_GetParent ( $IPS_VARIABLE));
    	$profile_name = IPS_GetVariable ($IPS_VARIABLE)["VariableCustomProfile"];
		$player_name = IPS_GetVariableProfile($profile_name)["Associations"][$IPS_VALUE]["Name"];
		SO_update_sonos_data(1);
		remove($player_name);
	 }


function remove($player_name)

{
Global $Sonos_Data,$name_and_ip;

		foreach($Sonos_Data as $key => $id)
		{
			if($Sonos_Data[$key]["Name"] == $player_name )
			{
				$sonosip = $name_and_ip[IPS_GetVariableProfile("Sonos_Master")["Associations"][GetValueInteger($Sonos_Data[$key]["Sonos_Master_ID"])]["Name"]];
            $memberip = $Sonos_Data[$key]["IP"];
            $memberid = $Sonos_Data[$key]["Player_RINCON"];

			}
		}

      $sonos = new PHPSonos($sonosip);
      $RemoveMember = $sonos->RemoveMember($memberid);
      $sonos = new PHPSonos($memberip); //Slave Sonos ZP IPAddress
      $sonos->SetAVTransportURI("");

}


?>';

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

//	echo $this->sendPacket($content);

		// set AVtransporturi ist für STOP notwendig
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
	When you switch on a Sonos Device it plays its autoplay Command/Playlist and Settings. It´s all there but currently no GUI setting is possible.
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
// If you don´t set $id you may get duplicate playlists!!!

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
// Abwärtskompatibel zu Paresys Original sein
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
Den String kann man z.B. über folgendes Codeschnipsel bekommen:
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