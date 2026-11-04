<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 leading-tight">
            Scanner Gate: {{ $event->title }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-900 min-h-screen">
        <div class="max-w-xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-gray-800 p-6 rounded-2xl shadow-xl border border-gray-700 text-center">
                
                <h3 class="text-white font-bold mb-4 text-sm uppercase tracking-wider">Arahkan Kamera ke QR Code Tiket</h3>
                
                <div id="reader" class="w-full bg-black rounded-xl overflow-hidden border-2 border-gray-600"></div>

                <div id="scan-result" class="mt-6 p-4 rounded-xl hidden text-center font-bold"></div>

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
            resultBox.classList.remove('hidden', 'bg-green-500', 'bg-red-500', 'bg-yellow-500');
            resultBox.classList.add('bg-gray-700', 'text-white');
            resultBox.innerText = 'Memproses verifikasi...';

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
                resultBox.classList.remove('bg-gray-700');

                if (data.status === 'success') {
                    resultBox.classList.add('bg-green-500', 'text-gray-950');
                    resultBox.innerHTML = `✅ ${data.message}<br><span class="text-xs">Atas Nama: <strong>${data.participant}</strong></span>`;
                } else if (data.status === 'warning') {
                    resultBox.classList.add('bg-yellow-500', 'text-gray-950');
                    resultBox.innerHTML = `⚠️ ${data.message}<br><span class="text-xs">Atas Nama: <strong>${data.participant}</strong></span>`;
                } else {
                    resultBox.classList.add('bg-red-500', 'text-white');
                    resultBox.innerHTML = `❌ ${data.message}`;
                }

                setTimeout(() => { isProcessing = false; }, 3000);
            })
            .catch(err => {
                resultBox.classList.add('bg-red-500', 'text-white');
                resultBox.innerText = 'Error koneksi server!';
                setTimeout(() => { isProcessing = false; }, 3000);
            });
        }

        let html5QrcodeScanner = new Html5QrcodeScanner("reader", { fps: 10, qrbox: 250 });
        html5QrcodeScanner.render(onScanSuccess);
    </script>
</x-app-layout>
