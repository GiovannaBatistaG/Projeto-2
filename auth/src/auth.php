<?php
class Auth{

    private array $users = [ 
    ['id' => 1, 'nome' => 'João Silva', 'email' => 'joao@email.com','senha' => 'SenhaForte1'],
    ['id' => 2, 'nome' => 'Maria Santos', 'email' => 'maria@email.com', 'senha' => 'SenhaForte2'],
    ['id' => 3, 'nome' => 'Pedro Souza', 'email' => 'pedro@email.com', 'senha' => 'SenhaForte3']
    ];
    
    public function __construct($id, $nome, $email, $senha){
        $this ->id = $id;
        $this ->nome = $nome;   
        $this ->email = $email;
        $this ->senha = $senha;
    }

    private function emailValidate(string $email): bool{
        return filter_var($email, FILTER_VALIDATE_EMAIL) !== false;
    }

    private function passwordValidate(string $password): bool{
        return strlen($password) >= 8
        && preg_match('/[A-Z]/', $password)
        && preg_match('/[a-z]/', $password);
    }

    private function emailDuplicate(string $email): bool{
        foreach($this->users as $user){
            if($user['email'] === $email){
                return true;
            }
        }
        return false;
    }
    
    public function registerUser(string $nome, string $email, string $senha): string{
        if(!$this->emailValidate($email)){
            return "Email inválido.";
        }
        if($this->emailDuplicate($email)){
            return "Email já cadastrado.";
        }
        if(!$this->passwordValidate($senha)){
            return "Senha deve ter no mínimo 8 caracteres, incluindo letras maiúsculas e minúsculas.";
        }
        $id = count($this->users) + 1;
        $hash = password_hash($senha, PASSWORD_DEFAULT);
        $this->users[] = ['id' => $id, 'nome' => $nome, 'email' => $email, 'senha' => $hash];

        return "Registro concluido";
    }

    public function loginUser(string $email, string $senha): string{
        foreach($this->users as $user){
            if($user['email'] === $email){
                if(password_verify($senha, $user['senha'])){
                    return "Login bem-sucedido. Bem-vindo, " . $user['nome'] . "!";
                }else{
                    return "Senha incorreta.";
                }
            }
        }
        return "Email não encontrado.";
    }
    public function resetPassword(int $id, string $newPassword): string{
        if(!$this->passwordValidate($newPassword)){
            return "Senha deve ter no mínimo 8 caracteres, incluindo letras maiúsculas e minúsculas.";
        }
        foreach($this->users as $user){
            if($user['id'] === $id){
                $user['senha'] = password_hash($newPassword, PASSWORD_DEFAULT);
                return "Senha redefinida com sucesso.";
            }
        }
        return "Usuário não encontrado.";
    }
}

?>