<?php
//アクセスキーID、シークレットアクセスキー、トラッキングID
$access_key_id = 'AKIAIJIM2Z2YFT36YGRQ';
$secret_access_key = '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';
$associate_id = 'moriofukuda-22';
//必要なパラメータ
$baseurl = 'http://ecs.amazonaws.jp/onca/xml';
$params = array();
$params['Service'] = 'AWSECommerceService';
$params['AWSAccessKeyId'] = $access_key_id;
$params['AssociateTag'] = $associate_id;
$params['Version'] = '2012-04-26';
$params['Timestamp'] = gmdate('Y-m-d\TH:i:s\Z');
$params['Operation'] = 'ItemLookup';
$params['ItemId'] = '4774144371';
//$params['ItemId'] = $ASIN;
$params['ResponseGroup'] = 'Images';
 
//パラメータをキーでソート
ksort($params); $string = '';
foreach ($params as $k => $v) {
	$string .= '&'.rfc3986_urlencode($k).'='.rfc3986_urlencode($v);
}

//最初の&を取り除く
$string = substr($string, 1);
  
// 署名を作成
$parsed_url = parse_url($baseurl);
$str2sign = "GET\n{$parsed_url['host']}\n{$parsed_url['path']}\n{$string}";
$signature = base64_encode(hash_hmac('sha256', $str2sign, $secret_access_key, true));
	 
// URL を作成
$url = $baseurl.'?'.$string.'&Signature='.rfc3986_urlencode($signature);

// XMLのレスポンス
$response = file_get_contents($url);
		 
// XMLをパース
$parsed_xml = null;
if(isset($response)){
	$parsed_xml = simplexml_load_string($response);
}

//取りあえず出力しとく
var_dump((string)$parsed_xml->Items->Item->LargeImage->URL);

// RFC3986エンコード
function rfc3986_urlencode($str) {
	return str_replace('%7E', '~', rawurlencode($str));
}
