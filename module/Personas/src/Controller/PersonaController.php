<?php

namespace Persona\Controller;

use Persona\Model\PersonaTable;
use Laminas\Mvc\Controller\AbstractActionController;
use Laminas\View\Model\ViewModel;
use Persona\Form\PersonaForm;
use Persona\Model\Persona;

class PersonaController extends AbstractActionController {

    private $tablePersona;

    // Add this constructor:
    public function __construct(PersonaTable $tablePersona) {
        $this->tablePersona = $tablePersona;
    }

    public function indexAction() {
        return new ViewModel([
            'personas' => $this->tablePersona->fetchAll(),
        ]);
    }

    public function addAction() {

        $listaDepartamentos = [];
        $departamentos = $this->tablePersona->fetchAllD();
        foreach ($departamentos as $departamento) {
            try {
                $listaDepartamentos[$departamento->idDepartamento] = $departamento->departamento;
            } catch (Laminas\Validator\Exception\RuntimeException $exc) {
                echo "Error: " . $exc->getMessage();
            }
        }

        $form = new PersonaForm($listaDepartamentos);
        $form->get('submit')->setValue('Add');

        $request = $this->getRequest();

        if (!$request->isPost()) {
            return ['form' => $form];
        }

        $persona = new Persona();
        $form->setInputFilter($persona->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return ['form' => $form];
        }

        $persona->exchangeArray($form->getData());
        $this->tablePersona->savePersona($persona);
        return $this->redirect()->toRoute('persona');
    }

    public function editAction() {
        $idPersona = (int) $this->params()->fromRoute('idPersona', 0);

        if (0 === $idPersona) {
            return $this->redirect()->toRoute('persona', ['action' => 'add']);
        }

        // Retrieve the album with the specified id. Doing so raises
        // an exception if the album is not found, which should result
        // in redirecting to the landing page.
        try {
            $persona = $this->tablePersona->getPersona($idPersona);
            $municipioEscogido = $this->tablePersona->getMuncipio($persona->idMunicipio);
//            var_dump($municipioEscogido);
        } catch (\Exception $e) {
            return $this->redirect()->toRoute('persona', ['action' => 'index']);
        }

        $listaDepartamentos = [];
        $departamentos = $this->tablePersona->fetchAllD();
        foreach ($departamentos as $departamento) {
            try {
                $listaDepartamentos[$departamento->idDepartamento] = $departamento->departamento;
            } catch (Laminas\Validator\Exception\RuntimeException $exc) {
                echo "Error: " . $exc->getMessage();
            }
        }

        $listaMunicipios = [];
        $municipios = $this->tablePersona->fetchAllM($municipioEscogido->idDepartamento);
        foreach ($municipios as $municipio) {
            try {
                $listaMunicipios[$municipio->idMunicipio] = $municipio->municipio;
            } catch (Laminas\Validator\Exception\RuntimeException $exc) {
                echo "Error: " . $exc->getMessage();
            }
        }

        $form = new PersonaForm($listaDepartamentos, $listaMunicipios);
        $form->bind($persona);
        $form->get('departamento')->setValue($municipioEscogido->idDepartamento);
//        $form->get('idMunicipio')->setValue($persona->idMunicipio);
//        $form->get('departamento')->setAttribute('disabled', true);
        $form->get('identificacion')->setAttribute('disabled', true);
//        $form->bind($depa);
        $form->get('submit')->setAttribute('value', 'Edit');

        $request = $this->getRequest();
        $viewData = ['idPersona' => $idPersona, 'form' => $form];

        if (!$request->isPost()) {
            return $viewData;
        }

        $form->setInputFilter($persona->getInputFilter());
        $form->setData($request->getPost());

        if (!$form->isValid()) {
            return $viewData;
        }

        try {
            $this->tablePersona->savePersona($persona);
        } catch (\Exception $e) {
            
        }

        // Redirect to album list
        return $this->redirect()->toRoute('persona', ['action' => 'index']);
    }

    public function deleteAction() {
        $idPersona = (int) $this->params()->fromRoute('idPersona', 0);
        if (!$idPersona) {
            return $this->redirect()->toRoute('persona');
        }

        $request = $this->getRequest();
        if ($request->isPost()) {
            $del = $request->getPost('del', 'No');

            if ($del == 'Yes') {
                $idPersona = (int) $request->getPost('idPersona');
                $this->tablePersona->deletePersona($idPersona);
            }

            // Redirect to list of albums
            return $this->redirect()->toRoute('persona');
        }

        return [
            'idPersona' => $idPersona,
            'persona' => $this->tablePersona->getPersona($idPersona),
        ];
    }

    public function verificarIdentificacionAction() {
        $response = [
            'existe' => 1,
        ];
        $identificacion = (int) $this->params()->fromQuery('identificacion', 0);

        if (!$this->tablePersona->getPersonaByIdentificacion($identificacion)) {
            $response['existe'] = 0;
        }
        return new \Laminas\View\Model\JsonModel($response);
    }

    public function getMunicipiosAction() {

        $idDepartamento = (int) $this->params()->fromQuery('idDepartamento', 0);
        $municipios = $this->tablePersona->fetchAllM($idDepartamento);

        $view = new ViewModel([
            'idMunicipios' => $municipios
        ]);

        $view->setTerminal(true);

        return $view;
    }
}
