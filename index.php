<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>بوابة الجامعة</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Arial', sans-serif;
        }
        
        body {
            background-color: #f5f5f5;
            color: #333;
        }
        
        .header {
            background-color: #1a3e72;
            color: white;
            padding: 20px 0;
            text-align: center;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }
        
        .logo-container {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 15px;
        }
        
        .logo {
            width: 60px;
            height: 60px;
            margin-left: 15px;
        }
        
        .university-name {
            font-size: 24px;
            font-weight: bold;
        }
        
        .main-content {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            min-height: 70vh;
            padding: 20px;
        }
        
        .buttons-container {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 20px;
            max-width: 800px;
            width: 100%;
        }
        
        .nav-button {
            background-color: #1a3e72;
            color: white;
            border: none;
            padding: 20px;
            font-size: 18px;
            border-radius: 8px;
            cursor: pointer;
            transition: all 0.3s ease;
            text-align: center;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        .nav-button:hover {
            background-color: #0d2b52;
            transform: translateY(-3px);
            box-shadow: 0 6px 8px rgba(0, 0, 0, 0.15);
        }
        
        @media (max-width: 768px) {
            .buttons-container {
                grid-template-columns: 1fr;
            }
            
            .university-name {
                font-size: 20px;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="logo-container">
            <img src="ss.png" alt="شعار الجامعة" class="logo">
            <h1 class="university-name">جامعة السيد محمد بن علي السنوسي الإسلامية</h1>
        </div>
    </header>
    
    <main class="main-content">
        <h1>نتائج كلية القرآن الكريم وعلومه</h1>
<br><br>

        <div class="buttons-container">
            <a class="nav-button" href='Quran_scince.php'><b>قسم علوم القرآن الكريم</b></a>
            <a class="nav-button" href='quran_explain.php'><b>قسم تفسير القرآن الكريم </b></a>
            <a class="nav-button" href='readings.php'><b>قسم القراءات القرنية </b></a>
         </div>
    </main>
</body>
</html>
