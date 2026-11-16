<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
            Gate Scanner: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-800/90 p-6 sm:p-8 shadow-2xl border border-slate-800 text-center rounded-xl">
                
                <h3 class="text-white font-black mb-4 text-xs uppercase tracking-widest text-slate-300">
                    Arahkan Kamera ke QR Code Tiket
                </h3>
                
                <!-- Scanner Container -->
                <div id="reader" class="w-full bg-slate-950 overflow-hidden border border-slate-700 rounded-lg shadow-inner"></div>

                <!-- Result Box -->
                <div id="scan-result" class="mt-6 p-4 hidden text-center font-bold text-sm rounded-lg transition-all"></div>

                <!-- Manual Ticket Code Input Fallback -->
                <div class="mt-6 pt-6 border-t border-slate-700/60 text-left">
                    <p class="text-xs uppercase tracking-wider text-slate-400 font-bold mb-2">Input Manual Kode Tiket</p>
                    <form id="manual-form" class="flex gap-2">
                        <input type="text" id="manual-ticket-code" placeholder="Contoh: RL-ABCD1234" 
                            class="flex-1 bg-slate-900 border border-slate-700 rounded-lg px-3 py-2 text-white text-sm placeholder-slate-500 focus:outline-none focus:border-red-500 uppercase tracking-wider">
                        <button type="submit" 
                            class="bg-red-600 hover:bg-red-700 text-white font-bold text-xs uppercase px-4 py-2 rounded-lg transition tracking-wider shrink-0">
                            Check-In
                        </button>
                    </form>
                </div>

                <div class="mt-6">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition">
                        &larr; Balik ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <style>
        #reader {
            border: 1px solid #334155 !important;
        }
        #reader video {
            border-radius: 0.5rem;
            object-fit: cover;
        }
        #reader button {
            background-color: #dc2626 !important;
            color: #ffffff !important;
            font-weight: 700 !important;
            font-size: 0.75rem !important;
            text-transform: uppercase !important;
            letter-spacing: 0.05em !important;
            padding: 0.5rem 1rem !important;
            border-radius: 0.5rem !important;
            border: none !important;
            cursor: pointer !important;
            margin: 0.25rem !important;
            transition: background-color 0.2s !important;
        }
        #reader button:hover {
            background-color: #b91c1c !important;
        }
        #reader select {
            background-color: #0f172a !important;
            color: #f8fafc !important;
            border: 1px solid #334155 !important;
            padding: 0.4rem 0.75rem !important;
            border-radius: 0.5rem !important;
            font-size: 0.875rem !important;
            margin: 0.25rem !important;
        }
        #reader a {
            color: #94a3b8 !important;
            font-size: 0.75rem !important;
            font-weight: 600 !important;
            text-decoration: underline !important;
        }
        #reader a:hover {
            color: #ffffff !important;
        }
        #reader__header_message {
            color: #94a3b8 !important;
            font-size: 0.875rem !important;
        }
    </style>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let isProcessing = false;

        function playSuccessBeep() {
            try {
                const audioCtx = new (window.AudioContext || window.webkitAudioContext)();
                const osc = audioCtx.createOscillator();
                const gain = audioCtx.createGain();
                osc.connect(gain);
                gain.connect(audioCtx.destination);
                osc.frequency.setValueAtTime(880, audioCtx.currentTime);
                gain.gain.setValueAtTime(0.2, audioCtx.currentTime);
                gain.gain.exponentialRampToValueAtTime(0.01, audioCtx.currentTime + 0.15);
                osc.start();
                osc.stop(audioCtx.currentTime + 0.15);
            } catch (e) {
                // Audio not allowed or unsupported; ignore silently
            }
        }

        function verifyTicket(ticketCode) {
            if (isProcessing) return;
            isProcessing = true;

            const cleanCode = ticketCode.trim();
            const resultBox = document.getElementById('scan-result');
            resultBox.classList.remove(
                'hidden', 'bg-red-600', 'bg-slate-900', 'bg-red-950', 
                'border-red-600', 'border-red-500', 'border-red-800', 'border-slate-700',
                'text-white', 'text-red-300'
            );
            resultBox.classList.add('bg-slate-900', 'text-white', 'border', 'border-slate-700');
            resultBox.innerText = `Memverifikasi ${cleanCode}...`;

            fetch("{{ route('admin.scanner.process', $event->id) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ ticket_code: cleanCode })
            })
            .then(response => response.json())
            .then(data => {
                resultBox.classList.remove('bg-slate-900', 'border-slate-700');

                if (data.status === 'success') {
                    playSuccessBeep();
                    resultBox.classList.add('bg-red-600', 'text-white', 'border', 'border-red-500');
                    resultBox.innerHTML = `✅ ${data.message}<br><span class="text-xs font-medium">Atas Nama: <strong>${data.participant}</strong></span>`;
                } else if (data.status === 'warning') {
                    resultBox.classList.add('bg-slate-900', 'text-white', 'border', 'border-red-600');
                    resultBox.innerHTML = `⚠️ ${data.message}<br><span class="text-xs font-medium">Atas Nama: <strong>${data.participant}</strong></span>`;
                } else {
                    resultBox.classList.add('bg-red-950', 'text-red-300', 'border', 'border-red-800');
                    resultBox.innerHTML = `❌ ${data.message}`;
                }

                setTimeout(() => { isProcessing = false; }, 2500);
            })
            .catch(err => {
                resultBox.classList.add('bg-red-950', 'text-red-300', 'border', 'border-red-800');
                resultBox.innerText = 'Error koneksi server!';
                setTimeout(() => { isProcessing = false; }, 2500);
            });
        }

        function onScanSuccess(decodedText, decodedResult) {
            verifyTicket(decodedText);
        }

        function onScanFailure(error) {
            // Ignore routine frame parsing misses
        }

        // Dynamic QR Box calculation (75% of container)
        const qrboxFunction = function(viewfinderWidth, viewfinderHeight) {
            const minEdgePercentage = 0.75;
            const minEdgeSize = Math.min(viewfinderWidth, viewfinderHeight);
            const qrboxSize = Math.max(50, Math.floor(minEdgeSize * minEdgePercentage));
            return {
                width: qrboxSize,
                height: qrboxSize
            };
        };

        const scannerConfig = {
            fps: 15,
            qrbox: qrboxFunction,
            aspectRatio: 1.0,
            showTorchButtonIfSupported: true,
            showZoomSliderIfSupported: true,
            rememberLastUsedCamera: true,
            formatsToSupport: [
                Html5QrcodeSupportedFormats.QR_CODE
            ],
            experimentalFeatures: {
                useBarCodeDetectorIfSupported: true
            },
            videoConstraints: {
                facingMode: { ideal: "environment" }
            }
        };

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", scannerConfig, /* verbose= */ false);
        html5QrcodeScanner.render(onScanSuccess, onScanFailure);

        // Handle manual ticket submission
        document.getElementById('manual-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const input = document.getElementById('manual-ticket-code');
            if (input.value.trim()) {
                verifyTicket(input.value.trim());
                input.value = '';
            }
        });
    </script>
</x-app-layout>
