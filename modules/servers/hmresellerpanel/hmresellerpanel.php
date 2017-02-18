<?php
	/*
		Created by Host Media Labs and offered freely for public use as open source.
		For more information regarding Host Media Reseller Hosting please visit: https://www.hostmedia.co.uk/reseller-hosting/
	*/

	if (!defined("WHMCS")) {
	    die("This file cannot be accessed directly");
	}

	function hmresellerpanel_MetaData()
	{
	    return array(
	        'DisplayName' => 'HM Reseller Panel',
	        'APIVersion' => '1.0',
	        'RequiresServer' => true
	    );
	}

	define('API_URL', 'https://cp.hostmedia.co.uk/api/');

	function hmresellerpanel_ConfigOptions() {
	    $configarray = array(
			"Package ID" => array( "Type" => "text", "Size" => "55", "Description" => "The UUID for your created package." )
		);
		return $configarray;
	}

	function hmresellerpanel_CreateAccount($params) {
	    $serviceid = $params["serviceid"];
	    $pid = $params["pid"];
	    $producttype = $params["producttype"];
	    $domain = $params["domain"];
		$username = $params["username"];
		$password = $params["password"];
	    $clientsdetails = $params["clientsdetails"];
	    $customfields = $params["customfields"];
	    $configoptions = $params["configoptions"];

	    $configoption1 = $params["configoption1"]; // Package ID

	    $server = $params["server"]; # True if linked to a server
	    $serverid = $params["serverid"];
	    $serverip = $params["serverip"];
	    $serverusername = $params["serverusername"];
	    $serverpassword = $params["serverpassword"];
	    $serveraccesshash = $params["serveraccesshash"];
	    $serversecure = $params["serversecure"]; # If set, SSL Mode is enabled in the server config

		$curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, API_URL.'account/apikey/'. $serveraccesshash .'/format/json');

		$apiparams['username'] = $params["username"];
		$apiparams['password'] = $params["password"];
		$apiparams['domain'] = $params["domain"];
		$apiparams['location'] = $params['customfields']['location']; // Location ID
		$apiparams['packageid'] = $configoption1;

		$paramfields = '';
		foreach($apiparams as $key => $value) { 
		     $paramfields .= $key.'='.$value.'&'; 
		}

		curl_setopt($curl, CURLOPT_POST, count($paramfields));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $paramfields);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 400);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		// Run cURL and check for errors
		$result = curl_exec($curl);
		curl_close($curl);
		$result = json_decode(strip_tags($result), true);

		// If success update IP fields:
		if ( $result['message'] == 'Success' ) {
			$table = "tblhosting";
			$array = array("dedicatedip"=>$result['ip']);
			$where = array("id"=>$serviceid);
			update_query($table,$array,$where);
			$result = "success";
		} else {
			$result = $result['message'].$result['error'];

		}

		return $result;
	}

	function hmresellerpanel_TerminateAccount($params) {
	    $serveraccesshash = $params["serveraccesshash"];

	    $apiparams['domain'] = $params["domain"];
	    $apiparams['id'] = $params["domain"];

		$paramfields = '';
		foreach($apiparams as $key => $value) { 
		     $paramfields .= $key.'='.$value.'&'; 
		}
		print_r($paramfields);
		print_r($serveraccesshash);

	    $curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, API_URL.'account/apikey/'. $serveraccesshash .'/id/'.$params["domain"].'/format/json');

		curl_setopt($curl, CURLOPT_POST, count($paramfields));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $paramfields);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 400);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "delete");
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		$result = curl_exec($curl);

		curl_close($curl);

		$result = json_decode(strip_tags($result), true);

		if ( $result['message'] == 'Success' ) {
			$result = "success";
		} else {
			$result = $result['message'].$result['error'];
		}

		return $result;
	}

	function hmresellerpanel_SuspendAccount($params) {
	    $server = $params["server"]; # True if linked to a server
	    $serverid = $params["serverid"];
	    $serverip = $params["serverip"];
	    $serverusername = $params["serverusername"];
	    $serverpassword = $params["serverpassword"];
	    $serveraccesshash = $params["serveraccesshash"];
	    $serversecure = $params["serversecure"]; # If set, SSL Mode is enabled in the server config

	    $curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, API_URL.'suspend/apikey/'. $serveraccesshash .'/format/json');

		$apiparams['id'] = $params["domain"];
		$paramfields = '';
		foreach($apiparams as $key => $value) { 
		     $paramfields .= $key.'='.$value.'&'; 
		}

		curl_setopt($curl, CURLOPT_POST, count($paramfields));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $paramfields);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 400);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		$result = curl_exec($curl);

		curl_close($curl);
		$result = json_decode(strip_tags($result), true);

		if ( $result['message'] == 'Success' ) {
			$result = "success";
		} else {
			$result = $result['message'].$result['error'];
		}

		return $result;

	}

	function hmresellerpanel_UnsuspendAccount($params) {
	    $server = $params["server"]; # True if linked to a server
	    $serverid = $params["serverid"];
	    $serverip = $params["serverip"];
	    $serverusername = $params["serverusername"];
	    $serverpassword = $params["serverpassword"];
	    $serveraccesshash = $params["serveraccesshash"];
	    $serversecure = $params["serversecure"]; # If set, SSL Mode is enabled in the server config

	    $curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, API_URL.'unsuspend/apikey/'. $serveraccesshash .'/format/json');

		$apiparams['id'] = $params["domain"];
		$paramfields = '';
		foreach($apiparams as $key => $value) { 
		     $paramfields .= $key.'='.$value.'&'; 
		}

		curl_setopt($curl, CURLOPT_POST, count($paramfields));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $paramfields);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 400);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		$result = curl_exec($curl);

		curl_close($curl);
		$result = json_decode(strip_tags($result), true);

		if ( $result['message'] == 'Success' ) {
			$result = "success";
		} else {
			$result = $result['message'].$result['error'];
		}

		return $result;
	}

	function hmresellerpanel_ChangePassword($params) {
	    $server = $params["server"]; # True if linked to a server
	    $serverid = $params["serverid"];
	    $serverip = $params["serverip"];
	    $serverusername = $params["serverusername"];
	    $serverpassword = $params["serverpassword"];
	    $serveraccesshash = $params["serveraccesshash"];
	    $serversecure = $params["serversecure"]; # If set, SSL Mode is enabled in the server config

	    $curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, API_URL.'account/apikey/'. $serveraccesshash .'/format/json');

		$apiparams['id'] = $params["domain"];
		$apiparams['password'] = $params["password"];
		$paramfields = '';
		foreach($apiparams as $key => $value) { 
		     $paramfields .= $key.'='.$value.'&'; 
		}

		curl_setopt($curl, CURLOPT_POST, count($paramfields));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $paramfields);
		curl_setopt($curl, CURLOPT_USERPWD, $serverusername . ":" . $serverpassword);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 400);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		$result = curl_exec($curl);

		curl_close($curl);
		$result = json_decode(strip_tags($result), true);

		if ( $result['message'] == 'Success' ) {
			$result = "success";
		} else {
			$result = $result['message'].$result['error'];
		}

		return $result;
	}

	function hmresellerpanel_ChangePackage($params) {
	    $server = $params["server"]; # True if linked to a server
	    $serverid = $params["serverid"];
	    $serverip = $params["serverip"];
	    $serverusername = $params["serverusername"];
	    $serverpassword = $params["serverpassword"];
	    $serveraccesshash = $params["serveraccesshash"];
	    $serversecure = $params["serversecure"]; # If set, SSL Mode is enabled in the server config

	    $curl = curl_init();
		curl_setopt($curl, CURLOPT_URL, API_URL.'account/apikey/'. $serveraccesshash .'/format/json');

		$apiparams['id'] = $params["domain"];

	    # Product module option settings from ConfigOptions array above
	    $configoption1 = $params["configoption1"];

	    $apiparams['packageid'] = $configoption1;
		
		$paramfields = '';
		foreach($apiparams as $key => $value) { 
		     $paramfields .= $key.'='.$value.'&'; 
		}

		curl_setopt($curl, CURLOPT_POST, count($paramfields));
		curl_setopt($curl, CURLOPT_POSTFIELDS, $paramfields);
		curl_setopt($curl, CURLOPT_HEADER, 0);
		curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($curl, CURLOPT_TIMEOUT, 400);
		curl_setopt($curl, CURLOPT_CUSTOMREQUEST, "POST");
		curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, 0);
		curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, 0);

		$result = curl_exec($curl);

		curl_close($curl);
		$result = json_decode(strip_tags($result), true);

		if ( $result['message'] == 'Success' ) {
			$result = "success";
		} else {
			$result = $result['message'].$result['error'];
		}

		return $result;
	}

	function hmresellerpanel_ClientArea($params) {
		$result = select_query("tblhosting","",array("id"=>$params['serviceid']));
	    $data = mysql_fetch_array($result);
		$code = '<form id="login_form" action="https://'.$data["dedicatedip"].':2083/login/" method="post" target="_blank">
	        <input type="hidden" name="user" value="'.$params["username"].'" />
			<input type="hidden" name="pass" value="'.$params["password"].'" />
	        <div class="controls">
	            <div class="login-btn">
	                <button name="login" type="submit" id="login_submit" tabindex="3">Access Control Panel</button>
	            </div>
	        </div>
	    </form>';
		return $code;
	}

?>