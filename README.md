# Testes com Mocks utilizando [PHP Unit 9](https://phpunit.de)

### Especificações do Projeto
- Os usuários podem dar lances em um leilão
- Um leiloeiro avalia o leilão informando qual o maior valor de lance, qual o menor valor e os 3 maiores lances
- Um usuário não pode dar dois lances consecutivos
- Um usuário só pode dar no máximo 5 lances
- Testes devem ser criados para verificar essas especificações
- Leilões com mais de uma semana devem ser finalizados 

### Criar um mock/dublê da classe

Cria uma classe 'Falsa' que representará a classe LeilaoDao. 
```
$leilaoDao = $this->createMock(LeilaoDao::class);
```

### Configurar métodos
O método recuperarNaoFinalizados irá retornar um array:
```
        $leilaoDao->method('recuperarNaoFinalizado')
            ->willReturn([$fiat, $variant]);
```
