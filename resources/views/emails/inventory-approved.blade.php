<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            line-height: 1.6;
            color: #2D3748;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #F0FDFA;
        }
        .container {
            background-color: #ffffff;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(45, 212, 191, 0.1);
            padding: 30px;
            border: 1px solid #99F6E4;
        }
        .header {
            text-align: center;
            padding-bottom: 20px;
            border-bottom: 2px solid #5EEAD4;
            margin-bottom: 20px;
        }
        .header h1 {
            color: #0D9488;
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 20px 0;
        }
        .details {
            background-color: #F0FDFA;
            padding: 20px;
            border-radius: 6px;
            margin: 20px 0;
            border-left: 4px solid #2DD4BF;
        }
        .details strong {
            color: #0F766E;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #115E59;
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #99F6E4;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Peminjaman Inventaris Disetujui</h1>
        </div>
        
        <div class="content">
            <p>Halo,</p>
            <p>Kami ingin memberitahukan bahwa peminjaman inventaris telah disetujui dengan detail sebagai berikut:</p>
            
            <div class="details">
                <p><strong>Nama Item:</strong> {{ $inventory->item_name }}</p>
                <p><strong>Peminjam:</strong> {{ $reserf->name }}</p>
                <p><strong>Tanggal Mulai:</strong> {{ $reserf->start_time->format('d M Y H:i') }}</p>
                <p><strong>Tanggal Selesai:</strong> {{ $reserf->end_time->format('d M Y H:i') }}</p>
            </div>
            
            {{-- <p>Silakan hubungi petugas laboratorium jika ada pertanyaan lebih lanjut.</p> --}}
        </div>
        
        <div class="footer">
            <p>Email ini dikirim secara otomatis, mohon tidak membalas email ini.</p>
        </div>
    </div>
</body>
</html>