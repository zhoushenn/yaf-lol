<?php
namespace yol\validator;

/**
 * 验证器
 * @author zhoushen 445484792@qq.com
 * @example
 * $validator = new Validator;
 * $validator->throwException(true);
 *  $input = [
        'username' => 'zhoushen',
        'url'      => 'noturl'
        ];
    $rules = [
        'username|用户名' => 'require|maxLen(2)',
        'url|用户网站'    => 'url',
        ];
    $result = $validator->validate($input, $rules);
     if($result === true){
        echo '验证通过';
     }
 *
 *
 */
class Validator{

	public $throwException = true;
	public $rules   = array(); 
	public $rulesClassName  = 'yol\validator\Rules';
	public $validError  = array();

    /**
     * Validator constructor.
     * @param string $ruleClass 指定验证规则类
     * @param bool|true $throwException 是否抛出异常
     */
    public function __construct($ruleClass = '', $throwException = true)
    {
        if(!empty($ruleClass)) $this->rulesClassName = $ruleClass;
        $this->throwException = $throwException;
    }

    /**
     * 设置加载的验证规则类
     * 如果要添加自己的验证规则请继承Rules添加验证规则
     * @param $class
     */
	public function setRulesClass($class){
		$this->rulesClassName;
	}

    /**
     * 是否抛出异常
     * @param $boolean
     */
	public function throwException($boolean){
		$this->throwException = $boolean;
	}

    /**
     * 执行验证
     * @param array $input
     * @param array $rules
     * @return array|bool
     * @throws ArtValidatorException
     */
	public function validate(array $input, array $rules){

		$this->rules = $rules;

		foreach($this->rules as $fieldStr => $ruleStr){ 
			
			list($field, $fieldCN) = explode('|', $fieldStr); //字段名称，和字段中文
			$fieldRules = explode('|', $ruleStr);

			foreach ($fieldRules as $fieldRule) {

				$args = (array)$input[$field]; //输入值
				if( strpos($fieldRule, '(') === false ){
					$callback = $fieldRule;
				}else{
					list($callback, $argsSlace) = explode('(', $fieldRule);
					$args = array_merge($args, explode(',', str_replace(')', '', $argsSlace)) );
				}
				$callback = 'valid_' . $callback;
				if( ! method_exists($this->rulesClassName, $callback) ){
					throw new ValidatorException(
                        sprintf('%s 类不存在验证规则 %s', $this->rulesClassName, $callback)
                    );
				}
				$result = call_user_func_array(array($this->rulesClassName, $callback), $args);
				if($result === true){ 
					continue;
				}
				if($this->throwException){
					throw new ValidatorException( $fieldCN . $result );
				}

				$this->validError[$field] = $fieldCN . $result;
				break;
			}
			
		}

		return empty($this->validError) ? true : $this->validError;
	}

}
