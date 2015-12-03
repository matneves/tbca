<?php

namespace TBCA\BackendBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use TBCA\BackendBundle\Form\AdminType;
use TBCA\BackendBundle\Entity\Admin;
use TBCA\BackendBundle\Entity\AlimentoNutriente;
use TBCA\BackendBundle\Entity\Nutriente;
use TBCA\BackendBundle\Entity\Alimento;
use TBCA\BackendBundle\Entity\AlimentoStaging;
use TBCA\BackendBundle\Entity\AlimentoNutrienteStaging;

class AdminController extends Controller
{
    public function adminAction($page)
    {
        if  (!isset($_SESSION['TBCA_Backend_Authorized']) ||
            (!isset($_SESSION['TBCA_Backend_Authorized']) &&
            ($page == "login" || !$page)))
        {
            $response = $this->forward('TBCABackendBundle:Admin:login');
        }
        else
        {
            switch ($page)
            {
                case '':
                $response = $this->forward('TBCABackendBundle:Admin:list');
                break;

                case 'list':
                $response = $this->forward('TBCABackendBundle:Admin:list');
                break;

                case 'login':
                $response = $this->forward('TBCABackendBundle:Admin:list');
                break;

                case 'logout':
                $response = $this->forward('TBCABackendBundle:Admin:logout');
                break;

                case 'categories':
                $response = $this->forward('TBCABackendBundle:Admin:categories');
                break;

                case (preg_match('/categories\/\d+/', $page) ? true : false):
                $response = $this->forward('TBCABackendBundle:Admin:category');
                break;

                case 'staging':
                $response = $this->forward('TBCABackendBundle:Admin:staging');
                break;

                case (preg_match('/staging\/\w+\/\d+\/(\d+)?/', $page) ? true : false):
                $response = $this->forward('TBCABackendBundle:Admin:stagechange');
                break;

                case (preg_match('/show\/\d+/', $page) ? true : false):
                $response = $this->forward('TBCABackendBundle:Admin:show');
                break;

                case (preg_match('/users\/(\d+|add)/', $page) ? true : false):
                $response = $this->forward('TBCABackendBundle:Admin:user');
                break;

                case (preg_match('/users\/?(\/remove\/\d+)?/', $page) ? true : false):
                $response = $this->forward('TBCABackendBundle:Admin:users');
                break;

                case 'add':
                $response = $this->forward('TBCABackendBundle:Admin:add');
                break;

                case 'remove':
                $response = $this->forward('TBCABackendBundle:Admin:remove');
                break;

                default:
                $response = $this->forward('TBCABackendBundle:Admin:notfound');
                break;
            }
        }
        if (isset($_SESSION['TBCA_Backend_Level']) && $_SESSION['TBCA_Backend_Level'] == 1) {
            $response->setContent(str_replace(
                "<!--usuarios-->",
                "<li><a href=\""
                .$this->get('router')->generate('admin', array('page' => 'users'))
                ."\">Usuários</a></li>",
                $response->getContent()
            ));
        }
        return $response;
    }

    public function loginAction(Request $request)
    {
        $admin = new Admin();
        $form = $this->createForm(new AdminType(), $admin);

        $form->handleRequest($request);

        if ($form->isValid())
        {
            $data = $form->getData();

            $row = $this->getDoctrine()
                ->getManager()
                ->getRepository('TBCABackendBundle:Admin')
                ->findOneByEmail($data->getEmail());

            if (!empty($row) && md5($data->getSenha()) == $row->getSenha())
            {
                $_SESSION['TBCA_Backend_Authorized'] = $row->getEmail();
                $_SESSION['TBCA_Backend_Level'] = $row->getSuper();
                return $this->forward(
                    'TBCABackendBundle:Admin:admin',
                    array('page' => 'list')
                );
            }
            else
            {
                $this->addFlash('danger','Dados inválidos!');
            }
        }

        return $this->render(
            'TBCABackendBundle:Admin:login.html.twig',
            array('form' => $form->createView())
        );
    }

    public function logoutAction()
    {
        if (isset($_SESSION['TBCA_Backend_Authorized']))
        {
            unset($_SESSION['TBCA_Backend_Authorized']);
            unset($_SESSION['TBCA_Backend_Level']);
            $this->addFlash('success','Você saiu com sucesso!');
        }
        return $this->redirectToRoute('admin');
    }

    public function listAction()
    {
        $alimentos = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:Alimento')
            ->findAll();

        return $this->render('TBCABackendBundle:Admin:list.html.twig', array(
            'alimentos' => $alimentos,
        ));
    }

    public function categoriesAction()
    {
        $categorias = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:Categoria')
            ->findAll();

        return $this->render('TBCABackendBundle:Admin:categories.html.twig', array(
            'categorias' => $categorias,
        ));
    }

    public function categoryAction(Request $request)
    {
        preg_match('/\d+$/',$request->getUri(),$matches);

        $categoria = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:Categoria')
            ->findOneById($matches[0]);

        $alimentos = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:Alimento')
            ->findByIdCategoria($categoria->getId());

        return $this->render('TBCABackendBundle:Admin:category.html.twig', array(
            'categoria' => $categoria,
            'alimentos' => $alimentos
        ));
    }

    public function stagingAction()
    {
        $staging = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:AlimentoNutrienteStaging')
            ->findAll();

        $alimentoStaging = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:AlimentoStaging')
            ->findAll();

        $proposals = array();
        $i = 0;
        foreach ($staging as $relation)
        {
            if ($relation->getEditor() != $_SESSION['TBCA_Backend_Authorized'])
            {
                $proposals[$i]['idAlimento'] = $relation->getIdAlimento();
                $proposals[$i]['idNutriente'] = $relation->getIdNutriente();
                $proposals[$i]['valor'] = $relation->getValor();

                $alimento = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('TBCABackendBundle:Alimento')
                    ->findOneById($relation->getIdAlimento());
                $proposals[$i]['alimento'] = $alimento->getNome();

                $nutriente = $this->getDoctrine()
                    ->getManager()
                    ->getRepository('TBCABackendBundle:Nutriente')
                    ->findOneById($relation->getIdNutriente());
                $proposals[$i]['nutriente'] = $nutriente->getNome();
                $proposals[$i]['unidade'] = $nutriente->getUnidade();
                $i++;
            }
        }

        $alimentoProposals = array();
        foreach ($alimentoStaging as $alimentoChange)
        {
            if ($alimentoChange->getEditor() != $_SESSION['TBCA_Backend_Authorized'])
            {
                $alimentoProposals[] = $alimentoChange;
            }
        }

        return $this->render('TBCABackendBundle:Admin:staging.html.twig',
        array(
          'proposals' => $proposals,
          'alimentoProposals' => $alimentoProposals
        ));
    }

    public function stagechangeAction(Request $request)
    {
        if ($this->getRequest()->headers->get('referer') ==
            $this->generateUrl('admin', array('page' => 'staging'), true))
        {
            preg_match('/(approve|reprove)\/\d+\/(\d+)?$/',$request->getUri(),$matches);
            $params = explode('/',$matches[0]);

            if ($params[2]) {
              $staging = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('TBCABackendBundle:AlimentoNutrienteStaging')
                  ->findOneBy(
                      array(
                          'idAlimento' => $params[1],
                          'idNutriente' => $params[2]
                      )
                  );

              if ($staging->getEditor() != $_SESSION['TBCA_Backend_Authorized'])
              {
                  $em = $this->getDoctrine()->getManager();

                  if ($params[0] == 'approve')
                  {
                      $alimentoNutriente = $this->getDoctrine()
                          ->getManager()
                          ->getRepository('TBCABackendBundle:AlimentoNutriente')
                          ->findOneBy(
                              array(
                                  'idAlimento' => $params[1],
                                  'idNutriente' => $params[2]
                              )
                          );

                      $alimentoNutriente->setValor($staging->getValor());

                      $em->persist($alimentoNutriente);
                      $em->remove($staging);

                  }
                  $em->remove($staging);

                  $em->flush();
              }
            } else {
              $alimentoStaging = $this->getDoctrine()
                  ->getManager()
                  ->getRepository('TBCABackendBundle:AlimentoStaging')
                  ->findOneById($params[1]);
              $em = $this->getDoctrine()->getManager();
              if($params[0] == 'approve') {
                if ($alimentoStaging->getValores()) {
                  $alimento = new Alimento;
                  $alimento->setNome($alimentoStaging->getNome());
                  $alimento->setIdCategoria(0);
                  $em->persist($alimento);
                  $em->flush();
                  $valores = json_decode($alimentoStaging->getValores(), true);
                  foreach ($valores as $key => $valor) {
                    $energia = 0;
                    $nome = $key;
                    if (preg_match('/energia/i',$nome)) {
                      $nome = "Energia";
                      if (preg_match('/kcal/i',$key))
                        $energia = 2;
                      else
                        $energia = 3;
                    }
                    $nome = str_replace('_',' ',$nome);
                    var_dump($nome);
                    $nutriente = $this->getDoctrine()
                        ->getManager()
                        ->getRepository('TBCABackendBundle:Nutriente')
                        ->findOneByNome($nome);

                    if ($valor) {
                      $alimentoNutriente = new AlimentoNutriente;
                      $alimentoNutriente->setIdAlimento($alimento->getId());
                      if ($energia) {
                        $alimentoNutriente->setIdNutriente($energia);
                      } else {
                        $alimentoNutriente->setIdNutriente($nutriente->getId());
                      }
                      $alimentoNutriente->setValor($valor);
                      $em->persist($alimentoNutriente);
                    }
                  }
                } else {
                  $alimento = $this->getDoctrine()
                      ->getManager()
                      ->getRepository('TBCABackendBundle:Alimento')
                      ->findOneByNome($alimentoStaging->getNome());
                  $em->remove($alimento);
                }
              }
              $em->remove($alimentoStaging);
              $em->flush();
          }
        }
        return $this->redirectToRoute('admin', array("page" => "staging"));
    }

    public function showAction(Request $request)
    {
        preg_match('/\d+$/',$request->getUri(),$matches);

        $alimento = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:Alimento')
            ->findOneById($matches[0]);

        $alimentoNutrientes = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:AlimentoNutriente')
            ->findByIdAlimento($alimento->getId());

        $valoresNutricionais = array();
        foreach ($alimentoNutrientes as $alimentoNutriente) {
            $nutriente = $this->getDoctrine()
                ->getManager()
                ->getRepository('TBCABackendBundle:Nutriente')
                ->findOneById($alimentoNutriente->getIdNutriente());

            $valoresNutricionais[] = array(
                'id' => $nutriente->getId(),
                'nome' => $nutriente->getNome(),
                'valor' => $alimentoNutriente->getValor(),
                'unidade' => $nutriente->getUnidade()
            );
        }

        if(count($request->request) > 0)
        {
            $mudancas = array();
            $i = 0;
            $hasChanges = false;
            foreach ($_POST as $key => $valorNutriente) {
                $match = preg_match('/(\d|\.)+/', $valorNutriente, $matches);
                if ($match) {
                    $valorNutriente = $matches[0];
                    if ($valoresNutricionais[$i]['valor'] != $valorNutriente)
                    {
                        $alimentoNutrienteStaging = new AlimentoNutrienteStaging();

                        $alimentoNutrienteStaging->setValor($valorNutriente);
                        $alimentoNutrienteStaging->setIdAlimento($alimento->getId());
                        $alimentoNutrienteStaging->setIdNutriente($valoresNutricionais[$i]['id']);
                        $alimentoNutrienteStaging->setDeletado(0);
                        $alimentoNutrienteStaging->setEditor($_SESSION['TBCA_Backend_Authorized']);

                        $em = $this->getDoctrine()->getManager();
                        $em->persist($alimentoNutrienteStaging);
                        $em->flush();

                        $hasChanges = true;
                    }
                }
                $i++;
            }
            if ($hasChanges == true)
            {
                $this->addFlash('warning','A sua alteração foi enviada e está aguardando aprovação');
            }
            else
            {
                $this->addFlash('danger','Nenhuma alteração enviada');
            }
        }

        return $this->render('TBCABackendBundle:Admin:show.html.twig', array(
            'alimento' => $alimento,
            'nutrientes' => $valoresNutricionais
        ));
    }

    public function notfoundAction()
    {
        throw $this->createNotFoundException('Página não encontrada');
    }

    public function usersAction(Request $request)
    {
        if ($_SESSION['TBCA_Backend_Level'] != 1)
        {
            return $this->redirectToRoute('admin', array("page" => "list"));
        }

        $adminRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:Admin');

        if (preg_match('/\d+$/',$request->getUri(),$matches))
        {
            $toRemove = $adminRepository->findOneById($matches[0]);

            $em = $this->getDoctrine()->getManager();
            $em->remove($toRemove);
            $em->flush();

            if ($toRemove->getEmail() == $_SESSION['TBCA_Backend_Authorized'])
            {
                return $this->redirectToRoute('admin', array("page" => "logout"));
            }
            return $this->redirectToRoute('admin', array("page" => "users"));
        }

        $usuarios = $adminRepository->findAll();

        return $this->render('TBCABackendBundle:Admin:users.html.twig', array(
            'usuarios' => $usuarios
        ));
    }

    public function userAction(Request $request)
    {
        if ($_SESSION['TBCA_Backend_Level'] != 1)
        {
            return $this->redirectToRoute('admin', array("page" => "list"));
        }

        $adminRepository = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:Admin');

        if (preg_match('/\d+$/',$request->getUri(),$matches))
        {
            $admin = $adminRepository->findOneById($matches[0]);
            $_SESSION['old_password'] = $admin->getSenha();
        }
        else
        {
            $admin = new Admin();
        }

        $form = $this->createFormBuilder($admin)
            ->add('email')
            ->add('senha', 'password', array(
                'attr' => array('value' => $admin->getSenha())
            ))
            ->add('nome')
            ->add('super', 'choice', array(
                'choices' => array(
                    1 => 'Sim',
                    0 => 'Não'
                )
            ))
            ->add('enviar', 'submit', array(
                'label' => 'Enviar',
                'attr' => array('class' => 'btn btn-lg btn-default btn-alterar')
            ))
            ->getForm();

        $form->handleRequest($request);

        if ($form->isValid()) {
            $newAdmin = $form->getData();
            if ($newAdmin->getSenha() == $_SESSION['old_password']) {
                $newAdmin->setSenha($newAdmin->getSenha());
                unset($_SESSION['old_password']);
            }
            else
            {
                $newAdmin->setSenha(md5($newAdmin->getSenha()));
            }

            $em = $this->getDoctrine()->getManager();
            $em->persist($newAdmin);
            $em->flush();

            return $this->redirectToRoute('admin',array('page' => 'users'));
        }

        return $this->render('TBCABackendBundle:Admin:user.html.twig', array(
            'form' => $form->createView(),
        ));
    }

    public function addAction()
    {
      $nutrientes = $this->getDoctrine()
          ->getManager()
          ->getRepository('TBCABackendBundle:Nutriente')
          ->findAll();

      if (isset($_POST['nome'])) {
          $alimentoStaging = new AlimentoStaging;
          $alimentoStaging->setNome($_POST['nome']);
          $alimentoStaging->setEditor($_SESSION['TBCA_Backend_Authorized']);
          $first = true;
          $valores = array();
          foreach ($_POST as $key => $nutriente) {
            if ($first) {
              $first = false;
              continue;
            }
            $valores[$key] = $nutriente;
          }
          $alimentoStaging->setValores(json_encode($valores));
          $em = $this->getDoctrine()->getManager();
          $em->persist($alimentoStaging);
          $em->flush();
          $this->addFlash('warning','A sua adição foi enviada e está aguardando aprovação');
      }

      return $this->render('TBCABackendBundle:Admin:add.html.twig', array(
          'nutrientes' => $nutrientes,
      ));
    }

    public function removeAction()
    {
      if (isset($_POST['id'])) {
        $alimento = $this->getDoctrine()
            ->getManager()
            ->getRepository('TBCABackendBundle:Alimento')
            ->findOneById($_POST['id']);

        $alimentoStaging = new AlimentoStaging;
        $alimentoStaging->setNome($alimento->getNome());
        $alimentoStaging->setEditor($_SESSION['TBCA_Backend_Authorized']);
        $alimentoStaging->setValores(0);
        $em = $this->getDoctrine()->getManager();
        $em->persist($alimentoStaging);
        $em->flush();
        $this->addFlash('warning','A sua remoção foi enviada e está aguardando aprovação');
      }
      return $this->forward('TBCABackendBundle:Admin:list');
    }
}
