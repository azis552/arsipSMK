<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Render Multi-Page PDF & Drag QR Code</title>
    <style>
        #pdf-container {
            position: relative;
            margin: 0 auto;
            width: fit-content;
        }
        #pdf-render {
            border: 1px solid black;
        }
        #qr-code {
            position: absolute;
            cursor: grab;
            width: 100px;
            top: 10px;
            left: 10px;
        }
        .controls {
            text-align: center;
            margin-top: 10px;
        }
    </style>
</head>

<body>
    <h1>Render Multi-Page PDF & Drag QR Code</h1>
    <div id="pdf-container">
        <canvas id="pdf-render"></canvas>
        <img id="qr-code" src="<?= $qrCodePath ?>" draggable="true" alt="QR Code">
    </div>

    <div class="controls">
        <button id="prev-page">Previous Page</button>
        <span id="page-info">Page: <span id="page-num"></span> / <span id="page-count"></span></span>
        <button id="next-page">Next Page</button>
    </div>

    <form action="<?= base_url('suratkeluar/save-coordinates') ?>" method="post">
        <input type="hidden" name="id" value="<?= $id ?>">
        <input type="hidden" id="x-coord" name="x">
        <input type="hidden" id="y-coord" name="y">
        <input type="hidden" id="page-number" name="page">
        <input type="hidden" id="pdfPath" name="pdfPath" value="<?= $pdfPathBack ?>">
        <input type="hidden" id="qrCodePath" name="qrCodePath" value="<?= $qrCodePathBack ?>">
        <button type="submit">Save Coordinates</button>
    </form>

    <script src="https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.min.js"></script>
    <script>
        const qrCode = document.getElementById('qr-code');
        const pdfCanvas = document.getElementById('pdf-render');
        const pdfContainer = document.getElementById('pdf-container');
        const pdfPath = "<?= $pdfPath ?>"; // Path ke PDF dari server

        const prevPageBtn = document.getElementById('prev-page');
        const nextPageBtn = document.getElementById('next-page');
        const pageNumDisplay = document.getElementById('page-num');
        const pageCountDisplay = document.getElementById('page-count');

        let pdfDoc = null;
        let currentPage = 1;
        let totalPages = 0;
        let viewport = null; // Viewport untuk menyesuaikan rendering

        // Fungsi untuk merender halaman tertentu
        const renderPage = async (num) => {
            const page = await pdfDoc.getPage(num);
            viewport = page.getViewport({ scale: 1 });

            // Sesuaikan dimensi canvas untuk ukuran asli PDF
            pdfCanvas.width = viewport.width;
            pdfCanvas.height = viewport.height;

            const context = pdfCanvas.getContext('2d');
            const renderContext = {
                canvasContext: context,
                viewport: viewport,
            };
            await page.render(renderContext).promise;

            // Menambahkan border setelah halaman dirender
            context.beginPath();
            context.rect(0, 0, pdfCanvas.width, pdfCanvas.height);
            context.lineWidth = 5;
            context.strokeStyle = 'red';
            context.stroke();

            // Perbarui informasi halaman
            pageNumDisplay.textContent = num;
            pageCountDisplay.textContent = totalPages;
        };

        // Fungsi untuk memuat PDF
        const loadPDF = async (path) => {
            const pdfjsLib = window['pdfjs-dist/build/pdf'];
            pdfjsLib.GlobalWorkerOptions.workerSrc = 'https://cdnjs.cloudflare.com/ajax/libs/pdf.js/2.16.105/pdf.worker.min.js';

            pdfDoc = await pdfjsLib.getDocument(path).promise;
            totalPages = pdfDoc.numPages;
            renderPage(currentPage);
        };

        // Navigasi halaman sebelumnya
        prevPageBtn.addEventListener('click', () => {
            if (currentPage <= 1) return;
            currentPage--;
            renderPage(currentPage);
        });

        // Navigasi halaman berikutnya
        nextPageBtn.addEventListener('click', () => {
            if (currentPage >= totalPages) return;
            currentPage++;
            renderPage(currentPage);
        });

        // Muat PDF saat halaman dimuat
        loadPDF(pdfPath);

         // Drag and drop QR Code
         qrCode.addEventListener('dragend', (event) => {
            const rect = pdfCanvas.getBoundingClientRect();
            const xRel = event.clientX - rect.left;
            const yRel = event.clientY - rect.top;

            const scaleX = viewport.width / rect.width;
            const scaleY = viewport.height / rect.height;

            const xInPDF = xRel * scaleX;
            const yInPDF = yRel * scaleY;

            qrCode.style.left = `${xRel}px`;
            qrCode.style.top = `${yRel}px`;

            document.getElementById('x-coord').value = xInPDF.toFixed(2);
            document.getElementById('y-coord').value = yInPDF.toFixed(2);
            document.getElementById('page-number').value = currentPage;

            console.log(`x: ${xInPDF}, y: ${yInPDF}, page: ${currentPage}`);
        });

    </script>
</body>

</html>
