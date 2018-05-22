<?php
namespace App\Lib\Tools;
class AliSendSMS 
{
    private $phoneNum;
    private $pinCode;
    
    public static $connectTimeout = 30;//30 second
	public static $readTimeout = 80;//80 second
    
    function  __construct($phone,$pin)
    {
	$this->phoneNum=$phone;
	$this->pinCode = $pin;
    }
    
    public function Send() {
	if(empty($this->phoneNum)){
	    $data['code'] = 200;
	    $data['msg'] = -1;
	    echo json_encode($data);
	    
	}else{
	$apiUrl = "http://dysmsapi.aliyuncs.com/?";
	date_default_timezone_set("Etc/GMT");
	$dateTimeFormat = 'Y-m-d\TH:i:s\Z'; // ISO8601规范  
	$accessKeyId = 'xxxxxxxxx';      // 这里填写您的Access Key ID  
	$accessKeySecret = 'xxxxxxxxx';  // 这里填写您的Access Key Secret  
	$ParamString = "{\"code\":\"" . strval($this->pinCode) . "\"}";
	$data = array(
	    // 公共参数  
	    'SignName' => 'xxxxx',    //这里填写您的短信接口签名名称
	    'Format' => 'JSON',
	    'Version' => '2017-05-25',
	    'AccessKeyId' => $accessKeyId,
	    'SignatureVersion' => '1.0',
	    'SignatureMethod' => 'HMAC-SHA1',
	    'SignatureNonce' => uniqid(),
	    'Timestamp' => date($dateTimeFormat),
	    // 接口参数  
        'Action' => 'SendSms',
	    'TemplateCode' => 'SMS_xxxxxxx',   //这里填写您的短信模版ID
	   'PhoneNumbers'=>$this->phoneNum,
        'TemplateParam'=>$ParamString
	);
	$data['Signature'] = $this->computeSignature($data, $accessKeySecret);

	echo($this->https_request($apiUrl . http_build_query($data)));
	
	    }
       //echo date($dateTimeFormat); 
    }
    
    private function computeSignature($parameters, $accessKeySecret) {
	// 将参数Key按字典顺序排序  
	ksort($parameters);
	// 生成规范化请求字符串  
	$canonicalizedQueryString = '';
	foreach ($parameters as $key => $value) {
	    $canonicalizedQueryString .= '&' . $this->percentEncode($key)
		    . '=' . $this->percentEncode($value);
	}
	// 生成用于计算签名的字符串 stringToSign  
	$stringToSign = 'GET&%2F&' . $this->percentencode(substr($canonicalizedQueryString, 1));
	//echo "<br>".$stringToSign."<br>";  
	// 计算签名，注意accessKeySecret后面要加上字符'&'  
	$signature = base64_encode(hash_hmac('sha1', $stringToSign, $accessKeySecret . '&', true));
	return $signature;
    }

    private function percentEncode($str) {
	// 使用urlencode编码后，将"+","*","%7E"做替换即满足ECS API规定的编码规范  
	$res = urlencode($str);
	$res = preg_replace('/\+/', '%20', $res);
	$res = preg_replace('/\*/', '%2A', $res);
	$res = preg_replace('/%7E/', '~', $res);
	return $res;
    }
    
    public function https_request($url)  
    {  
    $curl = curl_init();  
    curl_setopt($curl, CURLOPT_URL, $url);  
    curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, FALSE);  
    curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, FALSE);  
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);  
    $data = curl_exec($curl);  
    if (curl_errno($curl)) {return 'ERROR '.curl_error($curl);}  
    curl_close($curl);  
    return $data;  
    } 
}

