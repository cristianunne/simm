<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Event\Event;
use Cake\Filesystem\File;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * AnalisisCostosMaquinas Controller
 *
 */
class AnalisisCostosMaquinasController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'calculateCostosMaquina', 'checkMaquinaIsOkeyToCostos', 'viewCostoMaquina',
                'createdSheetResumen', 'createdSheetMaquinas', 'calculateCostosGruposCopy'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'calculateCostosMaquina', 'checkMaquinaIsOkeyToCostos', 'viewCostoMaquina',
                'createdSheetResumen', 'createdSheetMaquinas', 'calculateCostosGruposCopy'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }



    public function index()
    {

    }


    public function calculateCostosMaquina()
    {
        $seccion = 'system';
        $sub_seccion = 'Maquinas';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));


        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        //AL Igual que los otros, solo se cargaran las maquinas que tengan todos los datos para analizar
        //Primer paso es filtar las maquinas segun los remitos

        $array_options = [];


        $maquinas_model = $this->loadModel('Maquinas');
        $maquinas = $maquinas_model->find('list', [
            'keyField' => 'idmaquinas',
            'valueField' => ['marca', 'name']
        ])->order(['marca' => 'ASC']);


        $this->set(compact('maquinas'));


        //Traigo los lotes
        $tablaLotes = $this->loadModel('Lotes');
        $lotes =  $tablaLotes->find('all', [
            'contain' => []
        ])->where(['Lotes.active' => true, 'Lotes.empresas_idempresas' => $id_empresa]);
        $this->set(compact('lotes'));

        //Traigo los datos de los propietarios
        $tablaPropietarios = $this->loadModel('Propietarios');
        $propietarios =  $tablaPropietarios->find('all', [
            'contain' => []
        ])->where(['Propietarios.active' => true, 'Propietarios.empresas_idempresas' => $id_empresa]);
        $this->set(compact('propietarios'));

        //Traigo los datos de los propietarios
        $tablaDestinos = $this->loadModel('Destinos');

        $destinos =  $tablaDestinos->find('all', [
            'contain' => 'Users'
        ])->where(['Destinos.active' => true, 'Destinos.empresas_idempresas' => $id_empresa]);
        $this->set(compact('destinos'));


        if ($this->request->is('post')) {

            //Cargo las variables
            $array_options['maquina'] = $this->request->getData()['maquina'];
            $array_options['fecha_inicio'] = $this->request->getData()['fecha_inicio'];
            $array_options['fecha_fin'] = $this->request->getData()['fecha_final'];
            $array_options['lotes_idlotes'] = $this->request->getData()['lotes_idlotes'];
            $array_options['parcelas_idparcelas'] = $this->request->getData()['parcelas_idparcelas'];
            $array_options['propietarios_idpropietarios'] = $this->request->getData()['propietarios_idpropietarios'];
            $array_options['destinos_iddestinos'] = $this->request->getData()['destinos_iddestinos'];

            $maquinas_model = $this->loadModel('Maquinas');
            $maquina_data = $this->getDataMaquinaById($maquinas_model, $array_options);
            $data = $this->calculateCostosByMaquina($maquina_data, $array_options);

            debug($maquina_data->toArray()[0]['name']);

            $maq_marca = isset($maquina_data->toArray()[0]['marca']) ? $maquina_data->toArray()[0]['marca'] : null;
            $maq_name =  isset($maquina_data->toArray()[0]['name']) ? $maquina_data->toArray()[0]['name'] : null;

            //Almacenos los datos

            $informes_maquinas_model = $this->loadModel('InformesMaquinas');
            $infomes_maq_entity = $informes_maquinas_model->newEntity();

            //Datos para el excel
            //Datos de filtro
            $lote = $this->getNameOfLotesOnlyName($array_options);
            $parcela = $this->getNamesOfParcelasOnlyName($array_options);
            $propietarios = $this->getNamesOfPropietariosOnlyName($array_options);
            $destinos = $this->getNamesOfDestinosOnlyName($array_options);


            $result_metod = $data[0]['result_metod'];
            $costos = $data[0]['costos'];
            $costos_grupos = $data[1]['costos_groups'];
            $resumen = $data[2]['resumen'];

            $array_excel['filtro'] = [
                'maq_marca' => $maq_marca,
                'maq_name' => $maq_name,
                'fecha_inicio' => $array_options['fecha_inicio'],
                'fecha_fin' => $array_options['fecha_fin'],
                'lote' => $lote,
                'parcela' => $parcela,
                'propietarios' => $propietarios,
                'destinos' => $destinos
            ];

            $array_excel['result_metod'] = $result_metod;
            $array_excel['costos'] = $costos;
            $array_excel['costos_groups'] = $costos_grupos;
            $array_excel['resumen'] = $resumen;

            $infomes_maq_entity->maquinas_idmaquinas =  $array_options['maquina'];
            $infomes_maq_entity->fecha_inicio =  $array_options['fecha_inicio'];
            $infomes_maq_entity->fecha_fin =  $array_options['fecha_fin'];

            $infomes_maq_entity->users_idusers =  $user_id;
            $infomes_maq_entity->empresas_idempresas =  $id_empresa;
            //Cargo el lote,parcela, propietariodestino
            $infomes_maq_entity->lote =  $lote[0];
            $infomes_maq_entity->parcela =  $parcela[0];
            $infomes_maq_entity->propietario =  $propietarios[0];
            $infomes_maq_entity->destino =  $destinos[0];



            //Guardo el excel

            $result_excel =  $this->createExcel($array_excel);

            $path_excel = null;
            if($result_excel != false){
                $infomes_maq_entity->name =  $result_excel['name'];
                $infomes_maq_entity->path =  $result_excel['path'];
                $path_excel = $result_excel['path'];

            }

            $data['path_excel'] = $path_excel;

            if($informes_maquinas_model->save($infomes_maq_entity)){

                $this->prepareDataToShowView($maquina_data, $data, $session, $array_options);
            }

        }


    }

    private function createExcel($infomes_maq_entity)
    {


        $this->viewBuilder()->setLayout(null);
        $this->autoRender = false;

        $spreadsheet = new Spreadsheet();

        $myWorkSheet_maq =  $this->createSheetMaquina($spreadsheet, $infomes_maq_entity);


        //utilizo el now, es mejor
        $nombre = "informe_maq_" .hash('sha256' , (date("Y-m-d H:i:s")));

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

    private function createSheetMaquina($spreadsheet, $data_excel)
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
            $myWorkSheet_res->mergeCells('B1:J1');
            $myWorkSheet_res->getRowDimension('1')->setRowHeight(75);

            //EL titulo tiene que decir Informe de Costo - NOmbre de empresa
            $empresa_name = $empresas_data->name;
            $titulo = 'Informe de Costos - ' . $empresa_name;

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
            $myWorkSheet_res->mergeCells('A2:J2');
            $myWorkSheet_res->getRowDimension('2')->setRowHeight(45);


            $myWorkSheet_res->mergeCells('A3:F3');
            $myWorkSheet_res->setCellValue('A3', 'Datos considerados en el análisis');

            $myWorkSheet_res->getStyle('A3')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A3')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A3')->getAlignment()->setVertical('center');

            $myWorkSheet_res->setCellValue('A4', 'Grupo:');
            $myWorkSheet_res->setCellValue('B4', '');
            $myWorkSheet_res->setCellValue('C4', 'Período:');
            $myWorkSheet_res->setCellValue('D4', 'de: '. $data_excel['filtro']['fecha_inicio']. ' a '. $data_excel['filtro']['fecha_fin']);

            $myWorkSheet_res->setCellValue('A5', 'Lote:');
            $myWorkSheet_res->setCellValue('B5', $data_excel['filtro']['lote'][0]);
            $myWorkSheet_res->setCellValue('C5', 'Parcela:');
            $myWorkSheet_res->setCellValue('D5', $data_excel['filtro']['parcela'][0]);
            $myWorkSheet_res->setCellValue('E5', 'Propietario:');
            $myWorkSheet_res->setCellValue('F5', $data_excel['filtro']['propietarios'][0]);

            $myWorkSheet_res->setCellValue('A6', 'Industria destino:');
            $myWorkSheet_res->setCellValue('B6', $data_excel['filtro']['destinos'][0]);

            $myWorkSheet_res->getRowDimension('3')->setRowHeight(25);
            $myWorkSheet_res->getRowDimension('4')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('5')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('6')->setRowHeight(17);


            $myWorkSheet_res->getStyle('A4')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A4')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A4')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('C4')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('C4')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('c4')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('A5')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A5')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A5')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('C5')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('C5')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('C5')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('E5')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('E5')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('E5')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('A6')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A6')->getAlignment()->setHorizontal('left');
            $myWorkSheet_res->getStyle('A6')->getAlignment()->setVertical('center');



            $myWorkSheet_res->getStyle('B4')->getAlignment()->setIndent(1);
            $myWorkSheet_res->getStyle('F5')->getAlignment()->setIndent(1);


            foreach (range('A4:J4', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }
            foreach (range('A5:J5', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }
            foreach (range('A6:J6', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }

            ////////////////////////////////////////////////////////////////////////////////////////////////
            //Segundo BOX, LO HAGO CON BORDES, Empiezo desde A8
            $myWorkSheet_res->mergeCells('A7:J7');
            $myWorkSheet_res->getRowDimension('7')->setRowHeight(45);

            $myWorkSheet_res->getRowDimension('9')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('10')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('11')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('12')->setRowHeight(17);



            $myWorkSheet_res->mergeCells('A8:B8');
            $myWorkSheet_res->setCellValue('A8', 'Resumen de resultados');

            $myWorkSheet_res->getStyle('A8')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A8')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('A8')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getRowDimension('8')->setRowHeight(25);
            $myWorkSheet_res->getStyle('A8:B8')->applyFromArray($styleArray);


            $myWorkSheet_res->setCellValue('A9', 'Toneladas:');
            $myWorkSheet_res->setCellValue('A10', 'Toneladas en el periodo:');
            $myWorkSheet_res->setCellValue('A11', '% sobre total:');
            $myWorkSheet_res->setCellValue('A12', 'Horas de trabajo (h):');


            $toneladas = intval( $data_excel['resumen']['toneladas']);
            $myWorkSheet_res->getStyle('B9')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B9', $toneladas, DataType::TYPE_NUMERIC);

            $toneladas_total_preriodo = intval( $data_excel['resumen']['toneladas_total_preriodo']);
            $myWorkSheet_res->getStyle('B10')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B10', $toneladas_total_preriodo, DataType::TYPE_NUMERIC);

            $porc_sobre_total = intval( $data_excel['resumen']['porc_sobre_total']);
            $myWorkSheet_res->getStyle('B11')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B11', $porc_sobre_total, DataType::TYPE_NUMERIC);

            $horas_trabajo = intval( $data_excel['resumen']['horas_trabajo']);
            $myWorkSheet_res->getStyle('B12')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B12', $horas_trabajo, DataType::TYPE_NUMERIC);



            ////////////////////////////////////////////////////////////////////////////////////////////////
            //Segundo BOX, LO HAGO CON BORDES, Empiezo desde A8

            $myWorkSheet_res->mergeCells('A15:J15');
            $myWorkSheet_res->getRowDimension('15')->setRowHeight(45);

            $myWorkSheet_res->getRowDimension('17')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('18')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('19')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('20')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('21')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('22')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('23')->setRowHeight(17);




            $myWorkSheet_res->mergeCells('A16:B16');
            $myWorkSheet_res->setCellValue('A16', 'Resumen de Costos');

            $myWorkSheet_res->getStyle('A16')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A16')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('A16')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getRowDimension('16')->setRowHeight(25);
            $myWorkSheet_res->getStyle('A16:B16')->applyFromArray($styleArray);


            $myWorkSheet_res->setCellValue('A17', 'Costo Total ($/h):');
            $myWorkSheet_res->setCellValue('A18', 'Costo de la Maquina ($/h):');
            $myWorkSheet_res->setCellValue('A19', 'Costos Fijos ($/h):');
            $myWorkSheet_res->setCellValue('A20', 'Costos Semifijos ($/h):');
            $myWorkSheet_res->setCellValue('A21', 'Costos Variables ($/h)');
            $myWorkSheet_res->setCellValue('A22', 'Costo horario de personal ($/h):');
            $myWorkSheet_res->setCellValue('A23', 'Costo horario de administracion ($/h):');


            $costo_total = intval( $data_excel['costos']['costo_h']);
            $myWorkSheet_res->getStyle('B17')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B17', $costo_total, DataType::TYPE_NUMERIC);

            $costo_maq = intval( $data_excel['costos_groups']['costo_maquina']);
            $myWorkSheet_res->getStyle('B18')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B18', $costo_maq, DataType::TYPE_NUMERIC);

            $costos_fijos = intval( $data_excel['costos_groups']['costos_fijos']);
            $myWorkSheet_res->getStyle('B19')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B19', $costos_fijos, DataType::TYPE_NUMERIC);

            $costos_semifijos= intval( $data_excel['costos_groups']['costos_semifijos']);
            $myWorkSheet_res->getStyle('B20')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B20', $costos_semifijos, DataType::TYPE_NUMERIC);

            $costos_variables = intval( $data_excel['costos_groups']['costos_variables']);
            $myWorkSheet_res->getStyle('B21')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B21', $costos_variables, DataType::TYPE_NUMERIC);

            $costo_horario_personal = intval( $data_excel['costos_groups']['costo_horario_personal']);
            $myWorkSheet_res->getStyle('B22')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B22', $costo_horario_personal, DataType::TYPE_NUMERIC);

            $costo_administracion= intval( $data_excel['costos_groups']['costo_administracion']);
            $myWorkSheet_res->getStyle('B23')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B23', $costo_administracion, DataType::TYPE_NUMERIC);

            ////////////////////////////////////////////////////////////////////////////////////////////////
            //Tercero BOX, LO HAGO CON BORDES, Empiezo desde A26

            $myWorkSheet_res->mergeCells('A26:J26');
            $myWorkSheet_res->getRowDimension('26')->setRowHeight(45);


            $myWorkSheet_res->getRowDimension('28')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('29')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('30')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('31')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('32')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('33')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('34')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('35')->setRowHeight(17);
            $myWorkSheet_res->getRowDimension('36')->setRowHeight(17);


            $myWorkSheet_res->mergeCells('A27:B27');
            $myWorkSheet_res->setCellValue('A27', 'Costos detallados');

            $myWorkSheet_res->getStyle('A27')->applyFromArray($font_bold);
            $myWorkSheet_res->getStyle('A27')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('A27')->getAlignment()->setVertical('center');
            $myWorkSheet_res->getRowDimension('27')->setRowHeight(25);
            $myWorkSheet_res->getStyle('A27:B27')->applyFromArray($styleArray);


            $myWorkSheet_res->setCellValue('A28', 'Interés ($/h):');
            $myWorkSheet_res->setCellValue('A29', 'Seguro ($/h):');
            $myWorkSheet_res->setCellValue('A30', 'Depreciación de la máquina($/h):');
            $myWorkSheet_res->setCellValue('A31', 'Depreciación de los neumáticos ($/h):');
            $myWorkSheet_res->setCellValue('A32', 'Arreglos en la máquina ($/h)');
            $myWorkSheet_res->setCellValue('A33', 'Consumo de combustible ($/h):');
            $myWorkSheet_res->setCellValue('A34', 'Consumo de lubricantes ($/h):');
            $myWorkSheet_res->setCellValue('A35', 'Operador ($/h)');
            $myWorkSheet_res->setCellValue('A36', 'Mantenimiento ($/h):');



            $interes = intval( $data_excel['result_metod']['interes']);
            $myWorkSheet_res->getStyle('B28')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B28', $interes, DataType::TYPE_NUMERIC);

            $seguro = intval( $data_excel['result_metod']['seguro']);
            $myWorkSheet_res->getStyle('B29')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B29', $seguro, DataType::TYPE_NUMERIC);

            $dep_maq = intval( $data_excel['result_metod']['dep_maq']);
            $myWorkSheet_res->getStyle('B30')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B30', $dep_maq, DataType::TYPE_NUMERIC);

            $dep_neum = intval( $data_excel['result_metod']['dep_neum']);
            $myWorkSheet_res->getStyle('B31')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B31', $dep_neum, DataType::TYPE_NUMERIC);

            $arreglos_maq = intval( $data_excel['result_metod']['arreglos_maq']);
            $myWorkSheet_res->getStyle('B32')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B32', $arreglos_maq, DataType::TYPE_NUMERIC);

            $cons_comb= intval( $data_excel['result_metod']['cons_comb']);
            $myWorkSheet_res->getStyle('B33')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B33', $cons_comb, DataType::TYPE_NUMERIC);

            $cons_lub = intval( $data_excel['result_metod']['cons_lub']);
            $myWorkSheet_res->getStyle('B34')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B34', $cons_lub, DataType::TYPE_NUMERIC);

            $operador = intval( $data_excel['result_metod']['operador']);
            $myWorkSheet_res->getStyle('B35')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B35', $operador, DataType::TYPE_NUMERIC);

            $mantenimiento = intval( $data_excel['result_metod']['mantenimiento']);
            $myWorkSheet_res->getStyle('B36')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B36', $mantenimiento, DataType::TYPE_NUMERIC);


            return $myWorkSheet_res;

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }


    }

    public function viewCostoMaquina()
    {

        //Traigo los datos de la sesioN

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $data_result = $session->read('data');
        $maquina_data = $session->read('maquina');
        $array_options = $session->read('options');
        $resumen = $session->read('resumen');


        $maquinas_model = $this->loadModel('Maquinas');
        $maquinas = $maquinas_model->find('list', [
            'keyField' => 'idmaquinas',
            'valueField' => ['marca', 'name']
        ])->order(['marca' => 'ASC']);
        $this->set(compact('maquinas'));

        $this->set(compact('maquinas'));


        $maq = $array_options['maquina'];
        $this->set(compact('maq'));

        $fecha_inicio = date_create($array_options['fecha_inicio']);
        $this->set(compact('fecha_inicio'));

        $fecha_fin = date_create($array_options['fecha_fin']);
        $this->set(compact('fecha_fin'));

        $lotes = $this->getNamesOfLotes($array_options);
        $this->set(compact('lotes'));

        $lote_value = $array_options['lotes_idlotes'];
        $this->set(compact('lote_value'));

        $parcelas = $this->getNamesOfParcelas($array_options);
        $this->set(compact('parcelas'));

        $parcela_value = $array_options['parcelas_idparcelas'];
        $this->set(compact('parcela_value'));


        $propietarios = $this->getNamesOfPropietarios($array_options);
        $this->set(compact('propietarios'));

        $propietarios_value = $array_options['propietarios_idpropietarios'];
        $this->set(compact('propietarios_value'));



        $destinos = $this->getNamesOfDestinos($array_options);
        $this->set(compact('destinos'));

        $destinos_value = $array_options['destinos_iddestinos'];
        $this->set(compact('destinos_value'));


        $this->set(compact('resumen'));
        $this->set(compact('data_result'));


        $path_excel = $data_result['path_excel'];
        $this->set(compact('path_excel'));


        //Elimino todos los datos de la session

        $session->delete('data');
        $session->delete('maquina');
        $session->delete('options');
        $session->delete('resumen');


    }

    private function prepareDataToShowView($maquina_data = null, $data_result = null, $session = null, $array_options = null)
    {

        //Recorro la maquina
        $data_maquina = null;
        foreach ($maquina_data as $maq){

            $data_maquina['maquina'] = [
              'name' => $maq->name,
                'marca' => $maq->marca
            ];

            break;
        }

        $session->write('maquina', $data_maquina);
        $session->write('options', $array_options);
        $session->write('data', $data_result);
        return $this->redirect(['action' => 'viewCostoMaquina']);
    }


    private function getNamesOfLotes($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['lotes_idlotes'] != 0){

            //TRaigo el nombre del remito
            $lotes_model = $this->loadModel('Lotes');

            $lotes_data = $lotes_model->find('list', [
                'keyField' => 'idlotes',
                'valueField' => 'name'
            ])
                ->where(['idlotes' => $array_options['lotes_idlotes']])->toArray();

            return $lotes_data;
        }
        $array[0] = 'Todos';
        return $array;
    }

    private function getNameOfLotesOnlyName($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['lotes_idlotes'] != 0){

            //TRaigo el nombre del remito
            $lotes_model = $this->loadModel('Lotes');

            $lotes_data = $lotes_model->find('all', [
            ])
                ->select(['name'])
                ->where(['idlotes' => $array_options['lotes_idlotes']])->toArray();

            return $lotes_data;
        }
        $array[0] = 'Todos';
        return $array;

    }

    private function getNamesOfParcelas($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['parcelas_idparcelas'] != 0){

            //TRaigo el nombre del remito
            $parcelas_model = $this->loadModel('Parcelas');

            $parcela_data = $parcelas_model->find('list', [
                'keyField' => 'idparcelas',
                'valueField' => 'name'
            ])
                ->where(['idparcelas' => $array_options['parcelas_idparcelas']])->toArray();

            return $parcela_data;
        }
        $array[0] = 'Todos';
        return $array;

    }

    private function getNamesOfParcelasOnlyName($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['parcelas_idparcelas'] != 0){

            //TRaigo el nombre del remito
            $parcelas_model = $this->loadModel('Parcelas');

            $parcela_data = $parcelas_model->find('all', [
            ])
                ->select(['name'])
                ->where(['idparcelas' => $array_options['parcelas_idparcelas']])->toArray();

            return $parcela_data;
        }
        $array[0] = 'Todos';
        return $array;
    }

    private function getNamesOfPropietarios($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['propietarios_idpropietarios'] != 0){

            //TRaigo el nombre del remito
            $propietarios_model = $this->loadModel('Propietarios');

            $propietarios_data = $propietarios_model->find('list', [
                'keyField' => 'idpropietarios',
                'valueField' => 'name'
            ])
                ->where(['idpropietarios' => $array_options['propietarios_idpropietarios']])->toArray();

            return $propietarios_data;
        }
        $array[0] = 'Todos';
        return $array;
    }

    private function getNamesOfPropietariosOnlyName($array_options = null)
    {
        //NOmbre del Lote
        if($array_options['propietarios_idpropietarios'] != 0){

            //TRaigo el nombre del remito
            $propietarios_model = $this->loadModel('Propietarios');

            $propietarios_data = $propietarios_model->find('all', [

            ])->select(['name'])
                ->where(['idpropietarios' => $array_options['propietarios_idpropietarios']])->toArray();

            return $propietarios_data;
        }
        $array[0] = 'Todos';
        return $array;
    }



    private function getNamesOfDestinos($array_options = null)
    {

        //NOmbre del Lote
        if($array_options['destinos_iddestinos'] != 0){

            //TRaigo el nombre del remito
            $destinos_model = $this->loadModel('Destinos');

            $destinos_data = $destinos_model->find('list', [
                'keyField' => 'iddestinos',
                'valueField' => 'name'
            ])
                ->where(['iddestinos' => $array_options['destinos_iddestinos']])->toArray();

            return $destinos_data;
        }
        $array[0] = 'Todos';
        return $array;

    }


    private function getNamesOfDestinosOnlyName($array_options = null)
    {
        if($array_options['destinos_iddestinos'] != 0){

            //TRaigo el nombre del remito
            $destinos_model = $this->loadModel('Destinos');

            $destinos_data = $destinos_model->find('all', [
            ])->select(['name'])
                ->where(['iddestinos' => $array_options['destinos_iddestinos']])->toArray();

            return $destinos_data;
        }
        $array[0] = 'Todos';
        return $array;

    }

    private function calculateCostosByMaquina($maquina_with_data, $array_options)
    {

        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        $toneladas = $this->getToneladasExtraidas($maquina_with_data);
        $toneladas_total_preriodo = $this->getTotalToneladasPeriodo($array_options);
        $porc_sobre_total = $toneladas_total_preriodo == 0 ? null : (($toneladas * 100) / $toneladas_total_preriodo);
        $porc_sobre_total = $porc_sobre_total == null ? null : number_format($porc_sobre_total, 2);
        $horas_trabajo = $this->getHorasTrabajadas($maquina_with_data);

        //creo el arrary
        $resumen = [
            'toneladas' => $toneladas,
            'toneladas_total_preriodo' => $toneladas_total_preriodo,
            'porc_sobre_total' => $porc_sobre_total,
            'horas_trabajo' => $horas_trabajo
        ];


        $session->write('resumen', $resumen);

        //creo el arrary
        $resumen_['resumen'] = [
            'toneladas' => $toneladas,
            'toneladas_total_preriodo' => $toneladas_total_preriodo,
            'porc_sobre_total' => $porc_sobre_total,
            'horas_trabajo' => $horas_trabajo
        ];

        $var_and_constantes =  $this->calculatedVariablesAndConstantes($maquina_with_data);

        $metod_costos = $this->getMetodologiaCostosByMaquina($maquina_with_data);

        $constantes = $this->getConstantes();

        $costos_by_maquina = $this->calculateCostoByMaquina($var_and_constantes, $metod_costos, $constantes, $toneladas);

        $costos_groups = $this->calculateCostosByCategory($costos_by_maquina);

        $result = [$costos_by_maquina, $costos_groups, $resumen_];

        return $result;

    }


    private function getToneladasExtraidas($maquina_with_data)
    {
        //REcorro el remito
        //debug($maquina_with_data->toArray());
        $toneladas = null;
        foreach ($maquina_with_data as $maq)
        {
           foreach ($maq->remitos as $rem)
           {
               //debug($rem);
               $toneladas = $toneladas + $rem->ton;
           }

           break;
        }

        return $toneladas;
    }

    private function getTotalToneladasPeriodo($array_options = [])
    {
        //TRaigo los remitos, pero no filtro por maquina
        $remitos_model = $this->loadModel('Remitos');
        $remitos_array = $remitos_model->find('RemitosByConditionsAllData', $array_options);

        $toneladas = null;

        foreach ($remitos_array as $item) {

            $toneladas = $toneladas + $item->ton;
        }

        return $toneladas;
    }

    private function getHorasTrabajadas($maquina_with_data)
    {
        //debug($maquina_with_data->toArray());
        $horas = null;
        foreach ($maquina_with_data as $maq)
        {
            foreach ($maq->uso_maquinaria as $uso)
            {
                $horas = $horas + $uso->horas_trabajo;
            }

            break;
        }

        return $horas;
    }

    private function getHorasTrabajadasPeriodo($array_options = [])
    {


    }
    private function getMetodologiaCostosByMaquina($maquina_with_data)
    {

        $tabla_metodcostos = $this->loadModel('MetodCostos');

        $metod = null;

        foreach ($maquina_with_data as $maq){

            $met = $maq->costos_maquinas[0]->metod_costos_hashmetod_costos;
            $metod = $tabla_metodcostos->find('getMetodCostosByHash', ['hash' => $met])
            ->first();

            break;
        }

        return $metod;

    }

    private function calculatedVariablesAndConstantes($maquina_with_data = null)
    {

        //DEfino lOS NOMBRES DE LOS DATOS TEORICOS Y/O REALES, DEBEN COINCIDIR CON LOS DEFINIDOS EN LA MET/COST
        $VAD = NULL; $VUM = NULL; $HTU = NULL; $HME = NULL; $TIS = null;
        $FCI = null; $VAN = null; $HFU = null; $VUE = null;
        $CCT = NULL; $CLT = NULL; $COM = NULL; $COH = NULL;
        $LUB = NULL; $LUH = NULL; $SAL = NULL; $VUN = NULL;


        $result = null;

        foreach ($maquina_with_data as $maq)
        {

            //debug($maq);
            if($maq->costos_maquinas[0]->alquilada == false){
                $VAD = $maq->costos_maquinas[0]->val_adq;
                $TIS = $maq->costos_maquinas[0]->tasa_int_simple;
                $FCI = $maq->costos_maquinas[0]->factor_cor;
                $HTU = $maq->costos_maquinas[0]->horas_total_uso;
                $VAN = $maq->costos_maquinas[0]->val_neum;
                $HFU = $maq->costos_maquinas[0]->horas_efec_uso;
                $VUE = $maq->costos_maquinas[0]->vida_util;

                //DEpreciacion de los neumativos
                $VUN = $maq->costos_maquinas[0]->vida_util_neum;
            }

            //COmbustibles
            //Tengo que reccorer USO_MAQUINARIA y sumar los valores de combustibles y horas
            $COH = 0;
            $gastos_comb = 0;
            $gastos_lub = 0;

            $horas_tol = 0;
            $litros_comb_tol = 0;
            $litros_lub_tot = 0;
            $precio_comb = 0;
            $index_pre_com = 0;
            $precio_lub = 0;
            $index_pre_lu = 0;

            foreach ($maq->uso_maquinaria as $uso_maq){
                if(count($uso_maq->uso_comb_lub) > 0) {

                    $horas_tol = $horas_tol + $uso_maq->horas_trabajo;

                    foreach ($uso_maq->uso_comb_lub as $uso_comb){
                        //COnsulto por la categoria

                        if($uso_comb->categoria == 'Combustible'){
                            $litros_comb_tol = $litros_comb_tol + $uso_comb->litros;
                            $precio_comb = $precio_comb + $uso_comb->precio;
                            $index_pre_com++;


                        }
                        if($uso_comb->categoria == 'Lubricante'){
                            $litros_lub_tot = $litros_lub_tot + $uso_comb->litros;
                            $precio_lub = $precio_lub + $uso_comb->precio;
                            $index_pre_lu++;
                        }
                    }

                    $HME = $horas_tol;
                    $CCT = $litros_comb_tol;
                    $CLT = $litros_lub_tot;

                    //COH puede dar error de division por cero
                    if($HME > 0){
                        $COH = $CCT / $HME;
                    } else {
                        $COH = NULL;
                    }
                    //CAlculo el precio del combustible
                    if($index_pre_com > 0){
                        $COM = $precio_comb / $index_pre_com;
                    } else {
                        $COM = NULL;
                    }

                    if($index_pre_lu > 0){
                        $LUB = $precio_lub / $index_pre_lu;
                    } else {
                        $LUB = NULL;
                    }

                    if($HME > 0){
                        $LUH = $CLT / $HME;
                    } else {
                        $LUH = NULL;
                    }
                    $gastos_comb = $COM * $CCT;
                    $gastos_lub = $LUB * $CLT;

                    //Calculo el Salario, es la SUMA $maq->operarios_maquinas
                    //Recorro el Arreglo de sueldos
                    $suma_sal = null;

                    foreach ($maq->operarios_maquinas as $op_maq){
                        $suma_sal = $suma_sal + $op_maq->sueldo;
                    }

                    if($HME > 0){
                        $SAL = $suma_sal / $HME;
                    } else {
                        $SAL = null;
                    }

                }

                $precio_ton_aux = null;
                $toneladas =  null;
                $precio_ton = null;
                $i = 0;

                foreach ($maq->remitos as $remito) {

                    //debug('toneladas: '.$remito->ton);

                    $toneladas = $toneladas + $remito->ton;
                    $precio_ton_aux = $precio_ton_aux + $remito->precio_ton;
                    $i++;

                }

                if($i > 0){
                    $precio_ton = $precio_ton_aux / $i;
                }

                $gastos_sueldos = 0;

                foreach ($maq->operarios_maquinas as $oper)
                {
                    $gastos_sueldos = $gastos_sueldos + $oper->sueldo;
                }


            }



            $result = ['data' => [
                    'VAD' => $VAD,
                    'VUN' => $VUN,
                    'HTU' => $HTU,
                    'HME' => $HME,
                    'TIS' => $TIS,
                    'FCI' => $FCI,
                    'VAN' => $VAN,
                    'HFU' => $HFU,
                    'VUE' => $VUE,
                    'CCT' => $CCT,
                    'CLT' => $CLT,
                    'COM' => $COM,
                    'COH' => $COH,
                    'LUB' => $LUB,
                    'LUH' => $LUH,
                    'SAL' => $SAL ],
                    'gastos'=> [
                        'gasto_combustible' => $gastos_comb,
                        'gasto_lubricante' => $gastos_lub,
                        'gasto_sueldo' => $gastos_sueldos
                    ]];
            break;
        }

        return $result;

    }


    private function calculateCostoByMaquina($var_and_constantes, $metod_costos = null, $constantes = null, $toneladas_data = null)
    {


        $interes_for = $metod_costos['interes'];
        $seguro_for = $metod_costos['seguro'];
        $dep_maq_for = $metod_costos['dep_maq'];
        $dep_neum_for = $metod_costos['dep_neum'];
        $arreglos_maq_for = $metod_costos['arreglos_maq'];
        $cons_comb_for = $metod_costos['cons_comb'];
        $cons_lub_for = $metod_costos['cons_lub'];
        $operador_for = $metod_costos['operador'];
        $mantenimiento_for = $metod_costos['mantenimiento'];
        $administracion_for = $metod_costos['administracion'];

        //Preparo las constantes a utilizar
        $CSE = NULL; $CVD = NULL; $AME = NULL; $CMA = NULL; $CAD = NULL;

        if(isset($constantes['CSE'])){
            $CSE = $constantes['CSE'];
        }
        if(isset($constantes['CVD'])){
            $CVD = $constantes['CVD'];
        }
        if(isset($constantes['AME'])){
            $AME = $constantes['AME'];
        }
        if(isset($constantes['CMA'])){
            $CMA = $constantes['CMA'];
        }

        if(isset($constantes['CAD'])){
            $CAD = $constantes['CAD'];
        }
        //DEfino nuevamente las variables
        $VAD = null;
        $VUN = null;
        $HTU = null;
        $HME = null;
        $TIS = null;
        $FCI = null;
        $VAN = null;
        $HFU = null;
        $VUE = null;
        $CCT = null;
        $CTL = null;
        $COM = null;
        $COH = null;
        $LUB = null;
        $LUH = null;
        $SAL = null;


        $VAD = $var_and_constantes['data']['VAD'];
        $VUN = $var_and_constantes['data']['VUN'];
        $HTU = $var_and_constantes['data']['HTU'];
        $HME = $var_and_constantes['data']['HME'];
        $TIS = $var_and_constantes['data']['TIS'];
        $FCI = $var_and_constantes['data']['FCI'];
        $VAN = $var_and_constantes['data']['VAN'];
        $HFU = $var_and_constantes['data']['HFU'];
        $VUE = $var_and_constantes['data']['VUE'];
        $CCT = $var_and_constantes['data']['CCT'];
        $CLT = $var_and_constantes['data']['CLT'];
        $COM = $var_and_constantes['data']['COM'];
        $COH = $var_and_constantes['data']['COH'];
        $LUB = $var_and_constantes['data']['LUB'];
        $LUH = $var_and_constantes['data']['LUH'];
        $SAL = $var_and_constantes['data']['SAL'];

        $suma = "$CSE + $CVD";


        $interes = 0;
        $seguro = 0;
        $dep_maq = 0;
        $dep_neum = 0;
        $arreglos_maq = 0;
        $cons_comb = 0;
        $cons_lub = 0;
        $operador = 0;
        $mantenimiento = 0;
        $administracion = 0;

        //Evaluo las formulas
        if(@eval('return '. $interes_for. ';') != null and @eval('return '. $interes_for. ';') != ''){
            $interes = @eval('return '.$interes_for.';');
            if(is_nan($interes)){
                $interes = null;
            }
        }

        if(@eval('return '. $seguro_for. ';') != null and @eval('return '. $seguro_for. ';') != ''){
            $seguro = @eval('return '.$seguro_for.';');
            if(is_nan($seguro)){
                $seguro = null;
            }
        }

        if(@eval('return '. $dep_maq_for. ';') != null and @eval('return '. $dep_maq_for. ';') != ''){
            $dep_maq = @eval('return '.$dep_maq_for.';');

            if(is_nan($dep_maq)){
                $dep_maq = null;
            }
        }

        if(@eval('return '. $dep_neum_for. ';') != null and @eval('return '. $dep_neum_for. ';') != ''){
            $dep_neum = @eval('return '.$dep_neum_for.';');
            if(is_nan($dep_neum)){
                $dep_neum = null;
            }
        }

        if(@eval('return '. $arreglos_maq_for. ';') != null and @eval('return '. $arreglos_maq_for. ';') != ''){
            $arreglos_maq = @eval('return '.$arreglos_maq_for.';');

            if(is_nan($arreglos_maq)){
                $arreglos_maq = null;
            }
        }

        if(@eval('return '. $cons_comb_for. ';') != null and @eval('return '. $cons_comb_for. ';') != ''){
            $cons_comb = @eval('return '.$cons_comb_for.';');

            if(is_nan($cons_comb)){
                $cons_comb = null;
            }
        }

        if(@eval('return '. $cons_lub_for. ';') != null and @eval('return '. $cons_lub_for. ';') != ''){
            $cons_lub = @eval('return '.$cons_lub_for.';');
            if(is_nan($cons_lub)){
                $cons_lub = null;
            }

        }

        if(@eval('return '. $operador_for. ';') != null and @eval('return '. $operador_for. ';') != ''){
            $operador = @eval('return '.$operador_for.';');

            if(is_nan($operador)){
                $operador = null;
            }
        }

        if(@eval('return '. $mantenimiento_for. ';') != null and @eval('return '. $mantenimiento_for. ';') != ''){
            $mantenimiento = @eval('return '.$mantenimiento_for.';');
            if(is_nan($mantenimiento)){
                $mantenimiento = null;
            }
        }

        if(@eval('return '. $administracion_for. ';') != null and @eval('return '. $administracion_for. ';') != ''){
            $administracion = @eval('return '.$administracion_for.';');


            if(is_nan($administracion)){
                $administracion = null;
            }
        }

       $result['result_metod'] = [
            "interes" => $interes,
            "seguro" => $seguro,
            "dep_maq" => $dep_maq,
            "dep_neum" => $dep_neum,
            "arreglos_maq" => $arreglos_maq,
            "cons_comb" => $cons_comb,
            "cons_lub" => $cons_lub,
            "operador" => $operador,
            "mantenimiento" => $mantenimiento,
            "administracion" => $administracion
        ];

        //Calculo los valores a mostrar
        $costo_hora = $interes + $seguro + $dep_maq + $dep_neum + $arreglos_maq + $cons_comb + $cons_lub + $operador +
            $mantenimiento + $administracion;

        if($HME > 0){
            $prod_rend_h = $toneladas_data / $HME;
        } else {
            $prod_rend_h = null;
        }

        if($prod_rend_h > 0){
            $costo_t = $costo_hora / $prod_rend_h;
        } else {
            $costo_t = null;
        }


        $result["costos"] = [
            "costo_h" => $costo_hora,
            "prod_rend_h" => $prod_rend_h,
            "costo_ton" => $costo_t,
            "toneladas" => $toneladas_data,
            "horas" => $HME
        ];


        return $result;


    }

    private function calculateCostosByCategory($costos_by_maquina = null)
    {

        $costos_fijos = null;
        $costos_semifijos = null;
        $costos_variables = null;
        $costo_horario_personal = null;
        $costo_administracion = null;

        $costo_maquina = null;



        $costos_fijos = $costos_by_maquina['result_metod']['interes'] + $costos_by_maquina['result_metod']['seguro'];
        $costos_semifijos = $costos_by_maquina['result_metod']['dep_maq'] + $costos_by_maquina['result_metod']['dep_neum'] +
            $costos_by_maquina['result_metod']['arreglos_maq'];

        $costos_variables = $costos_by_maquina['result_metod']['cons_comb'] + $costos_by_maquina['result_metod']['cons_lub'];

        $costo_horario_personal = $costos_by_maquina['result_metod']['operador'] + $costos_by_maquina['result_metod']['mantenimiento'];

        $costo_administracion = $costos_by_maquina['result_metod']['administracion'];

        $costo_maquina = $costos_fijos + $costos_semifijos + $costos_variables;

        $costos_gruoup['costos_groups'] = [
            'costo_maquina' => $costo_maquina,
            'costos_fijos' => $costos_fijos,
            'costos_semifijos' => $costos_semifijos,
            'costos_variables' => $costos_variables,
            'costo_horario_personal' => $costo_horario_personal,
            'costo_administracion' => $costo_administracion
        ];



        return $costos_gruoup;

    }

    private function getConstantes()
    {
        //Traigo los datos de la sesioN
        $session = $this->request->getSession();
        $user_id = $session->read('Auth.User.idusers');
        $user_role = $session->read('Auth.User.role');
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');


        //Traigo las constantes
        $constantes_model = $this->loadModel('Constantes');

        $constantes = $constantes_model->find('list', [
            'keyField' => 'name',
            'valueField' => 'value'
        ])
            ->where(['active' => true, 'empresas_idempresas' => $id_empresa])
            ->toArray();

        return $constantes;
    }


    private function getRemitosArrayByConditions($options = [])
    {
        //model remitos
        $model_remitos = $this->loadModel('Remitos');
        $remitos_array = $model_remitos->find('RemitosByConditions', $options);

        return $remitos_array;
    }

    private function getMaquinasByRemitos($remitos = null)
    {

        $tabla_remitosmaq = $this->loadModel('RemitosMaquinas');
        $maquinas_array =  $tabla_remitosmaq->find('getMaquinasByRemitos', $remitos);

        return $maquinas_array;

    }

    public function prueba()
    {

        $worksgroup = 'Todos';
        $fecha_inicio = '2022-09-20';
        $fecha_final = '2023-02-20';
        //$fecha_inicio = '2020-09-20';
        //$fecha_final = '2020-02-20';
        $lotes = 'Todos';
        $parcelas = 'Todos';
        $propietarios = 1;
        $destinos ='Todos';


        $array_options['maquina'] = 1;
        $maquina = 1;
        $array_options['fecha_inicio'] = $fecha_inicio;
        $array_options['fecha_fin'] = $fecha_final;
        $array_options['lotes_idlotes'] = $lotes;
        $array_options['parcelas_idparcelas'] = $parcelas;
        $array_options['propietarios_idpropietarios'] = $propietarios;
        $array_options['destinos_iddestinos'] = $destinos;

        //$date_start = $array_options['fecha_inicio'];
       // $date_end = $array_options['fecha_fin'];


        //$conditions['fecha >='] = $fecha_inicio;
        //$conditions['fecha <='] = $fecha_final;

        //$conditions_usos['fecha >='] = date($date_start);
        //$conditions_usos['fecha <='] = date($date_end);

        $maquinas_model = $this->loadModel('Maquinas');
        $maquinas = $maquinas_model->find('GetMaquinaEvaluationsOnly', $array_options);


        if($maquinas)
        {
            $maquina_data = $this->getDataMaquinaById($maquinas_model, $array_options);


            debug($maquina_data->toArray());
           //$remitos_model = $this->loadModel('Remitos');
            /*$remitos_data = $remitos_model->find('all', [
               'contain' => ['RemitosMaquinas' => ['Maquinas' =>
                   function ($q) use ($maquina) {
                       return $q->where(['idmaquinas IN' => $maquina]);
                   }]]
           ])->where($conditions);*/





            //$remitos_data = $remitos_model->find('RemitosByConditionsMaquina', $array_options);
            //debug($remitos_data->toArray());

           /* $maquina_data = $maquinas_model->find('all', [
                'contain' => ['RemitosMaquinas']
            ]);
            debug($maquina_data->toArray());*/
        }

    }



    private function getDataMaquinaById($maquinas_model, $array_options)
    {
        $maquina =  $array_options['maquina'];
        $conditions['fecha >='] =  $array_options['fecha_inicio'];
        $conditions['fecha <='] =  $array_options['fecha_fin'];

        $conditions_rem['fecha >='] =  $array_options['fecha_inicio'];
        $conditions_rem['fecha <='] =  $array_options['fecha_fin'];


       if($array_options['lotes_idlotes'] != 0 && $array_options['lotes_idlotes'] != null) {
           $conditions_rem['lotes_idlotes'] = $array_options['lotes_idlotes'];
       }

       if($array_options['parcelas_idparcelas'] != 0 && $array_options['parcelas_idparcelas'] != null){
           $conditions_rem['parcelas_idparcelas'] = $array_options['parcelas_idparcelas'];
       }

       if($array_options['propietarios_idpropietarios'] != 0){
           $conditions_rem['propietarios_idpropietarios'] = $array_options['propietarios_idpropietarios'];
       }

       if($array_options['destinos_iddestinos'] != 0){
           $conditions_rem['destinos_iddestinos'] = $array_options['destinos_iddestinos'];
       }

       $maquina_data = $maquinas_model->find('all', [
            'contain' => ['Remitos' => function ($q) use ($conditions_rem) {
                return $q->where($conditions_rem);
            }, 'CostosMaquinas' =>    function ($q) {
                return $q->where(['CostosMaquinas.active' => true])
                    ->contain('CentrosCostos');
            },
                'UsoMaquinaria' =>
                    function ($q) use ($conditions){
                        return $q->where($conditions)
                            ->contain('UsoCombLub');
                    },
                'OperariosMaquinas' =>
                    function ($q) {
                        return $q->where(['OperariosMaquinas.active' => true])
                            ->contain('Operarios');
                    },
                'ArreglosMecanicos' =>
                    function ($q) use ($conditions) {
                        return $q->where($conditions);
                    }

            ]
        ])->where(['Maquinas.idmaquinas' => $maquina]);

        return $maquina_data;
    }


    /**
     * Este metodo verifica que la maquina tenga toda la info cargada para analizarse
     * @param $maquina
     */
    public function checkMaquinaIsOkeyToCostos()
    {
        $this->autoRender = false;
        //Recupero los objetos de la post

        $maquina = $_POST['maquina'];
        $fecha_inicio = $_POST['fecha_inicio'];
        $fecha_final = $_POST['fecha_final'];
        $lotes = $_POST['lotes'];
        $parcelas = $_POST['parcelas'];
        $propietarios = $_POST['propietarios'];
        $destinos = $_POST['destinos'];



        if($this->request->is('ajax')) {

            $array_options['maquina'] = $maquina;
            $array_options['fecha_inicio'] = $fecha_inicio;
            $array_options['fecha_fin'] = $fecha_final;
            $array_options['lotes_idlotes'] = $lotes;
            $array_options['parcelas_idparcelas'] = $parcelas;
            $array_options['propietarios_idpropietarios'] = $propietarios;
            $array_options['destinos_iddestinos'] = $destinos;


            $maquinas_model = $this->loadModel('Maquinas');
            $maquinas = $maquinas_model->find('GetMaquinaEvaluationsOnly', $array_options);


            if($maquinas)
            {
                return $this->json(['result' => true]);
            }

        }

        return $this->json(['result' => false]);
    }

}
