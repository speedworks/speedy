<?php
/**
 * Created by PhpStorm.
 * @Author : Shakti Phartiyal
 * Date: 11/29/16
 * Time: 11:23 AM
 */

namespace Core\DB;
use Core\System\System;
use \PDO;
use \Exception;

class DB
{
    private $dsn=null;
    private $DBCON=null;
    private $table=null;
    private $selects=null;
    private $andWhereConds=null;
    private $orWhereConds=null;
    private $inConds=null;
    private $notInConds=null;
    private $query="";
    private $statement=null;

    /**
     * Initializes ADODB Connection
     * @return DBODB COnnection Handle
     */
    public static function ADO()
    {
        $db = ADONewConnection($_ENV['DB_ADO_DRIVER']);
        $db->connect($_ENV['DB_SERVER'], $_ENV['DB_USER'], $_ENV['DB_PASSWORD'], $_ENV['DB_NAME']);
        return $db;
    }

    /**
     * DB constructor.
     */
    protected function __construct()
    {
        $this->dsn=$_ENV['DB_TYPE'].':dbname='.$_ENV['DB_NAME'].';host='.$_ENV['DB_SERVER'].';port='.$_ENV['DB_PORT'].';charset='.$_ENV['DB_CHARSET'];
        try
        {
            $this->DBCON = new PDO($this->dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
            $this->DBCON->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, $_ENV['DB_FETCH_MODE']);
        }
        catch (PDOException $e)
        {
            echo 'Databse Connection failed: ' . $e->getMessage();
            die;
        }
    }

    /**
     * @param $tableName
     * @return DB
     */
    public static function table($tableName)
    {
        $db = new DB();
        $db->table =$tableName;
        return $db;
    }


    /**
     *
     */
    private function closeCON()
    {
        $this->DBCON = null;
        unset($this->DBCON);
    }

    /**
     * @return $this
     */
    public function select()
    {
        $args=func_get_args();
        $numArgs = func_num_args();
        if($numArgs == 0)
        {
            $this->selects = '*';
        }
        else
        {
            $this->selects = implode(',', $args);
        }
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function where()
    {
        $numArgs = func_num_args();
        $args = func_get_args();
        $col = null;
        if($numArgs == 2)
        {
            if(gettype($args[1]) == 'integer')
            {
                $col = $args[1];
            }
            else
            {
                $col = '"'.$args[1].'"';
            }
            $this->andWhereConds[] = $args[0].' = '.$col;
        }
        else if($numArgs == 3)
        {
            if(gettype($args[2]) == 'integer')
            {
                $col = $args[2];
            }
            else
            {
                $col = '"'.$args[2].'"';
            }
            $this->andWhereConds[] = $args[0].' '.$args[1].' '.$col;
        }
        else
        {
            throw new Exception("Invalid / Missing Parameters for WHERE clause");
        }
        return $this;
    }

    /**
     * @return $this
     * @throws Exception
     */
    public function orWhere()
    {
        $numArgs = func_num_args();
        $args = func_get_args();
        $col = null;
        if($numArgs == 2)
        {
            if(gettype($args[1]) == 'integer')
            {
                $col = $args[1];
            }
            else
            {
                $col = '"'.$args[1].'"';
            }
            $this->orWhereConds[] = $args[0].' = '.$col;
        }
        else if($numArgs == 3)
        {
            if(gettype($args[2]) == 'integer')
            {
                $col = $args[2];
            }
            else
            {
                $col = '"'.$args[2].'"';
            }
            $this->orWhereConds[] = $args[0].' '.$args[1].' '.$col;
        }
        else
        {
            throw new Exception("Invalid / Missing Parameters for WHERE clause");
        }
        return $this;
    }

    /**
     * @param $column
     * @param $inArray
     * @return $this
     */
    public function whereIn($column, $inArray)
    {
        $this->inConds[] = $column.' IN ('.implode(',', $inArray).')';
        return $this;
    }

    /**
     * @param $column
     * @param $notInArray
     * @return $this
     */
    public function notIn($column, $notInArray)
    {
        $this->notInConds[] = $column.' NOT IN ('.implode(',', $notInArray).')';
        return $this;
    }



    protected function selectionQueryMaker()
    {
        $this->query = 'SELECT '.$this->selects.' FROM '.$this->table;
        $where="";
        $flag = 0;
        if($this->andWhereConds != null)
        {
            if($flag == 0)
            {
                $where .= ' WHERE ';
            }
            $flag = 1;
            foreach ($this->andWhereConds as $conds)
            {
                $where.= '('.$conds.')';
                $where.= ' AND ';
            }
        }
        if($this->orWhereConds != null)
        {
            if($flag == 0)
            {
                $where .= ' WHERE ';
            }
            else
            {
                $where = substr($where, 0, -4);
                $where.='  OR ';
            }
            $flag = 1;
            foreach ($this->orWhereConds as $conds)
            {
                $where.= '('.$conds.')';
                $where.= '  OR ';
            }
        }
        if($this->inConds != null)
        {
            if($flag == 0)
            {
                $where .= ' WHERE ';
            }
            else
            {
                $where = substr($where, 0, -4);
                $where.=' AND ';
            }
            $flag = 1;
            foreach ($this->inConds as $conds)
            {
                $where.= '('.$conds.')';
                $where.= ' AND ';
            }
        }
        if($this->notInConds != null)
        {
            if($flag == 0)
            {
                $where .= ' WHERE ';
            }
            else
            {
                $where = substr($where, 0, -4);
                $where.=' AND ';
            }
            $flag = 1;
            foreach ($this->notInConds as $conds)
            {
                $where.= '('.$conds.')';
                $where.= ' AND ';
            }
        }

        if($flag == 1)
        {
            $where = substr($where, 0, -4);
        }
        $this->query.= $where;
    }

    /**
     * @return array
     */
    public function get()
    {
        $this->selectionQueryMaker();
        $this->statement = $this->DBCON->prepare($this->query);
        $this->statement->execute();
        $this->closeCON();
        return $this->statement->fetchAll();
    }
    public function first()
    {
        $this->selectionQueryMaker();
        $this->statement = $this->DBCON->prepare($this->query);
        $this->statement->execute();
        $this->closeCON();
        return $this->statement->fetch();
    }
    public function update()
    {

    }
    public function delete()
    {

    }

    public static function raw($query)
    {
        $db = new DB();
        $db->statement = $db->DBCON->prepare($query);
        $db->statement->execute();
        $db->closeCON();
        return $db->statement->fetch();
    }
}