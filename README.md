# Testes com Mocks utilizando [PHP Unit 9](https://phpunit.de)

### Especificações do Projeto
- Os usuários podem dar lances em um leilão
- Um leiloeiro avalia o leilão informando qual o maior valor de lance, qual o menor valor e os 3 maiores lances
- Um usuário não pode dar dois lances consecutivos
- Um usuário só pode dar no máximo 5 lances
- Testes devem ser criados para verificar essas especificações
- Leilões com mais de uma semana devem ser finalizados 

### Diferença entre os dublês de teste

|Tipo|Descrição|
|---|---|
|Mock|Tem como objetivo verificar o comportamento. São objetos pré-programados com expectativas das mensagens (métodos e seus parâmetors) que vão receber.|
|Dummy|Nunca são utilizados. Geralmente servem pra peencher requisitos dps parâmetros de algum método.|
|Fakes|Realmente funcionam, mas normalmente tomam algum tipo de atalho. Ex: Banco em memória|
|Stubs|Fornecem respostas pré-definidas ás chamadas dos métodos pré-definidos durante o teste. Normalmente não respondem ao que não foram explicidamente programados para responder.|
|Spies|Stubs que também gravam algum tipo de informação. Ex: Guardar quantidade de mensagens enviadas em um serviço de e-mail. |

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

### Verificar se o método foi chamado x vezes
```
        $leilaoDao->expects($this->exactly(2))
            ->method('atualiza');
```

### Verificar se o método foi chamado x vezes com certos parâmetros em cada vez
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
 
 ### Personalizando o Mock
 
 Definindo argumentos pro construtor, por exemplo: 
 ```
 $leilaoDao = $this->getMockBuilder(LeilaoDao::class)
        ->setConstructorArgs([new \PDO('sqlite::memory:')])
        ->getMock;
 ```
 
 ### Testando parâmetros de um método
 ```
 $this->enviadorEmail->expects($this->exactly(2))
            ->method('notificarTerminoLeilao')
            ->willReturnCallBack(function (Leilao $leilao) {
                static::assertTrue($leilao->estaFinalizado());
            });
 ```
 
 ### Importante
 - Evitar métodos estáticos porque não é possível criar dublês de testes com eles
 - Em caso de necessidade de métodos estáticos, pegar o retornar dele e receber por injeção de dependência
 - Utilizar injeção de dependência
 
 
 
