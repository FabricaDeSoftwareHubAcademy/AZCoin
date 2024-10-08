<?php
// echo '<pre>';
// print_r($var);
// echo '</pre>';
// exit;

//Inclui o autoload
require __DIR__.'../../vendor/autoload.php';

//DEPENDÊNCIAS
use \App\Entity\Carteira;
use \App\Entity\Campanha;
use \App\Entity\Feedback;
use \App\Entity\Produto;
use \App\Session\Login;
use \App\Entity\Usuario;

//OBRIGA O USUÁRIO A ESTAR LOGADO
Login::requireLogin();

//DADOS DO USUÁRIO LOGADO
$usuarioLogado = Login::getUsuarioLogado();


//CONSULTA A CARTEIRA
$obCarteira = Carteira::getCarteira($usuarioLogado['id_usuario']);
// echo '<pre>';
//  print_r($obCarteira);
//  echo '</pre>';
//  exit;

// CONSULTA A CAMPANHA ATIVA
$obCampanhaAtiva = Campanha::getCampanhaAtiva();



//SELECIONA TODOS OS FEEDBACKS FEITOS NA CAMPANHA VIGENTE
$obFeedbacksCampanhaAtiva = Feedback::getFeedbacks('id_campanha = '.$obCampanhaAtiva->id_campanha);



// $obFeedbacksRecebidos = Feedback::getFeedbacks('id_feedback = '.$obFeedback->destinatario_usuario);

//CONSULTA OS PRODUTOS
$objProduto = Produto::getProdutos(null, 'id_produto DESC', 3);

$FeedbackCards = Usuario::getCardsFeedback();

// $obFeedbacksFiltrados= Feedback:: getFeedbackAproRecufunction($id_usu, $remetente_ou_destinatario, $orderfiltro);

$result = '';
  foreach($objProduto as $Prod){
    $result .= '
              <div class="swiper-slide">
                <div class="project-img">
                  <img src=" '. $Prod->imagem.'" alt = " '. $Prod->nome.'">
                </div>
              </div>                    
    ';
}


$carrosel = '';
$cont=0;

foreach($FeedbackCards as $friends){
    $cont++;
    $carrosel.=
    ' 
    <a href="./feedback_user_historico.php">
        <img class="carousel-item carousel-item-'.$cont.' " src=" '.$friends->imagem.' "alt="'.$friends->nome.'" >
    </a>    
     ';
}



$azcoins_recebidos ='0.00 ';
$azcoins_enviar ='0.00';
$x = $obCarteira->valor_recebido_campanha;
$y = $obCarteira->saldo_doacao_usuario;
if($obCarteira->saldo_recebido_feedback > 0){
  $azcoins_recebidos = $obCarteira->saldo_recebido_feedback;
}
if($obCarteira->saldo_doacao_usuario > 0){
  $azcoins_enviar = $obCarteira->saldo_doacao_usuario;
} 

//INCLUI O MENU USUARIO
$tituloPagina = 'Fala ' .$usuarioLogado['apelido'].', você está na sua página';
require './../includes/menu_user.php';
?>
<!DOCTYPE html>
<html lang="pt-br">
<head>
  <meta charset="UTF-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>HOME</title>
  <link rel="stylesheet" href="../css/usuarios/home.cs ">
  <link rel="stylesheet" href="../css/usuarios/lojaHome.css">
  <link rel="stylesheet" href="../css/usuarios/historico_feedback_homeUser.css">
  <link rel="stylesheet" href="https://unpkg.com/swiper@10.2.0/swiper-bundle.min.css"/>
</head>
<body>
    <!--TELA HOME USUARIO-->
    <div class="main-home">

      <div class="dadosCards">
        <div class="azcard">

            <div class="background"></div>
            
            <div class="cardcontent">
              <div class="first-content">
                <svg width="100" height="23" viewBox="0 0 100 23" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M84.8223 20.5462V6.40381H87.1982V20.5462H84.8223Z" fill="white"/>
                  <path d="M68.5221 21.3329C67.0183 21.3329 65.705 21.017 64.5822 20.3852C63.4793 19.733 62.6174 18.826 61.9958 17.6643C61.3741 16.5026 61.0635 15.1677 61.0635 13.6595V13.2926C61.0635 11.764 61.3741 10.4291 61.9958 9.28771C62.6174 8.12602 63.4694 7.22923 64.552 6.59744C65.6549 5.94524 66.9081 5.61914 68.3117 5.61914C69.6751 5.61914 70.8778 5.91468 71.9207 6.50571C72.9834 7.07637 73.8154 7.91199 74.4168 9.01259C75.0182 10.0928 75.3192 11.387 75.3192 12.8951V13.8734H62.868C62.928 15.7281 63.4793 17.165 64.5221 18.184C65.5848 19.1827 66.918 19.682 68.5221 19.682C69.8855 19.682 70.9383 19.3661 71.68 18.7343C72.4417 18.1025 73.0232 17.328 73.4245 16.4109L75.0484 17.1446C74.7477 17.8172 74.3265 18.4796 73.7852 19.1318C73.2638 19.7635 72.5821 20.2935 71.7401 20.7215C70.8981 21.1291 69.8254 21.3329 68.5221 21.3329ZM62.8978 12.2837H73.4845C73.4042 10.6736 72.8931 9.44057 71.9505 8.58457C71.0083 7.70819 69.7952 7.27002 68.3117 7.27002C66.8477 7.27002 65.635 7.70819 64.6725 8.58457C63.71 9.44057 63.1185 10.6736 62.8978 12.2837Z" fill="white"/>
                  <path d="M76.9023 20.5462V6.46176H78.5898V8.22956H78.9388C79.2105 7.59199 79.6083 7.12833 80.1319 6.83852C80.6748 6.54871 81.4216 6.40381 82.3719 6.40381H84.0302V8.02671H82.1684C81.1015 8.02671 80.2481 8.32618 79.6083 8.92511C78.9681 9.52402 78.648 10.4611 78.648 11.7362V20.5462H76.9023Z" fill="white"/>
                  <path d="M94.4018 20.547C93.6017 20.547 92.9964 20.351 92.5862 19.9589C92.1962 19.5668 92.0013 19.018 92.0013 18.3123V7.84425H87.2002V6.25639H92.0013V0.904785H93.8479V6.25639H99.08V7.84425H93.8479V18.077C93.8479 18.6651 94.1556 18.9592 94.7714 18.9592H98.2798V20.547H94.4018Z" fill="white"/>
                  <path d="M38.0938 20.5472V5.92008H39.8861V7.84631H40.2567C40.5657 7.30457 41.0601 6.80295 41.7401 6.34146C42.4197 5.8599 43.3882 5.61914 44.6448 5.61914C45.8601 5.61914 46.8388 5.88999 47.5805 6.43177C48.3426 6.9735 48.8886 7.62561 49.218 8.38805H49.589C49.9392 7.62561 50.4851 6.9735 51.2268 6.43177C51.9681 5.88999 53.0086 5.61914 54.3477 5.61914C55.3778 5.61914 56.2737 5.82981 57.0361 6.25115C57.7983 6.65247 58.3957 7.22429 58.8285 7.96671C59.2608 8.68902 59.4774 9.54177 59.4774 10.5249V20.5472H57.623V10.7055C57.623 9.66217 57.2936 8.82948 56.6344 8.20747C55.9957 7.56539 55.0894 7.24436 53.9149 7.24436C52.7201 7.24436 51.7209 7.62561 50.9175 8.38805C50.1141 9.13046 49.7124 10.224 49.7124 11.6686V20.5472H47.8584V10.7055C47.8584 9.66217 47.529 8.82948 46.8695 8.20747C46.2311 7.56539 45.3247 7.24436 44.1503 7.24436C42.9554 7.24436 41.9563 7.62561 41.1529 8.38805C40.3495 9.13046 39.9478 10.224 39.9478 11.6686V20.5472H38.0938Z" fill="white"/>
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M14.4155 4.04834H14.4012C13.2468 4.04834 10.3014 4.18827 9.29951 4.38333C8.20552 4.59164 7.14694 4.9463 6.15441 5.43711C5.20577 5.90751 4.35675 6.5454 3.65127 7.3178C2.94528 8.08957 2.44926 9.05179 2.16321 10.2045L7.66987 10.0773C7.9479 9.10833 8.43474 8.40935 9.13049 7.98036C9.82623 7.55067 10.717 7.32342 11.8026 7.29868C12.257 7.28603 12.7117 7.3041 13.1634 7.35278C13.5367 7.38716 13.8986 7.49542 14.2267 7.67082C14.5151 7.8324 14.7412 8.08024 14.8709 8.37685C15.0081 8.68322 15.0289 9.10197 14.9335 9.62992C14.9108 9.86304 14.8351 10.0884 14.7118 10.29C14.5885 10.4916 14.4206 10.6644 14.2202 10.7961C13.7362 11.0984 13.1998 11.3139 12.6367 11.4321C11.9156 11.5973 11.1844 11.7173 10.4474 11.7915C9.63311 11.8799 8.80203 11.9912 7.95412 12.1255C7.10621 12.2605 6.25719 12.4347 5.40709 12.6481C4.58816 12.8519 3.80334 13.1671 3.07623 13.5842C2.36146 13.9985 1.73772 14.5442 1.24031 15.1903C0.720145 15.8561 0.368617 16.695 0.18572 17.7071C0.0189174 18.6258 0.0496438 19.4156 0.2779 20.0764C0.490759 20.7149 0.892 21.2793 1.43235 21.7005C2.01978 22.1442 2.7014 22.4569 3.42849 22.6165C4.2865 22.8103 5.16729 22.8938 6.04795 22.8646C7.31183 22.8315 8.56595 22.6405 9.77906 22.2964C11.03 21.9447 12.191 21.3441 13.1875 20.5334C13.159 20.8312 12.9285 21.779 12.9428 22.0652C12.9538 22.3515 12.9428 22.6685 13.0405 22.9049C13.0405 22.9049 19.898 22.8688 27.9364 22.8688C32.18 22.8688 33.3564 18.5318 33.3564 18.5318H22.3397C22.3397 18.5318 31.6609 11.464 33.5594 7.94961C34.0345 7.06972 34.1355 4.05576 34.1355 4.05576H14.7535C14.6745 4.05046 14.5582 4.04834 14.4133 4.04834H14.4155ZM20.5521 8.8836C20.5828 8.71397 20.6047 8.55175 20.6201 8.39275H26.5855C26.5855 8.39275 22.9839 11.3017 19.5974 14.2203L20.5521 8.88041V8.8836ZM6.52312 18.923C6.21356 18.9107 5.90864 18.8461 5.62217 18.7322C5.51577 18.6916 5.41918 18.6302 5.33837 18.552C5.25756 18.4737 5.1943 18.3803 5.15249 18.2774C5.07932 18.0767 5.10897 17.8222 5.24136 17.5141C5.37511 17.2 5.58202 16.9199 5.84604 16.6957C6.11614 16.4673 6.4179 16.2764 6.74261 16.1285C7.10294 15.9645 7.47843 15.8335 7.86413 15.7373C8.27236 15.632 8.6802 15.543 9.0877 15.4702C9.51862 15.3995 9.94621 15.3331 10.3705 15.2709C10.7956 15.2115 11.2053 15.1454 11.5996 15.0726C11.9947 15.0005 12.3678 14.9168 12.7288 14.8235C13.0578 14.7415 13.3757 14.6223 13.6759 14.4684L13.1118 15.7575C12.9849 16.0278 12.8405 16.2902 12.6794 16.543C12.4331 16.9187 12.126 17.2537 11.7697 17.5353C11.2917 17.9105 10.7564 18.212 10.1829 18.429C9.52446 18.6905 8.6908 18.8538 7.68197 18.9188C7.46247 18.9326 7.25726 18.9389 7.05536 18.9389C6.87208 18.9389 6.69322 18.9389 6.52093 18.922L6.52312 18.923Z" fill="#EF4E23"/>
                  <path d="M85.1649 5.16654C85.3933 5.46874 85.6752 5.6198 86.0102 5.6198C86.3607 5.6198 86.6423 5.46874 86.8556 5.16654C87.084 4.86435 87.1982 4.49165 87.1982 4.04843C87.1982 3.58508 87.084 3.21238 86.8556 2.93033C86.6423 2.62815 86.3607 2.47705 86.0102 2.47705C85.6752 2.47705 85.3933 2.62815 85.1649 2.93033C84.9365 3.21238 84.8223 3.58508 84.8223 4.04843C84.8223 4.49165 84.9365 4.86435 85.1649 5.16654Z" fill="#EF4E23"/>
                </svg>
                <svg width="65" height="13" viewBox="0 0 65 13" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path d="M6.3507 12L9.4707 2.419H11.9017L15.0217 12H13.2927L12.5777 9.673H8.7947L8.0797 12H6.3507ZM9.2237 8.243H12.1357L10.8357 3.966H10.5237L9.2237 8.243ZM15.7859 12V10.518L20.6349 4.226V3.901H16.0719V2.419H22.7799V3.901L17.9569 10.193V10.518H22.7799V12H15.7859ZM24.6025 12V2.419H26.6565L29.4125 9.374L32.1555 2.419H34.2095V12H32.5845V5.305L29.8675 12H28.9445L26.2275 5.305V12H24.6025ZM36.2949 12V2.419H42.8469V3.901H37.9199V6.241H42.4179V7.723H37.9199V10.518H42.8469V12H36.2949ZM44.5341 12V2.419H50.1111L51.5541 3.862V7.034L50.2411 8.347L51.8661 12H50.0201L48.5121 8.477H46.1591V12H44.5341ZM46.1591 7.047H49.3571L49.9291 6.475V4.421L49.3571 3.849H46.1591V7.047ZM53.3005 12V10.518H54.2755V3.901H53.3005V2.419H56.8755V3.901H55.9005V10.518H56.8755V12H53.3005ZM60.5094 12V3.901H57.8444V2.419H64.7994V3.901H62.1344V12H60.5094Z" fill="url(#paint0_linear_0_1)"/>
                  <path d="M6 1L1 12" stroke="#EF4E23"/>
                  <defs>
                  <linearGradient id="paint0_linear_0_1" x1="8.91887" y1="7.34781" x2="81.9494" y2="7.34781" gradientUnits="userSpaceOnUse">
                  <stop stop-color="white"/>
                  <stop offset="0.557292" stop-color="white" stop-opacity="0.56"/>
                  <stop offset="0.762566" stop-color="#F6F1F1" stop-opacity="0.46"/>
                  </linearGradient>
                  </defs>
                </svg>
              </div>
              <svg class="chip" width="46" height="34" viewBox="0 0 46 34" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path fill-rule="evenodd" clip-rule="evenodd" d="M35.6506 33.2065C40.9363 33.2065 45.261 29.6322 45.261 25.2641V8.0808C45.261 4.06296 41.6019 0.717471 36.9022 0.206543H8.54042C3.86117 0.717471 0.330078 4.06296 0.330078 8.0808V25.2641C0.330078 29.6324 4.50369 33.2065 9.78938 33.2065H35.6506Z" fill="url(#paint0_linear_3777_3166)"/>
                <path opacity="0.5" d="M29.1998 12.4101L24.8198 10.1342L27.1426 0.939453H36.5003C42.9382 1.88029 42.9382 7.85806 42.9382 7.85806H45.2607M29.1998 12.4101L31.6552 10.1449H45.2607M29.1998 12.4101V17.0403M29.1998 21.6704L24.7531 23.9297L26.9435 32.9313C26.9435 32.9313 34.4095 32.9254 37.1639 32.9395C42.838 31.7096 42.9557 26.1867 42.9557 26.1867H45.2607M29.1998 21.6704L31.5891 23.9411H45.2607M29.1998 21.6704V17.0403M29.1998 17.0403L42.8712 17.0833M45.2607 12.553L42.8714 12.5587L42.8926 21.5736H45.2607M0.260742 26.1258H2.45431C2.45431 26.1258 2.38802 31.497 7.90178 32.8845C9.2236 32.9232 18.0592 32.9039 18.0592 32.9039L20.2405 23.7592L15.9266 21.5738M15.9266 21.5738L15.948 12.4399M15.9266 21.5738L13.6701 23.8939H0.260742M15.948 12.4399L20.3582 10.3158L18.2494 0.961195C18.2494 0.961195 15.9597 0.96405 9.62177 0.966905C3.31719 1.00007 2.52059 7.92372 2.52059 7.92372H0.260742M15.948 12.4399L13.679 10.0843H0.260742M0.264102 12.4705H2.49922M2.49922 12.4705L2.52035 21.6346M2.49922 12.4705L2.50969 17.0115M2.52035 21.6346H0.260742M2.52035 21.6346L2.50969 17.0115M2.50969 17.0115L15.9373 17.0069" stroke="#002436" stroke-width="1.5" stroke-miterlimit="2.613"/>
                <defs>
                <linearGradient id="paint0_linear_3777_3166" x1="24.2932" y1="6.43988" x2="29.3288" y2="31.0488" gradientUnits="userSpaceOnUse">
                <stop stop-color="#FFF27A"/>
                <stop offset="0.782254" stop-color="#F4C33F"/>
                </linearGradient>
                </defs>
              </svg>
              <span id="black">AZ BLACK</span>
              <div class="last-content">
                <span><?php echo $usuarioLogado['nome']; ?></span>
                <img src="..\assets\moedaAZ.png" alt="">
              </div>
            </div>
        </div>

        <div class="saldosaz">
            <div class="azrecebidos">
              <div class="primeiro">
                <img src="../assets/moedaAZ.png" alt="">
                <span>AZcoins Recebidos:</span>
              </div>
              <h1 class="valor">R$ <?= $azcoins_recebidos; ?></h1>
              <span class="xpira">Expira em: <?= date("d/m/Y", strtotime($obCampanhaAtiva->data_final)); ?></span>
            </div>
            <div class="azparaenvios">
              <div class="primeiro">
                <img src="../assets/moedaAZ.png" alt="">
                <span>AZcoins à Enviar:</span>
              </div>
              <span class="valor">R$ <?= $azcoins_enviar; ?></span>
              <span class="xpira">Expira em: <?= date("d/m/Y", strtotime($obCampanhaAtiva->data_final)); ?></span>
            </div>
        </div>
      </div>

      <div class="carousel"> 
            <div class='carousel-container'>
                                        
            <?php 
            if(count($FeedbackCards) >= 5){
              echo $carrosel;                    
              echo '<div class="carousel-controls">';  
            }
            ?>
        
              </div>
            </div>

      </div>

    </div>
    <!-- SCRIPT -->
      
    <script src="../js/home.js"></script>
    <script src="../js/loja.js"></script>
</body>

<?php require '../includes/footer.php' ?>