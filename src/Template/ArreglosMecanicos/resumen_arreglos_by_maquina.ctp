
<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>

<?= $this->Html->css('jquery-confirm.min.css') ?>

<?= $this->element('header')?>
<?= $this->element('sidebar')?>


<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('arreglos_mecanicos_white.png' , ["alt" => 'User Image' ,
                        "class" => 'img-circle img-header', 'pathPrefix' => '/webroot/img/icons/']) ?>
                    Resumen -  Arreglo Mecánico
                </h3>
            </div>

            <div class="card-body">
                <div class="row">
                    <div class="col-md-6 card box-simm-shadow" style="margin: 0 auto; padding: 1.25rem">

                        <div class="form-group">
                            <?= $this->Form->control('maquinas_idmaquinas', ['options' => $maquinas_data,
                                'empty' => '(Elija una opción)', 'type' => 'select', 'id' => 'maquinas_idmaquinas',
                                'class' => 'form-control', 'label' => 'Máquina:', 'required']) ?>
                        </div>

                        <div class="form-group" id="sandbox-container">
                            <?=  $this->Form->label('fecha', 'Desde: ') ?>

                            <div class="input-append date">
                                <input id="fecha_inicio" name="fecha_inicio" type="month" class="span2">
                            </div>

                        </div>

                        <div class="form-group" id="sandbox-container">
                            <?=  $this->Form->label('fecha', 'Hasta: ') ?>

                            <div class="input-append date">
                                <input id="fecha_final" name="fecha_final" type="month" class="span2">
                            </div>

                        </div>

                        <br>
                        <br>


                        <div>
                            <table class="table table-bordered table-hover dataTable" id="tabladata">

                                <thead>
                                <tr>
                                    <th scope="col"><?= $this->Paginator->sort('Mano de Obra') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Repuestos') ?></th>
                                    <th scope="col"><?= $this->Paginator->sort('Total') ?></th>

                                </tr>
                                </thead>
                                <tbody>
                                <tr>

                                </tr>
                                </tbody>

                            </table>

                        </div>

                        <br>
                        <br>


                        <div class="form-group" style="margin-top: 40px;">
                            <div class="pull-right">
                                <?= $this->Form->button("Aceptar", ['class' => 'btn-navy btn bg-navy', 'escape' => false, 'onclick' => 'resumeArreglos()']) ?>
                            </div>
                            <div class="pull-left">
                                <?= $this->Html->link("Volver", ['action' => 'index'], ['class' => 'btn btn-danger btn-flat']) ?>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?= $this->Html->script('jquery-confirm.min.js') ?>

<?= $this->Html->script('../plugins/datatables/jquery.dataTables.min.js') ?>
<?= $this->Html->script('../plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>
<?= $this->Html->script('../plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/dataTables.buttons.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.bootstrap4.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.html5.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.print.min.js') ?>
<?= $this->Html->script('../plugins/datatables-buttons/js/buttons.colVis.min.js') ?>


<script>
    $(function () {

        $('#tabladata').DataTable({
            "paging": false,
            "lengthChange": false,
            "searching": false,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false,
            "conlumnDefs" : [
                {
                    "targets": "_all",
                    "defaultContent": "-"
                }]
        })
    })
</script>
