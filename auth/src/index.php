<?php
require_once 'src/auth.php';

$auth = new Auth();

//cadastro
echo $auth->registerUser('Ana Costa', 'ana@email.com', 'SenhaForte4');


?>