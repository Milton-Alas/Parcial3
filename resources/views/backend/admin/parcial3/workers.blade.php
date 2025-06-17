@extends('backend.menus.superior')

@section('content-admin-css')
<link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
<link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
<link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
<link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css" rel="stylesheet">

<style>
    #canvas {
        cursor: crosshair;
        height: 500px;
        width: 100%;
    }

    .container-canvas {
        width: 80%;
        height: 100%;
        display: flex;
        margin: auto;
        justify-content: center;
    }

    #utils {
        width: 100%;
        height: 500px;
        padding: 10px;
    }

    .util-group {
        margin-bottom: 15px;
    }

    .util-input {
        margin: 5px;
        padding: 5px;
    }
</style>
@stop

<div id="divcontenedor" style="display: none" class="container py-4">
    <h2 class="mb-5 text-center fw-bold">🌐 Web Worker</h2>
    <section id="webworker">
        <div class="card shadow mb-5">
            <div class="card-header bg-success text-white">#️⃣ Cálculo de Números Primos</div>
            <div class="card-body text-center">

                <div class="mb-3">
                    <label for="limit" class="form-label">Límite superior para calcular números primos:</label>
                    <input type="number" id="limit" class="form-control" placeholder="Ej: 300000">
                </div>

                <button id="startWorker" class="btn btn-primary mb-3">Iniciar Cálculo</button>

                <h4>Resultado (primeros 300 primos):</h4>
                <div id="result" style="max-height: 300px; overflow-y: auto;" class="border p-3 rounded"></div>
            </div>
        </div>
    </section>
</div>

@extends('backend.menus.footerjs')
@section('archivos-js')

<script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        document.getElementById("divcontenedor").style.display = "block";
    });
</script>

@stop
