
<?= $this->element('header')?>
<?= $this->element('sidebar')?>

<div class="content-wrapper">
    <div class="container">
        <?= $this->Flash->render() ?>

        <!-- Main content -->
        <div class="card color-palette-box">

            <div class="card-header bg-navy">
                <h3 class="card-title">
                    <?php echo $this->Html->image('arreglos_mecanicos_white.png', ["alt" => 'User Image' , "class" => 'img-circle img-header',
                        'pathPrefix' => '/webroot/img/icons/']) ?>
                    Ver Arreglos Mecánicos
                </h3>
            </div>

            <div class="card-body">

                <div class="row">
                    <div class="col-lg-6 col-md-6" style="margin: 0 auto;">
                        <p class="lead"><strong>Fecha de Carga del Comprobante: </strong> <?= h($arreglos->created->format('d-m-Y')) ?></p>
                        <p class="lead"><strong>Fecha de última modificación: </strong> <?= h($arreglos->modified->format('d-m-Y')) ?></p>
                        <p class="lead"><strong>Operador: </strong><?= h($arreglos->user->firstname . ' '. $arreglos->user->lastname) ?></p>
                        <br>
                        <div class="table-responsive">
                            <table class="table">
                                <tbody>
                                    <tr>
                                        <th style="width:50%">N° de Comprobante:</th>
                                        <td><?= h($arreglos->num_comprobante)?> </td>
                                    </tr>
                                    <tr>
                                        <th>Fecha:</th>
                                        <td><?= h($arreglos->fecha->format('d-m-Y')) ?></td>
                                    </tr>
                                    <tr>
                                        <th style="vertical-align: middle;">Concepto:</th>
                                        <td><?= h($arreglos->concepto)?></td>
                                    </tr>

                                    <!-- Consulto si el lote o la parcela estan vacaias -->

                                    <?php if($arreglos->parcelas_idparcelas != ''):  ?>

                                        <tr>
                                            <th>Lote:</th>
                                            <td><?= h($arreglos->parcela->lote->name)?></td>
                                        </tr>

                                        <tr>
                                            <th>Parcela:</th>
                                            <td><?= h($arreglos->parcela->name)?></td>
                                        </tr>

                                    <?php endif;  ?>
                                    <tr>
                                        <th>Máquina:</th>
                                        <td><?= h($arreglos->maquina->name)?></td>
                                    </tr>
                                    <tr>
                                        <th>Mano de Obra:</th>
                                        <td><?= h($arreglos->mano_obra)?></td>
                                    </tr>
                                    <tr>
                                        <th>Repuestos:</th>
                                        <td><?= h($arreglos->repuestos)?></td>
                                    </tr>
                                    <tr>
                                        <th>Total:</th>
                                        <td><?= h($arreglos->total)?></td>
                                    </tr>
                                </tbody>
                            </table>
                            <hr>
                            <?= $this->Html->link($this->Html->tag('span', ' Editar', ['class' => 'fas fa-edit', 'aria-hidden' => 'true']),
                                ['action' => 'edit', $arreglos->idarreglos_mecanicos], ['class' => 'btn bg-purple', 'escape' => false]) ?>
                        </div>
                    </div>
                </div>


            </div>
        </div>
    </div>
</div>
