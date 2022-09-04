<!-- Modal -->
<div class="modal fade" id="modal_view_costos_maq" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-xl " role="document">
        <div class="modal-content">
            <div class="modal-header bg-navy">
                <h4 class="modal-title" id="tittle_modal_propietarios">Ver Datos Teóricos</h4>
                <button type="button" class="close" style="margin-top:-25px" data-dismiss="modal" aria-label="Close" attr="modal_view_costos_maq"
                        onclick="closeModal(this)">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="row">

                    <div class="col-lg-6 col-md-6"  style="margin-bottom: 50px;">
                        <div id="div_tabladata">
                            <table id="tabladata" name="tabla_persona" class="table">
                                <tbody>
                                <tr>
                                    <th style="width: 16%;">Máquina:</th>
                                    <td id="maq_name"></td>
                                </tr>

                                <tr>
                                    <th style="width: 16%;">Grupo:</th>
                                    <td id="grupo_name"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6" style="margin-bottom: 50px;">
                        <div id="div_tabladata">
                            <table id="tabladata" name="tabla_persona" class="table">
                                <tbody>
                                <tr>
                                    <th style="width: 30%;">Centro de Costos:</th>
                                    <td id="centro_costo_name"></td>
                                </tr>

                                <tr>
                                    <th style="width: 30%;">Metodología de Costos:</th>
                                    <td id="met_costo_name"></td>
                                </tr>

                                </tbody>
                            </table>
                        </div>
                    </div>


                    <hr style="width: 98%;">


                    <div class="col-lg-6 col-md-6" style="margin-top: 30px;">
                        <div id="div_tabladata">
                            <table id="tabladata" name="tabla_persona" class="table table-view">
                                <tbody>
                                <tr>
                                    <th style="width: 50%;">Valor de Adquisición ($):</th>
                                    <td id="val_adq"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Vida útil (Años):</th>
                                    <td id="vida_util"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Horas totales de uso (horas):</th>
                                    <td id="horas_tot"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Horas mensuales de uso (horas):</th>
                                    <td id="horas_men"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Tasa de interés simple:</th>
                                    <td id="tasa_int"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Consumo (litros/hora):</th>
                                    <td id="cons_lit_h"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Alquiler ($/ton):</th>
                                    <td id="alquiler"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="col-lg-6 col-md-6" style="margin-top: 30px;">
                        <div id="div_tabladata">
                            <table id="tabladata" name="tabla_persona" class="table table-view">
                                <tbody>
                                <tr>
                                    <th style="width: 50%;">Valor de Neum./Piezas de Reposición ($):</th>
                                    <td id="val_neum"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Vida útil Neumáticos (horas):</th>
                                    <td id="vida_util_neum"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Horas efectivas de uso anual (horas):</th>
                                    <td id="horas_uso_anual"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Horas diarias de uso (horas):</th>
                                    <td id="horas_uso_dia"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Factor de correción:</th>
                                    <td id="fat_corr"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Coeficiente de arreglos mecánicos:</th>
                                    <td id="coef_arr_mec"></td>
                                </tr>
                                <tr>
                                    <th style="width: 50%;">Lubricante (litros/hora):</th>
                                    <td id="lubricante"></td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>




                <div class="modal-footer">
                    <button id="button_upload" type="button" class="btn-navy btn bg-navy" attr="modal_view_costos_maq" onclick="closeModal(this)">Salir</button>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Modal -->
