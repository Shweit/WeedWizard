import jsQR from "jsqr";
import {sanitizeHtml} from "bootstrap/js/src/util/sanitizer";

document.addEventListener('DOMContentLoaded', () => {
    // Initialisiere die Event Listener für alle Modal Elemente
    document.querySelectorAll('.modal.fade.checkAttendanceModal').forEach(modal => {
        const budBashId = modal.getAttribute('data-id');
        const videoElement = document.getElementById(`video-${budBashId}`);
        const startScanBtn = document.getElementById(`startScanBtn-${budBashId}`);
        const stopScanBtn = document.getElementById(`stopScanBtn-${budBashId}`);

        // Zustand des Scanning im Modal speichern
        modal.scanning = false;

        // Stream der Kamera starten
        startScanBtn.addEventListener('click', () => {
            if (navigator.mediaDevices && navigator.mediaDevices.getUserMedia) {
                navigator.mediaDevices.getUserMedia({ video: { facingMode: "environment" } }).then(stream => {
                    videoElement.srcObject = stream;
                    videoElement.removeAttribute('hidden');  // Video sichtbar machen
                    stopScanBtn.style.display = 'inline-block';
                    startScanBtn.style.display = 'none';
                    modal.scanning = true;
                    scanQRCode(videoElement, modal);
                }).catch(err => {
                    const errorAlert = document.getElementById(`checkAttendanceModal-errorAlert-${budBashId}`);
                    errorAlert.textContent = 'Fehler beim Zugriff auf die Kamera';
                    errorAlert.style.display = 'block';
                });
            }
        });

        // Stream der Kamera stoppen
        stopScanBtn.addEventListener('click', () => {
            stopScanning(videoElement, modal);
        });

        modal.addEventListener('hidden.bs.modal', () => {
            if (modal.scanning) {
                stopScanning(videoElement, modal);
            }
        });
    });
});

function scanQRCode(video, modal) {
    const canvasElement = document.createElement('canvas');

    // Setze willReadFrequently für bessere Performance beim Lesen von Daten
    if (canvasElement.getContext('2d', { willReadFrequently: true })) {
        const ctx = canvasElement.getContext('2d', { willReadFrequently: true });

        function drawFrame() {
            if (!modal.scanning) {
                return;
            }
            requestAnimationFrame(drawFrame);
            ctx.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
            const imageData = ctx.getImageData(0, 0, canvasElement.width, canvasElement.height);
            const code = jsQR(imageData.data, imageData.width, imageData.height, {
                inversionAttempts: "dontInvert",
            });

            if (code) {
                stopScanning(video, modal);
                handleData(code.data, modal.getAttribute('data-id'));
            }
        }

        drawFrame();
    } else {
        alert("2D Context mit willReadFrequently kann nicht initialisiert werden.");
    }
}

function stopScanning(video, modal) {
    const stream = video.srcObject;
    const tracks = stream ? stream.getTracks() : [];
    tracks.forEach(track => track.stop());
    video.srcObject = null;
    video.setAttribute('hidden', true);  // Video verstecken
    document.getElementById(`stopScanBtn-${modal.getAttribute('data-id')}`).style.display = 'none';
    document.getElementById(`startScanBtn-${modal.getAttribute('data-id')}`).style.display = 'inline-block';
    modal.scanning = false;
}

function handleData(data, budBashId) {
    const alert = document.getElementById('checkAttendanceModal-errorAlert-' + budBashId);
    if (isValidQRCode(data)) {
        const domain = `${window.location.protocol}//${window.location.host}`;
        let fullUrl = domain + data;

        fullUrl += `?budBashId=${budBashId}`;

        fetch(fullUrl, {
            method: 'GET'
        })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    alert.innerHTML = sanitizeHtml(data.success);
                    alert.className = 'alert alert-success';
                    alert.style.display = 'block';
                } else {
                    alert.innerHTML = sanitizeHtml(data.error);
                    alert.className = 'alert alert-danger';
                    alert.style.display = 'block';
                }
            })
            .catch(error => {
                aler('Error:', error);
            });
    } else {
        alert.innerHTML = 'Ungültiger QR-Code';
        alert.style.display = 'block';
    }
}

function isValidQRCode(data) {
    const regex = /^\/budBash-locator\/\d+\/checkAttendance\/\d+\/[^\/]+$/i;
    return regex.test(data);
}
