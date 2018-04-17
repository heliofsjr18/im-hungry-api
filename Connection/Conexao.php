<?php
/**
 * Classe de conexão ao banco de dados usando PDO no padrão Singleton.
 * Modo de Usar:
 * require_once './Database.class.php';
 * $db = Database::conexao();
 * E agora use as funções do PDO (prepare, query, exec) em cima da variável $db.
 */
class Database
{
    /**
     *
     * @var PDO 
     */
    protected static $db;
    # Private construct - garante que a classe só possa ser instanciada internamente.
    private function __construct()
    {
        # Informações sobre o banco de dados:
        //$db_host = "localhost";
        $db_host = "localhost";
        $db_nome = "u181619366_hungr";
        $db_usuario = "u181619366_hungr";
        $db_senha = "lw@N~s>d*4CE";
        $db_driver = "mysql";
        $param = array(
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set lc_time_names="pt_BR";',
            PDO::MYSQL_ATTR_INIT_COMMAND => 'set time_zone = "America/Recife"'
        );
        # Informações sobre o sistema:
        $sistema_titulo = "API - I'm Hingry";
        $sistema_email = "rafael.vasconcelos@outlook.com";
        try
        {
            # Atribui o objeto PDO à variável $db.
            self::$db = new PDO("$db_driver:host=$db_host; dbname=$db_nome", $db_usuario, $db_senha, $param);
            # Garante que o PDO lance exceções durante erros.
            self::$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            # Garante que os dados sejam armazenados com codificação UFT-8.
            self::$db->exec('SET NAMES utf8');
        }
        catch (PDOException $e)
        {
            # Envia um e-mail para o e-mail oficial do sistema, em caso de erro de conexão.
            mail($sistema_email, "PDOException em $sistema_titulo", $e->getMessage());
            # Então não carrega nada mais da página.
            die("Connection Error: " . $e->getMessage());
        }
    }
    # Método estático - acessível sem instanciação.
    public static function conexao()
    {
        # Garante uma única instância. Se não existe uma conexão, criamos uma nova.
        if (!self::$db)
        {
            new Database();
        }
        # Retorna a conexão.
        return self::$db;
    }
}