<?php
/**
 * Created by PhpStorm.
 * User: lamb
 * Date: 2019/4/23
 * Time: 11:25 AM
 */

class Sql
{
    protected $_dbHandle;
    protected $_result;
    protected $_table;

    public function connect($host, $user, $password, $dbname)
    {
        try {
            $dsn = sprintf("mysql:host=%s;dbname=%s;charset=utf8", $host, $dbname);
            /*
            PDO::ATTR_DEFAULT_FETCH_MODE - 设置默认的提取模式。
            ------------------------------------------------
            PDO::FETCH_ASSOC：返回一个索引为结果集列名的数组
            PDO::FETCH_BOTH（默认）：返回一个索引为结果集列名和以0开始的列号的数组
            PDO::FETCH_BOUND：返回 TRUE ，并分配结果集中的列值给 PDOStatement::bindColumn() 方法绑定的 PHP 变量。
            PDO::FETCH_CLASS：返回一个请求类的新实例，映射结果集中的列名到类中对应的属性名。如果 fetch_style 包含 PDO::FETCH_CLASSTYPE（例如：PDO::FETCH_CLASS | PDO::FETCH_CLASSTYPE），则类名由第一列的值决定
            PDO::FETCH_INTO：更新一个被请求类已存在的实例，映射结果集中的列到类中命名的属性
            PDO::FETCH_LAZY：结合使用 PDO::FETCH_BOTH 和 PDO::FETCH_OBJ，创建供用来访问的对象变量名
            PDO::FETCH_NUM：返回一个索引为以0开始的结果集列号的数组
            PDO::FETCH_OBJ：返回一个属性名对应结果集列名的匿名对象
             */
            $this->_dbHandle = new PDO($dsn, $user, $password, array(PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC));
        } catch (PDOException $e) {
            exit('错误：' . $e->getMessage());
        }
    }

    // 根据条件 (id) 查询
    public function selectAll()
    {
        $sql = sprintf('select * from `%s`', $this->_table);
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();
        return $sth->fetchALL();
    }
    // 根据条件 (id) 查询
    public function select($id)
    {
        $sql = sprintf("select * from `%s` where `id` = '%s'", $this->_table, $id);
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->fetch();
    }
    // 根据条件 (id) 删除
    public function delete($id)
    {
        $sql = sprintf("delete from `%s` where `id` = '%s'", $this->_table, $id);
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();
        //返回行数
        return $sth->rowCount();
    }

    // 自定义SQL查询，返回影响的行数
    public function query($sql)
    {
        $sth = $this->_dbHandle->prepare($sql);
        $sth->execute();

        return $sth->rowCount();
    }

    // 新增数据
    public function add($data)
    {
        $sql = sprintf("insert into `%s` %s", $this->_table, $this->formatInsert($data));
        echo $sql;
        return $this->query($sql);
    }

    // 修改数据
    public function update($id, $data)
    {
        $sql = sprintf("update `%s` set %s where `id` = '%s'", $this->_table, $this->formatUpdate($data), $id);

        return $this->query($sql);
    }

    //将数组转换为插入格式的sql语句
    private function formatInsert($data)
    {
        $fields = array();
        $values = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s`", $key);
            $values[] = sprintf("'%s'", $value);//注意引号
        }
        $field = implode(',', $fields);
        $value = implode(',', $values);

        return sprintf("(%s) values (%s)",$field,$value);
    }
    // 将数组转换成更新格式的sql语句
    private function formatUpdate($data)
    {
        $fields = array();
        foreach ($data as $key => $value) {
            $fields[] = sprintf("`%s` = '%s'", $key, $value);
        }
        return implode(',', $fields);
    }
}