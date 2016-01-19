<?php
namespace yol\base;

/**
 * @author: zhouwenlong
 * @since: 2016/1/19
 */
abstract class Model
{
    /**
     * 指定验证规则
     * @param $rules
     * @return array
     */
    abstract public function rules();

    /**
     * 执行数据库校验
     * @param array $input
     * @throws \yol\validator\ArtValidatorException
     */
    public function checkRules(array $input)
    {
        $validator = new \yol\validator\Validator();
        $validator->validate($input, $this->rules());
    }
}