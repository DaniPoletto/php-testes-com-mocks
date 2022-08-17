<?php

namespace Alura\Leilao\Tests\Domain;

use Alura\Leilao\Model\Leilao;
use PHPUnit\Framework\TestCase;
use Alura\Leilao\Service\Encerrador;
use Alura\Leilao\Service\EnviadorEmail;
use Alura\Leilao\Dao\Leilao as LeilaoDao;

class EncerradorTest extends TestCase
{
    /**
     * @var Avaliador
     */
    private $encerrador;
    /**
     * @var MockObject
     */
    private $enviadorEmail;
    /** 
     * @var Leilao
     */
    private $leilaoFiat;
    /**
     * @var Leilao
     */
    private $leilaoVariant;

    protected function setUp() : void
    {
        $this->leilaoFiat = new Leilao(
            'fiat', 
            new \DateTimeImmutable('8 days ago')
        );

        $this->leilaoVariant = new Leilao(
            'variant', 
            new \DateTimeImmutable('10 days ago')
        );

        $leilaoDao = $this->createMock(LeilaoDao::class);
        $leilaoDao->method('recuperarNaoFinalizados')
            ->willReturn([$this->leilaoFiat, $this->leilaoVariant]);
        $leilaoDao->method('recuperarFinalizados')
            ->willReturn([$this->leilaoFiat, $this->leilaoVariant]);
        $leilaoDao->expects($this->exactly(2))
            ->method('atualiza')
            ->withConsecutive(
                [$this->leilaoFiat],
                [$this->leilaoVariant]
            );
        
        $this->enviadorEmail = $this->createMock(EnviadorEmail::class);

        $this->encerrador = new Encerrador($leilaoDao, $this->enviadorEmail);
    }

    public function testLeiloesComMaisDeUmaSemanaDevemSerEncerrados()
    {
        $this->encerrador->encerra();

        $leiloes = [$this->leilaoFiat, $this->leilaoVariant];

        self::assertCount(2, $leiloes);
        self::assertTrue($leiloes[0]->estaFinalizado());
        self::assertTrue($leiloes[1]->estaFinalizado());
    }

    public function testDeveContinuarProcessamentoAoEncontrarErroAoEnviarEmail()
    {
        $e = new \DomainException('Erro ao enviar e-mail');
        $this->enviadorEmail->expects($this->exactly(2))
            ->method('notificarTerminoLeilao')
            ->willThrowException($e);

        $this->encerrador->encerra();
    }

    public function testSoDeveEnviarLeilaoPorEmailAposFinalizado()
    {
        $this->enviadorEmail->expects($this->exactly(2))
            ->method('notificarTerminoLeilao')
            ->willReturnCallBack(function (Leilao $leilao) {
                static::assertTrue($leilao->estaFinalizado());
            });
        
        $this->encerrador->encerra();
    }
}