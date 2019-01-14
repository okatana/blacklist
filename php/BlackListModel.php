<?php

require 'PDOAdapter.php';

class BlackListModel
{
    private $pdo;
    private $logger;
    private $user_id;

    function __construct($config, $logger)
    {
        $this->logger = $logger;
        try {
            $this->pdo = new PDOAdapter($config['dsn'], $config['username'], $config['password'], $this->logger);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

    }

    public  function checkLogin($login, $password)
    {
        $this->logger->info('$login = ' . $login);
        $this->logger->info('$password = ' . $password);
        $sql = 'select user_id, password, allow_edit from blacklist_user where login=? and active=1';
        $result = $this->pdo->execute('selectOne', $sql, array($login));

        $this->logger->info('result = ' . print_r($result, true));
        if ($result && $result->password === sha1($password)) {
            return ['user_id'=>$result->user_id,
                    'allow_edit'=>$result->allow_edit
            ];
        }
        return false;
    }

    public  function getVidsInsurance(){
        $sql = <<<SQL
SELECT id, name FROM blacklist_vid_insurance ORDER BY name
SQL;
        $result = $this->pdo->execute('selectAll', $sql);
        return $result;
    }


    public function getCheckResults($lastname,$firstname,$midname,$birthday){
        $lastnameCondition = $lastname ? ' AND lastname rlike '. $this->pdo->getDbh()->quote($lastname): '';
       /* $lastnameCondition = $lastname ? ' AND lastname rlike '. $this->pdo->quote($lastname): '';
        $lastnameCondition = $lastname ? ' AND lastname rlike '. $this->pdo->quote($lastname): '';
        $lastnameCondition = $lastname ? ' AND lastname rlike '. $this->pdo->quote($lastname): '';*/
        $sql = <<<SQL
SELECT *  FROM blacklist_client 
where 1 $lastnameCondition
SQL;
        $result = $this->pdo->execute('selectAll', $sql );
        return $result;
    }
}
