<?php
                            if ($arrListaTickets) {
                               
                                    ?>
                                    <div class="col-12 row mb-5">
                                        <div class="col-12 bg-cyan"><b></b></div>
                                        <div class="col-12 row">
                                            <table class="table table-striped table-bordered">
                                                <thead>
                                                    <tr>
                                                        <th width="20%" style="background:#CEFCFE; border:1px solid black">NOMBRE TICKET</th>
                                                        <th width="10%" style="background:#CEFCFE; border:1px solid black">ENTIDAD</th>
                                                        <th width="10%" style="background:#CEFCFE; border:1px solid black">DEPARTAMENTO</th>
                                                        <th width="10%" style="background:#CEFCFE; border:1px solid black">RESUMEN</th>
                                                        <th width="10%" style="background:#CEFCFE; border:1px solid black">FECHA CREACION</th>
                                                        <th width="10%" style="background:#CEFCFE; border:1px solid black">FECHA CIERRE</th>
                                                        <th width="10%" style="background:#CEFCFE; border:1px solid black">RESPUESTA</th>
                                                        <th width="10%" style="background:#CEFCFE; border:1px solid black">SOLUCION</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php
                                                     foreach ($arrListaTickets as $ticket_id => $tickets) {
                                                        ?>
                                                    <tr>
                                                        <th style="border:1px solid black"><?= $tickets->nombre_ticket ?></th>
                                                        <th style="border:1px solid black"><?= $tickets->nombre_entidad?></th>
                                                        <th style="border:1px solid black"><?= $tickets->nombre_departamento ?></th>
                                                        <th style="border:1px solid black"><?= $tickets->resumen ?></th>
                                                        <th style="border:1px solid black"><?= $tickets->fecha_creacion ?></th>
                                                         
                                                        <th style="border:1px solid black"><?= $tickets->fecha_cierre ?></th>
                                                        <th style="border:1px solid black"><?= $tickets->respuesta ?></th>
                                                        <th style="border:1px solid black"><?= $tickets->solucion?></th>
                                                    </tr>
                                                   <?php } ?>
                                                </tbody>
                                            </table>
                                        </div>
                                    </div>
                                    <?php
                                }
                            
                            ?>