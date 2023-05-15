<?php

namespace App\Utility;

use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Filesystem\File;
use Cake\ORM\TableRegistry;
use PhpOffice\PhpSpreadsheet\Cell\DataType;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

class ExcelProcesssing
{

    public function createInformeGrupos($metadata, $data_costos, $data_year_back, $data_six_month_back, $maquinas_data_back)
    {


        $get_function_class = new GetFunctions();

        $informes_model = TableRegistry::getTableLocator()->get('Informes');
        $entity_informe = $informes_model->newEntity();

        $result = $this->CostosGruposExcelInforme($metadata, $data_costos, $data_year_back, $data_six_month_back, $maquinas_data_back);



        if($result != false)
        {
            $metadata['path_file'] = $result['path'];
            $metadata['name'] = $result['name'];

            $entity_informe_ = $informes_model->patchEntity($entity_informe, $metadata);

            //DEvuelvo un arreglo con la operacion y el id

            if ($informes_model->save($entity_informe_)) {
                return ['id' => $entity_informe_->idinformes, 'informe' => true, 'path' => $result['path']];
            }
            return ['id' => '', 'informe' => false];
        } else {
            return ['id' => '', 'informe' => false];
        }


    }

    public function CostosGruposExcelInforme($metadata, $data_costos, $data_year_back, $data_six_month_back, $maquinas_data_back)
    {

        $spreadsheet = new Spreadsheet();

        $myWorkSheet_res = $this->createSheetResumenCostosGrupos($spreadsheet, $metadata, $data_costos, $data_year_back,
            $data_six_month_back);

        //$myWorkSheet_res =  $this->createdSheetResumen($data, $spreadsheet, $data_resumen, $data_year_back, $data_six_month_back);
        $myWorkSheet_maq =  $this->createdSheetMaquinas($data_costos, $spreadsheet);

        $myWorkSheet_eficiencia = $this->createSheetRendimiento($spreadsheet, $data_costos, $maquinas_data_back);

        $spreadsheet->removeSheetByIndex(3);

        //utilizo el now, es mejor
        $nombre = "informe_" .hash('sha256' , (date("Y-m-d H:i:s")));

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

    private function createSheetResumenCostosGrupos($spreadsheet, $metadata, $data_costos, $data_year_back, $data_six_month_back)
    {
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

        //obtengo el id de la empresa desde el metadata
        $id_empresa = $metadata['empresas_idempresas'];

        //Primero creo el worksheet antes de hacer la llamada al metodo que crea su header
        $myWorkSheet_res = new Worksheet($spreadsheet, 'Resumen');
        $spreadsheet->addSheet($myWorkSheet_res, 0);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');

        $myWorkSheet_res = $this->createInfoHeaderSheet($id_empresa, $myWorkSheet_res, $font_bold);

        //Llamo al metod que crea los metadatos
        $this->createInfoMetadataSheet($myWorkSheet_res, $metadata, $font_bold);

        //Llamo al metodo que crea el contenido
        $this->createResumenResultadosCostosGrupos($myWorkSheet_res, $data_costos, $styleArray, $font_bold);

        //Llamo al metodo que crea el box margenes y costos
        $this->createBoxCostosMargenesCostosGrupos($myWorkSheet_res, $data_costos, $styleArray, $font_bold);

        //Creo el box para el resumen por centro de costos
        $index_row =  $this->createBoxResumenByCentroCostos($myWorkSheet_res, $data_costos, $styleArray, $font_bold);

        //Creo el box para el ONeYearBack


        $index_row = $this->createBoxOneYearBack($myWorkSheet_res, $data_year_back, $index_row, $styleArray, $font_bold);

        //Creo el box para el Six Month
        $index_row = $this->createBoxSixMonthBack($myWorkSheet_res, null, $index_row, $styleArray, $font_bold);


        return $myWorkSheet_res;


    }


    private function createSheetMaquinasCostosGrupos($spreadsheet, $data_costos)
    {
        //CAculo el costo total para obtener el porcentaje
    }

    private function createInfoHeaderSheet($id_empresa, $myWorkSheet_res, $font_bold)
    {
        try{
            //TRigo el logo de la empresa
            $empresa_model = TableRegistry::getTableLocator()->get('Empresas');
            $empresas_data = $empresa_model->get($id_empresa);

            //configuro el path y el file
            $path = null;

            if($empresas_data->logo == null or empty($empresas_data->logo))
            {
                //logo default
                $path = LOGOS . 'edificio.png';
            }  else {
                $path = LOGOS . $empresas_data->logo;
            }


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
            $drawing->setPath( $path);
            $drawing->setHeight(75);
            $drawing->setWidth(75);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(45);
            $drawing->setOffsetY(15);
            $drawing->setWorksheet($myWorkSheet_res);

            return $myWorkSheet_res;

        } catch (InvalidPrimaryKeyException $e){
          return $e;

        } catch (RecordNotFoundException $e){
            return $e;
        }

    }

    private function createInfoMetadataSheet($myWorkSheet_res, $metadata, $font_bold)
    {

        $myWorkSheet_res->mergeCells('A2:J2');
        $myWorkSheet_res->getRowDimension('2')->setRowHeight(45);

        $myWorkSheet_res->mergeCells('A3:F3');
        $myWorkSheet_res->setCellValue('A3', 'Datos considerados en el análisis');

        $myWorkSheet_res->getStyle('A3')->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle('A3')->getAlignment()->setHorizontal('left');
        $myWorkSheet_res->getStyle('A3')->getAlignment()->setVertical('center');


        $myWorkSheet_res->setCellValue('A4', 'Grupo:');
        $myWorkSheet_res->setCellValue('B4', $metadata['worksgroups']);
        $myWorkSheet_res->setCellValue('C4', 'Período:');
        $myWorkSheet_res->setCellValue('D4', 'de: '. $metadata['fecha_inicio']. ' a '. $metadata['fecha_fin']);

        $myWorkSheet_res->setCellValue('A5', 'Lote:');
        $myWorkSheet_res->setCellValue('B5', $metadata['lote']);
        $myWorkSheet_res->setCellValue('C5', 'Parcela:');
        $myWorkSheet_res->setCellValue('D5', $metadata['parcela']);
        $myWorkSheet_res->setCellValue('E5', 'Propietario:');
        $myWorkSheet_res->setCellValue('F5', $metadata['propietario']);

        $myWorkSheet_res->setCellValue('A6', 'Industria destino:');
        $myWorkSheet_res->setCellValue('B6', $metadata['destino']);


        //Seteo el estilo de los datos
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

    }

    private function createBoxCostosMargenesCostosGrupos($myWorkSheet_res, $data_costos, $styleArray, $font_bold)
    {

        ////////////////////////////////////////////////////////////////////////////////////////////////
        //Tercer BOX, LO HAGO CON BORDES, Empiezo desde A16
        $myWorkSheet_res->getRowDimension('16')->setRowHeight(17);
        $myWorkSheet_res->getRowDimension('17')->setRowHeight(17);
        $myWorkSheet_res->getRowDimension('18')->setRowHeight(17);
        $myWorkSheet_res->getRowDimension('19')->setRowHeight(17);

        $myWorkSheet_res->mergeCells('A16:D16');
        $myWorkSheet_res->setCellValue('A16', 'Costos y márgenes de Elaboración y Transporte');

        $myWorkSheet_res->getStyle('A16')->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle('A16')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('A16')->getAlignment()->setVertical('center');
        $myWorkSheet_res->getRowDimension('16')->setRowHeight(25);
        $myWorkSheet_res->getStyle('A16:D16')->applyFromArray($styleArray);

        $myWorkSheet_res->setCellValue('A17', 'Costo de Elaboración ($/t):');
        $myWorkSheet_res->setCellValue('A18', 'Costo de Transporte ($/t):');


        $cos_prod = intval($data_costos['general']['categorias']['costos']['elaboracion']);
        $myWorkSheet_res->getStyle('B17')->getNumberFormat()->setFormatCode('#,##0');
        $myWorkSheet_res->setCellValueExplicit('B17', $cos_prod, DataType::TYPE_NUMERIC);

        $cos_trns = intval($data_costos['general']['categorias']['costos']['transporte']);
        $myWorkSheet_res->getStyle('B18')->getNumberFormat()->setFormatCode('#,##0');
        $myWorkSheet_res->setCellValueExplicit('B18', $cos_trns, DataType::TYPE_NUMERIC);

        $myWorkSheet_res->setCellValue('C17', 'MAI elaboración ($/t):');
        $myWorkSheet_res->setCellValue('C18', 'MAI transporte ($/t):');

        $mai_prod = floatval($data_costos['general']['categorias']['mai']['elaboracion']);
        $myWorkSheet_res->getStyle('D17')->getNumberFormat()->setFormatCode('#,##0');
        $myWorkSheet_res->setCellValueExplicit('D17', $mai_prod, DataType::TYPE_NUMERIC);

        $mai_tran = floatval($data_costos['general']['categorias']['mai']['transporte']);
        $myWorkSheet_res->getStyle('D18')->getNumberFormat()->setFormatCode('#,##0');
        $myWorkSheet_res->setCellValueExplicit('D18', $mai_tran, DataType::TYPE_NUMERIC);

        $myWorkSheet_res->getStyle('D17')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('D17')->getAlignment()->setVertical('center');
        $myWorkSheet_res->getStyle('D18')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('D18')->getAlignment()->setVertical('center');


    }

    private function createBoxOneYearBack($myWorkSheet_res, $data_year_back, $index_row, $styleArray, $font_bold)
    {

        //sumo 4 posiciones al index
        $index_row = $index_row + 4;

        $cell_a = 'A' . $index_row;
        $cell_b = 'B' . $index_row;

        //titulo
        $myWorkSheet_res->mergeCells($cell_a. ':' . $cell_b);
        $myWorkSheet_res->setCellValue($cell_a, 'Resumen de Resultados / 1 año (Toneladas)');

        $myWorkSheet_res->getStyle($cell_a)->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle($cell_a)->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle($cell_a)->getAlignment()->setVertical('center');
        $myWorkSheet_res->getRowDimension($index_row)->setRowHeight(25);
        $myWorkSheet_res->getStyle($cell_a. ':' . $cell_b)->applyFromArray($styleArray);

        $index_row++;
        //escribo el texto de la primer celda de resultados

        $myWorkSheet_res->setCellValue('A' . $index_row, 'Toneladas extraídas:');

        //el Primer resultado es la sumatoria de las toneladas
        $toneladas_total_1year = null;

        if($data_year_back != null)
        {
            $celda_value = 'B' . $index_row;
            $toneladas_total_1year = floatval($data_year_back['general']['toneladas']);

            $myWorkSheet_res->getStyle($celda_value)->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit($celda_value, $toneladas_total_1year, DataType::TYPE_NUMERIC);

            $index_row++;
            $costo_total_1year = null;

            //Cargo los valores de los centros de costos
            foreach ($data_year_back['centros'] as $centro)
            {

                $celda = 'A' . $index_row;
                $myWorkSheet_res->setCellValue($celda, $centro['name']);

                //Completo los valores
                $celda_value = 'B' . $index_row;
                $costo = floatval($centro['costo_total']);
                $myWorkSheet_res->getStyle($celda_value)->getNumberFormat()->setFormatCode('#,##0');
                $myWorkSheet_res->setCellValueExplicit($celda_value, $costo, DataType::TYPE_NUMERIC);

                $index_row++;

            }
        }




        return $index_row;
    }

    private function createBoxSixMonthBack($myWorkSheet_res, $data_six_month_back, $index_row, $styleArray, $font_bold)
    {
        //sumo 4 posiciones al index
        $index_row = $index_row + 4;

        $cell_a = 'A' . $index_row;
        $cell_b = 'B' . $index_row;

        //titulo
        $myWorkSheet_res->mergeCells($cell_a. ':' . $cell_b);
        $myWorkSheet_res->setCellValue($cell_a, 'Resumen de Resultados / 6 meses (Toneladas)');

        $myWorkSheet_res->getStyle($cell_a)->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle($cell_a)->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle($cell_a)->getAlignment()->setVertical('center');
        $myWorkSheet_res->getRowDimension($index_row)->setRowHeight(25);
        $myWorkSheet_res->getStyle($cell_a. ':' . $cell_b)->applyFromArray($styleArray);

        $index_row++;
        //escribo el texto de la primer celda de resultados

        $myWorkSheet_res->setCellValue('A' . $index_row, 'Toneladas extraídas:');

        //el Primer resultado es la sumatoria de las toneladas
        $toneladas_total_1year = null;

        if($data_six_month_back != null)
        {
            $celda_value = 'B' . $index_row;
            $toneladas_total_1year = floatval($data_six_month_back['general']['toneladas']);

            $myWorkSheet_res->getStyle($celda_value)->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit($celda_value, $toneladas_total_1year, DataType::TYPE_NUMERIC);

            $index_row++;
            $costo_total_1year = null;

            //Cargo los valores de los centros de costos
            foreach ($data_six_month_back['centros'] as $centro)
            {

                $celda = 'A' . $index_row;
                $myWorkSheet_res->setCellValue($celda, $centro['name']);

                //Completo los valores
                $celda_value = 'B' . $index_row;
                $costo = floatval($centro['costo_total']);
                $myWorkSheet_res->getStyle($celda_value)->getNumberFormat()->setFormatCode('#,##0');
                $myWorkSheet_res->setCellValueExplicit($celda_value, $costo, DataType::TYPE_NUMERIC);

                $index_row++;

            }
        }





        return $index_row;
    }

    private function createBoxResumenByCentroCostos($myWorkSheet_res, $data_costos, $styleArray, $font_bold)
    {
        ////////////////////////////////////////////////////////////////////////////////////////////////
        //Cuarto BOX - RESUMEN 1 A;O ATRAS - COMIENZA EN LA CELDA 23

        $myWorkSheet_res->getRowDimension('23')->setRowHeight(17);
        $myWorkSheet_res->getRowDimension('24')->setRowHeight(17);
        $myWorkSheet_res->getRowDimension('25')->setRowHeight(17);
        $myWorkSheet_res->getRowDimension('26')->setRowHeight(17);

        //titulo
        $myWorkSheet_res->mergeCells('A23:B23');
        $myWorkSheet_res->setCellValue('A23', 'Resumen por Centro de Costos (Toneladas)');

        $myWorkSheet_res->getStyle('A23')->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle('A23')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('A23')->getAlignment()->setVertical('center');
        $myWorkSheet_res->getRowDimension('23')->setRowHeight(25);
        $myWorkSheet_res->getStyle('A23:B23')->applyFromArray($styleArray);

        $myWorkSheet_res->setCellValue('A24', 'Toneladas extraídas:');


        foreach (range('A23:B23', $myWorkSheet_res->getHighestColumn()) as $col) {
            $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
        }

        foreach (range('A24:B24', $myWorkSheet_res->getHighestColumn()) as $col) {
            $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
        }



        $index_row = 24;

        foreach ($data_costos['centros'] as $centro)
        {
            $celda = 'A' . $index_row;
            $myWorkSheet_res->setCellValue($celda, $centro['name']);

            //Completo los valores

            $celda_value = 'B' . $index_row;
            $toneladas = floatval($centro['toneladas_total']);
            $myWorkSheet_res->getStyle($celda_value)->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit($celda_value, $toneladas, DataType::TYPE_NUMERIC);
            $index_row++;
        }

        return $index_row;

    }


    private function createResumenResultadosCostosGrupos($myWorkSheet_res, $data_costos, $styleArray, $font_bold)
    {
        //Array informe se corresponde con los metadatos
        //Segundo BOX, LO HAGO CON BORDES, Empiezo desde A8
        //debug($data_costos);

        //$data_costos son los datos de los costos calculados y organizados

        $myWorkSheet_res->mergeCells('A7:J7');
        $myWorkSheet_res->getRowDimension('7')->setRowHeight(45);
        $myWorkSheet_res->getRowDimension('9')->setRowHeight(17);
        $myWorkSheet_res->getRowDimension('10')->setRowHeight(17);
        $myWorkSheet_res->getRowDimension('11')->setRowHeight(17);
        $myWorkSheet_res->getRowDimension('12')->setRowHeight(17);

        $myWorkSheet_res->mergeCells('A8:D8');
        $myWorkSheet_res->setCellValue('A8', 'Resumen de resultados');

        $myWorkSheet_res->getStyle('A8')->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle('A8')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('A8')->getAlignment()->setVertical('center');
        $myWorkSheet_res->getRowDimension('8')->setRowHeight(25);
        $myWorkSheet_res->getStyle('A8:D8')->applyFromArray($styleArray);

        $myWorkSheet_res->setCellValue('A9', 'Toneladas producidas (t):');
        $myWorkSheet_res->setCellValue('A10', 'Costo total por t ($/t):');
        $myWorkSheet_res->setCellValue('A11', 'Costo variable ($/t):');
        $myWorkSheet_res->setCellValue('A12', 'Costo fijo ($/t):');

        $myWorkSheet_res->setCellValue('C10', 'MAI económico ($/t):');
        $myWorkSheet_res->setCellValue('C11', 'MAI financiero ($/t):');

        //Convertir todos estos valores a enteros

        $ton = floatval($data_costos['general']['toneladas']);
        $myWorkSheet_res->getStyle('B9')->getNumberFormat()->setFormatCode('#,##0');
        $myWorkSheet_res->setCellValueExplicit('B9', $ton, DataType::TYPE_NUMERIC);

        $costo_tot = floatval($data_costos['general']['costo_total']);
        $myWorkSheet_res->getStyle('B10')->getNumberFormat()->setFormatCode('#,##0');
        $myWorkSheet_res->setCellValueExplicit('B10', $costo_tot, DataType::TYPE_NUMERIC);


        $costo_var = floatval($data_costos['general']['costos_suma']['sum_costo_variable']);
        $myWorkSheet_res->getStyle('B11')->getNumberFormat()->setFormatCode('#,##0');
        $myWorkSheet_res->setCellValueExplicit('B11', $costo_var, DataType::TYPE_NUMERIC);

        $costo_fijo = floatval($data_costos['general']['costos_suma']['sum_costos_fijos']);
        $myWorkSheet_res->getStyle('B12')->getNumberFormat()->setFormatCode('#,##0');
        $myWorkSheet_res->setCellValueExplicit('B12', $costo_fijo, DataType::TYPE_NUMERIC);


        $mai_ = floatval($data_costos['general']['mai']['economico']);
        $myWorkSheet_res->getStyle('D10')->getNumberFormat()->setFormatCode('#,##0');
        $myWorkSheet_res->setCellValueExplicit('D10', $mai_, DataType::TYPE_NUMERIC);

        $mai_fin = floatval($data_costos['general']['mai']['financiero']);
        $myWorkSheet_res->getStyle('D11')->getNumberFormat()->setFormatCode('#,##0');
        $myWorkSheet_res->setCellValueExplicit('D11', $mai_fin, DataType::TYPE_NUMERIC);


        $myWorkSheet_res->getStyle('A9:D12')->applyFromArray($styleArray);


        $myWorkSheet_res->getStyle('A9')->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle('A9')->getAlignment()->setHorizontal('left');
        $myWorkSheet_res->getStyle('A9')->getAlignment()->setVertical('center');

        $myWorkSheet_res->getStyle('A10')->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle('A10')->getAlignment()->setHorizontal('left');
        $myWorkSheet_res->getStyle('A10')->getAlignment()->setVertical('center');

        $myWorkSheet_res->getStyle('A11')->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle('A11')->getAlignment()->setHorizontal('left');
        $myWorkSheet_res->getStyle('A11')->getAlignment()->setVertical('center');

        $myWorkSheet_res->getStyle('A12')->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle('A12')->getAlignment()->setHorizontal('left');
        $myWorkSheet_res->getStyle('A12')->getAlignment()->setVertical('center');

        $myWorkSheet_res->getStyle('C10')->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle('C10')->getAlignment()->setHorizontal('left');
        $myWorkSheet_res->getStyle('C10')->getAlignment()->setVertical('center');
        $myWorkSheet_res->getStyle('C10')->getAlignment()->setIndent(1);

        $myWorkSheet_res->getStyle('C11')->applyFromArray($font_bold);
        $myWorkSheet_res->getStyle('C11')->getAlignment()->setHorizontal('left');
        $myWorkSheet_res->getStyle('C11')->getAlignment()->setVertical('center');
        $myWorkSheet_res->getStyle('C11')->getAlignment()->setIndent(1);


        $myWorkSheet_res->getStyle('B9')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('B9')->getAlignment()->setVertical('center');

        $myWorkSheet_res->getStyle('B10')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('B10')->getAlignment()->setVertical('center');

        $myWorkSheet_res->getStyle('B11')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('B11')->getAlignment()->setVertical('center');

        $myWorkSheet_res->getStyle('B12')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('B12')->getAlignment()->setVertical('center');

        $myWorkSheet_res->getStyle('D10')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('D10')->getAlignment()->setVertical('center');

        $myWorkSheet_res->getStyle('D11')->getAlignment()->setHorizontal('center');
        $myWorkSheet_res->getStyle('D11')->getAlignment()->setVertical('center');

        foreach (range('A9:D9', $myWorkSheet_res->getHighestColumn()) as $col) {
            $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('A10:D10', $myWorkSheet_res->getHighestColumn()) as $col) {
            $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('A11:D11', $myWorkSheet_res->getHighestColumn()) as $col) {
            $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
        }

        foreach (range('A12:D12', $myWorkSheet_res->getHighestColumn()) as $col) {
            $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
        }

    }

    private function createdSheetMaquinas($data_costos, $spreadsheet)
    {

        //CAculo el costo total para obtener el porcentaje

        $costo_total = 0;

        foreach ($data_costos['centros'] as $centro){

            $costo_total = $costo_total + $centro['costo_total'];
        }



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


        $myWorkSheet_maq = new Worksheet($spreadsheet, 'Maquinas');

        $spreadsheet->addSheet($myWorkSheet_maq, 1);

        $myWorkSheet_maq->setCellValue('A1', 'Distribución por Centro de Costos (Toneladas)');
        $myWorkSheet_maq->mergeCells('A1:G1');

        $myWorkSheet_maq->getStyle('A1')->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('A1')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('A1')->getAlignment()->setVertical('center');
        $myWorkSheet_maq->getRowDimension('1')->setRowHeight(35);
        $myWorkSheet_maq->getStyle('A1:G1')->applyFromArray($styleArray);


        $i = 3;

        foreach ($data_costos['centros'] as $centro) {

            if ($i == 3) {

                $porc = $costo_total != 0 ? ($centro['costo_total'] * 100 / $costo_total) : 0;
                $porc = number_format($porc, 2, ',', '.');

                $myWorkSheet_maq->setCellValue('A2', $centro['name']);
                $myWorkSheet_maq->setCellValue('B2', 'Toneladas');
                $myWorkSheet_maq->setCellValue('C2', 'Costo/t');
                $myWorkSheet_maq->setCellValue('D2', 'Horas');
                $myWorkSheet_maq->setCellValue('E2', 't/h');
                $myWorkSheet_maq->setCellValue('F2', 'Costo/h');
                $myWorkSheet_maq->setCellValue('G2', $porc . '% costo total');


                $myWorkSheet_maq->getStyle('A2')->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('A2')->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('A2')->getAlignment()->setVertical('center');
                $myWorkSheet_maq->getRowDimension('2')->setRowHeight(25);

                $myWorkSheet_maq->getStyle('B2')->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('B2')->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('B2')->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('C2')->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('C2')->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('C2')->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('D2')->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('D2')->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('D2')->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('E2')->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('E2')->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('E2')->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('F2')->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('F2')->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('F2')->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('G2')->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('G2')->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('G2')->getAlignment()->setVertical('center');


                foreach (range('A1:G1', $myWorkSheet_maq->getHighestColumn()) as $col) {
                    $myWorkSheet_maq->getColumnDimension($col)->setAutoSize(true);
                }

                //cArgo la info de cabecera, PUedo especificar el row directamente

                $myWorkSheet_maq->setCellValue('B3', number_format($centro['toneladas_total'], 2, ',', '.'));
                $myWorkSheet_maq->setCellValue('C3',  number_format($centro['costo_total'], 2, ',', '.'));

                $myWorkSheet_maq->getStyle('B3')->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('B3')->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('C3')->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('C3')->getAlignment()->setVertical('center');


                $myWorkSheet_maq->getRowDimension('3')->setRowHeight(20);


                /*$myWorkSheet_maq->setCellValue('D3',  $centro['horas']);
                $myWorkSheet_maq->setCellValue('E3', $centro['ton_h']);
                $myWorkSheet_maq->setCellValue('F3', 'Costo/h');
                $myWorkSheet_maq->setCellValue('G3', '% costo total');*/


            } else {

                $i++;
                $i++;

                $porc =  $costo_total != 0 ? ($centro['costo_total'] * 100 / $costo_total) : 0;
                $porc = number_format($porc, 2, ',', '.');

                $myWorkSheet_maq->setCellValue('A'.$i, $centro['name']);
                $myWorkSheet_maq->setCellValue('B'.$i, 'Toneladas');
                $myWorkSheet_maq->setCellValue('C'.$i, 'Costo/t');
                $myWorkSheet_maq->setCellValue('D'.$i, 'Horas');
                $myWorkSheet_maq->setCellValue('E'.$i, 't/h');
                $myWorkSheet_maq->setCellValue('F'.$i, 'Costo/h');
                $myWorkSheet_maq->setCellValue('G'.$i, $porc. '% costo total');

                $myWorkSheet_maq->getStyle('A'.$i)->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('A'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('A'.$i)->getAlignment()->setVertical('center');
                $myWorkSheet_maq->getRowDimension($i)->setRowHeight(25);

                $myWorkSheet_maq->getStyle('B'.$i)->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('C'.$i)->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('D'.$i)->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('D'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('D'.$i)->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('E'.$i)->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('E'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('E'.$i)->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('F'.$i)->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('F'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('F'.$i)->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('G'.$i)->applyFromArray($font_bold);
                $myWorkSheet_maq->getStyle('G'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('G'.$i)->getAlignment()->setVertical('center');

                $i++;

                //cArgo la info de cabecera, PUedo especificar el row directamente

                $myWorkSheet_maq->setCellValue('B'.$i, number_format($centro['toneladas_total'], 2, ',', '.'));
                $myWorkSheet_maq->setCellValue('C'.$i,  number_format($centro['costo_total'], 2, ',', '.'));


                $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setVertical('center');

                $myWorkSheet_maq->getRowDimension($i)->setRowHeight(20);
            }


            foreach ($centro['maquinas'] as $maq)
            {
                $i++;
                $myWorkSheet_maq->setCellValue('A'.$i, $maq['name']);
                $myWorkSheet_maq->setCellValue('B'.$i, number_format($maq['costos']['toneladas'], 2, ',', '.'));
                $myWorkSheet_maq->setCellValue('C'.$i,   number_format($maq['costos']['costo_ton'], 2, ',', '.'));
                $myWorkSheet_maq->setCellValue('D'.$i, $maq['costos']['horas']);
                $myWorkSheet_maq->setCellValue('E'.$i,  number_format($maq['costos']['prod_rend_h'], 2, ',', '.'));
                $myWorkSheet_maq->setCellValue('F'.$i,  number_format($maq['costos']['costo_h'], 2, ',', '.'));

                //Si la maquina es alquilada lo digo o no
                if($maq['alquiler']){
                    $myWorkSheet_maq->setCellValue('G'.$i, 'Servicio rentado');
                } else {
                    $myWorkSheet_maq->setCellValue('G'.$i, 'Máquina propia');
                }

                $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('B'.$i)->getAlignment()->setVertical('center');


                $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('C'.$i)->getAlignment()->setVertical('center');


                $myWorkSheet_maq->getStyle('D'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('D'.$i)->getAlignment()->setVertical('center');


                $myWorkSheet_maq->getStyle('E'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('E'.$i)->getAlignment()->setVertical('center');


                $myWorkSheet_maq->getStyle('F'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('F'.$i)->getAlignment()->setVertical('center');


                $myWorkSheet_maq->getStyle('G'.$i)->getAlignment()->setHorizontal('center');
                $myWorkSheet_maq->getStyle('G'.$i)->getAlignment()->setVertical('center');
            }


        }
        return $myWorkSheet_maq;
    }

    public function createSheetRendimiento($spreadsheet, $data_costos, $maquinas_data_back)
    {

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


        $myWorkSheet_maq = new Worksheet($spreadsheet, 'Eficiencia');

        $spreadsheet->addSheet($myWorkSheet_maq, 2);

        $myWorkSheet_maq->setCellValue('A1', 'Indicadores');
        $myWorkSheet_maq->mergeCells('A1:I1');

        $myWorkSheet_maq->getStyle('A1')->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('A1')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('A1')->getAlignment()->setVertical('center');
        $myWorkSheet_maq->getRowDimension('1')->setRowHeight(35);
        $myWorkSheet_maq->getStyle('A1:G1')->applyFromArray($styleArray);

        $myWorkSheet_maq->setCellValue('B2', 'Toneladas');
        $myWorkSheet_maq->setCellValue('C2', 'Litros Combustibles');
        $myWorkSheet_maq->setCellValue('D2', 'l/t');
        $myWorkSheet_maq->setCellValue('E2', 'Horas');
        $myWorkSheet_maq->setCellValue('F2', 'l/h');
        $myWorkSheet_maq->setCellValue('G2', 'l/h -t');
        $myWorkSheet_maq->setCellValue('H2', 'Hora Plan');
        $myWorkSheet_maq->setCellValue('I2', 'Eficiencia');

        $myWorkSheet_maq->getStyle('B2')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('B2')->getAlignment()->setVertical('center');

        $myWorkSheet_maq->getStyle('C2')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('C2')->getAlignment()->setVertical('center');

        $myWorkSheet_maq->getStyle('D2')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('D2')->getAlignment()->setVertical('center');

        $myWorkSheet_maq->getStyle('E2')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('E2')->getAlignment()->setVertical('center');

        $myWorkSheet_maq->getStyle('F2')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('F2')->getAlignment()->setVertical('center');

        $myWorkSheet_maq->getStyle('G2')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('G2')->getAlignment()->setVertical('center');
        $myWorkSheet_maq->getStyle('H2')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('H2')->getAlignment()->setVertical('center');
        $myWorkSheet_maq->getStyle('I2')->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('I2')->getAlignment()->setVertical('center');

        $myWorkSheet_maq->getStyle('B2')->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('C2')->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('D2')->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('E2')->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('F2')->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('G2')->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('H2')->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('I2')->applyFromArray($font_bold);

        $suma_combustible = 0;

        $i = 3;

        foreach ($data_costos['centros'] as $centro) {


            $myWorkSheet_maq->setCellValue('A' . $i, $centro['name']);


            foreach ($centro['maquinas'] as $maq)
            {
                if(isset($maq['eficiencia']))
                {
                    $i++;
                    $myWorkSheet_maq->setCellValue('A'.$i, $maq['name']);
                    $myWorkSheet_maq->setCellValue('B'.$i, $maq['costos']['toneladas']);

                    $myWorkSheet_maq->setCellValueExplicit('B'.$i, $maq['costos']['toneladas'], DataType::TYPE_NUMERIC);

                    $suma_combustible = $suma_combustible + $maq['eficiencia']['litros_comb'];



                    $myWorkSheet_maq->setCellValueExplicit('C'.$i, $maq['eficiencia']['litros_comb'], DataType::TYPE_NUMERIC);


                    $lit_ton = $maq['costos']['toneladas'] == 0 ? 0 : ($maq['eficiencia']['litros_comb'] / $maq['costos']['toneladas']);


                    $myWorkSheet_maq->setCellValueExplicit('D'.$i, number_format($lit_ton, 2, '.', ','), DataType::TYPE_NUMERIC);


                    $myWorkSheet_maq->setCellValue('E'.$i,  $maq['costos']['horas']);

                    $lit_h = $maq['costos']['horas'] == 0 ? 0 : ($maq['eficiencia']['litros_comb'] / $maq['costos']['horas']);

                    $myWorkSheet_maq->getStyle('F'.$i)->getNumberFormat()->setFormatCode('#.##0');
                    $myWorkSheet_maq->setCellValueExplicit('F'.$i, $lit_h, DataType::TYPE_NUMERIC);


                    //Litros de -t

                    $litros_comb = 0;
                    foreach ($maquinas_data_back as $data)
                    {
                        if($data['idmaquina'] == $maq['idmaquinas'])
                        {
                            $litros_comb =  $data['litros_combustible'];
                        }

                    }

                    $litro_minus_t = $maq['costos']['horas'] == 0 ? 0 : ($litros_comb / $maq['costos']['horas']);

                    $myWorkSheet_maq->getStyle('G'.$i)->getNumberFormat()->setFormatCode('#.##0');
                    $myWorkSheet_maq->setCellValueExplicit('G'.$i, $litro_minus_t, DataType::TYPE_NUMERIC);
                    //HOra plan
                    $HMU = $maq['eficiencia']['HMU'];

                    $myWorkSheet_maq->setCellValueExplicit('H'.$i, $HMU, DataType::TYPE_NUMERIC);

                    $eficiencia = $HMU == 0 ? 0 : ($maq['costos']['horas'] / $HMU);
                    $myWorkSheet_maq->getStyle('I'.$i)->getNumberFormat()->setFormatCode('#.##0');
                    $myWorkSheet_maq->setCellValue('I'.$i,  number_format($eficiencia, 2, ',', '.') . '%');

                    $myWorkSheet_maq->setCellValueExplicit('I'.$i, $eficiencia, DataType::TYPE_NUMERIC);
                    //Cargo el calculo de un mes respecto del otro
                    $porc_mes_minus_mes = $litros_comb == 0 ? 0 : ((($lit_h / $litro_minus_t) - 1) * 100);
                    $myWorkSheet_maq->setCellValue('F'. ($i + 1),  number_format($porc_mes_minus_mes, 2, ',', '.') . '%');



                    $myWorkSheet_maq->getStyle('B' . $i)->getAlignment()->setHorizontal('center');
                    $myWorkSheet_maq->getStyle('B' . $i)->getAlignment()->setVertical('center');

                    $myWorkSheet_maq->getStyle('C' . $i)->getAlignment()->setHorizontal('center');
                    $myWorkSheet_maq->getStyle('C' . $i)->getAlignment()->setVertical('center');

                    $myWorkSheet_maq->getStyle('D' . $i)->getAlignment()->setHorizontal('center');
                    $myWorkSheet_maq->getStyle('D' . $i)->getAlignment()->setVertical('center');

                    $myWorkSheet_maq->getStyle('E' . $i)->getAlignment()->setHorizontal('center');
                    $myWorkSheet_maq->getStyle('E' . $i)->getAlignment()->setVertical('center');

                    $myWorkSheet_maq->getStyle('F' . $i)->getAlignment()->setHorizontal('center');
                    $myWorkSheet_maq->getStyle('F' . $i)->getAlignment()->setVertical('center');

                    $myWorkSheet_maq->getStyle('F' . ($i + 1))->getAlignment()->setHorizontal('center');
                    $myWorkSheet_maq->getStyle('F' . ($i + 1))->getAlignment()->setVertical('center');

                    $myWorkSheet_maq->getStyle('G' . $i)->getAlignment()->setHorizontal('center');
                    $myWorkSheet_maq->getStyle('G' . $i)->getAlignment()->setVertical('center');
                    $myWorkSheet_maq->getStyle('H' . $i)->getAlignment()->setHorizontal('center');
                    $myWorkSheet_maq->getStyle('H' . $i)->getAlignment()->setVertical('center');
                    $myWorkSheet_maq->getStyle('I' . $i)->getAlignment()->setHorizontal('center');
                    $myWorkSheet_maq->getStyle('I' . $i)->getAlignment()->setVertical('center');


                    $i++;

                }



            }
            $i++;


        }

        foreach (range('A1:A1', $myWorkSheet_maq->getHighestColumn()) as $col) {
            $myWorkSheet_maq->getColumnDimension($col)->setAutoSize(true);
        }

        $myWorkSheet_maq->getStyle('A' . $i)->applyFromArray($font_bold);
        $myWorkSheet_maq->getStyle('C' . $i)->applyFromArray($font_bold);

        $myWorkSheet_maq->setCellValue('A'.$i,  'Totales: ');
        $myWorkSheet_maq->setCellValue('C'.$i,  $suma_combustible);

        $myWorkSheet_maq->getStyle('C' . $i)->getAlignment()->setHorizontal('center');
        $myWorkSheet_maq->getStyle('C' . $i)->getAlignment()->setVertical('center');

        return $myWorkSheet_maq;

    }


    public function createInformesMaquinas($metadata, $costos_maquinas)
    {
        $informes_maquinas_model = TableRegistry::getTableLocator()->get('InformesMaquinas');
        $infomes_maq_entity = $informes_maquinas_model->newEntity();

        $result = $this->createMaquinasExcelInforme($metadata, $costos_maquinas);



        if($result != false)
        {
            $metadata['path'] = $result['path'];
            $metadata['name'] = $result['name'];


            $entity_informe_ = $informes_maquinas_model->patchEntity($infomes_maq_entity, $metadata);

            //DEvuelvo un arreglo con la operacion y el id

            if ($informes_maquinas_model->save($entity_informe_)) {
                return ['id' => $entity_informe_->idinformes, 'informe' => true, 'path' => $result['path']];
            }
            return ['id' => '', 'informe' => false];
        } else {
            return ['id' => '', 'informe' => false];
        }




    }


    public function createMaquinasExcelInforme($metadata, $costos_maquinas)
    {

        $spreadsheet = new Spreadsheet();

        $myWorkSheet_maq =  $this->createSheetMaquina($spreadsheet, $costos_maquinas, $metadata);


        //$spreadsheet->removeSheetByIndex(2);

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

    private function createSheetMaquina($spreadsheet, $costos_maquinas, $metadata)
    {
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

        try {

            $empresa_model = TableRegistry::getTableLocator()->get('Empresas');
            $id_empresa = $metadata['empresas_idempresas'];
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


            $myWorkSheet_res->setCellValue('A4', 'Maquina:');
            $myWorkSheet_res->setCellValue('B4', $costos_maquinas['name']);
            $myWorkSheet_res->setCellValue('C4', 'Período:');
            $myWorkSheet_res->setCellValue('D4', 'de: '. $metadata['fecha_inicio']. ' a '. $metadata['fecha_fin']);

            $myWorkSheet_res->setCellValue('A5', 'Lote:');
            $myWorkSheet_res->setCellValue('B5', $metadata['lote']);
            $myWorkSheet_res->setCellValue('C5', 'Parcela:');
            $myWorkSheet_res->setCellValue('D5', $metadata['parcela']);
            $myWorkSheet_res->setCellValue('E5', 'Propietario:');
            $myWorkSheet_res->setCellValue('F5', $metadata['propietario']);

            $myWorkSheet_res->setCellValue('A6', 'Industria destino:');
            $myWorkSheet_res->setCellValue('B6', $metadata['destino']);

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



            $toneladas = intval($costos_maquinas['costos']['toneladas']);
            $myWorkSheet_res->getStyle('B9')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B9', $toneladas, DataType::TYPE_NUMERIC);

            $toneladas_total_preriodo = intval($costos_maquinas['costos']['toneladas_total_preriodo']);
            $myWorkSheet_res->getStyle('B10')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B10', $toneladas_total_preriodo, DataType::TYPE_NUMERIC);

            $porc_sobre_total = intval($costos_maquinas['costos']['porc_ton']);
            $myWorkSheet_res->getStyle('B11')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B11', $porc_sobre_total, DataType::TYPE_NUMERIC);

            $horas_trabajo = intval( $costos_maquinas['costos']['horas']);
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

            $costo_total = intval( $costos_maquinas['costos']['costo_h']);
            $myWorkSheet_res->getStyle('B17')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B17', $costo_total, DataType::TYPE_NUMERIC);

            $costo_maq = intval( $costos_maquinas['costos_groups']['costo_maquina']);
            $myWorkSheet_res->getStyle('B18')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B18', $costo_maq, DataType::TYPE_NUMERIC);

            $costos_fijos = intval( $costos_maquinas['costos_groups']['costos_fijos']);
            $myWorkSheet_res->getStyle('B19')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B19', $costos_fijos, DataType::TYPE_NUMERIC);

            $costos_semifijos= intval( $costos_maquinas['costos_groups']['costos_semifijos']);
            $myWorkSheet_res->getStyle('B20')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B20', $costos_semifijos, DataType::TYPE_NUMERIC);

            $costos_variables = intval( $costos_maquinas['costos_groups']['costos_variables']);
            $myWorkSheet_res->getStyle('B21')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B21', $costos_variables, DataType::TYPE_NUMERIC);

            $costo_horario_personal = intval( $costos_maquinas['costos_groups']['costo_horario_personal']);
            $myWorkSheet_res->getStyle('B22')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B22', $costo_horario_personal, DataType::TYPE_NUMERIC);

            $costo_administracion= intval( $costos_maquinas['costos_groups']['costo_administracion']);
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


            $interes = intval( $costos_maquinas['result_metod']['interes']);
            $myWorkSheet_res->getStyle('B28')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B28', $interes, DataType::TYPE_NUMERIC);

            $seguro = intval( $costos_maquinas['result_metod']['seguro']);
            $myWorkSheet_res->getStyle('B29')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B29', $seguro, DataType::TYPE_NUMERIC);

            $dep_maq = intval( $costos_maquinas['result_metod']['dep_maq']);
            $myWorkSheet_res->getStyle('B30')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B30', $dep_maq, DataType::TYPE_NUMERIC);

            $dep_neum = intval( $costos_maquinas['result_metod']['dep_neum']);
            $myWorkSheet_res->getStyle('B31')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B31', $dep_neum, DataType::TYPE_NUMERIC);

            $arreglos_maq = intval( $costos_maquinas['result_metod']['arreglos_maq']);
            $myWorkSheet_res->getStyle('B32')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B32', $arreglos_maq, DataType::TYPE_NUMERIC);

            $cons_comb= intval( $costos_maquinas['result_metod']['cons_comb']);
            $myWorkSheet_res->getStyle('B33')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B33', $cons_comb, DataType::TYPE_NUMERIC);

            $cons_lub = intval( $costos_maquinas['result_metod']['cons_lub']);
            $myWorkSheet_res->getStyle('B34')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B34', $cons_lub, DataType::TYPE_NUMERIC);

            $operador = intval( $costos_maquinas['result_metod']['operador']);
            $myWorkSheet_res->getStyle('B35')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B35', $operador, DataType::TYPE_NUMERIC);

            $mantenimiento = intval( $costos_maquinas['result_metod']['mantenimiento']);
            $myWorkSheet_res->getStyle('B36')->getNumberFormat()->setFormatCode('#,##0');
            $myWorkSheet_res->setCellValueExplicit('B36', $mantenimiento, DataType::TYPE_NUMERIC);


            return $myWorkSheet_res;



        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }



        return null;
    }



    public function createInformeVariaciones($metadata, $costos_maquinas)
    {
        $informes_maquinas_model = TableRegistry::getTableLocator()->get('InformesVariaciones');
        $infomes_maq_entity = $informes_maquinas_model->newEntity();

        $result = $this->createVariacionesExcelInforme($metadata, $costos_maquinas);

        if($result != false)
        {
            $metadata['path'] = $result['path'];
            $metadata['name'] = $result['name'];


            $entity_informe_ = $informes_maquinas_model->patchEntity($infomes_maq_entity, $metadata);

            //DEvuelvo un arreglo con la operacion y el id

            if ($informes_maquinas_model->save($entity_informe_)) {

                return ['id' => $entity_informe_->idinformes_variaciones, 'informe' => true, 'path' => $result['path']];
            }
            return ['id' => '', 'informe' => false];
        } else {
            return ['id' => '', 'informe' => false];
        }



    }

    public function createVariacionesExcelInforme($metadata, $costos_maquinas)
    {


        $spreadsheet = new Spreadsheet();

        $myWorkSheet_maq =  $this->createSheetVariaciones($spreadsheet, $metadata, $costos_maquinas);


        //$spreadsheet->removeSheetByIndex(2);

        //utilizo el now, es mejor
        $nombre = "informe_variaciones_" .hash('sha256' , (date("Y-m-d H:i:s")));

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

    private function createSheetVariaciones($spreadsheet, $metadata, $costos_maquinas)
    {

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

        try {

            $empresa_model = TableRegistry::getTableLocator()->get('Empresas');
            $id_empresa = $metadata['empresas_idempresas'];
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


            //EL titulo tiene que decir Informe de Costo - NOmbre de empresa
            $empresa_name = $empresas_data->name;
            $titulo = 'Informe de Variaciones - ' . $empresa_name;

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


            //empiezo a partir del row 2
            $index = 3;


            $index_column = 'B';
            //$count_column =count($costos_maquinas);

            //$count_row = count($costos_maquinas[0]) - 1;
            $index_aux = 0;

            foreach ($costos_maquinas as $data)
            {
                $index_row_aux = $index;
                //si es la primera vez creo los titulos
                if($index_aux == 0)
                {
                    foreach ($data as $key => $value)
                    {
                        if($key != 'x')
                        {
                            //cargo
                            $cell_name = 'A' . $index_row_aux;
                            $myWorkSheet_res->setCellValue($cell_name, $key);
                        }
                        $index_row_aux++;
                    }
                    $index_aux++;

                    //cargo el nombre de las columnas


                } else {


                    foreach ($data as $key => $value)
                    {
                        if($key != 'x')
                        {
                            //cargo
                            $cell_name = $index_column . $index_row_aux;
                            $myWorkSheet_res->setCellValue($cell_name, $value);
                        } else {
                            $cell_name = $index_column . 3;
                            $myWorkSheet_res->setCellValue($cell_name, $value);
                        }
                        $index_row_aux++;

                    }

                    $index_column++;

                }

            }


            $index_column = $this->decrement($index_column);
            //Combino la primer celda para porner el titulo y configuro una altura aceptable
            $rango = 'B1:' . $index_column . 1;
            $myWorkSheet_res->mergeCells($rango);
            $myWorkSheet_res->getRowDimension('1')->setRowHeight(75);


            return $myWorkSheet_res;

        } catch (InvalidPrimaryKeyException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));

        } catch (RecordNotFoundException $e){
            $this->Flash->error(__('Error al almacenar los cambios. Intenta nuevamente'));
        }


        return null;

    }

    private function decrement($index)
    {
        return chr(ord($index) - 1);
    }


    public function createInformeCamiones($metadata, $data_camiones_rentados)
    {


        return $this->CamionesRentadosExcelInforme($metadata, $data_camiones_rentados);
    }


    public function CamionesRentadosExcelInforme($metadata, $data_camiones_rentados)
    {

        $spreadsheet = new Spreadsheet();


        $this->createSheetTransporte($spreadsheet, $metadata, $data_camiones_rentados);

        $spreadsheet->removeSheetByIndex(1);

        //utilizo el now, es mejor
        $nombre = "informe_transporte_" .hash('sha256' , (date("Y-m-d H:i:s")));

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

    public function createSheetTransporte($spreadsheet, $metadata, $data_camiones_rentados)
    {
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

        //obtengo el id de la empresa desde el metadata
        $id_empresa = $metadata['empresas_idempresas'];

        //Primero creo el worksheet antes de hacer la llamada al metodo que crea su header
        $myWorkSheet_res = new Worksheet($spreadsheet, 'Resumen');
        $spreadsheet->addSheet($myWorkSheet_res, 0);
        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');



        $this->createInfoHeaderSheetTransporte($id_empresa, $myWorkSheet_res, $font_bold);

        $index = $this->createInfoMetadataSheetTransporte($myWorkSheet_res, 1, $metadata, $font_bold);

        //en toeria devuelve la posicion para empezar a escribir los datos
        $this->writeDataTransporteToSheet($index, $myWorkSheet_res, $data_camiones_rentados, $font_bold, $styleArray);


    }

    private function createInfoHeaderSheetTransporte($id_empresa, $myWorkSheet_res, $font_bold)
    {
        try{

            //TRigo el logo de la empresa
            $empresa_model = TableRegistry::getTableLocator()->get('Empresas');
            $empresas_data = $empresa_model->get($id_empresa);

            //configuro el path y el file
            $path = null;

            if($empresas_data->logo == null or empty($empresas_data->logo))
            {
                //logo default
                $path = LOGOS . 'edificio.png';
            }  else {
                $path = LOGOS . $empresas_data->logo;
            }


            //Combino la primer celda para porner el titulo y configuro una altura aceptable
            $myWorkSheet_res->mergeCells('B1:J1');
            $myWorkSheet_res->getRowDimension('1')->setRowHeight(75);

            //EL titulo tiene que decir Informe de Costo - NOmbre de empresa
            $empresa_name = $empresas_data->name;
            $titulo = 'Informe: viajes de Camiones Rentados - ' . $empresa_name;

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
            $drawing->setPath( $path);
            $drawing->setHeight(75);
            $drawing->setWidth(75);
            $drawing->setCoordinates('A1');
            $drawing->setOffsetX(45);
            $drawing->setOffsetY(15);
            $drawing->setWorksheet($myWorkSheet_res);

            return $myWorkSheet_res;

        } catch (InvalidPrimaryKeyException $e){
            return $e;

        } catch (RecordNotFoundException $e){
            return $e;
        }

    }


    private function createInfoMetadataSheetTransporte($myWorkSheet_res, $type, $metadata, $font_bold)
    {

        $myWorkSheet_res->mergeCells('A2:J2');
        $myWorkSheet_res->getRowDimension('2')->setRowHeight(45);


        //Si el type es 1, solo va el periodo
        if($type == 1)
        {
            $myWorkSheet_res->mergeCells('A3:J3');
            $myWorkSheet_res->setCellValue('A3', 'Datos considerados en el análisis');
            $myWorkSheet_res->getStyle('A3')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('A3')->getAlignment()->setVertical('center');

            $myWorkSheet_res->mergeCells('A4:J4');

            $text = 'Periodo: '. $metadata['fecha_inicio']. ' a '. $metadata['fecha_fin'];
            $myWorkSheet_res->setCellValue('A4', $text);

            //Seteo el estilo de los datos
            $myWorkSheet_res->getRowDimension('3')->setRowHeight(25);


            $myWorkSheet_res->getStyle('A4')->getAlignment()->setHorizontal('center');
            $myWorkSheet_res->getStyle('A4')->getAlignment()->setVertical('center');

            $myWorkSheet_res->getStyle('A4')->getAlignment()->setIndent(1);



            foreach (range('A4:J4', $myWorkSheet_res->getHighestColumn()) as $col) {
                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
            }


            return 5;
        }




    }



    private function writeDataTransporteToSheet($index, $myWorkSheet_res, $data_camiones_rentados, $font_bold, $styleArray)
    {

        //empezamos desde el index
        $index++;
        //Cargo la cabecera




        foreach ($data_camiones_rentados as $maq)
        {

            $myWorkSheet_res->setCellValue('A'.$index, $maq['maquina']['name']);
            $myWorkSheet_res->getStyle('A'.$index)->applyFromArray($font_bold);
            $index++;

            //cargo la cabecera
            $myWorkSheet_res->setCellValue('A'.$index, 'N° Remito');
            $myWorkSheet_res->setCellValue('B'.$index, 'Fecha');
            $myWorkSheet_res->setCellValue('C'.$index, 'Lote');
            $myWorkSheet_res->setCellValue('D'.$index, 'Parcela');
            $myWorkSheet_res->setCellValue('E'.$index, 'Destino');
            $myWorkSheet_res->setCellValue('F'.$index, 'Producto');
            $myWorkSheet_res->setCellValue('G'.$index, 'Toneladas');
            $myWorkSheet_res->setCellValue('H'.$index, 'Costo/t');
            $myWorkSheet_res->setCellValue('I'.$index, 'Total');
            $index++;



            foreach ($maq['data'] as $dat_lote)
            {

                //tengo que hacer otro foreach para recorrer los remitos

                foreach ($dat_lote['data'] as $data_destino){

                    foreach ($data_destino as $item)
                    {
                        $destino = null;
                        $sum_ton = 0;
                        $sum_costos = 0;
                        $costo_ton = 0;

                        foreach ($item['data'] as $rem)
                        {

                            $destino =  $item['destino'];
                            $myWorkSheet_res->setCellValue('A'.$index, $rem['idremitos']);
                            $myWorkSheet_res->setCellValue('B'.$index, $rem['fecha']);
                            $myWorkSheet_res->setCellValue('C'.$index, $dat_lote['lote']);
                            $myWorkSheet_res->setCellValue('D'.$index, $rem['parcela']);
                            $myWorkSheet_res->setCellValue('E'.$index, $item['destino']);
                            $myWorkSheet_res->setCellValue('F'.$index, $rem['producto']);
                            $myWorkSheet_res->setCellValue('G'.$index, $rem['toneladas']);
                            $myWorkSheet_res->setCellValue('H'.$index, $rem['precio']);
                            $myWorkSheet_res->setCellValue('I'.$index, $rem['total']);

                            $sum_ton = $sum_ton + $rem['toneladas'];
                            $sum_costos = $sum_costos + $rem['total'];

                            $range = 'A'.$index.  ':J' . $index;
                            foreach (range($range, $myWorkSheet_res->getHighestColumn()) as $col) {
                                $myWorkSheet_res->getColumnDimension($col)->setAutoSize(true);
                            }

                            $index++;

                        }

                        $costo_ton = $sum_costos / $sum_ton;

                        $index_row = 'A'.$index.':F'.$index;
                        $myWorkSheet_res->mergeCells($index_row);
                        //del lote
                        $texto = 'Subtotal viajes lote ' . $dat_lote['lote'] . ' => '  .$destino;

                        $myWorkSheet_res->setCellValue('A'.$index, $texto);
                        $myWorkSheet_res->setCellValue('G'.$index, $sum_ton);

                        $costo_ton = floatval($costo_ton);
                        $myWorkSheet_res->getStyle('H'.$index)->getNumberFormat()->setFormatCode('#,##0.##');
                        $myWorkSheet_res->setCellValueExplicit('H'.$index, $costo_ton, DataType::TYPE_NUMERIC);
                        $myWorkSheet_res->setCellValue('I'.$index, $sum_costos);

                        $myWorkSheet_res->getStyle('A'.$index)->applyFromArray($font_bold);
                        $myWorkSheet_res->getStyle('G'.$index)->applyFromArray($font_bold);
                        $myWorkSheet_res->getStyle('H'.$index)->applyFromArray($font_bold);
                        $myWorkSheet_res->getStyle('I'.$index)->applyFromArray($font_bold);


                        $index++;
                    }

                }


            }

            $index++;

        }




    }



}
