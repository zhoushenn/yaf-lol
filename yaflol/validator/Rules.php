<?php
namespace yol\validator;
/**
 * built in验证规则类，
 * 需要添加自己类的时候继承一个
 * 
 * @author zhoushen 445484792@qq.com
 *
 */
class Rules{

	public static function valid_require($value){

		if( ! empty($value) ){
			return true;
		}

		return '不能留空';
	}

	public static function valid_url($value){

		if( filter_var($value, FILTER_VALIDATE_URL) ){
			return true;
		}

		return '不是有效的url格式';
	}

	public static function valid_maxLen($value, $maxlen){ 

		if( mb_strlen($value, 'UTF-8') <= $maxlen ){
			return true;
		}

		return '不能超过最大长度' . $maxlen;
	}

	public static function valid_date($value){

		if( date('Y-m-d', strtotime($value)) == $value ){
			return true;
		}

		return '不是有效的日期格式';
	}

	public static function valid_datetime($value){

		if( date('Y-m-d H:i:s', strtotime($value)) == $value ){
			return true;
		}

		return '不是有效的日期时间格式';
	}

    public static function valid_email($value)
    {
        if( filter_var($value, FILTER_VALIDATE_EMAIL) ){
            return true;
        }

        return '不是有效的邮箱格式';
    }

	public static function valid_mobile($value){
		if( preg_match('/^(?:13|15|18)[0-9]{9}$/', $value) ){
			return true;
		}

		return '不是有效的手机格式';
	}

	public static function valid_nickname($value){
		$regexp = '/^\s*$|^c:\\con\\con$|[%,\*\"\s\t\<\>\&\'\(\)]|\xA1\xA1|\xAC\xA3|^Guest|^\xD3\xCE\xBF\xCD|\xB9\x43\xAB\xC8/is';
        if( preg_match($regexp,$string) ){
        	return true;
        }

        return '不是有效的昵称';
	}
	
}