<?php
//Essa classe foi criada gerenciar a paginação do projeto.
//Foi criada como classe para ser reutilizada para qualquer listagem do banco de dados.
namespace App\Db;

class Pagination{
    /**
     * Número máximo de registros por página
     * @var integer
     */
    private $limit;

    /**
     * Quantidade total de resultados do banco
     * @var integer
     */
    private $results;

    /**
     * Quantidade de páginas
     * @var integer
     */
    private $pages;

    /**
     * Página atual
     * @var integer
     */
    private $currentPage;

    /**
     * Construtor da classe
     * @param integer $results
     * @param integer $currentPage
     * @param integer $limit
     */
    public function __construct($results, $currentPage = 1, $limit = 10){
        $this->results      = $results;
        $this->limit        = $limit;
        $this->currentPage  = (is_numeric($currentPage) and $currentPage > 0) ? $currentPage : 1;
        $this->calculate();
    }

    /**
     * Metódo responsável por calcular a paginação
     */
    private function calculate(){
        //CALCULA O TOTAL DE PÁGINAS
        $this->pages = $this->results > 0 ? ceil($this->results / $this->limit) : 1;

        //VERIFICA SE A PÁGINA ATUAL NÃO EXCEDE O NÚMERO DE PÁGINAS
        $this->currentPage = $this->currentPage <= $this->pages ? $this->currentPage : $this->pages;
    }

    /**
     * Método responsável por retornar a cláusula limit da SQL
     * @return string
     */
    public function getLimit(){
        $offset = ($this->limit * ($this->currentPage - 1));
        return $this->limit.' OFFSET '.$offset;
    }

    /**
     * Método responsável por retornar as opções de páginas disponíveis
     * @return array
     */
    public function getPages(){
        //NÃO RETORNA PÁGINAS
        if($this->pages == 1) return [];

        //PÁGINAS
        $paginas = [];
        for ($i = 1; $i <= $this->pages; $i++){
            $paginas[] = [
                'pagina' => $i,
                'atual' => $i == $this->currentPage
            ];
        }
        return $paginas;
    }
}
