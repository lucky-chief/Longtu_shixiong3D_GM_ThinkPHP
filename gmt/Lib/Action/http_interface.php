<?php

function send_GM_Command($op,$gm_req,$serverId){
	$server = get_serverdata($serverId);
	if(!$server){
		return ;
	}
	$build = build_request($op,GMTKEY);
	$gm_req = array_merge($build,$gm_req);
	$gm_req['spId'] = '1';
	$gm_req['svrId'] = $serverId;
	return $result = request_admin($gm_req,$server);
}

function get_serverdata($serverId){
	$SER = M("Server");
	$result = $SER->where("id=".$serverId)->select();
	if(!$result)
	{
		return ;
	}
	return 'http://'.$result[0]['gmt_url'].':'.$result[0]['gmt_port'];
}

function build_request($op,$key) {
    $ts   = time();
    $sign = md5($op . md5($ts . $key));

    return array(
        'op' => $op,
        'ts'     => $ts,
        'sign'   => $sign
    );
}

function request_admin($request,$admin_url) {
    // 构造 HTTP POST 请求
    $query = http_build_query($request);
    // 用 curl 发送 HTTP POST 请求
    $curl = curl_init();
    $options = array(
                    CURLOPT_URL => $admin_url,
                    CURLOPT_POST => true,
                    CURLOPT_POSTFIELDS => $query,
                    CURLOPT_HEADER => false,
                    CURLOPT_RETURNTRANSFER => true
                    );
    curl_setopt_array($curl, $options);
    $response = curl_exec($curl);
    // 解析服务器的响应
    if (curl_error($curl)) {
        $msg = sprintf("GMT命令运行失败", admin_url,curl_error($curl));
        return array('result'=>false, 'info'=>$msg);
    }
    else {
        $info = curl_getinfo($curl);
        if ($info['http_code'] == 200) {
            $result = json_decode($response, true);
            if (is_array($result)) {
                return $result;//一个json里面有result，和info
				//return "命令运行成功！";
            }
        }
		echo $info['http_code'];
		echo '222';
        $msg = sprintf("code=%d, response=%s\n", $info['http_code'], $response);
        return array('result'=>false, 'info'=>$msg);
    }	
}
?>