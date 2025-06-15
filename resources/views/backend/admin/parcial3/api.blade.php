@extends('backend.menus.superior')

@section('content-admin-css')
<link href="{{ asset('css/adminlte.min.css') }}" type="text/css" rel="stylesheet" />
<link href="{{ asset('css/dataTables.bootstrap4.css') }}" type="text/css" rel="stylesheet" />
<link href="{{ asset('css/toastr.min.css') }}" type="text/css" rel="stylesheet" />
<link href="{{ asset('css/buttons_estilo.css') }}" rel="stylesheet">

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

<div id="divcontenedor" style="display: none">
    <section id="geolocalizacion">
        
    </section>

    <section class="canvas">
        <div class="container-main">
            <div class="row justify-content-center">
                <div class="col-12 col-md-10">
                    <h1 class="text-center text-primary ">API Canvas</h1>
                </div>
            </div>
            <div class="container-canvas">
                <canvas id="canvas" class="border border-darkw-100 bg-light rounded shadow">
                </canvas>
                <div class="border border-darkw-100 bg-light rounded shadow" id="utils">
                    <!-- Herramientas para dibujar -->
                    <div class="util-group">
                        <label>Herramienta:</label>
                        <select id="toolSelect" class="util-input">
                            <option value="circle">Círculo</option>
                            <option value="rectangle">Rectángulo</option>
                            <option value="square">Cuadrado</option>
                            <option value="text">Texto</option>
                        </select>
                    </div>

                    <!-- Selector de color -->
                    <div class="util-group">
                        <label>Color:</label>
                        <input type="color" id="colorPicker" class="util-input" value="#000000">
                    </div>

                    <!-- Tamaño -->
                    <div class="util-group">
                        <label>Tamaño:</label>
                        <input type="range" id="sizeSlider" class="util-input" min="5" max="100" value="20">
                        <span id="sizeValue">20</span>
                    </div>

                    <!-- Texto  cuando se seleleciona texto en select  -->
                    <div class="util-group" id="textGroup" style="display: none;">
                        <label>Texto:</label>
                        <input type="text" id="textInput" class="util-input" placeholder="Escribe aquí...">
                        <label>Tamaño fuente:</label>
                        <input type="range" id="fontSizeSlider" class="util-input" min="10" max="72" value="16">
                        <span id="fontSizeValue">16</span>
                    </div>

                    <!-- Botones -->
                    <div class="util-group">
                        <button id="clearCanvas" class="btn btn-warning">Limpiar Canvas</button>
                        <button id="generatePNG" class="btn btn-success">Generar PNG</button>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="section video">
    </div>
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
        initializeCanvas();
    });

    let currentTool = 'circle';
    let currentColor = '#000000';
    let currentSize = 20;
    let currentText = '';
    let fontSize = 16;

    function initializeCanvas() {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d'); //establece el diseño en 2d

        
        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;

       // Permite manejar el tipo de herramienta seleccionada ya sea circulo, rectángulo, cuadrado o texto
        document.getElementById('toolSelect').addEventListener('change', function() {
            currentTool = this.value;
            toggleTextGroup();
        });
        // Permite manejar el color seleccionado
        document.getElementById('colorPicker').addEventListener('change', function() {
            currentColor = this.value;
        });
        // Permite manejar el tamaño del objeto seleccionado
        document.getElementById('sizeSlider').addEventListener('input', function() {
            currentSize = this.value;
            document.getElementById('sizeValue').textContent = this.value;
        });
        // Permite manejar el valor del texto ingresado
        document.getElementById('textInput').addEventListener('input', function() {
            currentText = this.value;
        });
        // Permite manejar el tamaño de la fuente del texto ingresado
        document.getElementById('fontSizeSlider').addEventListener('input', function() {
            fontSize = this.value;
            document.getElementById('fontSizeValue').textContent = this.value;
        });

        // Event listener para dibujar en el canvas
        canvas.addEventListener('click', function(e) {
            const rect = canvas.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            drawShape(x, y);
        });

        // Event listeners para botones
        document.getElementById('clearCanvas').addEventListener('click', clearCanvas);
        document.getElementById('generatePNG').addEventListener('click', generarPNG);
    }

    function toggleTextGroup() {
        const textGroup = document.getElementById('textGroup');
        if (currentTool === 'text') {
            textGroup.style.display = 'block';
        } else {
            textGroup.style.display = 'none';
        }
    }

    // Función para dibujar la forma seleccionada en el canvas apartir del select currentTool
    function drawShape(x, y) {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        ctx.fillStyle = currentColor;
        ctx.strokeStyle = currentColor;

        switch (currentTool) {
            case 'circle':
                drawCircle(x, y, currentSize, currentColor);
                break;
            case 'rectangle':
                drawRectangle(x, y, currentSize * 2, currentSize, currentColor);
                break;
            case 'square':
                drawSquare(x, y, currentSize, currentColor);
                break;
            case 'text':
                drawText(x, y, currentText, fontSize, currentColor);
                break;
        }
    }

    function drawCircle(x, y, radius, color) {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        ctx.beginPath();
        ctx.arc(x, y, radius, 0, Math.PI * 2);
        ctx.fillStyle = color;
        ctx.fill();
        ctx.closePath();
    }

    function drawRectangle(x, y, width, height, color) {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = color;
        ctx.fillRect(x - width / 2, y - height / 2, width, height);
    }

    function drawSquare(x, y, size, color) {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = color;
        ctx.fillRect(x - size / 2, y - size / 2, size, size);
    }

    function drawText(x, y, text, size, color) {
        if (!text.trim()) {
            toastr.warning('Escribe un texto antes de colocarlo en el canvas');
            return;
        }

        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        ctx.fillStyle = color;
        ctx.font = size + 'px Arial';
        ctx.textAlign = 'center';
        ctx.fillText(text, x, y);
    }

    function clearCanvas() {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        toastr.success('Canvas limpiado');
    }

    function generarPNG() {
        const canvas = document.getElementById('canvas');
        if (!isCanvasTransparent(canvas)) {
            const link = document.createElement('a');
            link.download = 'canvas_image.png';
            link.href = canvas.toDataURL('image/png');
            link.click();
            toastr.success('Imagen descargada');
        } else {
            toastr.error('El canvas está vacío, no hay nada que exportar.');
        }
    }
    // Función para verificar si el canvas está vacío (transparente)
    function isCanvasTransparent(canvas) {
        const ctx = canvas.getContext("2d");
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        //Recorre cada 4 pixeles (RGBA) y verifica si el canal alfa es 0 (transparente) si es así retorna verdadero
        for (let i = 0; i < imageData.data.length; i += 4) {
            if (imageData.data[i + 3] !== 0) return false;
        }
        return true;
    }

    /*Enlaces de apoyo
    https://stackoverflow.com/questions/12796513/html5-canvas-to-png-file
   https://developer.mozilla.org/es/docs/Web/API/Canvas_API/Tutorial/Drawing_text
    https://developer.mozilla.org/es/docs/Web/API/Canvas_API/Tutorial/Drawing_shapes
    */
</script>

@stop