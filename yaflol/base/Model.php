<?php
namespace yol\base;

/**
 * @author: zhouwenlong
 * @since: 2016/1/19
 */
abstract class Model
{
    /**
     * @var \yol\db\DbQuery 数据库查询对象
     */
    private $db;

    /**
     * @var \yol\validator\Validator 验证器
     */
    public $validator;

    public function __construct(\yol\db\DbQuery $query = null)
    {
        if(!$query){
            $this->db = \yol\di\Container::getInstance()->get('db');
        }
        if(!empty($this->rules())){
            $this->validator = new \yol\validator\Validator();
        }
    }

    /**
     * 指定验证规则
     *
     * @param $rules
     * @return array
     *
     */
    abstract public function rules();

    /**
     * 指定表名称
     * @return mixed
     */
    abstract public function tableName();

    /**
     * 获取db
     * @return mixed|\yol\db\DbQuery
     */
    public function getDb()
    {
        return $this->db;
    }

    public function select()
    {
        //TODO:实现查询
    }

    public function delete()
    {
        //TODO:实现删除
    }

    /**
     * 增
     * @param array $data
     * @return int
     */
    public function insert(array $data)
    {
        $this->checkRules($data);
        return $this->db->insert($this->tableName(), array_keys($data))->values($data)->execute();
    }

    /**
     * 改
     * @param array $data
     * @param $where
     * @return int
     */
    public function update(array $data, $where)
    {
        $this->checkRules($data);
        return $this->db->update($this->tableName(), $data)->where($where)->execute();
    }

    /**
     * 执行数据库校验
     * @param array $input
     * @throws \yol\validator\ArtValidatorException
     */
    public function checkRules(array $input)
    {
        return $this->validator->validate($input, $this->rules());
    }
}