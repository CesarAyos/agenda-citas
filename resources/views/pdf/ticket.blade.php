<!DOCTYPE html>
<html>
<head>
    <title>Ticket de Cita Médica</title>
    <style>
        body { font-family: sans-serif; text-align: center; padding: 50px; }
        .ticket-box { border: 2px dashed #000; padding: 20px; display: inline-block; }
        .token { font-size: 24px; font-weight: bold; color: #2563eb; }
    </style>
</head>
<body>
    <div class="ticket-box">
        <h1>COMPROBANTE DE CITA</h1>
        <p><strong>Paciente:</strong> {{ $cita->nombre }} {{ $cita->apellido }}</p>
        <p><strong>Cédula:</strong> {{ $cita->cedula }}</p>
        <p><strong>Fecha y Hora:</strong> {{ $cita->cita_hora }}</p>
        <hr>
        <p>Código Único de Cita:</p>
        <p class="token">{{ strtoupper($cita->token_unique) }}</p>
        <p><small>Presente este ticket al momento de su cita.</small></p>
    </div>
</body>
</html>