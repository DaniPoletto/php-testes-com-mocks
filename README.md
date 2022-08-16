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

### Verificar se o método foi executado x vezes
```
        $leilaoDao->expects($this->exactly(2))
            ->method('atualiza');
```

### Verificar se o método foi chamado x vezes com certos parâmetros
```
        $leilaoDao->expects($this->exactly(2))
            ->method('atualiza')
            ->withConsecutive(
                [$fiat],
                [$variant]
            );
```

### Verificar se o método é chamado com parâmetros especificos
```
        $leilaoDao->expects($this->once())
            ->method('atualiza')
            ->with($fiat);
 ```
 
 
