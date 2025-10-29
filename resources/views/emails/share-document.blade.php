<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shared Document</title>
    <style>
        body {
            font-family: 'Segoe UI', Roboto, Arial, sans-serif;
            background-color: #fff1f2;
            margin: 0;
            padding: 40px 0;
            color: #374151;
        }

        .email-wrapper {
            max-width: 650px;
            margin: auto;
            background: #ffffff;
            border-radius: 12px;
            box-shadow: 0 4px 25px rgba(0,0,0,0.08);
            overflow: hidden;
        }

        .header {
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            text-align: center;
            padding: 30px 20px;
        }

        .header h2 {
            margin: 0;
            font-size: 22px;
            font-weight: 600;
            letter-spacing: 0.3px;
        }

        .content {
            padding: 35px 45px;
            line-height: 1.7;
        }

        .content h3 {
            margin-top: 0;
            font-size: 20px;
            color: #1e293b;
        }

        .content p {
            font-size: 15px;
            color: #475569;
        }

        .btn {
            display: inline-block;
            margin-top: 25px;
            background: linear-gradient(135deg, #ef4444, #dc2626);
            color: white;
            text-decoration: none;
            font-weight: 600;
            padding: 12px 26px;
            border-radius: 8px;
            transition: all 0.3s ease;
            box-shadow: 0 4px 10px rgba(239, 68, 68, 0.3);
        }

        .btn:hover {
            background: linear-gradient(135deg, #dc2626, #b91c1c);
            transform: translateY(-2px);
            box-shadow: 0 6px 14px rgba(220, 38, 38, 0.35);
        }

        .footer {
            background-color: #fef2f2;
            text-align: center;
            padding: 18px 10px;
            font-size: 13px;
            color: #6b7280;
        }

        .footer a {
            color: #ef4444;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<div class="email-wrapper">
    <div class="header">
        <h2>📄 Document Shared With You</h2>
    </div>

    <div class="content">
        <h3>{{ $document->title }}</h3>
        <p>{{ $document->description }}</p>

        <p>
            A document has been shared with you. You can download it securely using the button below:
        </p>

        <a href="{{ asset('storage/' . preg_replace('/^storage\//', '', $document->file_path)) }}" class="btn">
            📥 Download Document
        </a>
    </div>

    <div class="footer">
        <p>This message was sent automatically from the EMS system.</p>
        <p><a href="{{ url('/') }}">Open Employee Portal</a></p>
    </div>
</div>
</body>
</html>
