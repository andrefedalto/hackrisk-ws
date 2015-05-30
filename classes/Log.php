<?php
/**
 * Created by PhpStorm.
 * User: André
 * Date: 01/04/2015
 * Time: 14:38
 */

class Log
{
    private $_logs = array();

    /**
     * pushLog
     *
     * coloca uma entrada de log na pilha de logs
     *
     * @param Object $class passar a instancia do objeto que chama ($this)
     * @param int $code codigo do log, baseado nos response code do http
     * @param string $description breve descricao do log
     * @param bool $error é erro? true. Se for apenas logando a ação, use false (opcional, falso por padrao)
     */
    public function pushLog($class, $code, $description, $error = false)
    {
        $log = array(
            'class'         => get_class($class),
            'code'          => $code,
            'description'   => $description,
            'error'         => $error
        );

        array_push($this->_logs, $log);
    }


    /**
     * getLogs
     *
     * retorna um vetor com todos os logs (erros e não-erros)
     *
     * @return array
     */
    public function getLogs()
    {
        return $this->_logs;
    }

    /**
     * countErrors
     *
     * returna quantos erros estão na pilha de log
     *
     * @return int
     */
    public function countErrors()
    {
        $errors = 0;

        foreach($this->_logs as $log)
            if ($log['error'])
                $errors++;

        return $errors;
    }

    /**
     * setErrorCode
     *
     * Atualiza o codigo de erro do ultimo erro
     */
    public function setErrorCode($code)
    {
        if ($this->countErrors() > 0)
        {
            for ($i = sizeof($this->_logs) - 1; $i >= 0; $i--)
            {
                if ($this->_logs[$i]['error'])
                {
                    $this->_logs[$i]['code'] = $code;
                    break;
                }
            }
        }
    }

    /**
     * getLastCode
     *
     * returna o ultimo codigo, seja de erro ou sucesso
     *
     * @return int ultimo codigo
     */
    public function getLastCode()
    {
        if (sizeof($this->_logs) > 0)
            return $this->_logs[sizeof($this->_logs) - 1]['code'];
        else
            return 0;
    }

    /**
     * getErrors
     *
     * retorna um vetor com todos os erros na pilha de erros
     * returna null se não tiver erros
     *
     * @return array|null
     */
    public function getErrors()
    {
        if ($this->countErrors() > 0)
        {
            $errors = array();

            foreach($this->_logs as $log)
                if ($log['error'])
                    array_push($errors, $log);

            return $errors;
        }
        else
        {
            return null;
        }
    }

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @staticvar Singleton $instance The *Singleton* instances of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function getInstance()
    {
        static $instance = null;
        if (null === $instance) {
            $instance = new static();
        }

        return $instance;
    }

    /**
     * Protected constructor to prevent creating a new instance of the
     * *Singleton* via the `new` operator from outside of this class.
     */
    protected function __construct()
    {
    }

    /**
     * Private clone method to prevent cloning of the instance of the
     * *Singleton* instance.
     *
     * @return void
     */
    private function __clone()
    {
    }

    /**
     * Private unserialize method to prevent unserializing of the *Singleton*
     * instance.
     *
     * @return void
     */
    private function __wakeup()
    {
    }
}