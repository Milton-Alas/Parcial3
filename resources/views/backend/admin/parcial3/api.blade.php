@extends('backend.menus.superior')

@section('content-admin-css')
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" crossorigin="" />
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
               <div id="map" class="rounded" style="height: 500px; width: 100%;"></div>
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
                                            <option value="pencil">✏️ Lápiz (Dibujo libre)</option>
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

                                    <div class="mb-3" id="pencilGroup" style="display: none;">
                                        <label class="form-label fw-semibold">🖌️ Grosor del trazo</label>
                                        <input type="range" id="strokeWidth" class="form-range" min="1" max="20" value="3">
                                        <div class="text-end text-muted small">Grosor: <span id="strokeValue">3</span></div>
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

                                    <div class="mt-3 p-2  bg-opacity-10 rounded">
                                        <small class="text-muted">
                                            <strong>💡 Instrucciones:</strong><br>
                                            • <strong>Lápiz:</strong> Mantén presionado y arrastra<br>
                                            • <strong>Figuras:</strong> Haz clic y arrastra para redimensionar<br>
                                            • <strong>Texto:</strong> Haz clic donde quieras colocarlo
                                        </small>
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

<script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js" crossorigin=""></script>
<script src="{{ asset('js/jquery.dataTables.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/dataTables.bootstrap4.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/toastr.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/axios.min.js') }}" type="text/javascript"></script>
<script src="{{ asset('js/sweetalert2.all.min.js') }}"></script>
<script src="{{ asset('js/alertaPersonalizada.js') }}"></script>

<script>
  // Fix íconos Leaflet
  delete L.Icon.Default.prototype._getIconUrl;
  L.Icon.Default.mergeOptions({
    iconRetinaUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon-2x.png",
    iconUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-icon.png",
    shadowUrl: "https://unpkg.com/leaflet@1.9.4/dist/images/marker-shadow.png",
  });

  let map, marker;

  window.addEventListener('load', function () {
    // Vista inicial global (mundo entero) sin centrarse
    map = L.map("map").setView([0, 0], 2);

    L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
      attribution: "&copy; OpenStreetMap contributors"
    }).addTo(map);

    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function (position) {
        const lat = position.coords.latitude;
        const lng = position.coords.longitude;

        // Mostrar coordenadas
        document.getElementById("coords").textContent = `${lat.toFixed(6)}, ${lng.toFixed(6)}`;

        // Centrar el mapa y hacer zoom cuando se obtiene la ubicación
        map.setView([lat, lng], 15);

        // Agregar marcador en ubicación actual
        marker = L.marker([lat, lng]).addTo(map)
          .bindPopup("¡Estás aquí!")
          .openPopup();
      }, function (error) {
        document.getElementById("coords").textContent = "No disponible";
        alert("Error al obtener la ubicación: " + error.message);
      });
    } else {
      document.getElementById("coords").textContent = "No soportado";
      alert("Tu navegador no soporta geolocalización.");
    }
  });
</script>


<script type="text/javascript">
    $(document).ready(function() {
        document.getElementById("divcontenedor").style.display = "block";
        initializeCanvas();
    });

    let currentTool = 'pencil';
    let currentColor = '#000000';
    let currentText = '';
    let fontSize = 16;
    let strokeWidth = 3;

    // Variables para el dibujo libre con lápiz
    let isDrawing = false;
    let lastX = 0;
    let lastY = 0;

    // Variables para figuras redimensionables
    let isDrawingShape = false;
    let startX = 0;
    let startY = 0;

    // Array para almacenar todas las formas dibujadas
    let drawnShapes = [];
    let currentPreviewShape = null;

    function initializeCanvas() {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        canvas.width = canvas.offsetWidth;
        canvas.height = canvas.offsetHeight;

        //  listeners para controles
        // Cambiar tipo de figura
        document.getElementById('toolSelect').addEventListener('change', function() {
            currentTool = this.value;
            toggleToolGroups();
            updateCursor();
        });
        // Cambiar color
        document.getElementById('colorPicker').addEventListener('change', function() {
            currentColor = this.value;
        });
        // Cambiar grosor del trazo
        document.getElementById('strokeWidth').addEventListener('input', function() {
            strokeWidth = this.value;
            document.getElementById('strokeValue').textContent = this.value;
        });
        // Establecer el texto 
        document.getElementById('textInput').addEventListener('input', function() {
            currentText = this.value;
        });
        // Cambiar tamaño del texto
        document.getElementById('fontSizeSlider').addEventListener('input', function() {
            fontSize = this.value;
            document.getElementById('fontSizeValue').textContent = this.value;
        });

        //  listeners para  los eventos de canvas
        canvas.addEventListener('mousedown', handleMouseDown);
        canvas.addEventListener('mousemove', handleMouseMove);
        canvas.addEventListener('mouseup', handleMouseUp);
        canvas.addEventListener('mouseout', handleMouseOut);
        canvas.addEventListener('click', handleClick);

        // listeners para los botones de utilidades
        document.getElementById('clearCanvas').addEventListener('click', clearCanvas);
        document.getElementById('generatePNG').addEventListener('click', generarPNG);

        // Inicializar vista
        toggleToolGroups();
        updateCursor();
    }

    //Funcion para  mostrar/ocultar pencil cuando se seleccciona el lápiz y viceversa
    function toggleToolGroups() {
        const textGroup = document.getElementById('textGroup');
        const pencilGroup = document.getElementById('pencilGroup');

        textGroup.style.display = 'none';
        pencilGroup.style.display = 'none';

        if (currentTool === 'text') {
            textGroup.style.display = 'block';
        } else if (currentTool === 'pencil') {
            pencilGroup.style.display = 'block';
        }
    }

    // Actualizar el cursor  del div con id #canvas cuando se cambia a texto o lápiz
    function updateCursor() {
        const canvas = document.getElementById('canvas');
        if (currentTool === 'pencil') {
            //https://developer.mozilla.org/en-US/docs/Web/CSS/cursor
            // Usar un cursor personalizado para el lápiz
            canvas.style.cursor = 'url("data:image/svg+xml;utf8,<svg xmlns=\'http://www.w3.org/2000/svg\' width=\'20\' height=\'20\' viewport=\'0 0 20 20\'><circle cx=\'10\' cy=\'10\' r=\'2\' fill=\'black\'/></svg>") 10 10, auto';
        } else {
            canvas.style.cursor = 'crosshair';
        }
    }

    /*Permite establecer coordenadas de la ubicación actual del mouse
    Y dependiendo de la figura o trazo establece las coordenadas iniciales*/
    function handleMouseDown(e) {
        const rect = e.target.getBoundingClientRect();
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        if (currentTool === 'pencil') {
            isDrawing = true;
            lastX = x;
            lastY = y;
            currentPreviewShape = {
                type: 'pencil',
                points: [{
                    x: x,
                    y: y
                }],
                color: currentColor,
                strokeWidth: strokeWidth
            };
        } else if (currentTool !== 'text') {
            isDrawingShape = true;
            startX = x;
            startY = y;
        }
    }
    /*
Pernite establecer las coordenadas del mouse mientras se mantiene presionado el botón
    y dependiendo de la figura o trazo dibuja una línea o una figura de previsualización
     */
    function handleMouseMove(e) {
        //Valida se se esta dibujando dentro del canvas
        if (!isDrawing && !isDrawingShape) return;

        const rect = e.target.getBoundingClientRect(); //obtiene posicion y dimensions del canvas
        const x = e.clientX - rect.left;
        const y = e.clientY - rect.top;

        //Genera una previsualización de la forma o trazo que se está dibujando dependiendo de la herramienta seleccionada
        if (currentTool === 'pencil' && isDrawing) {
            currentPreviewShape.points.push({
                x: x,
                y: y
            });
            redrawCanvas();
            drawPencilLine(currentPreviewShape);
            lastX = x;
            lastY = y;
        } else if (isDrawingShape) {
            redrawCanvas();
            drawPreviewShape(startX, startY, x, y);
        }
    }
    /* Cuando el mouse sale del canvas se setean las variables isDrawing a false y currebtPreviewShape a null,
     y se guarda la figura dibujada en el array drawShapes */
    function handleMouseUp(e) {
        if (currentTool === 'pencil' && isDrawing) {
            drawnShapes.push(currentPreviewShape);
            currentPreviewShape = null;
            isDrawing = false;
        } else if (isDrawingShape) {
            const rect = e.target.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;
            //Si el mouse se ha movido más de 5 píxeles desde el inicio, se crea la forma final
            if (Math.abs(x - startX) > 5 || Math.abs(y - startY) > 5) {
                const shape = createFinalShape(startX, startY, x, y);
                drawnShapes.push(shape);
                redrawCanvas();
            }
            isDrawingShape = false;
        }
    }
    // Cuando el mouse sale del canvas, se finaliza la linea actual si se está dibujando
    function handleMouseOut() {
        if (isDrawing && currentPreviewShape) {
            // Finalizar la línea actual
            drawnShapes.push(currentPreviewShape);
            currentPreviewShape = null;
        }
        isDrawing = false;
        isDrawingShape = false;
    }
    // Valida que el usuario haya escrito un texto antes de colocarlo en el canvas
    function handleClick(e) {
        if (currentTool === 'text') {
            const rect = e.target.getBoundingClientRect();
            const x = e.clientX - rect.left;
            const y = e.clientY - rect.top;

            if (currentText.trim()) {
                const textShape = {
                    type: 'text',
                    x: x,
                    y: y,
                    text: currentText,
                    fontSize: fontSize,
                    color: currentColor
                };
                drawnShapes.push(textShape);
                redrawCanvas();
            } else {
                toastr.warning('Escribe un texto antes de colocarlo en el canvas');
            }
        }
    }
    // Dibuja una línea de lápiz en el canvas
    function drawPencilLine(shape) {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        if (shape.points.length < 2) return;

        ctx.strokeStyle = shape.color;
        ctx.lineWidth = shape.strokeWidth;
        ctx.lineCap = 'round';
        ctx.lineJoin = 'round';

        ctx.beginPath(); // Inicia un camino para dibujar ya sea una linea o una curva etc.
        ctx.moveTo(shape.points[0].x, shape.points[0].y);

        for (let i = 1; i < shape.points.length; i++) {
            ctx.lineTo(shape.points[i].x, shape.points[i].y);
        }

        ctx.stroke();
        ctx.closePath();
    }
    //muestra una previsualización de la forma que se está dibujando a partir de la figura seleccionada
    function drawPreviewShape(startX, startY, currentX, currentY) {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        ctx.strokeStyle = currentColor;
        ctx.fillStyle = currentColor;
        ctx.lineWidth = 2;

        const width = Math.abs(currentX - startX);
        const height = Math.abs(currentY - startY);
        const centerX = (startX + currentX) / 2;
        const centerY = (startY + currentY) / 2;

        ctx.globalAlpha = 0.7; // Semi-transparente para preview

        switch (currentTool) {
            case 'circle':
                const radius = Math.max(width, height) / 2;
                ctx.beginPath();
                ctx.arc(centerX, centerY, radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.closePath();
                break;
            case 'rectangle':
                ctx.fillRect(Math.min(startX, currentX), Math.min(startY, currentY), width, height);
                break;
            case 'square':
                const size = Math.max(width, height);
                const squareX = startX - (currentX > startX ? 0 : size);
                const squareY = startY - (currentY > startY ? 0 : size);
                ctx.fillRect(squareX, squareY, size, size);
                break;
        }

        ctx.globalAlpha = 1.0; // Restaurar opacidad
    }

    //crea la forma final según el tipo de herramienta seleccionada
    function createFinalShape(startX, startY, endX, endY) {
        const width = Math.abs(endX - startX);
        const height = Math.abs(endY - startY);
        const centerX = (startX + endX) / 2;
        const centerY = (startY + endY) / 2;

        switch (currentTool) {
            case 'circle':
                return {
                    type: 'circle',
                        centerX: centerX,
                        centerY: centerY,
                        radius: Math.max(width, height) / 2,
                        color: currentColor
                };
            case 'rectangle':
                return {
                    type: 'rectangle',
                        x: Math.min(startX, endX),
                        y: Math.min(startY, endY),
                        width: width,
                        height: height,
                        color: currentColor
                };
            case 'square':
                const size = Math.max(width, height);
                return {
                    type: 'square',
                        x: startX - (endX > startX ? 0 : size),
                        y: startY - (endY > startY ? 0 : size),
                        size: size,
                        color: currentColor
                };
        }
    }
    //redibuja el canvas completo recorriendo el array de formas dibujadas
    function redrawCanvas() {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        ctx.clearRect(0, 0, canvas.width, canvas.height);

        drawnShapes.forEach(shape => {
            drawShape(shape);
        });
    }

    // Dibuja la forma según su el tipo de forma seleccionada
    function drawShape(shape) {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');

        ctx.fillStyle = shape.color;
        ctx.strokeStyle = shape.color;

        switch (shape.type) {
            case 'pencil':
                drawPencilLine(shape);
                break;
            case 'circle':
                ctx.beginPath();
                ctx.arc(shape.centerX, shape.centerY, shape.radius, 0, Math.PI * 2);
                ctx.fill();
                ctx.closePath();
                break;
            case 'rectangle':
                ctx.fillRect(shape.x, shape.y, shape.width, shape.height);
                break;
            case 'square':
                ctx.fillRect(shape.x, shape.y, shape.size, shape.size);
                break;
            case 'text':
                ctx.font = shape.fontSize + 'px Arial';
                ctx.textAlign = 'center';
                ctx.fillText(shape.text, shape.x, shape.y);
                break;
        }
    }

    // Limpia el canvas y el array de formas dibujadas que es drawnShapes
    function clearCanvas() {
        const canvas = document.getElementById('canvas');
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        drawnShapes = [];
        toastr.success('Canvas limpiado');
    }

    // Si el canvas no está vacío, genera un PNG y lo descarga
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
    //Recorre todos los píxeles del canvas y verifica si alguno tiene un valor de alfa diferente de 0
    function isCanvasTransparent(canvas) {
        const ctx = canvas.getContext("2d");
        const imageData = ctx.getImageData(0, 0, canvas.width, canvas.height);
        for (let i = 0; i < imageData.data.length; i += 4) {
            if (imageData.data[i + 3] !== 0) return false;
        }
        return true;
    }
</script>

@stop