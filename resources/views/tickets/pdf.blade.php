<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>E-Ticket {{ $ticket->ticket_code }}</title>
    <style>
        body { font-family: sans-serif; padding: 10px; background: #fff; color: #111; }
        .card { border: 2px dashed #000; padding: 15px; border-radius: 8px; text-align: center; }
        .title { font-size: 16px; font-weight: bold; margin-bottom: 5px; text-transform: uppercase; }
        .code { font-family: monospace; font-size: 18px; font-weight: bold; margin: 10px 0; letter-spacing: 2px; }
        .meta { font-size: 11px; color: #555; margin-bottom: 15px; }
        .qr-container { margin: 15px auto; }
    </style>
</head>
<body>
    <div class="card">
        <div style="font-size: 10px; font-weight: bold; letter-spacing: 1px;">ROCKING LAWD E-TICKET</div>
        <div class="code">{{ $ticket->ticket_code }}</div>
        
        <div class="qr-container">
            <img src="data:image/png;base64, {!! base64_encode(QrCode::format('png')->size(150)->generate($ticket->ticket_code)) !!} ">
        </div>

        <div class="title">{{ $ticket->event->title }}</div>
        <div class="meta">
            📍 {{ $ticket->event->venue }}<br>
            📅 {{ \Carbon\Carbon::parse($ticket->event->event_date)->format('d M Y, H:i') }} WIB
        </div>

        <div style="font-size: 9px; color: #888; border-top: 1px solid #ccc; padding-top: 5px;">
            Pemegang: {{ $ticket->user->name }} | Ref: {{ $ticket->order->order_number }}
        </div>
    </div>
</body>
</html>
