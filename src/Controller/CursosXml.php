<?php


namespace Alura\Cursos\Controller;


use Alura\Cursos\Entity\Curso;
use Doctrine\ORM\EntityManagerInterface;
use Nyholm\Psr7\Response;
use Psr\Http\Message\ResponseInterface;
use Psr\Http\Message\ServerRequestInterface;
use Psr\Http\Server\RequestHandlerInterface;

class CursosXml implements RequestHandlerInterface
{
    /**
     * @var \Doctrine\Persistence\ObjectRepository
     */
    private $repositorioDeCursos;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->repositorioDeCursos = $entityManager->getRepository(Curso::class);
    }

    public function handle(ServerRequestInterface $request): ResponseInterface
    {
        /** @var Curso[] $cursos */
        $cursos = $this->repositorioDeCursos->findAll();
        $cursosXml = new \SimpleXMLElement('<cursos/>');
        foreach ($cursos as $curso){
            $cursoXml = $cursosXml->addChild('curso');
            $cursoXml->addChild('id', $curso->getId());
            $cursoXml->addChild('descricao', $curso->getDescricao());
        }
        return new Response(200, ['Content-Type' => 'application/xml'], $cursosXml->asXML());
    }
}