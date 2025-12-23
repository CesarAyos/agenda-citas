<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket de Cita Médica</title>
    <style>
        @page { margin: 0; }
        body { 
            font-family: 'Helvetica', 'Arial', sans-serif; 
            background-color: #f4f7f6;
            color: #333;
            margin: 0;
            padding: 40px;
        }
        .ticket-container {
            max-width: 500px;
            margin: 0 auto;
            background: #fff;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(0,0,0,0.1);
            border: 1px solid #e0e0e0;
        }
        .header {
            background-color: #1e40af; /* Azul institucional */
            color: white;
            padding: 20px;
            text-align: center;
        }
        .header h1 { margin: 0; font-size: 18px; text-transform: uppercase; letter-spacing: 1px; }
        .header p { margin: 5px 0 0; font-size: 12px; opacity: 0.9; }

        .content { padding: 30px; }
        .section-title { 
            font-size: 10px; 
            color: #6b7280; 
            text-transform: uppercase; 
            font-weight: bold; 
            margin-bottom: 5px;
            letter-spacing: 0.5px;
        }
        .info-group { margin-bottom: 20px; }
        .info-value { font-size: 16px; font-weight: 600; color: #111827; }

        .grid { width: 100%; margin-bottom: 20px; }
        .grid td { width: 50%; vertical-align: top; }

        .token-section {
            background-color: #eff6ff;
            border: 2px dashed #3b82f6;
            border-radius: 8px;
            padding: 15px;
            text-align: center;
            margin-top: 10px;
        }
        .token-label { font-size: 11px; color: #3b82f6; font-weight: bold; margin-bottom: 5px; }
        .token-code { font-size: 28px; font-family: monospace; font-weight: bold; color: #1e3a8a; letter-spacing: 2px; }

        .footer {
            padding: 20px;
            background: #f9fafb;
            border-top: 1px solid #eee;
            font-size: 10px;
            color: #6b7280;
            text-align: center;
            line-height: 1.5;
        }
        .watermark {
            position: absolute;
            bottom: 10px;
            right: 10px;
            font-size: 8px;
            color: #ddd;
        }
    </style>
</head>
<body>
    <div class="ticket-container">
        <div class="header">
            <h1>Hospital Dr. Ernesto Segundo Paolini</h1>
            <p>Central de Citas Médicas</p>
        </div>

        <div class="content">
            <div class="info-group">
                <div class="section-title">Paciente</div>
                <div class="info-value">{{ $cita->nombre }} {{ $cita->apellido }}</div>
            </div>

            <table class="grid">
                <tr>
                    <td>
                        <div class="section-title">Cédula de Identidad</div>
                        <div class="info-value">{{ $cita->cedula }}</div>
                    </td>
                    <td>
                        <div class="section-title">Departamento</div>
                        <div class="info-value">{{ $cita->departamento }}</div>
                    </td>
                </tr>
            </table>

            <div class="info-group">
                <div class="section-title">Fecha y Hora Programada</div>
                <div class="info-value">
                    {{ \Carbon\Carbon::parse($cita->cita_hora)->format('d/m/Y - h:i A') }}
                </div>
            </div>

            <div class="token-section">
                <div class="token-label">CÓDIGO ÚNICO DE VALIDACIÓN</div>
                <div class="token-code">{{ strtoupper($cita->token_unique) }}</div>
            </div>
        </div>

        <div class="footer">
            <strong>INSTRUCCIONES IMPORTANTES:</strong><br>
            Presente este comprobante impreso o en su móvil al llegar.<br>
            La atención se realizará por orden de llegada dentro del bloque horario.<br>
            Si no puede asistir, por favor cancele con 24h de antelación.
        </div>
    </div>
    
    <div class="watermark">Generado por Sistema de Gestión Hospitalaria - 2025</div>
</body>
</html>