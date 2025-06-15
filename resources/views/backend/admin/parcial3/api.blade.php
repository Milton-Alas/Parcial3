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

        /*styles de video*/
        .video-container {
            max-width: 100%;
            margin: 20px auto;
            padding: 20px;
            border: 1px solid #ddd;
            border-radius: 10px;
            background-color: #f9f9f9;
        }

        .video-player {
            width: 100%;
            max-width: 100%;
            height: auto;
            border-radius: 8px;
            margin-bottom: 15px;
        }

        .video-controls {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }

        .video-control-btn {
            padding: 8px 15px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
        }

        .video-control-btn:hover {
            background-color: #0056b3;
        }

        .video-control-btn:disabled {
            background-color: #6c757d;
            cursor: not-allowed;
        }

        .time-display {
            background-color: #343a40;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-family: monospace;
            font-size: 14px;
        }

        .status-display {
            background-color: #28a745;
            color: white;
            padding: 5px 10px;
            border-radius: 15px;
            font-size: 14px;
            font-weight: bold;
        }

        .status-paused {
            background-color: #ffc107;
            color: #212529;
        }

        .status-ended {
            background-color: #dc3545;
        }

        .video-slider-container {
            display: flex;
            align-items: center;
            gap: 10px;
            margin: 5px 0;
            justify-content: center;
        }

        .video-slider {
            flex: 1;
            max-width: 150px;
        }

        .video-slider-label {
            font-size: 14px;
            font-weight: bold;
            min-width: 80px;
        }

        .video-slider-value {
            min-width: 40px;
            font-size: 14px;
            color: #666;
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
                        <input type="range" id="sizeSlider" class="util-input" min="5" max="100"
                            value="20">
                        <span id="sizeValue">20</span>
                    </div>

                    <!-- Texto  cuando se seleleciona texto en select  -->
                    <div class="util-group" id="textGroup" style="display: none;">
                        <label>Texto:</label>
                        <input type="text" id="textInput" class="util-input" placeholder="Escribe aquí...">
                        <label>Tamaño fuente:</label>
                        <input type="range" id="fontSizeSlider" class="util-input" min="10" max="72"
                            value="16">
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
        <div class="section video">
        <section class="video">
            <div class="container-main">
                <div class="row justify-content-center">
                    <div class="col-12 col-mf-10">
                        <h1 class="text-center text-primary">Api de video</h1>
                    </div>
                </div>
                <div class="video-container">
                    <!-- Video Element -->
                    <video id="videoPlayer" class="video-player" preload="metadata">
                        <!-- Opción 1: URL pública -->
                        <source src="https://commondatastorage.googleapis.com/gtv-videos-bucket/sample/BigBuckBunny.mp4"
                            type="video/mp4">
                    </video>

                    <!-- Controles Básicos -->
                    <div class="video-controls">
                        <button id="playBtn" class="video-control-btn">▶️ Play</button>
                        <button id="pauseBtn" class="video-control-btn">⏸️ Pausa</button>
                        <button id="stopBtn" class="video-control-btn">⏹️ Detener</button>
                        <button id="backBtn" class="video-control-btn">⏪ -10s</button>
                        <button id="forwardBtn" class="video-control-btn">⏩ +10s</button>
                    </div>

                    <!-- Información del Video -->
                    <div class="video-controls">
                        <div id="timeDisplay" class="time-display">00:00 / 00:00</div>
                        <div id="statusDisplay" class="status-display">Cargando...</div>
                    </div>

                    <!-- Control de Velocidad -->
                    <div class="video-slider-container">
                        <label class="video-slider-label">Velocidad:</label>
                        <input type="range" id="speedSlider" class="video-slider" min="0.25" max="2"
                            step="0.25" value="1">
                        <span id="speedValue" class="video-slider-value">1x</span>
                    </div>

                    <!-- Control de Volumen -->
                    <div class="video-slider-container">
                        <label class="video-slider-label">Volumen:</label>
                        <input type="range" id="volumeSlider" class="video-slider" min="0" max="1"
                            step="0.1" value="1">
                        <span id="volumeValue" class="video-slider-value">100%</span>
                    </div>
                </div>
            </div>
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
            initializeVideo();
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

        function initializeVideo() {
            try {
                // Elementos del DOM - Verificar que existan antes de usarlos
                const video = document.getElementById('videoPlayer');
                
                // Verificar que el elemento del video exista
                if (!video) {
                    console.error("El elemento con ID 'videoPlayer' no se encontró.");
                    toastr.error('No se encontró el reproductor de video en la página.');
                    return; // Sale de la función si el video no existe
                }

                const playBtn = document.getElementById('playBtn');
                const pauseBtn = document.getElementById('pauseBtn');
                const backBtn = document.getElementById('backBtn');
                const forwardBtn = document.getElementById('forwardBtn');
                const timeDisplay = document.getElementById('timeDisplay');
                const statusDisplay = document.getElementById('statusDisplay');
                const speedSlider = document.getElementById('speedSlider');
                const speedValue = document.getElementById('speedValue');
                const volumeSlider = document.getElementById('volumeSlider');
                const volumeValue = document.getElementById('volumeValue');

                // Verificar que todos los elementos existan
                if (!playBtn || !pauseBtn || !backBtn || !forwardBtn || !timeDisplay || 
                    !statusDisplay || !speedSlider || !speedValue || !volumeSlider || !volumeValue) {
                    console.error("Algunos elementos de control del video no se encontraron.");
                    toastr.error('Error al inicializar controles del video.');
                    return;
                }

                // Función para formatear tiempo en MM:SS
                function formatTime(seconds) {
                    if (isNaN(seconds)) return "00:00";
                    const mins = Math.floor(seconds / 60);
                    const secs = Math.floor(seconds % 60);
                    return `${mins.toString().padStart(2, '0')}:${secs.toString().padStart(2, '0')}`;
                }

                // Función para actualizar el estado
                function updateStatus(status) {
                    statusDisplay.textContent = status;
                    statusDisplay.className = 'status-display';
                    
                    switch(status) {
                        case 'Pausado':
                            statusDisplay.classList.add('status-paused');
                            break;
                        case 'Finalizado':
                            statusDisplay.classList.add('status-ended');
                            break;
                        default:
                            // Reproduciendo - mantiene el color verde por defecto
                            break;
                    }
                }

                // Manejo de errores con try-catch
                function safeExecute(func, errorMessage) {
                    try {
                        func();
                    } catch (error) {
                        console.error(errorMessage, error);
                        updateStatus('Error');
                        toastr.error(errorMessage);
                    }
                }

                // Event Listeners para controles básicos
                playBtn.addEventListener('click', () => {
                    safeExecute(() => {
                        video.play();
                        updateStatus('Reproduciendo');
                        toastr.success('Video reproduciendo');
                    }, 'Error al reproducir video');
                });

                pauseBtn.addEventListener('click', () => {
                    safeExecute(() => {
                        video.pause();
                        updateStatus('Pausado');
                        toastr.info('Video pausado');
                    }, 'Error al pausar video');
                });

                // Event Listener para el botón de detener
                stopBtn.addEventListener('click', () => {
                    safeExecute(() => {
                        video.pause();        // Pausa el video
                        video.currentTime = 0; // Reinicia el tiempo a 0
                        updateStatus('Detenido'); // Actualiza el estado
                    }, 'Error al detener el video.');
                });

                backBtn.addEventListener('click', () => {
                    safeExecute(() => {
                        video.currentTime = Math.max(0, video.currentTime - 10);
                        toastr.info('Retrocedido 10 segundos');
                    }, 'Error al retroceder video');
                });

                forwardBtn.addEventListener('click', () => {
                    safeExecute(() => {
                        video.currentTime = Math.min(video.duration, video.currentTime + 10);
                        toastr.info('Adelantado 10 segundos');
                    }, 'Error al adelantar video');
                });

                // Control de velocidad
                speedSlider.addEventListener('input', () => {
                    safeExecute(() => {
                        const speed = parseFloat(speedSlider.value);
                        video.playbackRate = speed;
                        speedValue.textContent = `${speed}x`;
                        toastr.info(`Velocidad cambiada a ${speed}x`);
                    }, 'Error al cambiar velocidad');
                });

                // Control de volumen
                volumeSlider.addEventListener('input', () => {
                    safeExecute(() => {
                        const volume = parseFloat(volumeSlider.value);
                        video.volume = volume;
                        volumeValue.textContent = `${Math.round(volume * 100)}%`;
                    }, 'Error al cambiar volumen');
                });

                // Event Listeners del video
                function updateStatus(status) {
                statusDisplay.textContent = status;
                statusDisplay.className = 'status-display';
                
                    switch(status) {
                        case 'Pausado':
                            statusDisplay.classList.add('status-paused');
                            break;
                        case 'Detenido':
                        case 'Finalizado':
                            statusDisplay.classList.add('status-ended');
                            break;
                        default:
                            // Reproduciendo - mantiene el color verde por defecto
                            break;
                    }
                }    
                video.addEventListener('timeupdate', () => {
                    safeExecute(() => {
                        if (!isNaN(video.duration)) {
                            timeDisplay.textContent = `${formatTime(video.currentTime)} / ${formatTime(video.duration)}`;
                        }
                    }, 'Error al actualizar tiempo');
                });

                video.addEventListener('play', () => {
                    updateStatus('Reproduciendo');
                });

                video.addEventListener('pause', () => {
                    updateStatus('Pausado');
                });

                video.addEventListener('ended', () => {
                    updateStatus('Finalizado');
                    toastr.info('Video finalizado');
                });

                video.addEventListener('error', (e) => {
                    console.error('Error del video:', e);
                    updateStatus('Error al cargar');
                    toastr.error('Error al cargar el video');
                });

                // Inicialización
                safeExecute(() => {
                    // Configurar volumen inicial
                    video.volume = 1;
                    volumeValue.textContent = '100%';
                    
                    // Configurar velocidad inicial
                    video.playbackRate = 1;
                    speedValue.textContent = '1x';
                    
                    updateStatus('Cargando...');
                }, 'Error en inicialización del video');

            } catch (error) {
                console.error('Error al inicializar video:', error);
                toastr.error('Error al inicializar el reproductor de video');
            }
        }
    </script>

@stop
