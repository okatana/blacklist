<?php

require 'PDOAdapter.php';

class BlackListModel
{
    private $pdo;
    private $logger;
    private $preparedInserClient;
    private $preparedInserClientInfo;

    function __construct($config, $logger)
    {
        $this->logger = $logger;
        try {
            $this->pdo = new PDOAdapter($config['dsn'], $config['username'], $config['password'], $this->logger);
        } catch (Exception $e) {
            $this->logger->error($e->getMessage());
        }

    }

    public function checkLogin($login, $password)
    {
        if ($this->logger) {
            $this->logger->info('$login = ' . $login);
            $this->logger->info('$password = ' . $password);
        }
        $sql = 'select user_id, password, allow_edit from blacklist_user where login=? and active=1';
        $result = $this->pdo->execute('selectOne', $sql, array($login));

        if ($this->logger) {
            $this->logger->info('result = ' . print_r($result, true));
        }
        if ($result && $result->password === sha1($password)) {
            return ['user_id' => $result->user_id,
                'allow_edit' => $result->allow_edit
            ];
        }
        return false;
    }

    public function getVidsInsurance()
    {
        $sql = <<<SQL
SELECT id, name FROM blacklist_vid_insurance ORDER BY name
SQL;
        $result = $this->pdo->execute('selectAll', $sql);
        return $result;
    }


    public function getCheckResults($lastname, $firstname, $midname, $birthday, $vid)
    {
        $lastnameU = $lastname? mb_strtoupper($lastname):'';
        $firstnameU = $firstname? mb_strtoupper($firstname):'';
        $midnameU = $midname? mb_strtoupper($midname):'';
echo '$lastnameU=='.($lastnameU);
        $lastnameCondition = $lastname ? ' AND UPPER(lastname) rlike ' . $this->pdo->getDbh()->quote($lastnameU) : '';
        $firstCondition = $firstname ? ' AND UPPER(firstname) rlike ' . $this->pdo->getDbh()->quote($firstnameU) : '';
        $midnameCondition = $midname ? ' AND UPPER(midname) rlike ' . $this->pdo->getDbh()->quote($midnameU) : '';
        $birthdayCondition = $birthday ? ' AND birthday = ' . $this->pdo->getDbh()->quote($birthday) : '';
        $vidCondition = $vid ? ' AND vid_id = ' . $this->pdo->getDbh()->quote($vid) : '';

        $sql = <<<SQL
SELECT distinct  UPPER(lastname) as lastname , UPPER(firstname) as firstname , UPPER(midname) as midname, birthday, inf.comment , inf.vid_id, us.email, cl.client_id, us.manager
FROM blacklist_client cl , 
     blacklist_client_info inf, 
     blacklist_user us
     
where cl.client_id = inf.client_id AND cl.client_active=1 AND 
      cl.user_id = us.user_id 
      $lastnameCondition 
      $firstCondition 
      $midnameCondition 
      $birthdayCondition 
      $vidCondition  


SQL;
        try {
            $result = $this->pdo->execute('selectAll', $sql);
        } catch (Exception $ex) {
            $logfile = 'log/debug.log';
            file_put_contents($logfile, 'BlackListModel->getCheckResults() sql=' . $sql, FILE_APPEND);
            throw $ex;
        }
        return $result;
    }

    public function addClient($lastname, $firstname, $midname, $birthday, $vid_id, $comment_info)
    {

        $this->pdo->getDbh()->beginTransaction();

        try {

            $sql = <<<SQL
INSERT INTO blacklist_client (`lastname`, `firstname`, `midname`, `birthday`,  `user_id`) VALUES (?,?,?,?,?);
SQL;
            $result = $this->pdo->execute('execute', $sql,
                [$lastname, $firstname, $midname, $birthday, $_SESSION['user_id']]);

            if ($result) {
                $sql = <<<SQL
INSERT INTO blacklist_client_info(`client_id`,`user_id`,`comment`,`vid_id`)       
       values((SELECT MAX(`client_id`) FROM blacklist_client),?,?,?) ;
SQL;
                $result = $this->pdo->execute('execute', $sql,
                    [$_SESSION['user_id'], $comment_info, $vid_id]);
            }
            if ($result) {
                $this->pdo->getDbh()->commit();
            } else {
                $this->pdo->getDbh()->rollBack();
            }
        } catch (PDOException $err) {
            $result = false;
            $this->pdo->getDbh()->rollBack();
        }


        return $result;
    }


    public function getLastAddedClient()
    {
        $sql = <<<SQL
SELECT cl.client_id, 
       cl.lastname, 
       cl.firstname, 
       cl.midname, 
       cl.birthday, 
       cl.user_id,
       inf.user_id, 
       inf.comment,
       inf.vid_id,
       inf.client_id , 
       us.manager, 
       us.email, 
       us.user_id,
       vi.id,
       vi.name as insurance_name
       
from blacklist_client cl 
left join   blacklist_client_info inf ON inf.client_id = cl.client_id
left join blacklist_user us ON us.user_id = cl.user_id
left join blacklist_vid_insurance vi ON vi.id = inf.vid_id
where cl.client_id = (SELECT MAX(client_id) from blacklist_client)
SQL;
        $result = $this->pdo->execute('selectAll', $sql);
        return $result;
    }

    public function getUser($user_id)
    {
        $sql = <<<SQL
SELECT email, manager FROM blacklist_user
WHERE user_id=?
SQL;

        $result = $this->pdo->execute('selectOne', $sql, array($user_id));
        return $result;
    }

    public function getClientsByVids($vids)
    {  //виды пока не учитываю
        $sql = <<<SQL
        SELECT 
            cl.*, inf.*, us.*,ins.*
            FROM blacklist_client cl
            LEFT JOIN blacklist_client_info inf on inf.client_id = cl.client_id 
            LEFT JOIN blacklist_user us on us.user_id = cl.user_id 
            LEFT JOIN blacklist_vid_insurance ins on ins.id = inf.vid_id
            where cl.client_active=1
SQL;
        $result = $this->pdo->execute('selectAll', $sql);
        return $result;
    }

    public function prepareUploadClient()
    {
        $fieldNameOrder = [
            'lastname' => 0,
            'firstname' => 1,
            'midname' => 2,
            'birthday' => 3,
        ];

        $fieldNames = '';
        $delim = '';
        $whats = '';

        foreach ($fieldNameOrder as $name => $order) {
            $fieldNames .= $delim . $name;
            $whats .= $delim . '?';
            $delim = ',';
        }
        $fieldNames .= $delim . 'user_id';
        $whats .= $delim . '?';
        $sql = <<<SQL
      INSERT INTO blacklist_client ($fieldNames) VALUES ($whats);
SQL;
        $this->preparedInserClient = $this->pdo->prepare($sql);

        $sql = <<<SQL
INSERT INTO blacklist_client_info (client_id, user_id, `comment`, vid_id)
VALUES (?,?,?,?);
SQL;
        $this->preparedInserClientInfo = $this->pdo->prepare($sql);
    }

    public function uploadClient($lastname, $firstname, $midname, $birthday, $comment, $vid_id)
    {

        $this->pdo->getDbh()->beginTransaction();
        $user_id = $_SESSION['user_id'];

        try {
            $result = $this->pdo->executePrepared($this->preparedInserClient,
                [$lastname, $firstname, $midname, $birthday, $user_id]);
            if ($result) {
                $client_id = $this->pdo->getLastInsertId();
                $result = $this->pdo->executePrepared($this->preparedInserClientInfo,
                    [$client_id, $user_id, $comment, $vid_id]);
            }

            if ($result) {
                $this->pdo->getDbh()->commit();
            } else {
                $this->pdo->getDbh()->rollBack();
            }
        } catch (PDOException $err) {
            $result = false;
            $this->pdo->getDbh()->rollBack();
        }
        return $result;
    }

    public function getClient($id)
    {
        $sql = <<<SQL
SELECT cl.client_id as id, lastname, firstname, midname, birthday, inf.comment comment, ins.name insname, us.email, cl.client_id, us.manager
FROM blacklist_client cl 
JOIN blacklist_client_info inf  ON  cl.client_id = inf.client_id
JOIN blacklist_user us ON cl.user_id = us.user_id 
JOIN blacklist_vid_insurance ins ON ins.id = inf.vid_id
where  cl.client_id=?
SQL;
        $result = $this->pdo->execute('selectOne', $sql, array($id));
        return $result;
    }

public function getDeletedClient($id){
    $sql = <<<SQL
SELECT cl.client_id as id, lastname, firstname, midname, birthday, inf.comment comment, ins.name insname, us.email, cl.client_id, us.manager,delete_comment 
FROM blacklist_client cl 
JOIN blacklist_client_info inf  ON  cl.client_id = inf.client_id
JOIN blacklist_user us ON cl.user_id = us.user_id 
JOIN blacklist_vid_insurance ins ON ins.id = inf.vid_id
where  cl.client_id=? and client_active=?
SQL;
    $result = $this->pdo->execute('selectOne', $sql, array($id, 0));
    return $result;
}

    public function updateClient($clientID, $lastname, $firstname, $midname, $birthday, $comment_info)
    {
        $this->pdo->getDbh()->beginTransaction();
        try {
            $sql = <<<SQL
UPDATE blacklist_client SET `lastname`=?, `firstname`=?, `midname`=?, `birthday`=?,  `user_id`=?
WHERE client_id=?
SQL;
            $result = $this->pdo->execute('execute', $sql,
                [$lastname, $firstname, $midname, $birthday, $_SESSION['user_id'], $clientID]);

            if ($result) {
                $sql = <<<SQL
UPDATE blacklist_client_info SET comment = ? , user_id=?
WHERE client_id=?
SQL;
                $result = $this->pdo->execute('execute', $sql,
                    [$comment_info, $_SESSION['user_id'], $clientID]);
            }
            if ($result) {
                $this->pdo->getDbh()->commit();
            } else {
                $this->pdo->getDbh()->rollBack();
            }


        } catch (PDOException $err) {
            $result = false;
            $this->pdo->getDbh()->rollBack();
        }

        return $result;
    }

    public function deleteClientbyId($id, $delete_comment)
    {
        $sql = <<<SQL
UPDATE blacklist_client SET delete_comment = ? , user_id=?, client_active=?
WHERE client_id=?
SQL;
        $result = $this->pdo->execute('execute', $sql,
            [$delete_comment, $_SESSION['user_id'], 0, $id]);
        return $result;
    }
}
