<?php

include_once ("Connection.class.php");
class DaoCEP {
    public static $instance;

    private function __construct() {
        parent;
    }
    /**
     * recebe uma isntancia de conexão
     *
     * @return instance
     */
    public static function getInstance() {
        if (!isset(self::$instance)) {
            self::$instance = new DaoCEP();
        }
        return self::$instance;
    }
    /**
     * insere os dados no banco
     *
     * @param [array] $var
     * @return boolean
     */
    public function InserirCep($var) {
        try {
            $exist = DaoCEP::buscarCepPorCOD($var["cep"]);
            if ($exist["id"] != null) {
                DaoCEP::editarCEP($var, $$exist->id);
            } else {
                $sql = "INSERT INTO enderecos (
                    cep,
                    logradouro,
                    bairro,
                    cidade,
                    uf) VALUES (
                        :cep,
                        :logradouro,
                        :bairro,
                        :cidade,
                        :uf
                    )";
                $pSql = Connection::getInstance()->prepare($sql);
                $pSql->bindValue(":cep", $var["cep"]);
                $pSql->bindValue(":logradouro", $var["logradouro"]);
                $pSql->bindValue(":bairro", $var["bairro"]);
                $pSql->bindValue(":cidade", $var["cidade"]);
                $pSql->bindValue(":uf", $var["estado"]);
    
                return $pSql->execute();
            }
        } catch(Exception $e) {
            //echo $e->getMessage();
            print "Erro a inserir cep.";
        }
    }
    /**
     * Realiza um update na tabela
     *
     * @param [array] $var
     * @param [int] $id
     * @return int
     */
    public function editarCEP($var, $id) {
        try {
            $sql = "UPDATE enderecos set
            cep = :cep,
            logradouro = :logradouro,
            bairro = :bairro,
            cidade = :cidade,
            uf = :uf WHERE id = :id";
 
            $pSql = Connection::getInstance()->prepare($sql);
 
            $pSql->bindValue(":cep", $var["cep"]);
            $pSql->bindValue(":logradouro", $var["logradouro"]);
            $pSql->bindValue(":bairro", $var["bairro"]);
            $pSql->bindValue(":cidade", $var["cidade"]);
            $pSql->bindValue(":uf", $var["estado"]);
            $pSql->bindValue(":id", $id);

            return $pSql->execute();
        } catch (Exception $e) {
            print "Erro ao editar um cep.";
        }
    }
    /**
     * Verifica se um cep ja existe no banco de dados
     *
     * @param [int] $cep
     * @return int
     */
    public function buscarCepPorCOD($cep) {
        try {
            $sql = "SELECT * FROM enderecos WHERE cep = :cep ORDER BY id DESC LIMIT 1";
            $pSql = Connection::getInstance()->prepare($sql);
            $pSql->bindValue(":cep", $cep);
            $pSql->execute();
            $codigo = $pSql->fetch(PDO::FETCH_ASSOC);
            return $codigo;
        } catch (Exception $e) {
            echo $e->getMessage();
            print "Erro ao encontrar o cep.";
        }
    }
}
?>