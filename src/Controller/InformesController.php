<?php
namespace App\Controller;

use App\Controller\AppController;
use Cake\Datasource\Exception\InvalidPrimaryKeyException;
use Cake\Datasource\Exception\RecordNotFoundException;
use Cake\Filesystem\File;
use Cake\Http\Exception\NotFoundException;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Style\Border;
use PhpOffice\PhpSpreadsheet\Style\Style;
use PhpOffice\PhpSpreadsheet\Writer\Exception;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

/**
 * Informes Controller
 *
 * @property \App\Model\Table\InformesTable $Informes
 */
class InformesController extends AppController
{

    public function isAuthorized($user)
    {
        if (isset($user['role']) and $user['role'] === 'user') {
            if (in_array($this->request->getParam('action'), ['index', 'view', 'generateExcel', 'edit', 'delete', 'downloadAsExcel'])) {
                return true;
            }
        } else if (isset($user['role']) and $user['role'] === 'supervisor') {
            if (in_array($this->request->getParam('action'), ['index', 'view', 'generateExcel', 'edit', 'delete', 'downloadAsExcel'])) {
                return true;
            }
        }
        return parent::isAuthorized($user);
    }


    public function index()
    {

        //Variable usada para el sidebar
        $seccion = 'Informes';
        $sub_seccion = 'Informes';

        $this->set(compact('seccion'));
        $this->set(compact('sub_seccion'));

        //Consulto si la empresa no esta vacia
        //Traigo los datos de la sesion
        $session = $this->request->getSession();
        $id_empresa = $session->read('Auth.User.Empresa.idempresas');

        if(empty($id_empresa)){
            $this->Flash->error(__('Tenemos problemas para procesar la información. Inicie Sesión nuevamente.'));
        } else {
            $informes = $this->Informes->find('all', [
                'contain' => []
            ])->where(['empresas_idempresas' => $id_empresa]);
            $this->set(compact('informes'));

            //debug($informes->toArray());
        }
    }

    public function view()
    {

    }


    public function generateExcel()
    {

        $this->viewBuilder()->setLayout(null);
        $this->autoRender = false;

        $session = $this->request->getSession();

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



        //Ahora tengo que armar el excel en otro metodo y pasarle los rodales y los arboles
        $spreadsheet = new Spreadsheet();
        $nombre = "excel_prueba";
        $spreadsheet->getActiveSheet()->setTitle($nombre);
        $sheet = $spreadsheet->getActiveSheet();


        $spreadsheet->getDefaultStyle()->getFont()->setName('Times New Roman');

        //Combino la primer celda para porner el titulo y configuro una altura aceptable
        $sheet->mergeCells('B1:J1');
        $sheet->getRowDimension('1')->setRowHeight(75);

        //EL titulo tiene que decir Informe de Costo - NOmbre de empresa
        $empresa_name = 'Pindo S.A';
        $titulo = 'Informe de Costos - ' . $empresa_name;

        $sheet->setCellValue('B1', $titulo);

        $sheet->getStyle('B1')->applyFromArray($font_bold);
        $sheet->getStyle('B1')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B1')->getAlignment()->setVertical('center');
        $sheet->getStyle('B1')->getFont()->setBold(true)->setName('Arial')
            ->setSize(14);;



        //DIBUJO EL LOGO

        $drawing = new \PhpOffice\PhpSpreadsheet\Worksheet\Drawing();
        $drawing->setName('Logo');
        $drawing->setDescription('Logo');
        $drawing->setPath( LOGOS . 'edificio.png');
        $drawing->setHeight(75);
        $drawing->setWidth(75);
        $drawing->setCoordinates('A1');
        $drawing->setOffsetX(45);
        $drawing->setOffsetY(15);
        $drawing->setWorksheet($sheet);


        //Avanzo con el primer box de información
        //DEjo un row de distancia, empiezo desde el row 3
        //Tiene 6 columnas de dimension

        $sheet->mergeCells('A2:J2');
        $sheet->getRowDimension('2')->setRowHeight(45);

        $sheet->mergeCells('A3:F3');
        $sheet->setCellValue('A3', 'Datos considerados en el análisis');



        $sheet->getStyle('A3')->applyFromArray($font_bold);
        $sheet->getStyle('A3')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A3')->getAlignment()->setVertical('center');


        $sheet->setCellValue('A4', 'Grupo:');
        $sheet->setCellValue('B4', '');
        $sheet->setCellValue('C4', 'Período:');
        $sheet->setCellValue('D4', '');

        $sheet->setCellValue('A5', 'Lote:');
        $sheet->setCellValue('B5', '');
        $sheet->setCellValue('C5', 'Parcela:');
        $sheet->setCellValue('D5', '');
        $sheet->setCellValue('E5', 'Propietario:');
        $sheet->setCellValue('F5', '');

        $sheet->setCellValue('A6', 'Industria destino:');
        $sheet->setCellValue('B6', '');

        $sheet->getRowDimension('3')->setRowHeight(25);
        $sheet->getRowDimension('4')->setRowHeight(17);
        $sheet->getRowDimension('5')->setRowHeight(17);
        $sheet->getRowDimension('6')->setRowHeight(17);

        $sheet->getStyle('A4')->applyFromArray($font_bold);
        $sheet->getStyle('A4')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A4')->getAlignment()->setVertical('center');

        $sheet->getStyle('C4')->applyFromArray($font_bold);
        $sheet->getStyle('C4')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('c4')->getAlignment()->setVertical('center');

        $sheet->getStyle('A5')->applyFromArray($font_bold);
        $sheet->getStyle('A5')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A5')->getAlignment()->setVertical('center');

        $sheet->getStyle('C5')->applyFromArray($font_bold);
        $sheet->getStyle('C5')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('C5')->getAlignment()->setVertical('center');

        $sheet->getStyle('E5')->applyFromArray($font_bold);
        $sheet->getStyle('E5')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('E5')->getAlignment()->setVertical('center');

        $sheet->getStyle('A6')->applyFromArray($font_bold);
        $sheet->getStyle('A6')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A6')->getAlignment()->setVertical('center');

        foreach (range('A4:J4', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('A5:J5', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('A6:J6', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        ////////////////////////////////////////////////////////////////////////////////////////////////
        //Segundo BOX, LO HAGO CON BORDES, Empiezo desde A8
        $sheet->mergeCells('A7:J7');
        $sheet->getRowDimension('7')->setRowHeight(45);

        $sheet->getRowDimension('9')->setRowHeight(17);
        $sheet->getRowDimension('10')->setRowHeight(17);
        $sheet->getRowDimension('11')->setRowHeight(17);
        $sheet->getRowDimension('12')->setRowHeight(17);


        $sheet->mergeCells('A8:D8');
        $sheet->setCellValue('A8', 'Resumen de resultados');

        $sheet->getStyle('A8')->applyFromArray($font_bold);
        $sheet->getStyle('A8')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('A8')->getAlignment()->setVertical('center');
        $sheet->getRowDimension('8')->setRowHeight(25);
        $sheet->getStyle('A8:D8')->applyFromArray($styleArray);

        $sheet->setCellValue('A9', 'Toneladas producidas (t):');
        $sheet->setCellValue('A10', 'Costo total por t ($/t):');
        $sheet->setCellValue('A11', 'Costo variable ($/t):');
        $sheet->setCellValue('A12', 'Costo fijo ($/t):');

        $sheet->setCellValue('C10', 'MAI económico ($/t):');
        $sheet->setCellValue('C11', 'MAI transporte ($/t):');


        $sheet->setCellValue('B9', '');
        $sheet->setCellValue('B10', '');
        $sheet->setCellValue('B11', '');
        $sheet->setCellValue('B12', '');

        $sheet->setCellValue('D10', '');
        $sheet->setCellValue('D11', '');


        $sheet->getStyle('A9:D12')->applyFromArray($styleArray);




        $sheet->getStyle('A9')->applyFromArray($font_bold);
        $sheet->getStyle('A9')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A9')->getAlignment()->setVertical('center');

        $sheet->getStyle('A10')->applyFromArray($font_bold);
        $sheet->getStyle('A10')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A10')->getAlignment()->setVertical('center');

        $sheet->getStyle('A11')->applyFromArray($font_bold);
        $sheet->getStyle('A11')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A11')->getAlignment()->setVertical('center');

        $sheet->getStyle('A12')->applyFromArray($font_bold);
        $sheet->getStyle('A12')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('A12')->getAlignment()->setVertical('center');

        $sheet->getStyle('C10')->applyFromArray($font_bold);
        $sheet->getStyle('C10')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('C10')->getAlignment()->setVertical('center');
        $sheet->getStyle('C10')->getAlignment()->setIndent(1);

        $sheet->getStyle('C11')->applyFromArray($font_bold);
        $sheet->getStyle('C11')->getAlignment()->setHorizontal('left');
        $sheet->getStyle('C11')->getAlignment()->setVertical('center');
        $sheet->getStyle('C11')->getAlignment()->setIndent(1);


        $sheet->getStyle('B9')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B9')->getAlignment()->setVertical('center');

        $sheet->getStyle('B10')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B10')->getAlignment()->setVertical('center');

        $sheet->getStyle('B11')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B11')->getAlignment()->setVertical('center');

        $sheet->getStyle('B12')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('B12')->getAlignment()->setVertical('center');

        $sheet->getStyle('D10')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D10')->getAlignment()->setVertical('center');

        $sheet->getStyle('D11')->getAlignment()->setHorizontal('center');
        $sheet->getStyle('D11')->getAlignment()->setVertical('center');


        foreach (range('A9:D9', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('A10:D10', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        foreach (range('A11:D11', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }

        foreach (range('A12:D12', $sheet->getHighestColumn()) as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }





        $path = EXCELS . $nombre .'.xlsx';

        $writer = new Xlsx($spreadsheet);
        try {
            $writer->save($path);
        } catch (Exception $e) {

            $this->Flash->error(__('Tenemos problemas para procesar la información. Intente nuevamente.'));
        }


    }


    public function downloadAsExcel($id = null)
    {
        $this->autoRender = false;

        //TRaigo el informe
        try {

            $informe = $this->Informes->get($id);

            $path =  $path = WWW_ROOT.'files/excels/'.$informe->name . '.xlsx';

            $response = $this->response->withFile($path,
                ['download' => true]
            );
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

        return $this->redirect(['action' => 'index']);

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

            $informe =  $this->Informes->get($id);

            //debo consultar si esta constante se usa en otro lugar antes de eliminar

            if ($this->Informes->delete($informe)) {
                $this->Flash->success(__('El Registro ha sido eliminado.'));

                return $this->redirect(['action' => 'index']);
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
}
