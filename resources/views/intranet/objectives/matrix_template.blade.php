<div class="card mb-4">
@if ($status == "ok")
    <div class="card-body p-0">
        <table class="table table-bordered m-0" pilar="{{$pilar->id}}">
            <thead>
                <tr>
                    <th class="text-center align-middle t-head-dimension" width="50">Dimensión</th>
                    <th class="text-center align-middle t-head-code" width="50">Código</th>
                    <th class="text-center align-middle t-head-objective" width="180">Objetivo Estratégico</th>
                    <th class="text-center align-middle t-head-sponsor" width="50">Sponsor</th>
                    <th class="text-center align-middle t-head-kpi" width="50">KPI</th>
                    <th class="text-center align-middle t-head-formula" width="120">Fórmula</th>
                    <th class="text-center align-middle t-head-frequency" width="50">Frecuencia</th>
                    <th class="text-center align-middle t-head-type" width="50">Tipo</th>
                    <th class="text-center align-middle t-head-goal" width="50">Meta</th>
                    <th class="text-center align-middle t-head-curryear" width="50">2022</th>
                    <th class="text-center align-middle t-head-nextyear" width="50">2023</th>
                    <th class="text-center align-middle t-head-resmes" width="50">Res. Mes</th>
                    <th class="text-center align-middle t-head-resacum" width="50">Res. Acum.</th>
                    <th class="text-center align-middle t-head-actions" width="20"></th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="align-middle" rowspan="3">Rentabilidad</td>
                    <td class="align-middle" rowspan="3" align="center"><a href="#"><span class="badge bg-primary obj-code">ZZZ_RRR_01</span></a></td>
                    <td class="align-middle" rowspan="3"><a href="#">Maximizar la rentabilidad de CVC energia</a></td>
                    <td class="align-middle" rowspan="3">GAF</td>
                    <td class="align-middle">Margen Bruto</td>
                    <td class="align-middle">Ingresos - costos de ventas / Ingresos Netos</td>
                    <td class="align-middle">Mensual</td>
                    <td class="align-middle">Porcentaje</td>
                    <td class="align-middle">Por Definir</td>
                    <td class="align-middle" align="center">0</td>
                    <td class="align-middle" align="center">100</td>
                    <td class="align-middle" align="center"></td>
                    <td class="align-middle" align="center"></td>
                    <td class="align-middle" align="center"><span class="badge bg-secondary btn-more">...</span></td>
                </tr>
                <tr>
                    <td class="align-middle">Margen Bruto</td>
                    <td class="align-middle">Utilidad Neta / Ingresos Netos</td>
                    <td class="align-middle">Mensual</td>
                    <td class="align-middle">Porcentaje</td>
                    <td class="align-middle">Por Definir</td>
                    <td class="align-middle" align="center">0</td>
                    <td class="align-middle" align="center">100</td>
                    <td class="align-middle" align="center"></td>
                    <td class="align-middle" align="center"></td>
                    <td class="align-middle" align="center"><span class="badge bg-secondary btn-more">...</span></td>
                </tr>
                <tr>
                    <td class="align-middle">Margen Bruto</td>
                    <td class="align-middle">EBIT + gastos de depreciación + gastos de amortización</td>
                    <td class="align-middle">Mensual</td>
                    <td class="align-middle">Porcentaje</td>
                    <td class="align-middle">Por Definir</td>
                    <td class="align-middle" align="center">0</td>
                    <td class="align-middle" align="center">100</td>
                    <td class="align-middle" align="center"></td>
                    <td class="align-middle" align="center"></td>
                    <td class="align-middle" align="center"><span class="badge bg-secondary btn-more">...</span></td>
                </tr>
            </tbody>
        </table>
    </div>
@else
    <div class="card-body">
        <h3 class="text-danger">Pilar no encontrado</h3>
    </div>
@endif
</div>