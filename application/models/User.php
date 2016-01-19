<?php

/**
 * @author: zhouwenlong
 * @since: 2016/1/19
 */
class UserModel extends yol\base\Model
{
    public function tableName()
    {
        return 'cp_user';
    }


    public function rules()
    {
        return [
          'name|用户名'    => 'require|maxlen(20)',
          'email|用户邮箱' => 'require|email',
        ];
    }

}