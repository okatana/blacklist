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

      //  $this->logger->info('result = ' . print_r($result, true));
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
        $firstCondition = $firstname ? ' AND firstname rlike '. $this->pdo->getDbh()->quote($firstname): '';
        $midnameCondition = $midname ? ' AND midname rlike '. $this->pdo->getDbh()->quote($midname): '';
        $birthdayCondition = $birthday ? ' AND birthday = '. $this->pdo->getDbh()->quote($birthday): '';

        $sql = <<<SQL
SELECT* FROM blacklist_client cl , blacklist_client_info inf, blacklist_user us
where cl.client_id = inf.client_id AND cl.user_id = us.user_id  $lastnameCondition $firstCondition $midnameCondition $birthdayCondition
SQL;
        $result = $this->pdo->execute('selectAll', $sql );

        return $result;
    }
}
