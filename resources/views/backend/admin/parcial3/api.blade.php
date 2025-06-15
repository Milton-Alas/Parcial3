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
    <h2 class="mb-5 text-center fw-bold">🌐 APIs de Geolocalización, Canvas y Video</h2>
    <section id="geolocalizacion">
        <div class="card shadow mb-5">
            <div class="card-header bg-primary text-white">📍 Ubicación actual</div>
            <div class="card-body text-center">
                <p><strong>Coordenadas:</strong> <span id="coords" class="text-muted">Cargando...</span></p>
                <div id="map" class="rounded" style="height: 400px; max-width: 700px; margin: auto;"></div>
            </div>
        </div>
    </section>

    <section class="canvas py-5">
        <div class="card shadow mb-5">
            <div class="card-header bg-success text-white">
                🎨 API Canvas
            </div>

            <div class="card-body">
                <section class="canvas">
                    <div class="container">
                        <div class="row justify-content-center">
                            <div class="col-lg-8 text-center mb-4">
                                <canvas id="canvas" width="700" height="400" class="border border-secondary rounded shadow bg-light"></canvas>
                            </div>

                            <div class="col-lg-4">
                                <div class="p-3 border rounded shadow-sm bg-light">
                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">🛠 Herramienta</label>
                                        <select id="toolSelect" class="form-select">
                                            <option value="circle">Círculo</option>
                                            <option value="rectangle">Rectángulo</option>
                                            <option value="square">Cuadrado</option>
                                            <option value="text">Texto</option>
                                        </select>
                                    </div>

                                    <div class="mb-3 d-flex align-items-center" style="gap: 0.5rem;">
                                        <label for="colorPicker" class="form-label fw-semibold mb-0">🎨 Color</label>
                                        <input type="color" id="colorPicker" class="form-control form-control-color" value="#000000" style="width: 40px; height: 30px;">
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-semibold">📏 Tamaño</label>
                                        <input type="range" id="sizeSlider" class="form-range" min="5" max="100" value="5">
                                        <div class="text-end text-muted small">Tamaño: <span id="sizeValue">5</span></div>
                                    </div>

                                    <div class="mb-3" id="textGroup" style="display: none;">
                                        <label class="form-label fw-semibold">✏️ Texto</label>
                                        <input type="text" id="textInput" class="form-control mb-2" placeholder="Escribe aquí...">
                                        <label class="form-label">Tamaño fuente</label>
                                        <input type="range" id="fontSizeSlider" class="form-range" min="10" max="72" value="16">
                                        <div class="text-end text-muted small">Tamaño: <span id="fontSizeValue">16</span></div>
                                    </div>

                                    <div class="d-flex justify-content-between mt-4">
                                        <button id="clearCanvas" class="btn btn-danger">🧹 Limpiar</button>
                                        <button id="generatePNG" class="btn btn-success">💾 Guardar PNG</button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>

    <section id="video">
        <div class="card shadow mb-5">
            <div class="card-header bg-dark text-white">🎥 Reproductor de Video</div>
            <div class="card-body text-center">
                <video id="videoPlayer" width="700" class="rounded shadow mb-3">
                    <source src="{{ asset('videos/prueba.mp4') }}" type="video/mp4">
                    Tu navegador no soporta video HTML5.
                </video>

                <div class="d-flex flex-wrap justify-content-center gap-3 mb-3" style="gap: 1.5rem;">
                    <div>
                        <button onclick="video.play()" class="btn btn-success">
                        <i class="bi bi-play-fill me-1"></i> Play
                        </button>
                    </div>

                    <div>
                        <button onclick="video.pause()" class="btn btn-warning">
                        <i class="bi bi-pause-fill me-1"></i> Pausa
                        </button>
                    </div>

                    <div>
                        <button onclick="video.stop()" class="btn btn-danger">
                        <i class="bi bi-stop-fill me-1"></i> Detener
                        </button>
                    </div>

                    <div>
                        <button onclick="video.currentTime -= 10" class="btn btn-info">
                        <i class="bi bi-rewind-fill me-1"></i> -10s
                        </button>
                    </div>

                    <div>
                        <button onclick="video.currentTime += 10" class="btn btn-info">
                        <i class="bi bi-fast-forward-fill me-1"></i> +10s
                        </button>
                    </div>

                    <div class="d-flex align-items-center gap-2">
                        <label for="volume" class="form-label m-0">
                        <i class="bi bi-volume-up-fill"></i>
                        </label>
                        <input type="range" id="volume" min="0" max="1" step="0.1" value="1" class="form-range" style="width: 150px;">
                    </div>

                    <div class="d-flex align-items-center gap-2" style="gap: 0.3rem;">
                        <label for="speed" class="form-label m-0">
                        <i class="bi bi-speedometer"></i>
                        </label>
                        <select id="speed" class="form-select form-select-sm">
                        <option value="0.5">0.5x</option>
                        <option value="1" selected>1x</option>
                        <option value="1.5">1.5x</option>
                        <option value="2">2x</option>
                        </select>
                    </div>
                </div>

                <p class="text-muted">⏳ <span id="current">0</span> / <span id="duration">0</span> segundos</p>
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
        initializeCanvas();
    });

    let currentTool = 'circle';
    let currentColor = '#000000';
    let currentSize = 5;
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
