<!-- DataTables -->
<?= $this->Html->css('../plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-responsive/css/responsive.bootstrap4.min.css') ?>
<?= $this->Html->css('../plugins/datatables-buttons/css/buttons.bootstrap4.min.css') ?>


<?= $this->element('header')?>
<?= $this->element('sidebar')?>



<div class="content-wrapper content-wrapper-user">

    <div class="container">

        <div class="card color-palette-box">

            <?= $this->Flash->render() ?>

            <div class="card-header bg-indigo">
                <h3 class="card-title">
                    <i class="fas fa-user-shield"></i>
                    Análisis de Costos
                </h3>
            </div>
            <div class="card-body">
                <div class="gap-3 d-md-flex justify-content-md-center mb-3 pt-1">
                    <div class="btn-toolbar" role="toolbar" aria-label="Toolbar with button groups">
                        <div class="btn-group flex-vertical" role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'AnalisisCostos', 'action' => 'index'], ['class' => 'btn-simm btn-met-costos btn btn-default',
                                        'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Costos horarios teóricos</p>
                            </div>
                        </div>
                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'AnalisisCostos', 'action' => 'groupsCostosAnalysis'],
                                    ['class' => 'btn-simm btn-groupswork btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Análisis Costos (Grupos)</p>
                            </div>
                        </div>

                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'AnalisisCostos', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-maquina btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Análisis Costos (Máquinas)</p>
                            </div>
                        </div>


                        <div class="btn-group flex-vertical " role="group" aria-label="Third group">
                            <div>
                                <?= $this->Html->link('',
                                    ['controller' => 'AnalisisCostos', 'action' => 'index'],
                                    ['class' => 'btn-simm btn-centro_costos btn btn-default', 'escape' => false]) ?>
                            </div>
                            <div class="div_content">
                                <p class="center text-color-navy">Variaciones (Grupos y Máquinas)</p>
                            </div>
                        </div>

                    </div>
                </div>
            </div>
        </div>




    </div>

</div>

<?= $this->Html->script('../plugins/popper/umd/popper.min.js') ?>
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
            "paging": true,
            "lengthChange": false,
            "searching": true,
            "ordering": true,
            "info": true,
            "autoWidth": false,
            "responsive": false
        });
    })
</script>

