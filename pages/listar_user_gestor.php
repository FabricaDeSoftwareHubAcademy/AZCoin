<?php
// echo '<pre>';
// print_r($usuarioLogado);
// echo '</pre>';
// exit;

//Inclui o autoload
require __DIR__.'../../vendor/autoload.php';

//DEPENDÊNCIAS
use \App\Entity\Usuario;
use \App\Db\Pagination;
use \App\Session\Login;

//OBRIGA O USUÁRIO A ESTAR LOGADO
Login::requireLogin();

//DADOS DO USUÁRIO LOGADO
$usuarioLogado = Login::getUsuarioLogado();

// FILTRO DE BUSCA
// O ideal é usar o 'filter_input' para evitar que algum usuário mal intencionado tente expor alguma parte do código.
$busca = filter_input(INPUT_GET, 'busca');

//FILTRO DE PERFIL
$filtroPerfil = filter_input(INPUT_GET, 'filtroPerfil', FILTER_SANITIZE_NUMBER_INT);
$filtroPerfil = in_array($filtroPerfil,['1','2','3']) ? $filtroPerfil : '';

//FILTRO DE STATUS
$filtroStatus = filter_input(INPUT_GET, 'filtroStatus', FILTER_SANITIZE_NUMBER_INT);
$filtroStatus = in_array($filtroStatus,['1','2']) ? $filtroStatus : '';

//CONDIÇÕES SQL
$condicoes = [
    strlen($busca) ? "nome ILIKE '%".str_replace(' ','%',$busca)."%'" : null,
    strlen($filtroPerfil) ? "id_perfil_usuario = "."'$filtroPerfil'" : null,
    strlen($filtroStatus) ? "id_status_user = "."'$filtroStatus'" : null,
];
//REMOVE POSIÇÕES VAZIAS
$condicoes = array_filter($condicoes);

//CLÁUSULA WHERE PARA MONTAR NO FILTRO
$where = implode(' AND ',$condicoes);
$order = "id_usuario DESC";

//QUANTIDADE TOTAL DE USUÁRIOS
$quantidadeUsuarios = Usuario::getQuantidadeUsuarios($where);

//PAGINAÇÃO
$obPagination = new Pagination($quantidadeUsuarios, $_GET['pagina'] ?? 1, 10);

//OBTÉM OS USUÁRIOS
$usuarios = Usuario::getUsuarios($where,$order,$obPagination->getLimit());

//ALGUMA MENSAGEM QUE É PRA APARECER EM ALGUM LUGAR
$mensagem = '';
if(isset($_GET['status'])){
    switch($_GET['status']){
        case 'success':
            $mensagem = '<div>Ação executada com sucesso!</div>';
            break;
        case 'error':
            $mensagem = '<div>Ação não executada!</div>';
            break;
        }
}

//ORGANIZA A LISTAGEM
$resultados = '';
foreach($usuarios as $usuario){

    //VARIAVEL 'perfilUsuario'
    if($usuario->id_perfil_usuario == 1){
        $perfilUsuario = 'ADMINISTRADOR';
    }elseif($usuario->id_perfil_usuario == 2){
        $perfilUsuario = 'GESTOR';
    }elseif($usuario->id_perfil_usuario == 3){
        $perfilUsuario = 'COLABORADOR';
    }

    //VARIAVEL 'statusUsuario'
    if($usuario->id_status_user == 1){
        $statusUsuario = 'ATIVO';
        $mao= 'mao';
    } else if($usuario->id_status_user == 2) {
        $statusUsuario = 'INATIVO';
        $mao= 'mao tampada';

    }

    $resultados .= '<tr>
                        <td data-lable="Nome: ">'.$usuario->nome.'</td>
                        <td data-lable="Email: ">'.$usuario->email.'</td>
                        <td data-lable="Apelido: ">'.$usuario->apelido.'</td>
                        <td data-lable="Perfil: ">'.$perfilUsuario.'</td>
                        <td data-lable="Status: ">'.$statusUsuario.'</td>
                        <td data-lable="Editar: ">
                            <a href="editar_user_gestor.php?id_usuario='.$usuario->id_usuario.'">
                                <svg width="24" height="24" viewBox="0 0 25 25" fill="none" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" clip-rule="evenodd" d="M14.9666 1.20887C16.5785 -0.402949 19.1916 -0.402963 20.8035 1.20887L23.7911 4.19646C25.403 5.80829 25.403 8.42156 23.7911 10.0334L9.22746 24.597C8.96945 24.855 8.61952 25 8.25464 25H1.37577C0.615961 25 0 24.3841 0 23.6242V16.7453C0 16.3805 0.144952 16.0305 0.40295 15.7725L14.9666 1.20887ZM18.8579 3.1545C18.3206 2.61724 17.4495 2.61724 16.9122 3.1545L15.7034 4.36337L20.6366 9.29661L21.8455 8.08775C22.3827 7.55046 22.3827 6.67938 21.8455 6.14211L18.8579 3.1545ZM18.691 11.2422L13.7577 6.309L2.75155 17.3152V22.2484H7.68478L18.691 11.2422Z" fill="black"/>
                                </svg>
                            </a>
                        </td>
                        <td data-lable="Inativar: ">
                            <a class="'.$mao.'"href="delete_user_gestor.php?id_usuario='.$usuario->id_usuario.'">
                            <svg height="30px" width="30px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" 
                            viewBox="0 0 30.143 30.143" xml:space="preserve">
                            <g>
                                <path style="fill:#030104;" d="M20.034,2.357v3.824c3.482,1.798,5.869,5.427,5.869,9.619c0,5.98-4.848,10.83-10.828,10.83
                                    c-5.982,0-10.832-4.85-10.832-10.83c0-3.844,2.012-7.215,5.029-9.136V2.689C4.245,4.918,0.731,9.945,0.731,15.801
                                    c0,7.921,6.42,14.342,14.34,14.342c7.924,0,14.342-6.421,14.342-14.342C29.412,9.624,25.501,4.379,20.034,2.357z"/>
                                <path style="fill:#030104;" d="M14.795,17.652c1.576,0,1.736-0.931,1.736-2.076V2.08c0-1.148-0.16-2.08-1.736-2.08
                                    c-1.57,0-1.732,0.932-1.732,2.08v13.496C13.062,16.722,13.225,17.652,14.795,17.652z"/>
                            </g>
                       </svg>
                            </a>
                        </td>
                    </tr>';
}

//CASO NÃO ENCONTRAR NENHUM RESULTADO
$resultados = !empty($resultados) ? $resultados :   '<div class = "div-tabela">
                                                        <span style="text-align:center;">Nenhum Usuário Encontrado</span>
                                                    </div>';

//GETS
unset($_GET['status']);
unset($_GET['pagina']);
$gets = http_build_query($_GET);

//PAGINAÇÃO
$paginacao  = '';
$paginas    = $obPagination->getPages();
foreach($paginas as $key=>$pagina){
    $cordefundo = $pagina['atual'] ? 'rgb(215, 91, 54)' : 'rgb(23,61,87)';
    
    $paginacao .=   '<a style="background-color:'.$cordefundo.';" class="botao_pagina" href="?pagina='.$pagina['pagina'].'&'.$gets.'">'
                        .$pagina['pagina'].
                    '</a>';
}

//INCLUI O MENU GESTOR
$tituloPagina = 'LISTA DE USUÁRIOS';
require './../includes/menu_gestor.php';
?>
<!--PÁGINA PRINCIPAL-->
<main class="main_listar">
    <form method="get" class="formulario_listar">
        <div>
            <label>Nome:</label>
            <input type="text" name="busca" value="<?=$busca?>">
        </div>

        <div>
            <label>Perfil:</label>
            <select name="filtroPerfil">
                <option value="">Todos</option>
                <option value="1" <?=$filtroPerfil == '1' ? 'selected' : ''?>>Administrador</option>
                <option value="2" <?=$filtroPerfil == '2' ? 'selected' : ''?>>Gestor</option>
                <option value="3" <?=$filtroPerfil == '3' ? 'selected' : ''?>>Colaborador</option>
            </select>
        </div>

        <div>
            <label>Status:</label>
            <select name="filtroStatus">
                <option value="">Todos</option>
                <option value="1" <?=$filtroStatus == '1' ? 'selected' : ''?>>Ativo</option>
                <option value="2" <?=$filtroStatus == '2' ? 'selected' : ''?>>Inativo</option>
            </select>
        </div>

        <div>
            <button class="botao_filtrar" type="submit">FILTRAR</button>
        </div>
    </form>

    <div class="div_tabela">
        <table>
            <thead>
                <tr>
                    <th>Nome</th>
                    <th>Email</th>
                    <th>Apelido</th>
                    <th>Perfil</th>
                    <th>Status</th>
                    <!-- <th colspan="2">Editar/Excluir</th> -->
                    <th>Editar</th>
                    <th>Ativar/Inativar</th>
                </tr>
            </thead>
            <tbody>
                <?=$resultados?>
            </tbody>
        </table>
    </div>

    <div class="listar_paginacao">
        <?=$paginacao?>
    </div>

</main>
<?php require './../includes/footer.php';?>