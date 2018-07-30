<?php
namespace App\icbc;
use App\icbc\IcbcConstants;
use App\icbc\IcbcCa;
use App\icbc\RSA;
use \Exception;
/*include_once 'IcbcConstants.php';
include_once 'IcbcCa.php';
include_once 'RSA.php';*/
	class IcbcSignature{
		public static function sign($strToSign, $signType, $privateKey, $charset,$password){

			if (IcbcConstants::$SIGN_TYPE_CA == $signType){

				return IcbcCa::sign($strToSign,$privateKey,$password);

			}elseif (IcbcConstants::$SIGN_TYPE_RSA == $signType) {

				return RSA::sign($strToSign,$privateKey,IcbcConstants::$SIGN_SHA1RSA_ALGORITHMS);

			}elseif (IcbcConstants::$SIGN_TYPE_RSA2 == $signType) {

				return RSA::sign($strToSign,$privateKey,IcbcConstants::$SIGN_SHA256RSA_ALGORITHMS);

			}else{
				throw new Exception("Only support CA\RSA signature!");
			}
		}


		public static function verify($strToSign, $signType, $publicKey, $charset,$signedStr){

			if (IcbcConstants::$SIGN_TYPE_CA == $signType){

				return IcbcCa::verify($strToSign,$publicKey,$password);

			}elseif (IcbcConstants::$SIGN_TYPE_RSA == $signType) {

				return RSA::verify($strToSign,$signedStr,$publicKey,IcbcConstants::$SIGN_SHA1RSA_ALGORITHMS);

			}elseif (IcbcConstants::$SIGN_TYPE_RSA2 == $signType) {

				return RSA::verify($strToSign,$signedStr,$publicKey,IcbcConstants::$SIGN_SHA256RSA_ALGORITHMS);

			}else{

				throw new Exception("Only support CA or RSA signature verify!");

			}
		}
	}
?>