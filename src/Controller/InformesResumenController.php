<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Filesystem\File;
use Cake\Http\Exception\NotFoundException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * InformesResumen Controller
 *
 * @property \App\Model\Table\InformesResumenTable $InformesResumen
 */
class InformesResumenController extends AppController
{


    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index'. 'destinosReport', 'downloadAsExcel', 'delete', 'propietariosReport'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'destinosReport', 'downloadAsExcel', 'delete', 'propietariosReport'])) {
                return true;
            }
        }

        return parent::isAuthorized($user);
    }


    public function index()
    {
        $seccion = 'Informes';
        $sub_seccion = 'Informes';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


    }

    public function destinosReport()
    {
        $seccion = 'Informes';
        $sub_seccion = 'Informes';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        $destinos_model = $this->loadModel('Destinos');

        $destinos = $destinos_model->find('list', [
            'keyField' => 'iddestinos',
            'valueField' => 'name'
        ])
            ->where(['active' => true])->toArray();

        $destinos[0] = 'Todos';
        $this->set(compact('destinos'));


        $productos_model = $this->loadModel('Productos');

        $productos = $productos_model->find('list', [
            'keyField' => 'idproductos',
            'valueField' => 'name'
        ])
            ->order(['name' => 'ASC'])
            ->where(['active' => true])->toArray();

        $productos[0] = 'Todos';


        $this->set(compact('productos'));

        if ($this->request->is('post')) {

            $fecha_inicio = $this->request->getData()['fecha_inicio'];
            $fecha_fin = $this->request->getData()['fecha_final'];

            //Aca puede venir 0 indicando todos
            $destino = $this->request->getData()['destino'];
            $producto = $this->request->getData()['productos'];

            $array_options = [
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'destinos_iddestinos' => $destino,
                'productos_idproductos' => $producto
            ];

            //Llamo a la tabla remitos
            $remitos_model = $this->loadModel('Remitos');
            $remitos = $remitos_model->find('RemitosByConditions', $array_options);
            $destinos_distinct = $remitos_model->find('DestinosByRemitos', $array_options);
            $productos_array = $remitos_model->find('GetProductosDistinctByRemitos', $remitos);

            //SI destinos distinc esta vacio, no se puede procesar porque no hay remitos
            if(count($destinos_distinct) == 0){

                //INformo que no hay nada
                $this->Flash->error(__('No existen Remitos con la información solicitada!'));

            } else {
                $destinos_with_remitos = $destinos_model->find('all', [
                    'contain' => ['Remitos' =>  function ($q) use ($remitos) {
                        return $q->where(['idremitos IN' => $remitos])
                            ->contain(['Parcelas', 'Productos', 'Lotes', 'Propietarios', 'Destinos']);
                    }]
                ])->where(['iddestinos IN' => $destinos_distinct]);



                //controlo que no venga vacio el array tmb
                if(count($destinos_with_remitos->toArray()) > 0){
                    $result_report = $this->processInformeResumenDestino($destinos_with_remitos, $productos_array, $array_options);

                    if($result_report != false){
                        $array_informe['fecha_inicio'] = $fecha_inicio;
                        $array_informe['fecha_fin'] = $fecha_fin;
                        $array_informe['categoria'] = 'Destinos';
                        $array_informe['users_idusers'] = $user_id;
                        $array_informe['empresas_idempresas'] = $id_empresa;

                        $array_informe['name'] = $result_report['name'];
                        $array_informe['path'] = $result_report['path'];

                        //el clasificador en la variable elegida en destino o propietarios
                        $array_informe['clasificador'] = $this->getNameDestinoById($destino);

                        //el producto puede ser filtrado en destinos, pero para propietario es todos
                        $array_informe['producto'] = $this->getNameProductoById($producto);

                        $entity_informe = $this->InformesResumen->newEntity();
                        //Creo la entidad y cargo a la base de datos
                        $entity_informe_resumen = $this->InformesResumen->patchEntity($entity_informe, $array_informe);

                        //DEvuelvo un arreglo con la operacion y el id

                        if ($this->InformesResumen->save($entity_informe_resumen)) {

                            //Mando la descarga

                            //return $this->redirect(['action' => 'destinosReportIndex', $entity_informe_resumen->idinformes_resumen]);
                        }

                    } else {
                        //Reportfile es false, entonces no se pudo crear el excel
                        //vuelvo a destinos report
                        $this->Flash->error(__('No se puede crear el archivo de informe, intente nuevamente!'));
                        return $this->redirect(['action' => 'destinosReport']);

                    }
                } else {
                    $this->Flash->error(__('No existen Remitos con la información solicitada!'));
                    return $this->redirect(['action' => 'destinosReport']);
                }

            }


        }


    }



    public function destinosReportIndex($id = null)
    {
        $seccion = 'Informes';
        $sub_seccion = 'Informes';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $informes_resumen = $this->InformesResumen->find('all', [])
        ->where(['categoria LIKE' => 'Destinos']);
        $this->set(compact('informes_resumen'));
        $id_informe = $id;
        if($id_informe != null){

            $this->set(compact('id_informe'));
        }

    }

    private function processInformeResumenDestino($destinos_with_remitos, $productos, $array_options)
    {
        $result = $this->createdExcel($destinos_with_remitos, $productos, $array_options);

        return $result;

    }

    private function createdExcel($destinos_with_remitos, $productos, $array_options)
    {
        $this->viewBuilder()->setLayout(null);
        $this->autoRender = false;

        $spreadsheet = new Spreadsheet();

        $myWorkSheet_maq =  $this->createdSheetResumen($spreadsheet, $destinos_with_remitos, $productos, $array_options);


        //utilizo el now, es mejor
        $nombre = "informe_resumen_" .hash('sha256' , (date("Y-m-d H:i:s")));

        $path = EXCELS . $nombre .'.xlsx';
        $path_short = EXCELS_SHORT . $nombre .'.xlsx';


        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        //consulto por el fileexist
        $file_test = new File($path);
        if($file_test->exists()){

            return ['name' => $nombre, 'path' => $path_short];
        } else {
            return false;
        }
    }

    private function createdSheetResumen($spreadsheet, $destinos_with_remitos, $productos, $array_options)
    {
        $this->viewBuilder()->setLayout(null);
        $this->autoRender = false;


        $font_bold = [
            'font' => [
                'bold' => true
            ]
        ];

        $styleArray = [
            'borders' => [
                'outline' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN
                    ,
                    'color' => ['argb' => '00000000'],
                ],
            ],
        ];

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        try {
            //TRigo el logo de la empresa
            $empresa_model = $this->loadModel('Empresas');
            $empresas_data = $empresa_model->get($id_empresa);

            //configuro el path y el file
            $path = null;

            if ($empresas_data->logo == null or empty($empresas_data->logo)) {
                //logo default
                $path = LOGOS . 'edificio.png';
            } else {
                $path = LOGOS . $empresas_data->logo;
            }


            $myWorkSheet_res = new Worksheet($spreadsheet, 'Resumen');

            $spreadsheet->addSheet($myWorkSheet_res, 0);
            $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');

            //Combino la primer celda para porner el titulo y configuro una altura aceptable
            $myWorkSheet_res->mergeCells('B1:I1');
            $myWorkSheet_res->getRowDimension('1')->setRowHeight(75);

            //EL titulo tiene que decir Informe de Costo - NOmbre de empresa
            $empresa_name = $empresas_data->name;
            $titulo = 'Informe por Destinos - ' . $empresa_name;

            $myWorkSheet_res->setCellValue('B1', $titulo);

            $myWorkSheet_res->getStyle('B1')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('B1')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('B1')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getStyle('B1')->getFont()->setBold(true)->setName('Arial')
                ->setSize(14);

            //DIBUJO EL LOGO

            $drawing = new Drawing();
            $drawing->setName('Logo');
            $drawing->setDescription('Logo');
            $drawing->setPath($path);
            $drawing->setHeight(75);
            $drawing->setWidth(75);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(45);
            $drawing->setOffsetY(15);
            $drawing->setWorksheet($myWorkSheet_res);


            //Represento la primer tabla
            $myWorkSheet_res->mergeCells('A2:I2');
            $myWorkSheet_res->getRowDimension('2')->setRowHeight(45);

            $myWorkSheet_res->mergeCells('A3:I3');
            $myWorkSheet_res->setCellValue('A3', 'Datos considerados en el análisis');

            $myWorkSheet_res->getStyle('A3')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A3')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('A3')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getRowDimension('3')->setRowHeight(25);

            //DEtalles del filtro

            //Creo el titulo
            $title = 'Periodo: ' . $array_options['fecha_inicio'] . ' a ' . $array_options['fecha_fin'];

            $dest_title = 'Propietarios: ' . 'Todos';

            $prod_title = 'Productos: ' . $this->getNameProductoById($array_options['productos_idproductos']);

            $title = $title . '; ' . $dest_title . '; ' . $prod_title;

            $myWorkSheet_res->mergeCells('A3:I3');
            $myWorkSheet_res->setCellValue('A3', $title);

            $myWorkSheet_res->getStyle('A3')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A3')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('A3')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getRowDimension('3')->setRowHeight(25);

            //EL indice empieza en 5
            $index = 5;

            //proceso la informacion


            foreach ($destinos_with_remitos as $des)
            {
                //COmo estoy en un nuevo destino creo su cabecera
                $cell_coord = 'A'.$index.':I'.$index;
                $myWorkSheet_res->mergeCells($cell_coord);
                $myWorkSheet_res->setCellValue('A'.$index, $des->name);
                $myWorkSheet_res->getStyle('A'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('A'.$index)->getAlignment()->setHorizontal('left');
                $myWorkSheet_res->getStyle('A'.$index)->getAlignment()->setVertical('center');
                $myWorkSheet_res->getRowDimension($index)->setRowHeight(25);

                //sumo el index porque tengo que bajar una celda
                $index++;

                //Creo la cabecera de los datos

                $myWorkSheet_res->setCellValue('A'.$index, 'N° Remito:');
                $myWorkSheet_res->setCellValue('B'.$index, 'Fecha');
                $myWorkSheet_res->setCellValue('C'.$index, 'Lote');
                $myWorkSheet_res->setCellValue('D'.$index, 'Parcela');
                $myWorkSheet_res->setCellValue('E'.$index, 'Propietario');
                $myWorkSheet_res->setCellValue('F'.$index, 'Fletero');
                $myWorkSheet_res->setCellValue('G'.$index, 'Producto');
                $myWorkSheet_res->setCellValue('H'.$index, 'Toneladas');
                $myWorkSheet_res->setCellValue('I'.$index, 'Precio/t');

                $myWorkSheet_res->getStyle('A'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('B'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('C'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('D'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('E'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('F'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('G'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('H'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('I'.$index)->applyFromArray($font_bold);

                $myWorkSheet_res->getRowDimension($index)->setRowHeight(17);

                $cell_coord = 'A'.$index.':I'.$index;

                foreach (range($cell_coord, $myWorkSheet_res->getHighestColumn()) as $col) {
                    $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
                }
                $index++;

                //Proceso los datos

                $total_destino_ton = null;

                $total_destino_facturacion = null;


                //recorro los remitos
                foreach ($productos as $prod){
                    $index_producto = 0;
                    $precio_promedio = null;
                    $total_ton_producto = null;
                    $flags_subtotal = false;
                    //COmparo los productos
                    foreach ($des->remitos as $rem){

                        if($rem->productos_idproductos == $prod) {
                            $flags_subtotal = true;
                            $myWorkSheet_res->setCellValue('A' . $index, $rem->remito_number);

                            $myWorkSheet_res->setCellValue('B' . $index, date("d-m-Y", strtotime($rem->fecha)));
                            $myWorkSheet_res->setCellValue('C' . $index, $rem->lote->name);
                            $myWorkSheet_res->setCellValue('D' . $index, $rem->parcela->name);
                            $myWorkSheet_res->setCellValue('E' . $index, $this->getNamePropietarioById($rem->propietario_idpropietarios));
                            $myWorkSheet_res->setCellValue('F' . $index, 'Fletero');
                            $myWorkSheet_res->setCellValue('G' . $index, $rem->producto->name);
                            $myWorkSheet_res->setCellValue('H' . $index, $rem->ton);
                            $myWorkSheet_res->setCellValue('I' . $index, $rem->precio_ton);

                            $myWorkSheet_res->getStyle('A' . $index)->applyFromArray($font_bold);
                            $myWorkSheet_res->getStyle('A' . $index)->getAlignment()->setHorizontal('center');
                            $myWorkSheet_res->getStyle('A' . $index)->getAlignment()->setVertical('center');


                            $precio_promedio = $precio_promedio + $rem->precio_ton;
                            $total_ton_producto = $total_ton_producto + $rem->ton;
                            $index_producto++;
                            $index++;
                        }
                    }
                    //Cargo los resultados parciales del producto

                    if($flags_subtotal){

                        $myWorkSheet_res->setCellValue('A'.$index, 'Subtotal:');
                        $myWorkSheet_res->setCellValue('B'.$index, $this->getNameProductoById($prod));

                        $pp = $precio_promedio / $index_producto;
                        $facturacion_prod = $total_ton_producto * $pp;

                        $myWorkSheet_res->setCellValue('G'.$index, $facturacion_prod);
                        $myWorkSheet_res->setCellValue('H'.$index, $total_ton_producto);
                        $myWorkSheet_res->setCellValue('I'.$index, $pp);

                        $myWorkSheet_res->getStyle('A'.$index)->applyFromArray($font_bold);
                        $myWorkSheet_res->getStyle('B'.$index)->applyFromArray($font_bold);
                        $myWorkSheet_res->getStyle('G'.$index)->applyFromArray($font_bold);
                        $myWorkSheet_res->getStyle('H'.$index)->applyFromArray($font_bold);
                        $myWorkSheet_res->getStyle('I'.$index)->applyFromArray($font_bold);

                        //sumo el total final
                        $total_destino_ton = $total_destino_ton + $total_ton_producto;
                        $total_destino_facturacion =  $total_destino_facturacion + $facturacion_prod;
                        $index++;
                    }

                }

                //Cargo el total Final del Destino
                $myWorkSheet_res->setCellValue('A'.$index, 'Totales');
                $myWorkSheet_res->setCellValue('B'.$index, $des->name);
                $myWorkSheet_res->setCellValue('H'.$index, $total_destino_ton);
                $myWorkSheet_res->setCellValue('G'.$index, $total_destino_facturacion);

                $myWorkSheet_res->getStyle('A'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('B'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('G'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('H'.$index)->applyFromArray($font_bold);
                $index++;

                $iva = $total_destino_facturacion / 100 * 21;
                $myWorkSheet_res->setCellValue('A'.$index, 'IVA:');
                $myWorkSheet_res->setCellValue('G'.$index, $iva);

                $index++;
                //Cargo el total Final del Destino
                $myWorkSheet_res->setCellValue('A'.$index, 'Totales');
                $myWorkSheet_res->setCellValue('B'.$index, $des->name . ' + IVA');
                $myWorkSheet_res->setCellValue('G'.$index, $total_destino_facturacion + $iva);

                $myWorkSheet_res->getStyle('A'.$index)->applyFromArray($font_bold);
                $myWorkSheet_res->getStyle('G'.$index)->applyFromArray($font_bold);
                $index++;
                $index++;
            } //Lavel del foreach de DEstinos




            return $myWorkSheet_res;

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }


        return false;
    }


    private function getNameProductoById($id = null)
    {
        if($id != 0){

            //TRaigo el nombre del remito
            $destinos_model = $this->loadModel('Productos');

            $destinos_data = $destinos_model->find('all', [
            ])->select(['name'])
                ->where(['idproductos' => $id])->toArray();

            $array = $destinos_data[0]->name;

            return $array;
        }
        $array = 'Todos';
        return $array;

    }
    private function getNameDestinoById($id = null)
    {

        if($id != 0){

            //TRaigo el nombre del remito
            $destinos_model = $this->loadModel('Destinos');

            $destinos_data = $destinos_model->find('all', [
            ])->select(['name'])
                ->where(['iddestinos' => $id])->toArray();

            return $destinos_data[0]->name;
        }
        $array = 'Todos';
        return $array;

    }

    private function getNameParcelaById($id = null)
    {
        if($id != 0){

            //TRaigo el nombre del remito
            $destinos_model = $this->loadModel('Parcelas');

            $destinos_data = $destinos_model->find('all', [
            ])->select(['name'])
                ->where(['idparcelas' => $id])->toArray();

            return $destinos_data[0]->name;
        }

        $array = 'Todos';
        return $array;
    }

    private function getNamePropietarioById($id = null)
    {
        if($id != 0){

            //TRaigo el nombre del remito
            $prop_model = $this->loadModel('Propietarios');

            $prop_data = $prop_model->find('all', [
            ])->select(['name', 'lastname', 'firstname', 'tipo'])
                ->where(['idpropietarios' => $id])->toArray();

            if($prop_data[0]->tipo == 'Persona'){
                return $prop_data[0]->firstname . ' ' . $prop_data[0]->lastname;
            }

            return $prop_data[0]->name;
        }

        $array = 'Todos';
        return $array;
    }

    public function downloadAsExcel($id = null)
    {
        $this->autoRender = false;

        //TRaigo el informe
        try {

            $informe_resumen = $this->InformesResumen->get($id);

            $path =  $path = WWW_ROOT.'files/excels/'.$informe_resumen->name . '.xlsx';


            $response = $this->response->withFile($path,
                ['download' => true]
            );

            //debug($path);

            return $response;

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));
        }
        catch (NotFoundException $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));
        }
        catch (Exception $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));
        }

        return $this->redirect(['action' => 'destinosReportIndex']);

    }


    public function downloadAsExcelWithoutUrl($id = null)
    {
        $this->autoRender = false;

        //TRaigo el informe
        try {

            $informe_resumen = $this->InformesResumen->get($id);

            $path =  $path = WWW_ROOT.'files/excels/'.$informe_resumen->name . '.xlsx';
            $file_name = $informe_resumen->name . '.xlsx';


            if(file_exists($path)){

                header("Cache-Control: public");
                header("Content-Description: File Transfer");
                header("Content-Disposition: attachment; filename=$file_name");
                header("Content-Type: application/zip");
                header("Content-Transfer-Encoding: binary");
                readfile($path);
                exit;
            }
            return true;


        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));
        }
        catch (NotFoundException $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));
        }
        catch (Exception $e){
            $this->Flash->error(__('NO se ha localizado el archivo!'));
        }

        return true;

    }

    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);

        try{
            //Variable usada para el sidebar
            $seccion = 'system';
            $sub_seccion = 'MetodCostos';
            $this->set(compact('seccion'));
            $this->set(compact('sub_seccion'));

            $informe =  $this->InformesResumen->get($id);

            //debo consultar si esta constante se usa en otro lugar antes de eliminar

            if ($this->InformesResumen->delete($informe)) {
                $this->Flash->success(__('El Registro ha sido eliminado.'));

                return $this->redirect(['action' => 'destinosReportIndex']);
            } else {
                $this->Flash->error(__('El Registro no pudo ser eliminada. Intente nuevamente.'));
            }

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }
        catch (Exception $e){
            $this->Flash->error(__('Error al eliminar los cambios. Intenta nuevamente'));
        }
    }


    public function propietariosReport()
    {

        $seccion = 'Informes';
        $sub_seccion = 'Informes';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        $propietarios_model = $this->loadModel('Propietarios');

        $propietarios = $propietarios_model->find('list', [
            'keyField' => 'idpropietarios',
            'valueField' => function ($q) {
                if($q->tipo == 'Empresa'){
                    return $q->name;
                } else {
                    return $q->firstname . ' ' . $q->firstname;
                }


            }
        ])
            ->where(['active' => true])->toArray();

        $propietarios[0] = 'Todos';
        $this->set(compact('propietarios'));


        if ($this->request->is('post')) {

            $fecha_inicio = $this->request->getData()['fecha_inicio'];
            $fecha_fin = $this->request->getData()['fecha_final'];
            //Aca puede venir 0 indicando todos
            $propietario = $this->request->getData()['propietario'];

            $array_options = [
                'fecha_inicio' => $fecha_inicio,
                'fecha_fin' => $fecha_fin,
                'propietarios_idpropietarios' => $propietario
            ];

            $remitos_model = $this->loadModel('Remitos');
            $remitos = $remitos_model->find('RemitosByConditions', $array_options);
            //$propietarios_distinct = $remitos_model->find('PropietariosByRemitos', $remitos);

            //LA PARCELA ES EL ELEMENTO ORGANIZADOR

            //$parcelas_distinct = $remitos_model->find('GetParcelasDistinctByRemitos', $remitos);
            debug(v);

            //SI destinos distinc esta vacio, no se puede procesar porque no hay remitos
            /*if(count($propietarios_distinct) == 0){

                //INformo que no hay nada
                $this->Flash->error(__('No existen Remitos con la información solicitada!'));

            } else {
                $propietarios_with_remitos = $propietarios_model->find('all', [
                    'contain' => ['Remitos' => function ($q) use ($remitos) {
                        return $q->where(['idremitos IN' => $remitos])
                            ->contain(['Parcelas', 'Productos', 'Lotes', 'Propietarios', 'Destinos']);
                    }]
                ])->where(['idpropietarios IN' => $propietarios_distinct]);

                //controlo que no venga vacio el array tmb
                if(count($propietarios_with_remitos->toArray()) > 0){
                    //$result_report = $this->processInformeResumenPropietarios($propietarios_with_remitos, $parcelas_distinct, $array_options);
                }

            }*/

            }

    }

    private function processInformeResumenPropietarios($propietarios_with_remitos, $parcelas_distinct, $array_options)
    {

        $result = $this->createdExcelResumenPropietarios($propietarios_with_remitos, $parcelas_distinct, $array_options);

        return $result;
    }

    private function createdExcelResumenPropietarios($propietarios_with_remitos, $parcelas_distinct, $array_options)
    {

        $this->viewBuilder()->setLayout(null);
        $this->autoRender = false;

        $spreadsheet = new Spreadsheet();

        $myWorkSheet_maq =  $this->createdSheetResumen($spreadsheet, $propietarios_with_remitos, $parcelas_distinct, $array_options);


        //utilizo el now, es mejor
        $nombre = "informe_resumen_" .hash('sha256' , (date("Y-m-d H:i:s")));

        $path = EXCELS . $nombre .'.xlsx';
        $path_short = EXCELS_SHORT . $nombre .'.xlsx';


        $writer = new Xlsx($spreadsheet);
        $writer->save($path);

        //consulto por el fileexist
        $file_test = new File($path);
        if($file_test->exists()){

            return ['name' => $nombre, 'path' => $path_short];
        } else {
            return false;
        }
    }


}
