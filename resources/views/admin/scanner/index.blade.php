<x-app-layout>
    <x-slot name="header">
        <h2 class="font-black uppercase tracking-wider text-xl text-white leading-tight">
            Gate Scanner: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-slate-950 min-h-screen">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-slate-800/90 p-6 sm:p-8 shadow-2xl border border-slate-800 text-center">
                
                <h3 class="text-white font-black mb-4 text-xs uppercase tracking-widest text-slate-300">Arahkan Kamera ke QR Code Tiket</h3>
                
                <div id="reader" class="w-full bg-slate-950 overflow-hidden border-2 border-slate-700"></div>

                <div id="scan-result" class="mt-6 p-4 hidden text-center font-bold text-sm"></div>

                <div class="mt-6">
                    <a href="{{ route('admin.dashboard') }}" class="inline-flex items-center text-xs font-bold uppercase tracking-wider text-slate-400 hover:text-white transition">
                        &larr; Balik ke Dashboard
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://unpkg.com/html5-qrcode"></script>
    <script>
        let isProcessing = false;

        function onScanSuccess(decodedText, decodedResult) {
            if (isProcessing) return;
            isProcessing = true;

            const resultBox = document.getElementById('scan-result');
            resultBox.classList.remove('hidden', 'bg-red-600', 'bg-slate-900', 'border-red-600', 'text-white', 'text-red-300', 'border-slate-700');
            resultBox.classList.add('bg-slate-900', 'text-white', 'border', 'border-slate-700');
            resultBox.innerText = 'Verifikasi...';

            fetch("{{ route('admin.scanner.process', $event->id) }}", {
                method: "POST",
                headers: {
                    "Content-Type": "application/json",
                    "X-CSRF-TOKEN": "{{ csrf_token() }}"
                },
                body: JSON.stringify({ ticket_code: decodedText })
            })
            .then(response => response.json())
            .then(data => {
                resultBox.classList.remove('bg-slate-900', 'border-slate-700');

                if (data.status === 'success') {
                    resultBox.classList.add('bg-red-600', 'text-white', 'border', 'border-red-500');
                    resultBox.innerHTML = `✅ ${data.message}<br><span class="text-xs font-medium">Atas Nama: <strong>${data.participant}</strong></span>`;
                } else if (data.status === 'warning') {
                    resultBox.classList.add('bg-slate-900', 'text-white', 'border', 'border-red-600');
                    resultBox.innerHTML = `⚠️ ${data.message}<br><span class="text-xs font-medium">Atas Nama: <strong>${data.participant}</strong></span>`;
                } else {
                    resultBox.classList.add('bg-red-950', 'text-red-300', 'border', 'border-red-800');
                    resultBox.innerHTML = `❌ ${data.message}`;
                }

                setTimeout(() => { isProcessing = false; }, 3000);
            })
            .catch(err => {
                resultBox.classList.add('bg-red-950', 'text-red-300', 'border', 'border-red-800');
                resultBox.innerText = 'Error koneksi server!';
                setTimeout(() => { isProcessing = false; }, 3000);
            });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>
