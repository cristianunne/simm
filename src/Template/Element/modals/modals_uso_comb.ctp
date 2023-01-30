<!-- Modal -->
<div class="modal fade" id="modal_uso_comb" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">
                <h4 class="modal-title" id="tittle_modal_propietarios">Combustible</h4>
                <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_uso_comb"
                        onclick="closeModal(this)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">
                    <div class="col-md-12">

                        <table id="tabladata_2" class="table table-bordered table-hover dataTable">
                            <thead>
                            <tr>

                                <th scope="col"><?= $this->Paginator->sort('N°') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Categoria') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Producto') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Litros') ?></th>
                                <th scope="col"><?= $this->Paginator->sort('Precio ($/l)') ?></th>
                            </tr>
                            </thead>
                            <tbody>

                            <?php $i = 1; ?>
                            <?php foreach ($uso_comb_lub as $comb): ?>

                                    <?php if ($comb->categoria == 'Combustible'): ?>
                                        <tr>
                                            <td class="dt-center"><?= h($i) ?></td>
                                            <td class="dt-center"><?= h('Combustible') ?></td>
                                            <td class="dt-center"><?= h($comb->producto) ?></td>
                                            <td class="dt-center"><?= h($comb->litros) ?></td>
                                            <td class="dt-center"><?= h($comb->precio) ?></td>

                                            <?php $i = $i + 1; ?>
                                        </tr>
                                    <?php endif; ?>
                            <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>


                </div>


                <div class="modal-footer">
                    <button id="button_upload" type="button" class="btn-navy btn bg-navy" attr="modal_uso_comb" onclick="closeModal(this)">Salir</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
